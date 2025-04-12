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


    public function drawCard( $number = 1, array $deck = [])
    {
        if (empty($deck)) {
            $deck = $this->createCard();
        } 
        else {
            $deck = $deck ;
        }

        if ($number > count($deck)) {
            return null;
        }

        $cardsPicked = array();

        for ($x = 0; $x < $number; $x++) {
            $randomKey = array_rand($deck, 1);
            $cardsPicked[] = $deck[$randomKey];
            unset($deck[$randomKey]);
        }

        $count = count($deck);

        $data = [
            'card' => $cardsPicked,
            'cardsRemaining' => $count,
            'deck' => $deck,
        ];

        return $data;
    }

}
