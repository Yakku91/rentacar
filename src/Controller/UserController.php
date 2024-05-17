<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\User;
use App\Enum\FlashMessages;
use App\Form\AddressFormType;
use App\Form\ChangePasswordFormType;
use App\Form\EditUserFormType;
use App\Form\RegistrationFormType;
use App\Service\OrderMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Deployer\test;

class UserController extends AbstractController
{
    #[Route('/{_locale}/registrierung', name: 'register')]
    #[Route('/{_locale}/create-account', name: 'register_en')]
    public function register(
        MailerInterface $mailer,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    ): Response {
        $user = new User();
        $address = new Address();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $addressForm = $this->createForm(AddressFormType::class, $address);
        $form->handleRequest($request);
        $addressForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $addressForm->isValid()) {
            $address->setUser($user);
            $user->setAddress($address);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($address);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans(FlashMessages::SUCCESS_REGISTER->toString()));
            $email = (new TemplatedEmail())
                ->text($translator->trans('Register.success'))
                ->to($user->getEmail())
                ->subject($translator->trans('Register.success'))
                ->htmlTemplate('emails/email_register_confirmation.html.twig')
                ->context([
                    'user' => $user
                ]);
            $mailer->send($email);
            return $this->redirectToRoute('login');
        }
        return $this->render('user/register.html.twig', [
            'form' => $form,
            'addressForm' => $addressForm,
            'title' => 'Register',
        ]);
    }

    #[Route('/{_locale}/anmelden', name: 'login')]
    #[Route('/{_locale}/login', name: 'login_en')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'title' => '',
        ]);
    }

    #[Route('/{_locale}/aktualisieren', name: 'user_edit')]
    #[Route('/{_locale}/edit-profile', name: 'user_edit_en')]
    public function edit(
        EntityManagerInterface $entityManager,
        Request $request,
        TranslatorInterface $translator
    ): Response {
        $user = $this->getUser();
        $address = $user->getAddress();
        if ($address == null) {
            $address = new Address();
            $user->setAddress($address);
            $address->setUser($user);
        }
        $form = $this->createForm(EditUserFormType::class, $user);
        $addressForm = $this->createForm(AddressFormType::class, $address);
        $addressForm->handleRequest($request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $addressForm->isValid()) {
            $entityManager->persist($address);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans(FlashMessages::SUCCESS_EDIT->toString()));
            return $this->redirectToRoute('homepage');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'addressForm' => $addressForm,
            'title' => 'Edit.profile',
        ]);
    }

    #[Route('/{_locale}/logout', name: 'user_logout', methods: ['GET'])]
    public function logout(TranslatorInterface $translator)
    {
    }

    #[Route('/{_locale}/logout_message', name: 'logout_message')]
    public function logoutMessage(TranslatorInterface $translator)
    {
        $this->addFlash('success', $translator->trans(FlashMessages::SUCCESS_LOGOUT->toString()));
        return $this->redirectToRoute('homepage');
    }

    #[Route('/{_locale}/delete-account', name: 'delete_account')]
    public function deleteAccount(
        Request $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        Security $security
    ): Response {
        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);
        $isThereAnyActuallyBooking = $entityManager->getRepository(Order::class)->isThereAnyActuallyBooking($user);
        $dummyUser = $entityManager->getRepository(User::class)->getDummyUser();
        $orders = $entityManager->getRepository(Order::class)->findBy(['user' => $user]);
        $address = $dummyUser->getAddress();
        $address->setUser($dummyUser);
        $entityManager->persist($address);
        $entityManager->persist($dummyUser);

        if (!$isThereAnyActuallyBooking) {
            foreach ($orders as $order) {
                /** @var Order $order */
                $order->setUser($dummyUser);
                $entityManager->flush();
            }
            $security->logout(validateCsrfToken: false);
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans(FlashMessages::SUCCESS_DELETING_ACCOUNT->toString()));
            return $this->redirectToRoute('login');
        }
        $this->addFlash('danger', $translator->trans(FlashMessages::ERROR_DELETING_ACCOUNT->toString()));
        return $this->redirectToRoute('user_edit');
    }

    #[Route('/{_locale}/changePassword', name: 'change_password')]
    public function changePassword(
        Request $request,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        UserPasswordHasherInterface $hasher
    ): Response {
        $form = $this->createForm(ChangePasswordFormType::class);
        $user = $this->getUser();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldpassword = $form->get('oldPassword')->getData();
            if ($oldpassword && $hasher->isPasswordValid($user, $oldpassword)) {
                $newPassword = $form->get('newPassword')->getData();
                if ($newPassword) {
                    $password = $hasher->hashPassword($user, $newPassword);
                    $user->setPassword($password);
                    $entityManager->flush();
                    $this->addFlash('success', $translator->trans(FlashMessages::SUCCESS_EDIT->toString()));
                    return $this->redirectToRoute('homepage');
                }
            } else {
                $this->addFlash("warning", $translator->trans("Form.password.wrongPassword"));
            }
        }
        return $this->render('user/change_password.html.twig', ['title' => 'Password.change', 'form' => $form]);
    }

    #[Route('/{_locale}/rentalAgreement', name: 'rental_agreement')]
    public function rentalAgreement(
        Request $request,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManager,
        OrderMailer $orderMailer,
    ): Response {
        $rentals = $entityManager->getRepository(Order::class)->findBy(['user' => $this->getUser()]);

        if ($request->get('id')) {
            $order = $entityManager->getRepository(Order::class)->find($request->get('id'));
            $order->setStatus('Cancelled');
            $entityManager->flush();
            $orderMailer->sendCanceledRentalAgreementMail($order);
            $this->addFlash('success', $translator->trans(FlashMessages::SUCCESS_DELETING_RENTAL->toString()));
        }
        return $this->render(
            'user/rental_agreement.html.twig',
            ['title' => 'Cancel', 'rentals' => $rentals]
        );
    }
}
