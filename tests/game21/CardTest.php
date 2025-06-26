<?php

namespace App\game;

use App\Card\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    /**
     * Test that a Card object can be created and is an instance of the Card class.
     */
    public function testCreateCard(): void
    {
        $card = new Card('2', 'H');
        $this->assertInstanceOf(Card::class, $card);
    }



    /**
     * Test that the card's value is correctly stored and returned.
     */
    public function testCreateCardValue(): void
    {
        $card = new Card('2', 'H');
        $value = $card->getValue();
        $exp = '2';
        $this->assertEquals($exp, $value);
    }



    /**
     * Test that face cards returns the corrects integer value.
     */
    public function testIntValueOfHighCards(): void
    {
        $card = new Card('A', 'H');
        $value = $card->getIntValue();
        /** @phpstan-ignore method.alreadyNarrowedType */
        $this->assertIsInt($value);
    }



    /**
     * Test that number cards returns the corrects integer value.
     */
    public function testIntValueOfNumberCards(): void
    {
        $card = new Card('2', 'H');
        $value = $card->getIntValue();
        /** @phpstan-ignore method.alreadyNarrowedType */
        $this->assertIsInt($value);
    }



    /**
     * Test that the card's suit is correctly stored and returned.
     */
    public function testCreateCardSuit(): void
    {
        $card = new Card('2', 'H');
        $value = $card->getSuit();
        $exp = 'H';
        $this->assertEquals($exp, $value);
    }



    /**
     * Test that the card is returned as a string combining value and suit.
     */
    public function testCreateCardAsString(): void
    {
        $card = new Card('2', 'H');
        $value = $card->getAsString();
        $exp = '2H';
        $this->assertEquals($exp, $value);
    }
}
