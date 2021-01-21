<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 * @isGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'title' => "Page d'administration",
            'current_page' => 'panneau_admin',
        ]);
    }

    /**
     * @Route("/users", name="user_index", methods={"GET"})
     */
    public function userList(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'title' => "Liste des utilisateurs"
        ]);
    }

    /**
     * @Route("/posts", name="post_index", methods={"GET"})
     */
    public function postList(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            'title' => "Liste des articles"
        ]);
    }

    /**
     * @Route("/posts-not-valided", name="post_not_valided", methods={"GET"})
     */
    public function postListNotValided(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(array('isValided' => false), array('createdAt' => 'DESC')),
            'title' => "Liste des articles"
        ]);
    }

    /**
     * @Route("/posts-valided", name="post_valided", methods={"GET"})
     */
    public function postListValided(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(array('isValided' => true), array('createdAt' => 'DESC')),
            'title' => "Liste des articles"
        ]);
    }
    /**
     * @Route("/posts-members", name="post_members", methods={"GET"})
     */
    public function postListMembers(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(array('forMembers' => true), array('createdAt' => 'DESC')),
            'title' => "Liste des articles"
        ]);
    }
    /**
     * @Route("/posts-assembly", name="post_assembly", methods={"GET"})
     */
    public function postListAssembly(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(array('forAssembly' => true), array('createdAt' => 'DESC')),
            'title' => "Liste des articles"
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function userDelete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }
}