<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profile()
    {   
      $user = $this->getUser();
      return $this->render('user/profile.html.twig', ['user' => $user]);
    }
}
