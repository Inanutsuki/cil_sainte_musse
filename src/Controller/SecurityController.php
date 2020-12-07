<?php

namespace App\Controller;

use App\Form\UserRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    public const LAST_EMAIL = 'app_login_form_last_email';
    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     */
    public function login(): Response
    {
        return $this->render('security/login.html.twig', [
            'title' => "Connexion"
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout():Response
    {
        throw new \LogicException('Cette méthode peut être vide, elle sera intercepté par la clef logout dans le firwall (security.yaml).');
    }
    
    
    /**
     * @Route("/register", name="app_register", methods={"GET", "POST"})
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder):Response
    {
        $form_register = $this->createForm(UserRegistrationFormType::class);

        $form_register->handleRequest($request);

        if ($form_register->isSubmitted() && $form_register->isValid()) {

            $user = $form_register->getData();

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles("ROLE_USER");

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'title' => "S'enregistrer",
            'form_register' => $form_register->createView()
        ]);    
    }
}
