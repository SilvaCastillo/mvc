<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
        $data = [
            'name' => 'Card Game'
        ];

        return $this->render('card/home.html.twig', $data);
    }

    #[Route("/card/deck", name: "card_deck")]
    public function deck(SessionInterface $session): Response
    {
        if (!$session->has("deck")) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }

        /** @var DeckOfCards $deck */
        $deck = $session->get("deck");
        $getDeck = $deck->getDeck();

        $cardsAsString = array();
        foreach ($getDeck as $card) {
            $cardsAsString[] = $card->getAsString();
        }

        $data = [
            'name' => 'Card Deck',
            'deck' => $cardsAsString,
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "deck_shuffle")]
    public function deckShuffle(SessionInterface $session): Response
    {

        $deck = new DeckOfCards();
        $deck->shuffle();
        $getDeck = $deck->getDeck();

        $session->set('deck', $deck);

        $cardsAsString = array();
        foreach ($getDeck as $card) {
            $cardsAsString[] = $card->getAsString();
        }

        $data = [
            'name' => 'Card Deck',
            'deck' => $cardsAsString,
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_draw")]
    public function deckDraw(SessionInterface $session): Response
    {

        if (!$session->has("deck")) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }

        /** @var DeckOfCards $deck */
        $deck = $session->get("deck");
        $cardDrawn = $deck->draw();

        if ($cardDrawn === null) {
            $session->set('deck', $deck);
            $this->addFlash('warning', 'No more cards left!');
            return $this->redirectToRoute('card_deck');
        }


        $cardsAsString = array();
        $cardsAsString[] = $cardDrawn[0]->getAsString();
        $session->set('deck', $deck);
        $remainingCards = $deck->getRemaining();

        $data = [
            'name' => 'Card Draw',
            'cards' => $cardsAsString,
            'remainingCards' => $remainingCards,
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "draw_amount")]
    public function deckDrawMulti(SessionInterface $session, int $number): Response
    {

        if (!$session->has("deck")) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }

        /** @var DeckOfCards $deck */
        $deck = $session->get("deck");
        $cardsDrawn = $deck->draw($number);

        if ($cardsDrawn === null) {
            $session->set('deck', $deck);
            $this->addFlash('warning', 'No more cards left!');
            return $this->redirectToRoute('card_deck');
        }

        $cardsAsString = array();
        foreach ($cardsDrawn as $card) {
            $cardsAsString[] = $card->getAsString();
        }


        $session->set('deck', $deck);
        $remainingCards = $deck->getRemaining();

        $data = [
            'name' => 'Card Draw',
            'cards' => $cardsAsString,
            'remainingCards' => $remainingCards,
        ];

        return $this->render('card/draw.html.twig', $data);
    }

}
