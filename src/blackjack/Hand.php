<?php

namespace App\blackjack;

use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\Card;
use UnderflowException;

class Hand
{
    /** @var Card[] */
    private array $cards = [];
    private int $bet = 0;
    private bool $isStanding = false;


    /**
     * @param array<int, Card> $cards
     */
    public function __construct(array $cards, int $bet)
    {
        $this->cards = $cards;
        $this->bet = $bet;
    }

    public function addCard(CardGraphic $card): void
    {
        $this->cards[] = $card;
    }

    /**
     * @return array<int, Card>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function getBet(): int
    {
        return $this->bet;
    }

    public function hit(DeckOfCards $deck): void
    {
        $cards = $deck->draw();

        if ($cards === null) {
            throw new UnderflowException('Deck is empty!');
        }

        $card = $cards[0];

        $this->addCard($card);
    }

    public function stand(): void
    {
        $this->isStanding = true;
    }

    public function isBusted(): bool
    {
        $value = $this->getValue();

        if ($value > 21) {
            return true;
        }

        return false;
    }

    public function isBlackJack(): bool
    {
        return $this->getValue() === 21;

    }

    public function isStanding(): bool
    {
        return $this->isStanding;
    }

    public function getValue(): int
    {
        $value = 0;
        $aces = 0;

        foreach ($this->cards as $card) {
            $cardValue = $card->getIntValue();
            $add = $cardValue;

            if ($cardValue === 14) {
                $aces++;
                $add = 11;
            }

            if (in_array($cardValue, [11, 12, 13])) {
                $add = 10;
            }

            $value += $add;
        }

        while ($value > 21 && $aces > 0) {
            $value -= 10;
            $aces--;
        }

        return $value;
    }
}
