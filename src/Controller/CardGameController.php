<?php

namespace App\Controller;

use App\Card\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    private Card $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
        $data = [
            'name' => 'Card Game'
        ];

        return $this->render('card/home.html.twig', $data);
    }


    #[Route("/card/deck", name: "card_deck")]
    public function deck(): Response
    {

        // $deck = new \App\Card\deck();
        $deck = $this->card->deck(); 

        $data = [
            'name' => 'Card Deck',
            'deck' => $deck,
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "deck_shuffle")]
    public function deckShuffle(): Response
    {

        $shuffleDeck = $this->card->shuffleDeck();

        $data = [
            'name' => 'Card Deck',
            'deck' => $shuffleDeck,
        ];

        return $this->render('card/deck.html.twig', $data);
    }


    #[Route("/card/deck/draw", name: "card_draw")]
    public function deckDraw(): Response
    {
        $data = [
            'name' => 'Card Draw'
        ];

        return $this->render('card/draw.html.twig', $data);
    }

}