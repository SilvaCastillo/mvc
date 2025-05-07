<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use App\Card\CardGraphic;

use App\game\Game21;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'game_info')]
    public function gameRules(): Response
    {

        $data = [
            'name' => '21 Card Game',
        ];

        return $this->render('game/home.html.twig', $data);
    }


    #[Route('/game/init', name: 'game_init')]
    public function gameInit(Game21 $game21): Response
    {
        $game21->initializeGame();
        return $this->redirectToRoute('game_play');
    }


    /**
    * @SuppressWarnings("PHPMD.ElseExpression")
    */
    #[Route('/game/game_play', name: 'game_play')]
    public function gamePlay(SessionInterface $session, Game21 $game21): Response
    {
        $winner = " ";
        $playerTurn = $session->get("player_turn");
        if ($playerTurn === True) {

            $game21->drawCardForPlayer("player");
        } else {
            $deckValueIntBanker = $game21->getPlayerScore("banker");

            while ($deckValueIntBanker < 17) {
                $game21->drawCardForPlayer("banker");
                $deckValueIntBanker = $game21->getPlayerScore("banker");

            }
            $session->set("game_start", False);
        }

        $deckValueIntPlayer = $game21->getPlayerScore("player");
        $deckValueIntBanker = $game21->getPlayerScore("banker");

        $cardsAsStringPlayer = $game21->getDrawnCardsAsString("player");
        $cardsAsStringBanker = $game21->getDrawnCardsAsString("banker");

        $session->set("deckValueIntPlayer", $deckValueIntPlayer);
        $session->set("deckValueIntBanker", $deckValueIntBanker);

        if ($deckValueIntPlayer > 21) {
            $session->set("game_start", False);
        }

        $gameStatus = $session->get('game_start');

        if ($gameStatus == False) {
            $winner = $game21->checkWinner();
        }

        $data = [
            'name' => 'Game 21',
            'playerCards' => $cardsAsStringPlayer,
            'playerDeckValue' => $deckValueIntPlayer,
            'bankCards' => $cardsAsStringBanker,
            'bankDeckValue' => $deckValueIntBanker,
            'winner' => $winner,
            'gameStatus' => $gameStatus,
        ];

        return $this->render('game/draw.html.twig', $data);
    }

    #[Route('/game/stand', name: 'stand')]
    public function gameStand(SessionInterface $session): Response
    {
        $session->set("player_turn", False);

        return $this->redirectToRoute('game_play');
    }


    #[Route('/game/doc', name: 'doc')]
    public function gameDoc(): Response
    {
        $data = [
            'name' => '21 Card Game',
        ];

        return $this->render('game/doc.html.twig', $data);
    }
}
