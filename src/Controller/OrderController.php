<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\DeliveryAddress;
use App\Entity\Order;
use App\Enum\FlashMessages;
use App\Form\DeliveryAddressFormType;
use App\Form\FilterOrderFormType;
use App\Form\OrderFormType;
use App\Service\OrderMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{_locale}/mieten/liste', name: 'car_order_list')]
    public function list(EntityManagerInterface $entityManager, Request $request)
    {
        $queryArray = ['Pending', 'Allowed', 'Denied'];
        $form = $this->createForm(FilterOrderFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $queryArray = null;
            $data = $form->getData();

            if ($data['pending']) {
                $queryArray[] = 'Pending';
            }
            if ($data['allowed']) {
                $queryArray[] = 'Allowed';
            }
            if ($data['denied']) {
                $queryArray[] = 'Denied';
            }
            if ($queryArray == null) {
                $queryArray = ['Pending', 'Allowed', 'Denied'];
            }
        }

        $orders = $entityManager->getRepository(Order::class)->findBy(
            ['status' => $queryArray],
            ['startDate' => 'ASC']
        );

        return $this->render(
            'order/list.html.twig',
            ['orders' => $orders, 'title' => 'Order.list', 'filter_form' => $form]
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/{_locale}/mieten/{car}', name: 'car_order')]
    public function carOrder(
        Request $request,
        Car $car,
        OrderMailer $mailer,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): Response {
        $order = new Order();
        $user = $this->getUser();
        $order->setCar($car);
        $order->setLastEdit(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $order->setStatus('Pending');
        $deliveryAddress = new DeliveryAddress();

        if ($this->isGranted('IS_AUTHENTICATED')) {
            if ($user->getAddress() != null) {
                $deliveryAddress->setCountry($user->getAddress()->getCountry())
                    ->setZipCode($user->getAddress()->getZipCode())
                    ->setCity($user->getAddress()->getCity())
                    ->setStreet($user->getAddress()->getStreet())
                    ->setHouseNumber($user->getAddress()->getHouseNumber())
                    ->setComments($user->getAddress()->getComments());
            }
            $order->setUser($this->getUser())
                ->setFormOfAddress($user->getFormOfAddress())
                ->setFirstName($user->getFirstName())
                ->setLastName($user->getLastName())
                ->setEmail($user->getEmail())
                ->setPhoneNumber($user->getPhoneNumber())
                ->setMethodOfPayment($user->getPreferredMethodOfPayment());
        }

        $addressForm = $this->createForm(DeliveryAddressFormType::class, $deliveryAddress);
        $numberOfChildSeats = [];
        for ($i = 0; $i <= $car->getChildSeat(); $i++) {
            $numberOfChildSeats[] += $i;
        }
        $form = $this->createForm(OrderFormType::class, $order, [
            'isDogeCageCompatible' => $car->isIsDogeCageCompatible(),
            'numberOfChildSeats' => $numberOfChildSeats
        ]);
        $form->handleRequest($request);
        $addressForm->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $addressForm->isValid()) {
            $order->setDeliveryAddress($deliveryAddress);
            $deliveryAddress->setOrder($order);
            if ($order->getChildSeat() == null) {
                $order->setChildSeat(0);
            }
            $entityManager->persist($deliveryAddress);
            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('success', $translator->trans(FlashMessages::SUCCESS_ORDER->toString()));

            $mailer->sendCustomerMail($order);
            $mailer->sendAdminMail($order);
            return $this->redirectToRoute('homepage');
        }
        return $this->render('order/order.html.twig', [
            'form' => $form,
            'addressForm' => $addressForm,
            'title' => $car->getName(),
            'numberOfChildSeats' => $car->getChildSeat()
        ]);
    }

    #[Route('/{_locale}/allow', name: 'allow_rent_request')]
    public function allowRentRequest(
        Request $request,
        EntityManagerInterface $entityManager,
        OrderMailer $orderMailer
    ): Response {
        $order = $entityManager->getRepository(Order::class)->findOneBy(['id' => $request->request->get('id')]);
        $order->setStatus('Allowed');
        $order->setLastEdit(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $entityManager->persist($order);
        $entityManager->flush();
        $orderMailer->sendAllowedRequestMail($order);
        return $this->redirectToRoute('car_order_list');
    }

    #[Route('/{_locale}/deny', name: 'deny_rent_request')]
    public function denyRentRequest(
        Request $request,
        EntityManagerInterface $entityManager,
        OrderMailer $orderMailer
    ): Response {
        $order = $entityManager->getRepository(Order::class)->findOneBy(['id' => $request->request->get('id')]);
        $order->setStatus('Denied');
        $order->setLastEdit(new \DateTime('now', new \DateTimeZone('Europe/Berlin')));
        $entityManager->persist($order);
        $entityManager->flush();
        $orderMailer->sendDeniedRequestMail($order);
        return $this->redirectToRoute('car_order_list');
    }
}
