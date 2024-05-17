<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\ContactRequest;
use App\Enum\FlashMessages;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage2')]
    public function backHome()
    {
        return $this->redirectToRoute('homepage');
    }

    #[Route('/{_locale}', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->redirectToRoute('list_cars');
    }

    #[Route('/{_locale}/impressum', name: 'imprinttext')]
    public function imprint()
    {
        return $this->render('/footer/imprint.html.twig', ['title' => 'Imprint']);
    }

    #[Route('/{_locale}/datenschutz', name: 'privacy')]
    public function privacyPolicy()
    {
        return $this->render('/footer/privacypolicy.html.twig', ['title' => 'Privacy']);
    }

    #[Route('/{_locale}/kontakt', name: 'contact')]
    public function contact(
        MailerInterface $mailer,
        TranslatorInterface $translator,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $contact = new ContactRequest();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setDate(new \DateTime());
            $entityManager->persist($contact);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans(FlashMessages::SUCCESS_CONTACT->toString()));
            $email = (new TemplatedEmail())
                ->from(new Address('mobilitaetsmacher@bysaeth.de', 'Mobilmacher'))
                ->to($contact->getEMail())
                ->subject($translator->trans('Your.contact.request'))
                ->htmlTemplate('emails/email_contact.html.twig')
                ->context([
                    'contact' => $contact,
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('homepage');
        }


        return $this->render('/footer/contact.html.twig', [
            'form' => $form,
            'title' => 'Contact'
        ]);
    }

    #[Route('/{_locale}/contact-request', name: 'contact_request')]
    public function showContactRequests(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $contacts = $entityManager->getRepository(ContactRequest::class)->findAll();
        return $this->render(
            '/administration/contact_requests_list.html.twig',
            ['contacts' => $contacts, 'title' => 'Contact.requests']
        );
    }
}
