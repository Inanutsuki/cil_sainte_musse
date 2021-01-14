<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

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
        $thisUserGetRole = $this->getUser()->getRoles();
        $form = $this->createForm(PostType::class, $post, ['roles' => $thisUserGetRole]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getIsValided() === true && $thisUserGetRole == ['ROLE_ADMIN']) {
                $post->setCreatedAt(new \DateTime('now'));
                $post->setAuthor($user);
                $post->setIsValided(true);

                $manager->persist($post);
                $manager->flush();
            } else {
                $post->setCreatedAt(new \DateTime('now'));
                $post->setAuthor($user);
                $post->setIsValided(false);

                $manager->persist($post);
                $manager->flush();
            }

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
            'title' => "Mes articles",
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET", "POST"})
     */
    public function show(Post $post, HttpFoundationRequest $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);

        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $author = $this->getUser()->getLastName() . ' ' . $this->getUser()->getFirstName();

            $comment->setCreatedAt(new \DateTime('now'));
            $comment->setPost($post);
            $comment->setAuthor($author);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'formComment' => $formComment->createView()
        ]);
    }



    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post, EntityManagerInterface $manager): Response
    {
        $thisUserGetRole = $this->getUser()->getRoles();
        $form = $this->createForm(PostType::class, $post, ['roles' => $thisUserGetRole]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getIsValided() === true && $thisUserGetRole == ['ROLE_ADMIN']) {
                $post->setIsValided(true);
                $post->setModifiedAt(new \DateTime('now'));
                $manager->flush();
            } else {
                $post->setIsValided(false);
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
