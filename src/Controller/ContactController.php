<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Notification\ContactNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(Request $request, ContactNotification $notification, EntityManagerInterface $manager):Response
    {
        $contact = new Contact;
        $form_contact = $this->createForm(ContactFormType::class, $contact);

        $form_contact->handleRequest($request);

        if ($form_contact->isSubmitted() && $form_contact->isValid()) {
            $notification->notify($contact);
            $this->addFlash('success', "Votre email a bien été envoyé");

            $manager->persist($contact);
            $manager->flush();

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'title' => "Nous contacter",
            'form_contact' => $form_contact->createView()
        ]);    
    }
}
