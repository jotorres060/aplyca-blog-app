<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    #[Route('/blog', name: 'post_index')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $posts = $entityManager->getRepository(Post::class)->getPostsByUser($user);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/public-blog/post/{id}', name: 'post_show')]
    public function show($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->getPost($id);

        if ($post == null) {
            $this->addFlash('info', Post::NOT_FOUND);
            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/blog/post-by-user/{id}', name: 'post_show_by_user')]
    public function showByUser($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $post = $entityManager->getRepository(Post::class)->getPostByUser($id, $user);

        if ($post == null) {
            $this->addFlash('info', Post::NOT_FOUND);
            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/new-post', name: 'post_create')]
    public function create(Request $request, SluggerInterface $slugger): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverImage = $form->get('cover_image')->getData();

            if ($coverImage) {
                $originalFilename = pathinfo($coverImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverImage->guessExtension();

                try {
                    $coverImage->move(
                        $this->getParameter('cover_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception("Oops!, something went wrong.");
                }

                $post->setCoverImage($newFilename);
            }

            $user = $this->getUser();
            $post->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('info', Post::SUCCESS_REGISTER);
            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/create.html.twig', [
            'newPostform' => $form->createView(),
        ]);
    }

    #[Route('/edit-post/{id}', name: 'post_edit')]
    public function edit($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $post = $entityManager->getRepository(Post::class)->getPost($id, $user);

        return $this->render('post/edit.html.twig', [
            'post' => $post,
        ]);
    }
}
