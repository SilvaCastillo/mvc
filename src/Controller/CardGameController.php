<?php

namespace App\Controller;

// CardGameService
use App\Card\DeckOfCards;
use App\Service\CardGameService;
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
    public function deck(CardGameService $cardGameService): Response
    {
        $deck = $cardGameService->getDeck();
        $cardsAsString = $cardGameService->getDeckAsString($deck);

        $data = [
            'name' => 'Card Deck',
            'deck' => $cardsAsString,
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "deck_shuffle")]
    public function deckShuffle(SessionInterface $session, CardGameService $cardGameService): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $cardsAsString = $cardGameService->getDeckAsString($deck);

        $data = [
            'name' => 'Card Deck',
            'deck' => $cardsAsString,
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_draw")]
    public function deckDraw(SessionInterface $session, CardGameService $cardGameService): Response
    {


        $deck = $cardGameService->getDeck();
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
    public function deckDrawMulti(CardGameService $cardGameService, SessionInterface $session, int $number): Response
    {
        $deck = $cardGameService->getDeck();
        $cardsDrawn = $deck->draw($number);

        if ($cardsDrawn === null) {
            $session->set('deck', $deck);
            $this->addFlash('warning', 'No more cards left!');
            return $this->redirectToRoute('card_deck');
        }

        $cardsAsString = $cardGameService->getDeckAsString($deck);

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
