<?php

namespace App\Service;

use App\Entity\Order;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class OrderMailer
{
    private const CUSTOMER_ORDER_INFORMATION = 'emails/email_customer_order.html.twig';
    private const ADMIN_NEW_ORDER_INFORMATION = 'emails/email_new_order.html.twig';
    private const CUSTOMER_RENTAL_AGREEMENT_INFORMATION = 'emails/email_rental_agreement_canceled.html.twig';

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendCustomerMail(Order $order): void
    {
        $email = (new TemplatedEmail())
            ->text('Hier steht ein Text')
            ->to($order->getEmail())
            ->subject('Bestellung erfolgreich!')
            ->htmlTemplate(self::CUSTOMER_ORDER_INFORMATION)
            ->context([
                'order' => $order,
            ]);
        $this->mailer->send($email);
    }

    public function sendAllowedRequestMail(Order $order): void
    {
        $email = (new TemplatedEmail())
            ->to($order->getEmail())
            ->subject('Die Mietanfrage akzeptiert!')
            ->htmlTemplate('emails/email_order_confirmed.html.twig')
            ->context(['order' => $order]);
        $this->mailer->send($email);
    }

    public function sendDeniedRequestMail(Order $order): void
    {
        $email = (new TemplatedEmail())
            ->to($order->getEmail())
            ->subject('Die Mietanfrage wurde abgelehnt!')
            ->htmlTemplate('emails/email_order_denied.html.twig')
            ->context(['order' => $order]);
        $this->mailer->send($email);
    }

    public function sendAdminMail(Order $order): void
    {
        $email = (new TemplatedEmail())
            ->to('mobilitaetsmacher@bysaeth.de')
            ->subject('Neue Bestellung eingegangen!')
            ->htmlTemplate(self::ADMIN_NEW_ORDER_INFORMATION)
            ->context(['order' => $order]);
        $this->mailer->send($email);
    }

    public function sendCanceledRentalAgreementMail(Order $order): void
    {
        $email = (new TemplatedEmail())
            ->to($order->getEmail())
            ->subject('Ihre Buchung wurde storniert !')
            ->htmlTemplate(self::CUSTOMER_RENTAL_AGREEMENT_INFORMATION)
            ->context(['order' => $order]);
        $this->mailer->send($email);
    }
}
