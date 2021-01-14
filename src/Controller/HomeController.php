<?php

namespace App\Controller;

use App\Entity\DocumentUpload;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/membre/actualités", name="app_members_news", methods={"GET"})
     * @isGranted("ROLE_USER")
     */
    public function membersActualities(PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $postRepository->findBy(array('onlyMembers' => true), array('createdAt' => 'DESC'));
        $posts = $paginator->paginate(
            $data, // On passe les données.
            $request->query->getInt('page', 1), // Numéro de la page en cours, 1 par défaut.
            5 // Nombre d'éléments par page.
        );

        return $this->render('member_space/index.html.twig', [
            'posts' => $posts,
            'title' => "Articles pour les membres",
        ]);
    }

    /**
     * @Route("/assemblee/actualités", name="app_assembly_news", methods={"GET"})
     */
    public function assemblyActualities(PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $postRepository->findBy(array('onlyAssembly' => true), array('createdAt' => 'DESC'));
        $posts = $paginator->paginate(
            $data, // On passe les données.
            $request->query->getInt('page', 1), // Numéro de la page en cours, 1 par défaut.
            5 // Nombre d'éléments par page.
        );

        return $this->render('assembly_space/index.html.twig', [
            'posts' => $posts,
            'title' => "Articles pour les membres de l'assembléee",
            'current_page' => 'assembly',
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
    public function listMembersAssembly(UserRepository $userRepository): Response
    {
        $userList = $userRepository->findAll();
        foreach ($userList as $user) {
            if ($user->getRoles() == ["ROLE_ASSEMBLY"] || $user->isAdmin()) {

                $listMembersAssembly[] = $user;
            }
        }
        return $this->render('home/assembly_members.html.twig', [
            'title' => "Membres de l'assemblée générale",
            'current_page' => 'members_assembly',
            'listMembersAssembly' => $listMembersAssembly,
        ]);
    }

    /**
     * @Route("/histoire-cil", name="app_cil_story", methods={"GET"})
     */
    public function cilStory(): Response
    {

        return $this->render('presentation/cil-story.html.twig', [
            'title' => "Histoire du CIL",
            'current_page' => 'presentation',
        ]);
    }

    /**
     * @Route("/les-quartiers", name="app_neighborhoods", methods={"GET"})
     */
    public function neighborhoods(): Response
    {

        return $this->render('presentation/neighborhoods.html.twig', [
            'title' => "Sainte Musse - La Ginouse",
            'current_page' => 'presentation',
        ]);
    }

    /**
     * @Route("/rejoindre-le-cil", name="app_membership", methods={"GET"})
     */
    public function membership(): Response
    {
        $documentUpload = new DocumentUpload();

        return $this->render('presentation/membership.html.twig', [
            'document_upload' => $documentUpload,
            'title' => "Rejoindre le CIL",
            'current_page' => 'presentation',
        ]);
    }
}