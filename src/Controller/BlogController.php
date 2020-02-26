<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{ 
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
       $this->postRepository = $postRepository;
    } 

    /**
     * @Route("/", name="blog")
     */
    public function index()
    {
        $posts = $this->postRepository->findAll();

        return $this->render('blog/index.html.twig', ['posts' => $posts]);
    }
}
