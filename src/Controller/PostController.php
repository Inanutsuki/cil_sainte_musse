<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $post = new Post();
        $user = $this->getUser();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTime('now'));
            $post->setAuthor($user);
            $post->setIsValided(false);

            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('my_posts', [
                'id' => $this->getUser()->getId(),
            ]);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'id' => $post->getAuthor(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list/{id}", name="my_posts", methods={"GET"})
     */
    public function myPosts(PostRepository $postRepository, int $id): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser()->getId() != $id) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(['author' => $id]),
            'id' => $id,
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getIsValided() === true) {
                $post->setIsValided(true);
                $post->setModifiedAt(new \DateTime('now'));
                $manager->flush();
            } else {
                $post->setModifiedAt(new \DateTime('now'));
                $manager->flush();
            }

            return $this->redirectToRoute('my_posts', [
                'id' => $post->getAuthor()->getId(),
            ]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('my_posts', [
            'id' => $post->getAuthor()->getId(),
        ]);
    }
}
