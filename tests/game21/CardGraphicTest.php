<?php

namespace App\game;

use App\Card\CardGraphic;
use PHPUnit\Framework\TestCase;

class CardGraphicTest extends TestCase
{
    /**
     * Test that a CardGraphic object can be created and is an instance of the CardGraphic class.
     */
    public function testCreateCardGraphic(): void
    {
        $cardGraphic = new CardGraphic('2', 'H');
        $this->assertInstanceOf(CardGraphic::class, $cardGraphic);
    }



    /**
     * Test that the CardGraphic returns card suit symbol.
     */
    public function testGetSuitSymbol(): void
    {
        $cardGraphic = new CardGraphic('2', 'H');
        $suit = $cardGraphic->getSuitSymbol();
        $expSuit = '♥';
        $this->assertEquals($expSuit, $suit);
    }



    /**
     * Test that card returns as a string.
     */
    public function testGetAsString(): void
    {
        $cardGraphic = new CardGraphic('2', 'H');
        $string = $cardGraphic->getAsString();
        $this->assertIsString($string);
    }
}