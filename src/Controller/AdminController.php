<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Car;
use App\Entity\Order;
use App\Entity\User;
use App\Form\AddCarType;
use App\Form\AnalysisFormType;
use App\Form\AddressFormType;
use App\Form\EditUserType;
use App\Form\FilterUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends AbstractController
{
    #[Route('/{_locale}/addCar', name: 'add_car')]
    public function addCar(
        Request $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): Response {
        $car = new Car();
        $form = $this->createForm(AddCarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', $translator->trans('Form.addCar.flashmessage'));

            $imageFile = $form['image_file']->getData();
            if ($imageFile == null) {
                $fileName = 'keinBild.png';
            } else {
                $filePathInfo = pathinfo($imageFile->getClientOriginalName());
                $fileName = $filePathInfo['basename'];
                $destinationPath = $this->getParameter('kernel.project_dir') . '/public/assets';
                $imageFile->move($destinationPath, $fileName);
            }
            $car->setThumbnailURL($fileName);

            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('add_car', ['title' => 'Add.car']);
        }

        return $this->render('administration/addCar.html.twig', [
            'form' => $form,
            'title' => 'Add.car',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{_locale}/users', name: 'user_list')]
    public function showUsers(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(FilterUserFormType::class);
        $form->handleRequest($request);
        $queryArray = ['ROLE_USER', 'ROLE_ADMIN'];
        $data = $form->getData();
        if ($form->isSubmitted()) {
            $queryArray = null;
            if ($data['admin']) {
                $queryArray[] = 'ROLE_ADMIN';
            }
            if ($data['user']) {
                $queryArray[] = 'ROLE_USER';
            }
            if ($queryArray == null) {
                $queryArray = ['ROLE_USER', 'ROLE_ADMIN'];
            }
        }
        $filteredUsers = null;

        $users = $entityManager->getRepository(User::class)->findAll();
        /** @var User $user */
        foreach ($users as $user) {
            foreach ($user->getRoles() as $role) {
                foreach ($queryArray as $query) {
                    if ($role == $query) {
                        $filteredUsers[] = $user;
                    }
                }
            }
        }
        $filteredUsers = array_unique($filteredUsers, SORT_REGULAR);

        return $this->render(
            'administration/users.html.twig',
            ['title' => 'User.list', 'users' => $filteredUsers, 'filter_form' => $form]
        );
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{_locale}/edit-user/{id}', name: 'edit_user')]
    public function editUser(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $address = $user->getAddress();
        if ($address == null) {
            $address = new Address();
            $user->setAddress($address);
            $address->setUser($user);
        }
        $addressForm = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);
        $addressForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $addressForm->isValid()) {
            $entityManager->persist($address);
            $entityManager->flush();
            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'administration/edit-user.html.twig',
            ['title' => 'Edit.user', 'form' => $form, 'addressForm' => $addressForm]
        );
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{_locale}/analysis', name: 'order_analysis')]
    public function analysis(EntityManagerInterface $entityManager, Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(AnalysisFormType::class);
        $form->handleRequest($request);

        $relevantOrders = [];
        $relevantCars = [];
        $relevantCategories = [];
        $relevantSeats = [];
        $relevantLuggage = [];
        $extras = [
            'carSeat' => 0,
            'dogCage' => 0
        ];
        $count = 0;

        if ($form->isSubmitted() && $form->isValid()) {
            $orders = $entityManager->getRepository(Order::class)->findBy(['status' => 'Allowed']);

            $startTime = $form->get('startDate')->getData();
            $endTime = $form->get('endDate')->getData();
            $endTime->setTime(23, 59, 59);

            if ($endTime > $startTime) {
                $cars = [];
                $categories = [];
                $seats = [];
                $luggage = [];
                foreach ($orders as $order) {
                    if ($startTime <= $order->getEndDate() && $endTime >= $order->getStartDate()) {
                        $relevantOrders[] = $order;
                        $cars[] = $order->getCar()->getName();
                        $categories[] = $order->getCar()->getCategory();
                        $seats[] = $order->getCar()->getSeats();
                        $luggage[] = $order->getCar()->getLuggage();
                        if ($order->hasCarSeat()) {
                            $extras['carSeat']++;
                        }
                        if ($order->hasDogCage()) {
                            $extras['dogCage']++;
                        }
                    }
                }

                $count = count($relevantOrders);
                $relevantCars = array_count_values($cars);
                $relevantCategories = array_count_values($categories);
                $relevantSeats = array_count_values($seats);
                $relevantLuggage = array_count_values($luggage);
            } else {
                $this->addFlash('warning', $translator->trans('Flash.wrongDates'));
            }
        }

        return $this->render(
            'administration/order_analysis.html.twig',
            [
                'title' => 'Analysis',
                'form' => $form,
                'data' => $relevantOrders,
                'cars' => $relevantCars,
                'categories' => $relevantCategories,
                'seats' => $relevantSeats,
                'luggage' => $relevantLuggage,
                'extras' => $extras,
                'count' => $count
            ]
        );
    }
}
