<?php

namespace App\Card;


use App\Card\Card;

class CardGraphic extends Card
{
    private $representation = [
        'H' => '♥',
        'D' => '♦',
        'S' => '♠',
        'C' => '♣',
    ];

    public function __construct(string $value, string $suit)
    {
        parent::__construct($value, $suit);
    }

    public function getSuitSymbol(): string
    {
        return $this->representation[$this->getSuit()];
    }

    public function getAsString(): string
    {
        return $this->getValue() . $this->getSuitSymbol();
    }
}