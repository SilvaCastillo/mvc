<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        return $this->json($data);
    }


    #[Route('/api/deck', name: 'deck')]
    public function apiDeck(SessionInterface $session): JsonResponse
    {
        if (!$session->has("deck")) {
            $deck = new DeckOfCards();
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

        return $this->json($data);
    }


    #[Route('/api/deck/shuffle', name: 'api_shuffle')]
    public function apiDeckShuffle(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $getDeck = $deck->getDeck();
        $cardsAsString = array();
        foreach ($getDeck as $card) {
            $cardsAsString[] = $card->getAsString();
        }

        $session->set('deck', $deck);

        $data = [
            'name' => 'Card Deck',
            'deck' => $cardsAsString,
        ];

        return $this->json($data);
    }


    #[Route("/api/deck/draw", name: "api_draw")]
    public function deckDraw(SessionInterface $session): JsonResponse
    {

        if (!$session->has("deck")) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        /** @var DeckOfCards $deck */
        $deck = $session->get("deck");
        $cardDrawn = $deck->draw();

        if ($cardDrawn === null) {
            $session->set('deck', $deck);
            return $this->json([
                'success' => false,
                'message' => 'No more cards left!',
            ]);
        }


        $cardsAsString = array();
        $cardsAsString[] = $cardDrawn[0]->getAsString();
        $session->set('deck', $deck);
        $remainingCards = $deck->getRemaining();


        $data = [
            'name' => 'Card Draw',
            'card' => $cardsAsString,
            'remainingCards' => $remainingCards,
        ];

        return $this->json($data);
    }

    #[Route("/api/deck/draw/{number<\d+>}", name: "api_draw_amount")]
    public function deckDrawMulti(SessionInterface $session, int $number): Response
    {

        if (!$session->has("deck")) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        /** @var DeckOfCards $deck */
        $deck = $session->get("deck");
        $cardsDrawn = $deck->draw($number);

        if ($cardsDrawn === null) {
            $session->set('deck', $deck);
            return $this->json([
                'success' => false,
                'message' => 'No more cards left!',
            ]);
        }

        $cardsAsString = array();
        foreach ($cardsDrawn as $card) {
            $cardsAsString[] = $card->getAsString();

        }

        $session->set('deck', $deck);
        $remainingCards = $deck->getRemaining();

        $data = [
            'name' => 'Card Draw',
            'card' => $cardsAsString,
            'remainingCards' => $remainingCards,
        ];

        return $this->json($data);
    }


    /**
    * @SuppressWarnings("UnusedLocalVariable")
    */
    #[Route('/api/game', name: 'api_game')]
    public function apiGame(SessionInterface $session): JsonResponse
    {
        $players = ['player', 'banker'];
        $playerCards = $session->get('drawnPlayerCards', []);
        $bankerCards = $session->get('drawnBankCards', []);
        $playerIntValue = 0;
        $bankerIntValue = 0;
        $playerCardsAsString = "";
        $bankerCardsAsString = "";

        foreach ($players as $player) {
            /** @phpstan-ignore-next-line */
            foreach (${$player . "Cards"} as $card) {
                ${$player . "IntValue"} += $card->getIntValue();
                ${$player . "CardsAsString"} = $card->getAsString();
            }
        }

        $data = [
            'name' => '21 game',
            'playerScore' => $playerIntValue,
            'playerCards' => $playerCardsAsString,
            'bankerCards' => $bankerCardsAsString,
            'bankerScore' => $bankerIntValue,
        ];

        return $this->json($data);
    }
}
