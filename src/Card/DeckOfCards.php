<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    /**
     * @var CardGraphic[]
     */
    private array $cards = [];

    public function __construct()
    {
        $this->createDeck();
    }

    private function createDeck(): void
    {
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
        $suits = ['S', 'H', 'D', 'C'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new CardGraphic($value, $suit);
            }
        }
    }

    /**
     * @return CardGraphic[]
     */
    public function getDeck(): array
    {
        return $this->cards;
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    /**
     * @return CardGraphic[]|null
    */
    public function draw(int $number = 1): ?array
    {
        if ($number > count($this->cards)) {
            return null;
        }

        $cardsPicked = array();

        for ($x = 0; $x < $number; $x++) {
            $randomKey = array_rand($this->cards, 1);
            $cardsPicked[] = $this->cards[$randomKey];
            unset($this->cards[$randomKey]);
        }
        return $cardsPicked;
    }

    public function getRemaining(): int
    {
        return count($this->cards);
    }
}
