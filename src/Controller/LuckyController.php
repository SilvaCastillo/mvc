<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    #[Route('/lucky', name: 'lucky')]
    public function number(): Response
    {
        $number = random_int(0, 100);


        $data = [
            'name' => 'Luckynumer',
            'number' => $number
        ];

        return $this->render('lucky.html.twig', $data);
    }
}
