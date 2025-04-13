<?php

namespace App\Controller;

// use App\Card\Card;
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
            $cards = new DeckOfCards();
            $deck = $cards->getDeck();
            $session->set('deck', $cards);
        } else {
            $deck = $session->get("deck");
        }

        $data = [
            'name' => 'Card Deck',
            'deck' => $deck,
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "deck_shuffle")]
    public function deckShuffle(SessionInterface $session): Response
    {

        $cards = new DeckOfCards();
        $shuffleDeck = $cards->shuffle();
        $deck = $cards->getDeck();

        $session->set('deck', $cards);

        $data = [
            'name' => 'Card Deck',
            'deck' => $deck,
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_draw")]
    public function deckDraw(SessionInterface $session): Response
    {

        if (!$session->has("deck")) {
            $cards = new DeckOfCards();
            $deck = $cards->getDeck();
        } else {
            $deck = $session->get("deck");

            $drawCard = $deck->draw();

            if ($drawCard === null) {
                $session->set('deck', $deck);
                $this->addFlash('warning', 'No more cards left!');
                return $this->redirectToRoute('card_deck'); 
            }
        }
        
        $session->set('deck', $deck);
        $remainingCards = $deck->getRemaining();

        $data = [
            'name' => 'Card Draw',
            'card' => $drawCard,
            'remainingCards' => $remainingCards,
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "draw_amount")]
    public function deckDrawMulti(SessionInterface $session, int $number): Response
    {

        if (!$session->has("deck")) {
            $cards = new DeckOfCards();
            $deck = $cards->getDeck();
        } else {
            $deck = $session->get("deck");
            $drawCard = $deck->draw($number);

            if ($drawCard === null) {
                $session->set('deck', $deck);
                $this->addFlash('warning', 'No more cards left!');
                return $this->redirectToRoute('card_deck'); 
            }
        }

        $session->set('deck', $deck);
        $remainingCards = $deck->getRemaining();

        $data = [
            'name' => 'Card Draw',
            'card' => $drawCard,
            'remainingCards' => $remainingCards,
        ];

        return $this->render('card/draw.html.twig', $data);
    }

}
