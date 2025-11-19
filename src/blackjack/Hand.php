<?php

namespace App\blackjack;

use App\Card\DeckOfCards;
use App\Card\Card;

class Hand
{
    private array $cards = [];
    private int $bet = 0;
    private bool $isStanding = false;


    public function __construct(array $cards, int $bet)
    {
        $this->cards = $cards;
        $this->bet = $bet;
    }

    public function addCard($card): void
    {
        $this->cards[] = $card;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function hit(DeckOfCards $deck): void
    {
        $card = $deck->draw()[0];

        $card->addCard($card);
    }

    public function stand(): void
    {
        $this->isStanding = true;
    }

    public function isStanding(): bool
    {
        return $this->isStanding;
    }

    public function getValue(): int
    {
        $value = 0;
        $aces = 0;

        /** @var Card[]  $drawncards */
        foreach ($this->cards as $card) {
            $cardValue = $card->getIntValue();

            if ($cardValue === 14) {
                $aces++;
                $value += 11;
            } elseif (in_array($cardValue, ['J', 'Q', 'K'])) {
                $value += 10;
            } else {
                $value += $cardValue;
            }
        }

        while ($value > 21 && $aces > 0) {
                $value -= 10;
                $aces--;
            }

        return $value;
    }

}