<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    /**
     * @var CardGraphic[] An array of CardGraphic objects containing a full deck.
     */
    private array $cards = [];

    /**
     * CardGraphic constructor.
     *
     *      * Initializes the deck by creating the full deck.
    */
    public function __construct()
    {
        $this->createDeck();
    }

    /**
     * Creates the full 52-card of CardGraphic objects.
     *
     * Generate all combinations of card values and suits, and stores them in the $cards array.
     *
     * @return void
    */
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
     * Returns the current deck of cards.
     *
     * @return CardGraphic[] The array of remaining cards in the deck.
    */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Shuffle the deck of cards randomly.
     *
     * @return void
    */
    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    /**
     * Draws a number of random cards from the deck and removes them.
     *
     * @param int $number of cards to draw (default is 1).
     * @return CardGraphic[]|null An array of drawn cards, or null if not enough cards remain.
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

    /**
     * Returns the number of cards left in the deck.
     *
     * @return int The number of remaining cards.
    */
    public function getRemaining(): int
    {
        return count($this->cards);
    }
}
