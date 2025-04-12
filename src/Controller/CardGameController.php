<?php

namespace App\Controller;

use App\Card\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function deck(SessionInterface $session): Response
    {
        if (!$session->has("deck")) {
            $deck = $this->card->deck();
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

        $shuffleDeck = $this->card->shuffleDeck();

        $session->set('deck', $shuffleDeck);

        $data = [
            'name' => 'Card Deck',
            'deck' => $shuffleDeck,
        ];

        return $this->render('card/deck.html.twig', $data);
    }


    #[Route("/card/deck/draw", name: "card_draw")]
    public function deckDraw(SessionInterface $session): Response
    {

        if (!$session->has("deck")) {
            $drawCard = $this->card->drawCard();
        } else {
            $drawData = $session->get("deck");
            $deck = $drawData['deck']; 
            $drawCard = $this->card->drawCard(1, $deck);

            if ($drawCard === null) {
                $session->set('deck', $drawCard);
                $this->addFlash('warning', 'No more cards left!');
                return $this->redirectToRoute('card_deck'); 
            }
        }

        $session->set('deck', $drawCard);

        $data = [
            'name' => 'Card Draw',
            'card' => $drawCard,
        ];

        return $this->render('card/draw.html.twig', $data);
    }


    #[Route("/card/deck/draw/{number<\d+>}", name: "draw_amount")]
    public function deckDrawMulti(SessionInterface $session, int $number): Response
    {

        if (!$session->has("deck")) {
            $drawCard = $this->card->drawCard($number);
        } else {
            $drawData = $session->get("deck");
            $deck = $drawData['deck']; 
            $drawCard = $this->card->drawCard($number, $deck);

            if ($drawCard === null) {
                $session->set('deck', $drawCard);
                $this->addFlash('warning', 'No more cards left!');
                return $this->redirectToRoute('card_deck'); 
            }
        }

        $session->set('deck', $drawCard);

        $data = [
            'name' => 'Card Draw',
            'card' => $drawCard,
        ];

        return $this->render('card/draw.html.twig', $data);
    }

}
