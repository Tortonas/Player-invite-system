<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();



        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
