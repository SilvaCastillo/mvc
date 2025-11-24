<?php

namespace App\blackjack;

use App\blackjack\Hand;
use App\blackjack\Dealer;
use App\blackjack\BlackJackGame;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\Card;
use PHPUnit\Framework\TestCase;

class BlackJackGameTest extends TestCase
{
    private BlackJackGame $blackJG;
    private $bets = [100, 50, 300];

    protected function setUp(): void
    {
        $this->blackJG = new BlackJackGame("Per");
        $this->blackJG->startRound($this->bets);

    }

    public function testCreateBlackJackGame(): void
    {
        $playerHands = $this->blackJG->getPlayerHands();
        $this->assertCount(3, $playerHands);

        $dealerHand = $this->blackJG->getDealer();
        $this->assertCount(0, $dealerHand->getCards());
    }


    public function testDrawStartCardsForTable(): void
    {
        $this->blackJG->drawStartCardsForTable();

        $playerHands = $this->blackJG->getPlayerHands();
        $this->assertCount(3, $playerHands);
        foreach ($playerHands as $hand) {
            $this->assertCount(2, $hand->getCards());
        }

        $dealerHand = $this->blackJG->getDealer();
        $this->assertCount(2, $dealerHand->getCards());
    }


    public function testCheckForBlackJacks(): void
    {
        $this->blackJG = new BlackJackGame("Jack");
        $this->blackJG->startRound([100]);

        $card1 = new CardGraphic('Q', 'H');
        $card2 = new CardGraphic('A', 'D');


        $hand = $this->blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);

        $this->blackJG->checkForBlackJacks();

        $this->assertTrue($hand->isStanding());
    }


    public function testActionByPlayerStand(): void
    {
        $playerHands = $this->blackJG->getPlayerHands();
        $this->blackJG->actionByPlayer(0, "stand");
        $this->blackJG->actionByPlayer(1, "stand");
        $this->blackJG->actionByPlayer(2, "stand");

        foreach ($playerHands as $hand) {
            $this->assertTrue($hand->isStanding());
        }
    }

    public function testActionByPlayerReturnNull(): void
    {
        $hand = $this->blackJG->getPlayerHands()[0];
        $hand->stand();

        $hand = $this->blackJG->actionByPlayer(0, "stand");
        $this->assertNull($hand);
    }

    public function testActionByPlayerHitAndBusted(): void
    {
        $blackJG = new BlackJackGame("Jack");
        $blackJG->startRound([100]);

        $card1 = new CardGraphic('Q', 'H');
        $card2 = new CardGraphic('A', 'D');
        $card3 = new CardGraphic('K', 'C');


        $hand = $blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);
        $hand->addCard($card3);


        $blackJG->actionByPlayer(0, "hit");

        $this->assertTrue($hand->isStanding());
    }


    public function testActionByDealerAllPlayersBusted(): void
    {
        $blackJG = new BlackJackGame("Jack");
        $blackJG->startRound([100]);

        $card1 = new CardGraphic('Q', 'H');
        $card2 = new CardGraphic('8', 'D');
        $card3 = new CardGraphic('K', 'C');

        $hand = $blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);
        $hand->addCard($card3);

        $dealer = $blackJG->getDealer();
        $dealerValueBefore = $dealer->getValue();

        $blackJG->actionByDealer();

        $dealerValueAfter = $dealer->getValue();


        $this->assertEquals($dealerValueBefore, $dealerValueAfter);
    }


    public function testActionByDealerDrawsTo(): void
    {

        $this->blackJG->actionByDealer();

        $dealerHand = $this->blackJG->getDealer()->getValue();

        $this->assertGreaterThanOrEqual(17, $dealerHand);
    }

}
