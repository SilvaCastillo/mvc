<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ApiController extends AbstractController
{
    #[Route('/api', name: 'api')]
    public function api1(): Response
    {


        $data = [
            'name' => 'Api Routes',
        ];

        return $this->render('api.html.twig', $data);
    }


        #[Route('/api/quote', name: 'quote')]
    public function quote(): Response
    {


        $quotes = [
            "It always seems impossible until it’s done. - Nelson Mandela",
            "The only way to do great work is to love what you do. – Steve Jobs",
            "Be yourself; everyone else is already taken. - Oscar Wilde",
            "Knowledge is power. – Francis Bacon",
            "Simplicity is the ultimate sophistication. – Leonardo da Vinci"
        ];


        $randomQuote = $quotes[array_rand($quotes)];
        $dateOfDay = date("d/m/Y");
        date_default_timezone_set('Europe/Stockholm');
        $timeOfGenerate = date("H:i:s");


        $data = [
            'Name' => 'Quote of the day',
            'Date of day' => $dateOfDay,
            'Time of generate' => $timeOfGenerate,
            'Quote of the day' => $randomQuote

        ];

        
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

}
