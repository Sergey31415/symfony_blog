<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
      $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index()
    {
      $categories = $this->categoryRepository->findAll();

      return $this->render('admin/category/index.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/admin/category/new", name="admin_category_new" )
     */
    public function new(Request $request)
    {
      $category = new Category;
      $form = $this->createForm(CategoryType::class, $category);
      $form->handleRequest($request);

      if($form->isSubmitted()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return $this->redirectToRoute('admin_category');
      }

      return $this->render('admin/category/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/category/{id}/edit", name="admin_category_edit")
     */
    public function edit(Request $request, Category $category)
    {
      $form = $this->createForm(CategoryType::class, $category);
      $form->handleRequest($request);

      if($form->isSubmitted()) {
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_category');
      }

      return $this->render('admin/category/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/category/{id}/delete", name="admin_category_delete")
     */
    public function delete(Category $category, Request $request)
    {
      if(!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
        return $this->redirectToRoute('admin_category');
      }

      $em = $this->getDoctrine()->getManager();
      $em->remove($category);
      $em->flush();

      $this->addFlash('success', 'category has deleted');
      return $this->redirectToRoute('admin_category');
    }
}
