<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => "CIL Sainte Musse",
            'current_page' => 'accueil'
        ]);
    }

    /**
     * @Route("/actualités", name="app_news", methods={"GET"})
     */
    public function actualities(PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $postRepository->findBy(array('isValided' => true), array('createdAt' => 'DESC'));
        $posts = $paginator->paginate(
            $data, // On passe les données.
            $request->query->getInt('page', 1), // Numéro de la page en cours, 1 par défaut.
            5 // Nombre d'éléments par page.
        );

        return $this->render('home/news.html.twig', [
            'posts' => $posts,
            'title' => "Les actualites",
            'current_page' => 'news',
        ]);
    }

    /**
     * @Route("/members-assembly", name="app_members_assembly", methods={"GET"})
     */
    public function listMembersAssembly(UserRepository $userRepository, SerializerInterface $serialize): Response
    {
        $userList = $userRepository->findAll();
        foreach ($userList as $user) {
            if ($user->getRoles() == ["ROLE_ASSEMBLY"] || $user->isAdmin()) {

                $listMembersAssembly[] = $user;
            }
        }
        return $this->render('home/assembly_members.html.twig', [
            'title' => "Membres de l'assemblé générale",
            'current_page' => 'members_assembly',
            'listMembersAssembly' => $listMembersAssembly,
        ]);
    }
}
