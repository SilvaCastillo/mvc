<?php

namespace App\Card;


class Card
{

    public function createCard()
    {
        $values = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
        $suits = array('S', 'H', 'D', 'C');
        $cards = array();
        foreach ($suits as $suit) {
			foreach ($values as $value) {
				$cards[] = $value . $suit;
			}
		};

        return $cards;
    }


    public function deck(): array
    {
        $deck = $this->createCard();

        $data = [
            'deck' => $deck
        ];

        return $data;

    }

    public function shuffleDeck(): array
    {
        $deck = $this->createCard();
        shuffle($deck);
        $data = [
            'deck' => $deck
        ];

        return $data;

    }


}