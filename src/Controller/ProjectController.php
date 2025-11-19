<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route("/proj", name: 'proj')]
    public function projIndex(): Response
    {

        $data = [
            'name' => 'Black Jack'
        ];

        return $this->render('project/home.html.twig', $data);
    }

    #[Route("/proj/about", name: 'proj_about')]
    public function projAbout(): Response
    {

        $data = [
            'name' => 'About The Project'
        ];

        return $this->render('project/about.html.twig', $data);
    }

    #[Route("/proj/game", name: 'blackjack_form')]
    public function projGame(): Response
    {

        $data = [
            'name' => 'Black Jack'
        ];

        return $this->render('project/addBook.html.twig', $data);
    }

}
