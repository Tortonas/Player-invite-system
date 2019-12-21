<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $this->getUser()
        ]);
    }
}
