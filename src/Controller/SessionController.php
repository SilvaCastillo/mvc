<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionController extends AbstractController
{
    #[Route("/session", name: "session_show")]
    public function show(SessionInterface $session): Response
    {

        $allSession = $session->all();


        $data = [
            'name' => 'Home',
            'sessions' => $allSession
        ];

        return $this->render('session/show.html.twig', $data);
    }

    #[Route("/session/delete", name: "session_delete")]
    public function delete(SessionInterface $session): Response
    {

        $deletedSession = $session->clear();
        $this->addFlash('success', 'Session has been deleted.');

        return $this->redirectToRoute('session_show');
    }
}
