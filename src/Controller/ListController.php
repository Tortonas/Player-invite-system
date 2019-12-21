<?php

namespace App\Controller;

use App\Entity\Recruit;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    /**
     * @Route("/list", name="app_list")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if($request->isMethod('POST'))
        {
            /** @var User $user */
            $user = $this->getUser();
            if($request->request->get('recruited') != null)
            {
                // TODO: Increase recruited count, set action to 'recruited', check if contacted, then disaallow to recruit.

                /** @var Recruit $recruit */
                $recruit = $entityManager->getRepository(Recruit::class)
                    ->findOneBy(array(
                        'id' => $request->request->get('recruited')
                    ));

                if($recruit->getContacted() == 1)
                {
                    $recruit->setAction('recruited');
                    $user->increaseRecruited();

                    $entityManager->persist($user);
                    $entityManager->persist($recruit);
                    $entityManager->flush();

                    $this->addFlash('success', 'This player was successfully recruited!');
                }
                else
                {
                    $this->addFlash('danger', 'This player cannot be recruited without being contacted');
                }
            }
            else if($request->request->get('rejected') != null)
            {
                // TODO: Increase rejected count, set action to 'rejected', check if contacted if not, then disallow to reject.

                /** @var Recruit $recruit */
                $recruit = $entityManager->getRepository(Recruit::class)
                    ->findOneBy(array(
                        'id' => $request->request->get('rejected')
                    ));

                if($recruit->getContacted() == 1)
                {
                    $recruit->setAction('rejected');
                    $user->increaseRejected();

                    $entityManager->persist($user);
                    $entityManager->persist($recruit);
                    $entityManager->flush();

                    $this->addFlash('success', 'This player was successfully rejected!');
                }
                else
                {
                    $this->addFlash('danger', 'This player cannot be recruited without being contacted');
                }
            }
            else if($request->request->get('contacted') != null)
            {
                /** @var Recruit $recruit */
                $recruit = $entityManager->getRepository(Recruit::class)
                    ->findOneBy(array(
                        'id' => $request->request->get('contacted')
                    ));

                $recruit->flipContacted();
                $entityManager->persist($recruit);
                $entityManager->flush();
            }
        }


        /** @var Recruit $recruits */
        $recruits = $entityManager->getRepository(Recruit::class)
            ->findBy(array(
                'action' => 'pending',
                'user' => $this->getUser()->getId()
            ));

        return $this->render('list/index.html.twig', [
            'recruits' => $recruits,
        ]);
    }
}
