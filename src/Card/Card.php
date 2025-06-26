<?php

namespace App\Card;

class Card
{
    private string $value;
    private string $suit;

    /**
     * Maps suit letters to their matching value.
     * @var array<string, int>
    */
    private array $representation = [
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => 14,
    ];



    /**
     * Card constructor
     *
     * @param string $value The value of the card ('2'-'10', 'J', 'Q', 'K' or 'A')
     * @param string $suit The suit of the card ('H', 'S', 'D', or 'C')
    */
    public function __construct(string $value, string $suit)
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    /**
     * Returns the card's face value as string
     *
     * @return string The card's value, '2'-'10', 'J', 'Q', 'K' or 'A'
    */
    public function getValue(): string
    {
        return $this->value;
    }


    /**
     * Returns the card's value as integer
     *
     * Face cards like 'J', 'Q', 'K' and 'A' are converted to 11-14.
     * Number cards are returned as-is
     *
     * @return int The integer value of the card
    */
    public function getIntValue(): int
    {
        $value = $this->getValue();
        if (isset($this->representation[$value])) {
            return $this->representation[$value];
        }
        return intval($value);
    }


    /**
     * Returns the card's suit as a single character string.
     *
     * @return string One of 'H', 'S', 'D' or 'C'
    */
    public function getSuit(): string
    {
        return $this->suit;
    }


    /**
     * Returns the card's value and suit as a string.
     *
     * @return string The card representation as a string
    */
    public function getAsString(): string
    {
        return $this->value . $this->suit;
    }

}
