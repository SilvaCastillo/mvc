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

    public function addCard(): Hand
    {
        return $this->hand->addCard();
    }

    public function drawUntil17(DeckOfCards $deck): void
    {
        while ($this->hand->getValue() < 17) {
            $card = $deck->hit();
            $this->addCard($card);
        }
    }

    public function reset(): void
    {
        $this->hand = new Hand([], 0);
    }
}