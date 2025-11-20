<?php

namespace App\blackjack;

use App\blackjack\Hand;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

class HandTest extends TestCase
{
    private DeckOfCards $deckOfCards;

    /** @var CardGraphic[] */
    private $cards;

    private Hand $hand;


    protected function setUp(): void
    {
        $this->deckOfCards = new DeckOfCards();
        $cards = $this->deckOfCards->draw(2);
        self::assertNotNull($cards);

        $this->cards = $cards;

        $this->hand = new Hand($this->cards, 100);
    }

    public function testCreateHand(): void
    {
        $this->assertEquals(100, $this->hand->getBet());
        $this->assertCount(2, $this->hand->getCards());
    }


    public function testAddCardToHand(): void
    {
        $newCards = $this->deckOfCards->draw(1);
        self::assertNotNull($newCards);

        $this->hand->addCard($newCards[0]);

        $this->assertCount(3, $this->hand->getCards());
    }

    public function testHit(): void
    {
        $this->hand->hit($this->deckOfCards);
        $this->assertCount(3, $this->hand->getCards());
    }

    public function testStandTrue(): void
    {
        $this->hand->stand();
        $stand = $this->hand->isStanding();
        $this->assertTrue($stand);
    }

    public function testGetValue(): void
    {

        $card1 = new Card('6', 'H');
        $card2 = new Card('K', 'D');

        $cards = [$card1, $card2];

        $hand = new Hand($cards, 100);

        $this->assertEquals(16, $hand->getValue());
    }

    public function testGetValueWithAcesAndUnder21(): void
    {

        $card1 = new Card('6', 'H');
        $card2 = new Card('A', 'D');

        $cards = [$card1, $card2];

        $hand = new Hand($cards, 100);

        $this->assertEquals(17, $hand->getValue());
    }

    public function testGetValueWithAcesAndUOver21(): void
    {

        $card1 = new Card('Q', 'H');
        $card2 = new Card('A', 'D');
        $card3 = new Card('K', 'C');

        $cards = [$card1, $card2, $card3];

        $hand = new Hand($cards, 100);

        $this->assertEquals(21, $hand->getValue());
    }

    public function testIsBlackJack(): void
    {

        $card1 = new Card('Q', 'H');
        $card2 = new Card('A', 'D');
        $card3 = new Card('K', 'C');

        $cards = [$card1, $card2, $card3];

        $hand = new Hand($cards, 100);

        $this->assertTrue($hand->isBlackJack());
    }

    public function testIsBusted(): void
    {
        $card1 = new Card('6', 'H');
        $card2 = new Card('K', 'D');
        $card3 = new Card('K', 'S');

        $cards = [$card1, $card2, $card3];

        $hand = new Hand($cards, 100);

        $this->assertTrue($hand->isBusted());
    }
}
