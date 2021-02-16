<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
    * @Route("/blog", name="blog_index", methods={"GET"})
    */
    public function index(): Response
    {
        $name = 'Jorge Torres';
        return $this->render('blog.html.twig', ['name' => $name]);
    }
}