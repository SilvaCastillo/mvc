<?php

namespace App\Card;

class Card
{
    private string $value;
    private string $suit;

    /**
     * @var array<string, int>
    */
    private array $representation = [
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => 14,
    ];

    public function __construct(string $value, string $suit)
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getIntValue(): int
    {
        $value = $this->getValue();
        if (isset($this->representation[$value])) {
            return $this->representation[$value];
        }
        return intval($value);
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getAsString(): string
    {
        return $this->value . $this->suit;
    }

}
