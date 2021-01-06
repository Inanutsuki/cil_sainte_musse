<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @isGranted("ROLE_USER")
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/membre/actualités", name="app_members_news", methods={"GET"})
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
            'current_page' => 'members',
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
            'title' => "Articles pour les membres de l'assemblée",
            'current_page' => 'assembly',
        ]);
    }
}
