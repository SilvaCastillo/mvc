<?php

namespace App\blackjack;

use App\blackjack\BlackJackGame;
use App\Card\CardGraphic;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 */
class BlackJackGameTest extends TestCase
{
    private BlackJackGame $blackJG;
    /**
     * @var int[]
     */
    private array $bets = [100, 50, 300];

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

        $cardsBefore = count($hand->getCards());

        $this->blackJG->actionByPlayer(0, "hit");

        $cardsAfter = count($hand->getCards());

        $this->assertEquals($cardsBefore, $cardsAfter);
    }

    public function testActionByPlayerHitAndBusted(): void
    {
        $card1 = new CardGraphic('Q', 'H');
        $card2 = new CardGraphic('A', 'D');
        $card3 = new CardGraphic('K', 'C');

        $hand = $this->blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);
        $hand->addCard($card3);

        $this->blackJG->actionByPlayer(0, "hit");

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

    public function testPayoutCalculateHandBusted(): void
    {
        $card1 = new CardGraphic('Q', 'H');
        $card2 = new CardGraphic('8', 'D');
        $card3 = new CardGraphic('K', 'C');

        $hand = $this->blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);
        $hand->addCard($card3);

        $dealerValue = 17;
        $dealerBusted = false;

        $payout = $this->blackJG->payoutCalculate($hand, $dealerValue, $dealerBusted);

        $this->assertEquals(0, $payout);
    }

    public function testPayoutCalculateHandBlackJack(): void
    {
        $card1 = new CardGraphic('Q', 'H');
        $card2 = new CardGraphic('A', 'D');

        $hand = $this->blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);
        $handExpectedWins = $hand->getBet() * 2.5;

        $dealerValue = 17;
        $dealerBusted = false;

        $payout = $this->blackJG->payoutCalculate($hand, $dealerValue, $dealerBusted);

        $this->assertEquals($handExpectedWins, $payout);
    }

    public function testPayoutCalculateDealerBusted(): void
    {

        $card1 = new CardGraphic('Q', 'H');
        $card2 = new CardGraphic('K', 'D');

        $hand = $this->blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);

        $handExpectedWins = $hand->getBet() * 2;

        $dealerValue = 25;
        $dealerBusted = true;

        $payout = $this->blackJG->payoutCalculate($hand, $dealerValue, $dealerBusted);

        $this->assertEquals($handExpectedWins, $payout);
    }

    public function testPayoutCalculateHandMoreThanDealerHand(): void
    {
        $card1 = new CardGraphic('Q', 'H');
        $card2 = new CardGraphic('K', 'D');

        $hand = $this->blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);

        $handExpectedWins = $hand->getBet() * 2;

        $dealerValue = 17;
        $dealerBusted = false;

        $payout = $this->blackJG->payoutCalculate($hand, $dealerValue, $dealerBusted);

        $this->assertEquals($handExpectedWins, $payout);
    }

    public function testPayoutCalculateHandLessThanDealerHand(): void
    {
        $card1 = new CardGraphic('4', 'H');
        $card2 = new CardGraphic('K', 'D');

        $hand = $this->blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);

        $dealerValue = 17;
        $dealerBusted = false;

        $payout = $this->blackJG->payoutCalculate($hand, $dealerValue, $dealerBusted);

        $this->assertEquals(0, $payout);
    }

    public function testPayoutCalculateHandEqualToDealerHand(): void
    {
        $card1 = new CardGraphic('Q', 'H');
        $card2 = new CardGraphic('K', 'D');

        $hand = $this->blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);

        $dealerValue = 20;
        $dealerBusted = false;

        $payout = $this->blackJG->payoutCalculate($hand, $dealerValue, $dealerBusted);

        $this->assertEquals(100, $payout);
    }

    public function testFinishRound(): void
    {
        $blackJG = new BlackJackGame("Jack");
        $blackJG->startRound([100]);

        $card1 = new CardGraphic('7', 'H');
        $card2 = new CardGraphic('K', 'D');

        $hand = $blackJG->getPlayerHands()[0];
        $hand->addCard($card1);
        $hand->addCard($card2);

        $dealerHand = $blackJG->getDealer();
        $dealerHand->addCard(new CardGraphic('8', 'S'));
        $dealerHand->addCard(new CardGraphic('9', 'C'));

        $blackJG->finishRound();
        $newBalance = $blackJG->getBalance();

        $this->assertEquals(1000, $newBalance);
    }
}
