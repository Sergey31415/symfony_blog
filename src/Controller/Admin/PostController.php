<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
      $this->postRepository = $postRepository;
    }

    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index()
    {
        $posts = $this->postRepository->findAll();
        return $this->render('admin/post/index.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/admin/post/new", name="admin_post_new")
     */
    public function new(Request $request)
    {
      $post = new Post;
      $form = $this->createForm(PostType::class, $post);
      $form->handleRequest($request);

        if($form->isSubmitted()) {

          $image = $form->get('image')->getData();
          $this->uploadImage($image, $post);
          
          $em = $this->getDoctrine()->getManager();
          $em->persist($post);
          $em->flush();

          return $this->redirectToRoute('admin_post');
        }

      return $this->render('admin/post/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/post/{id}/edit", name="admin_post_edit")
     */
    public function edit(Request $request, Post $post)
    {
      $form = $this->createForm(PostType::class, $post);
      $form->handleRequest($request);

      if($form->isSubmitted()) {
        
        $image = $form->get('image')->getData();
        $this->uploadImage($image, $post);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_post');
      }

      return $this->render('admin/post/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/post/{id}/delete", name="admin_post_delete")
     */
    public function delete(Request $request, Post $post)
    {
      if(!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
        $this->addFlash('danger', 'csrf token is invalid');
        return $this->redirectToRoute('admin_post');
      }

      $em = $this->getDoctrine()->getManager();
      $em->remove($post);
      $em->flush();

      $this->addFlash('success', 'post has deleted');
      return $this->redirectToRoute('admin_post');
    }


    private function uploadImage($image, $post) {
      $filename = uniqid() . '-' . $image->getClientOriginalName();
      $image->move(
        $this->getParameter('post_images_directory'),
        $filename
      );

      $post->setImage($filename);
    }

}
