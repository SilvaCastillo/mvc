<?php

namespace App\blackjack;

use App\blackjack\Hand;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\Card;

class Dealer
{
    private Hand $hand;

    public function __construct()
    {
        $this->hand = new Hand([], 0);
    }

    public function getHand(): Hand
    {
        return $this->hand;
    }

    /**
     * @param CardGraphic[] $cards
     */
    public function addCards(array $cards): void
    {
        foreach ($cards as $card) {
            $this->hand->addCard($card);
        }
    }

    public function drawUntil17(DeckOfCards $deck): void
    {
        while ($this->hand->getValue() < 17) {
            $this->hand->hit($deck);
        }
    }

    public function reset(): void
    {
        $this->hand = new Hand([], 0);
    }
}
