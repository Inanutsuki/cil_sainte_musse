<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/actualités", name="app_news", methods={"GET"})
     */
    public function actualities(EntityManagerInterface $em, PostRepository $postRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $dataIsValided = $postRepository->findBy(array( 'isValided' => '1'));
        $posts = $paginator->paginate(
            $dataIsValided, // On passe les données.
            $request->query->getInt('page', 1), // Numéro de la page en cours, 1 par défaut.
            5 // Nombre d'éléments par page.
        );

        return $this->render('home/news.html.twig', [
            'posts' => $posts,
            'title' => "Les actualites",
            'current_page' => 'news',
        ]);
    }
}
