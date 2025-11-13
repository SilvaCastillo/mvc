<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\DeckOfCards;
use App\Repository\BookRepository;
use App\Service\CardGameService;
use App\Service\Game21Service;
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
    public function apiDeck(CardGameService $cardGameService): JsonResponse
    {
        $deck = $cardGameService->getDeck();
        $cardsAsString = $cardGameService->getDeckAsString($deck);

        $data = [
            'name' => 'Card Deck',
            'deck' => $cardsAsString,
        ];

        return $this->json($data);
    }


    #[Route('/api/deck/shuffle', name: 'api_shuffle')]
    public function apiDeckShuffle(SessionInterface $session, CardGameService $cardGameService): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $cardsAsString = $cardGameService->getDeckAsString($deck);

        $data = [
            'name' => 'Card Deck',
            'deck' => $cardsAsString,
        ];

        return $this->json($data);
    }


    #[Route("/api/deck/draw", name: "api_draw")]
    public function deckDraw(SessionInterface $session, CardGameService $cardGameService): JsonResponse
    {
        $deck = $cardGameService->getDeck();
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
    public function deckDrawMulti(CardGameService $cardGameService, SessionInterface $session, int $number): Response
    {
        $deck = $cardGameService->getDeck();
        $cardsDrawn = $deck->draw($number);

        if ($cardsDrawn === null) {
            $session->set('deck', $deck);
            return $this->json([
                'success' => false,
                'message' => 'No more cards left!',
            ]);
        }

        $cardsAsString = $cardGameService->getDeckAsString($deck);

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
    public function apiGame(Game21Service $game21): JsonResponse
    {
        $playerIntValue = $game21->getPlayerScore('player');
        $bankerIntValue = $game21->getPlayerScore('banker');

        $playerCardsAsString = $game21->getDrawnCardsAsString('player');
        $bankerCardsAsString = $game21->getDrawnCardsAsString('banker');

        $data = [
            'name' => '21 game',
            'playerScore' => $playerIntValue,
            'playerCards' => $playerCardsAsString,
            'bankerCards' => $bankerCardsAsString,
            'bankerScore' => $bankerIntValue,
        ];

        return $this->json($data);
    }


    #[Route('/api/library/books', name: 'apiBooks')]
    public function apiLibrary(BookRepository $bookRepository): Response
    {
        $books = $bookRepository
            ->findALL();

        $data = [
            'name' => 'Books',
            'books' => $books,
        ];

        return $this->json($data);
    }


    #[Route("/api/library/book/{isbn<\d+>}", name: "apiBookByIsbn")]
    public function apiGetBookByIsbn(BookRepository $bookRepository, int $isbn): Response
    {

        $book = $bookRepository
            ->findBookByIsbn($isbn);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with ISBN '.$isbn
            );
        }


        $data = [
            'name' => 'Book',
            'book' => $book,
        ];

        return $this->json($data);
    }


}
