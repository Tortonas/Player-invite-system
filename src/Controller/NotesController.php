<?php

namespace App\Controller;

use App\Entity\Recruit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{
    /**
     * @Route("/list/viewnote/{id}", name="app_viewnote")
     * @param int $id
     * @param Request $request
     * @Security("is_granted('ROLE_ADMIN')")
     * @return Response
     */
    public function index(int $id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Recruit $recruit */
        $recruit = $entityManager->getRepository(Recruit::class)->find($id);

        if($request->get('saveBtn') == 'save')
        {
            $recruit->setNote($request->get('noteText'));

            $entityManager->persist($recruit);
            $entityManager->flush();

            $this->addFlash('success', 'Succesfully saved!');
        }

        $canIPressSave = true;

        if($recruit->getUser()->getId() == $this->getUser()->getId())
        {
            $canIPressSave = true;
        }
        else
        {
            if($this->isGranted('ROLE_SUPER_ADMIN'))
            {
                $canIPressSave = true;
            }
            else
            {
                $canIPressSave = false;
            }
        }

        return $this->render('notes/index.html.twig', [
            'recruit' => $recruit,
            'canEdit' => $canIPressSave
        ]);
    }
}
