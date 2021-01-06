<?php

namespace App\Controller;

use App\Entity\DocumentUpload;
use App\Entity\Post;
use App\Form\DocumentUploadType;
use App\Repository\DocumentUploadRepository;
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
        ]);
    }

    /**
     * @Route("/posts-not-valided", name="post_not_valided", methods={"GET"})
     */
    public function postListNotValided(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(array('isValided' => false), array('createdAt' => 'DESC')),
        ]);
    }

    /**
     * @Route("/posts-valided", name="post_valided", methods={"GET"})
     */
    public function postListValided(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(array('isValided' => true), array('createdAt' => 'DESC')),
        ]);
    }
    /**
     * @Route("/posts-members", name="post_members", methods={"GET"})
     */
    public function postListMembers(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(array('onlyMembers' => true), array('createdAt' => 'DESC')),
        ]);
    }
    /**
     * @Route("/posts-assembly", name="post_assembly", methods={"GET"})
     */
    public function postListAssembly(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(array('onlyAssembly' => true), array('createdAt' => 'DESC')),
        ]);
    }



    /**
     * @Route("/{id}/edit-document", name="document_upload_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DocumentUpload $documentUpload): Response
    {
        $form = $this->createForm(DocumentUploadType::class, $documentUpload);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentUpload->setModifiedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_upload_index');
        }

        return $this->render('document_upload/edit.html.twig', [
            'document_upload' => $documentUpload,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new-document", name="document_upload_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $documentUpload = new DocumentUpload();
        $form = $this->createForm(DocumentUploadType::class, $documentUpload);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentUpload->setAddAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($documentUpload);
            $entityManager->flush();

            return $this->redirectToRoute('document_upload_index');
        }

        return $this->render('document_upload/new.html.twig', [
            'document_upload' => $documentUpload,
            'form' => $form->createView(),
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
