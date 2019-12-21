<?php

namespace App\Controller;

use App\Entity\Recruit;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        date_default_timezone_set("Europe/Vilnius"); // move this later somewhere else


        $entityManager = $this->getDoctrine()->getManager();

        /** @var Recruit $recruit */
        $recruit = $entityManager->getRepository(Recruit::class)
            ->findOneBy(array(
                'action' => 'pending',
                'user' => null
            ));

        if($request->isMethod('POST'))
        {
            /** @var User $loggedUser */
            $loggedUser = $this->getUser();
            if($request->request->get('steamlinkBtn') == 'clicked')
            {
                return $this->redirect($recruit->getSteamLink());
            }
            else if ($request->request->get('takePlayerBtn') == 'clicked')
            {
                $recruit->setUser($loggedUser);
                $recruit->setTakenDate(new \DateTime('now'));
                $loggedUser->increaseTotalTaken();
                $entityManager->persist($recruit);
                $entityManager->persist($loggedUser);
                $entityManager->flush();
            }
            else if($request->request->get('skipPlayerBtn') == 'clicked')
            {
                $recruit->setAction('skipped');
                $loggedUser->increaseSkipped();
                $entityManager->persist($recruit);
                $entityManager->persist($loggedUser);
                $entityManager->flush();
            }

            /** @var Recruit $recruit */
            $recruit = $entityManager->getRepository(Recruit::class)
                ->findOneBy(array(
                    'action' => 'pending',
                    'user' => null
                ));
        }

        return $this->render('home/index.html.twig', [
            'recruit' => $recruit,
        ]);
    }
}
