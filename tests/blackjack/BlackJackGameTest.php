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
        foreach ($playerHands as $hand) {
        }

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

}
