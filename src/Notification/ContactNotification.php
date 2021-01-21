<?php

namespace App\Notification;

use App\Entity\Contact;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class ContactNotification
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notify(Contact $contact)
    {
        $message = (new TemplatedEmail())
            ->from(new Address('no-reply@cil-sainte-musse.com', 'Cil Sainte Musse - La Ginouse'))
            ->to($contact->getEmail())
            ->subject('Nouvelle demande de contact')
            ->htmlTemplate('email/contact.html.twig')
            ->context([
                'contact' => $contact
            ]);

        $this->mailer->send($message);
    }
}
