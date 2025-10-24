<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MetricsController extends AbstractController
{
    #[Route("/metrics", name: 'metrics')]
    public function metricsIndex(): Response
    {

        $data = [
            'name' => 'Metrics analys'
        ];

        return $this->render('metrics.html.twig', $data);
    }

}
