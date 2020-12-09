<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @isGranted("ROLE_USER")
     */
    public function profile(PostRepository $postRepository): Response
    {
        return $this->render('home/news.html.twig', [
            'posts' => $postRepository->findAll(),
            'title' => "Les actualites",
            'current_page' => 'news',
        ]);
    }
}
