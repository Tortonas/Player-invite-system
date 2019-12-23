<?php

namespace App\Controller;

use App\Entity\Recruit;
use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @param Request $request
     * @return Response
     * @throws DBALException
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
                if($this->canITakeOrSkipPlayer())
                {
                    $this->addFlash('danger','I\'m sorry, you\'ve hit your daily limit which is ' . $this->getUser()->getDailyLimit());
                }
                else
                {
                    $recruit->setUser($loggedUser);
                    $recruit->setTakenDate(new \DateTime('now'));
                    $loggedUser->increaseTotalTaken();
                    $entityManager->persist($recruit);
                    $entityManager->persist($loggedUser);
                    $entityManager->flush();
                    $this->addFlash('success', 'Successfully added!');
                }
            }
            else if($request->request->get('skipPlayerBtn') == 'clicked')
            {
                if($this->canITakeOrSkipPlayer())
                {
                    $this->addFlash('danger','I\'m sorry, you\'ve hit your daily limit which is ' . $this->getUser()->getDailyLimit());
                }
                else
                {
                    $this->addFlash('success', 'Successfully skipped!');
                    $recruit->setAction('skipped');
                    $loggedUser->increaseSkipped();
                    $recruit->setUser($loggedUser);
                    $recruit->setTakenDate(new \DateTime('now'));

                    $entityManager->persist($recruit);
                    $entityManager->persist($loggedUser);
                    $entityManager->flush();
                }
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

    /**
     * @return bool
     */
    public function canITakeOrSkipPlayer()
    {
        /** @var Connection $conn */
        $conn = $this->getDoctrine()->getConnection();

        $sql = 'SELECT * FROM recruit WHERE user_id = :user_id AND taken_date >= CURDATE()';

        try
        {
            $stmt = $conn->prepare($sql);
        }
        catch (DBALException $e)
        {
            $this->addFlash('danger', 'SQL error in HomeController!');
        }
        $stmt->execute(array(
            'user_id' => $this->getUser()->getId()
        ));

        return $stmt->rowCount() >= $this->getUser()->getDailyLimit();
    }
}
