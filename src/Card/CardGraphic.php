<?php

namespace App\Card;

use App\Card\Card;

class CardGraphic extends Card
{
    /**
     * Maps suit letters to their matching Unicode symbols.
     *
     * @var array<string, string> One of ['H' => '♥', 'D' => '♦', 'S' => '♠', 'C' => '♣']
    */
    private array $representation = [
        'H' => '♥',
        'D' => '♦',
        'S' => '♠',
        'C' => '♣',
    ];

    /**
     * CardGraphic constructor.
     *
     * @param string $value The card's face value ('2'–'10', 'J', 'Q', 'K', 'A')
     * @param string $value The card's suit ('H', 'S', 'D', or 'C').
    */
    public function __construct(string $value, string $suit)
    {
        parent::__construct($value, $suit);
    }

    /**
     * Returns the Unicode symbol of the card's suit.
     *
     * @return string of '♥', '♦', '♠' or '♣'.
     */
    public function getSuitSymbol(): string
    {
        return $this->representation[$this->getSuit()];
    }

    /**
     * Returns the card as a string with value and suit symbol.
     *
     * @return string A string like '5♥', 'K♦' or 'A♣'.
     */
    public function getAsString(): string
    {
        return $this->getValue() . $this->getSuitSymbol();
    }
}
