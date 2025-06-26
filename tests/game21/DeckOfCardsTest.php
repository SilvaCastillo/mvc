<?php

namespace App\game;

use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

class DeckOfCardsTest extends TestCase
{
    /**
     * Test that a DeckOfCards object can be created and is an instance of the DeckOfCards class.
     */
    public function testCreateDeckOfCards(): void
    {
        $deckOfCards = new DeckOfCards();
        $this->assertInstanceOf(DeckOfCards::class, $deckOfCards);
    }



    /**
     * Test that DeckOfCards method getDeck() returns 52 cards.
     */
    public function testGetDeck(): void
    {
        $deckOfCards = new DeckOfCards();
        $deck = $deckOfCards->getDeck();

        $this->assertCount(52, $deck);
    }



    /**
     * Test that the DeckOfCards gets shuffled.
     */
    public function testShuffleDeck(): void
    {
        $deckOfCards = new DeckOfCards();
        $deck = $deckOfCards->getDeck();
        $deckOfCards->shuffle();
        $shuffledDeck = $deckOfCards->getDeck();
        $this->assertNotEquals($deck, $shuffledDeck);
    }



    /**
     * Test that getRemaining returns the correct integer value.
     */
    public function testGetRemainingAsInt(): void
    {
        $deckOfCards = new DeckOfCards();
        $value = $deckOfCards->getRemaining();
        $exp = 52;
        $this->assertEquals($exp, $value);
    }



    /**
     * Test that the draw method returns  an array.
     */
    public function testDrawFromDeck(): void
    {
        $deckOfCards = new DeckOfCards();
        $value = $deckOfCards->draw();
        $this->assertIsArray($value);
    }



    /**
     * Test that the draw method accepts an argument for the nu,ber of cards to draw.
     */
    public function testDrawAmount(): void
    {
        $deckOfCards = new DeckOfCards();
        $deckOfCards->draw(2);
        $deck = $deckOfCards->getDeck();

        $this->assertCount(50, $deck);
    }



    /**
     * Test that the draw method returns null when requested number of cards exceeds the available cards.
     */
    public function testDrawMoreThanAvailable(): void
    {
        $deckOfCards = new DeckOfCards();
        $value = $deckOfCards->draw(53);
        $this->assertNull($value);
    }
}
