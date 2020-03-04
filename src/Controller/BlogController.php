<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentType;
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
       $comment = new Comment;
       $comment->setUser($this->getUser());
       $comment->setPost($post);

       $form = $this->createForm(CommentType::class, $comment);
       $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
    }

    // this controller called directly via the 'blog/post_show.html.twig' template
    public function commentForm(Post $post)
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('blog/_comment_form.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
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
