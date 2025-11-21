<?php

namespace App\blackjack;

use App\blackjack\Dealer;
use App\blackjack\Hand;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

class DealerTest extends TestCase
{
    private DeckOfCards $deckOfCards;
    private Dealer $dealer;

    protected function setUp(): void
    {
        $this->deckOfCards = new DeckOfCards();
        $this->dealer = new Dealer();

    }

    public function testDealerGetHand(): void
    {
        $hand = $this->dealer->getHand();

        $this->assertInstanceOf(Hand::class, $hand);
        $this->assertCount(0, $hand->getCards());
    }

    public function testDealerCanReceiveCards(): void
    {
        $cards = $this->deckOfCards->draw(2);
        self::assertNotNull($cards);

        $this->dealer->addCards($cards);

        $this->assertCount(2, $this->dealer->getHand()->getCards());
    }


    public function testDealerHandReset(): void
    {
        $cards = $this->deckOfCards->draw();
        self::assertNotNull($cards);

        $this->dealer->addCards($cards);

        $handBefore = $this->dealer->getHand();

        $this->dealer->reset();

        $handAfter = $this->dealer->getHand();

        $this->assertNotSame($handBefore, $handAfter);

    }


    public function testDealerDrawUntil17(): void
    {
        $this->dealer->drawUntil17($this->deckOfCards);

        $this->assertGreaterThanOrEqual(17, $this->dealer->getHand()->getValue());
    }

}
