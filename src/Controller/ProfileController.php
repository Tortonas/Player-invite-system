<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if($request->isMethod('POST'))
        {
            /** @var User $loggedUser */
            $loggedUser = $this->getUser();
            if(password_verify($request->request->get('oldPassword'), $loggedUser->getPassword()))
            {
                if($request->request->get('newPassword') == $request->request->get('newPassword2'))
                {
                    $loggedUser->setPlainPassword($request->request->get('newPassword'));

                    $entityManager->persist($loggedUser);
                    $entityManager->flush();

                    $this->addFlash('success', "Your password was succesfully changed!");
                }
                else
                {
                    $this->addFlash('danger', "Your new passwords do not match!");
                }
            }
            else
            {
                $this->addFlash('danger', "Your old password is wrong!");
            }
        }

        return $this->render('profile/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
