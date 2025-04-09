<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: 'index')]
    public function index(): Response
    {

        $data = [
            'name' => 'Home'
        ];

        return $this->render('home.html.twig', $data);
    }

    #[Route("/about", name: 'about')]
    public function about(): Response
    {
        $data = [
            'name' => 'Om kursen mvc'
        ];

        return $this->render('about.html.twig', $data);
    }

    #[Route("/report", name: 'report')]
    public function report(): Response
    {
        $data = [
            'name' => 'Report'
        ];
        return $this->render('report.html.twig', $data);
    }
}
