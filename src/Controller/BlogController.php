<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/post/{id}", name="post_show")
     */
    public function postShow(Post $post)
    {   
        return $this->render('/blog/post_show.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/comment/{id}/new", name="comment_new")
     */
    public function commentNew(Request $request, Post $post)
    {

    }

    /**
     * @Route("/category/{id}", name="category_posts")
     */
    public function getCategoryPosts(Category $category)
    {
        $posts = $category->getPosts();
        
        return $this->render('/blog/index.html.twig', ['posts' => $posts]);
    }
}
