<?php

namespace App\game;

use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\Card;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Game21
{
    private SessionInterface $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    public function initializeGame(): void
    {
        $this->session->clear();

        $deck = new DeckOfCards();
        $deck->shuffle();
        $this->session->set('sharedDeck', $deck);
        $this->session->set('drawnPlayerCards', []);
        $this->session->set('drawnBankCards', []);
        $this->session->set('game_start', true);
        $this->session->set("player_turn", True);
    }

    public function drawCardForPlayer(string $who): void
    {
        /** @var DeckOfCards $deck */
        $deck = $this->session->get('sharedDeck');
        $card = $deck->draw();

        if ($card) {
            $key = $who === 'banker' ? 'drawnBankCards' : 'drawnPlayerCards';
            /** @var Card[] $cards */
            $cards = $this->session->get($key, []);
            $cards[] = $card[0];
            $this->session->set($key, $cards);
            $this->session->set('sharedDeck', $deck);
        }
    }


    public function getPlayerScore(string $who): int
    {
        $value = 0;
        $key = $who === 'banker' ? 'drawnBankCards' : 'drawnPlayerCards';
        $drawncards = $this->session->get($key, []);

        /** @var Card[]  $drawncards */
        foreach ($drawncards as $card) {
            $value += $card->getIntValue();
        }
        return $value;
    }


    /**
    * @return string[]
    */
    public function getDrawnCardsAsString(string $who): array
    {
        $cardsAsString = [];
        $key = $who === 'banker' ? 'drawnBankCards' : 'drawnPlayerCards';
        $drawncards = $this->session->get($key, []);

        /** @var Card[]  $drawncards */
        foreach ($drawncards as $card) {
            $cardsAsString[] = $card->getAsString();
        }
        return $cardsAsString;
    }


    public function checkWinner(): string
    {
        /** @var int $player */
        $player = $this->session->get('deckValueIntPlayer');

        /** @var int $banker */
        $banker = $this->session->get('deckValueIntBanker');
        $winner = "";
        
        if ($player > 21) {
            $winner = sprintf("Bank wins with %d vs %d", $banker, $player );
        } elseif ($banker > 21) {
            $winner = sprintf("Player wins with %d vs %d", $player, $banker );
        } elseif ($banker >= $player) {
            $winner = sprintf("Bank wins with %d vs %d", $banker, $player );
        } elseif ($player > $banker){
            $winner = sprintf("Player wins with %d vs %d", $player, $banker );
        }
        return $winner;
    }
}
