<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use App\Card\CardGraphic;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'game_info')]
    public function apiGame(): Response
    {

        $data = [
            'name' => '21 Card Game',
        ];

        return $this->render('game/home.html.twig', $data);
    }

    /**
    * @SuppressWarnings("ElseExpression")
    * @SuppressWarnings("CyclomaticComplexity")
    */
    #[Route('/game/init', name: 'game')]
    public function gameInit(SessionInterface $session): Response
    {
        $players = array("Player", "Bank");
        $drawDeckBank = $session->get('drawDeckBank', []);
        $drawDeckPlayer = $session->get('drawDeckPlayer', []);

        $winner = "";

        $cardsAsStringPlayer = array();
        $deckValueIntPlayer = 0;

        $cardsAsStringBank = array();
        $deckValueIntBank = 0;
        $decks = [];

        if (!$session->has("game_start")) {
            foreach ($players as $player) {
                $decks[$player] = new DeckOfCards();
                $session->set('deck' . $player, $decks[$player] );
            }
            $session->set("game_start", True);
            $session->set("player_turn", True);
        }

        $playerTurn = $session->get("player_turn");
        // @phpmd ignore ElseExpression
        if ($playerTurn === True) {
            /** @var DeckOfCards $playerDeck */
            $playerDeck = $session->get("deckPlayer");
            $cardDrawnPlayer = $playerDeck->draw();
            $session->set("deckPlayer", $playerDeck);
            $drawDeckPlayer = $session->get('drawDeckPlayer', []);
            $drawDeckPlayer[] = $cardDrawnPlayer[0]; // @phpstan-ignore-line
            $session->set("drawDeckPlayer", $drawDeckPlayer);

        } else {
            while ($deckValueIntBank < 21) {
            /** @var DeckOfCards $bankDeck */
            $bankDeck = $session->get("deckBank");
            $cardDrawnBank = $bankDeck->draw();

            if (!is_array($cardDrawnBank) || !isset($cardDrawnBank[0])) {
                break; // Prevents trying to access offset on null
            }

            $session->set("deckBank", $bankDeck);
            $drawDeckBank = $session->get('drawDeckBank', []);
            $drawDeckBank[] = $cardDrawnBank[0]; // @phpstan-ignore-line
            $deckValueIntBank += $cardDrawnBank[0]->getIntValue();
            $session->set("drawDeckBank", $drawDeckBank);
            };
        }

        $deckValueIntBank = 0;
        foreach ($players as $player) {
            /** @phpstan-ignore-next-line */
            foreach (${"drawDeck" . $player} as $value) {
                ${"deckValueInt" . $player} += $value->getIntValue();
                ${"cardsAsString" . $player}[] = $value->getAsString(); // @phpstan-ignore-line
            }
            if ($deckValueIntPlayer > 21) { // @phpstan-ignore-line
                $winner = "Bank wins with $deckValueIntBank vs $deckValueIntPlayer";
                $session->set("game_start", False);
            } elseif ($deckValueIntBank > 21) { // @phpstan-ignore-line
                $winner = "Player wins with $deckValueIntPlayer vs $deckValueIntBank";
                $session->set("game_start", False);
            } elseif ($deckValueIntBank >= $deckValueIntPlayer) { // @phpstan-ignore-line
                $winner = "Bank wins with $deckValueIntBank vs $deckValueIntPlayer";
                $session->set("game_start", False);
            }
        }

        $gameStatus = $session->get('game_start');

        $data = [
            'name' => 'Game 21',
            'playerCards' => $cardsAsStringPlayer,
            'playerDeckValue' => $deckValueIntPlayer,
            'bankCards' => $cardsAsStringBank,
            'bankDeckValue' => $deckValueIntBank,
            'winner' => $winner,
            'gameStatus' => $gameStatus,

        ];

        return $this->render('game/draw.html.twig', $data);
    }

    #[Route('/game/stand', name: 'stand')]
    public function gameStand(SessionInterface $session): Response
    {
        $session->set("player_turn", False);

        return $this->redirectToRoute('game');
    }

    #[Route('/game/reset', name: 'restart_game')]
    public function gameReset(SessionInterface $session): Response
    {
        $session->clear();

        return $this->redirectToRoute('game');
    }
}
