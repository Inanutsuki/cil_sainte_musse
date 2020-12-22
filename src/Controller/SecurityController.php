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
            'title' => "Connexion",
            'current_page' => 'connexion',
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout():Response
    {
        throw new \LogicException('Cette méthode peut être vide, elle sera intercepté par la clef logout dans le firewall (security.yaml).');
    }
}
