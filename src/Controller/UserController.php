<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="app_register", methods={"GET", "POST"})
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        if( !($this->getUser() === Null) ){
            return $this->redirectToRoute('app_home');
        }
        $form_register = $this->createForm(UserRegistrationFormType::class);

        $form_register->handleRequest($request);

        if ($form_register->isSubmitted() && $form_register->isValid()) {

            $user = $form_register->getData();

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setStatus(["Membre"]);
            $user->setRoles(["ROLE_USER"]);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'title' => "S'enregistrer",
            'current_page' => 'enregistrer',
            'form_register' => $form_register->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserType::class, $user, ['roles' => $this->getUser()->getRoles()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_show', [
                'id' => $user->getId(),
                'user' => $user,
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(UserRepository $userRepository, $id): Response
    {
        if ($id === 'me') {
            $user = $this->getUser();
        } else {
            $user = $userRepository->findOneBy(array('id' => $id));
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'current_page' => 'profil',
        ]);
    }
}
