<?php

namespace App\blackjack;

use App\blackjack\BankAccount;
use App\blackjack\Hand;
use App\blackjack\Dealer;
use App\Card\DeckOfCards;

class BlackJackGame
{
    private BankAccount $player;
    private Dealer $dealer;
    private DeckOfCards $deck;
    /** @var Hand[] */
    private array $playerHands;

    public function __construct(string $playerName, int $startBalance = 1000)
    {

        $this->player = new BankAccount($playerName, $startBalance);
        $this->dealer = new Dealer();
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->playerHands = [];
    }

    /**
     * @return array<int, hand>
     */
    public function getPlayerHands(): array
    {
        return $this->playerHands;
    }

    public function getDealer(): Hand
    {
        return $this->dealer->getHand();
    }

    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    public function getBalance(): int
    {
        return $this->player->getBalance();
    }

    /**
     * @param array<int, int> $bets
     */
    public function startRound(array $bets): void
    {
        $this->playerHands = [];
        $this->dealer->reset();

        foreach ($bets as $bet) {
            $this->playerHands[] = new Hand([], $bet);
            $this->player->placeBets($bet);
        }
    }

    public function checkForBlackJacks(): void
    {
        foreach ($this->playerHands as $hand) {
            if ($hand->isBlackJack()) {
                $hand->stand();
            }
        }
    }

    public function drawStartCardsForTable(): void
    {
        for ($i = 0; $i < 2; $i++) {
            foreach ($this->playerHands as $hand) {
                $hand->hit($this->deck);
            }

            $dealerCards = $this->deck->draw();
            if ($dealerCards !== null) {
                $this->dealer->addCards($dealerCards);

            }
        }

        $this->checkForBlackJacks();

    }

    public function actionByPlayer(int $handIndex, string $action): void
    {
        $hand = $this->playerHands[$handIndex];

        if ($hand->isBusted() or $hand->isStanding()) {
            return;
        }

        if ($action === "hit") {
            $hand->hit($this->deck);

            if ($hand->isBusted()) {
                $hand->stand();
            }
        } elseif ($action === "stand") {
            $hand->stand();

        }
    }


    public function actionByDealer(): void
    {
        $counter = 0;

        foreach ($this->playerHands as $hand) {
            if ($hand->isBusted()) {
                $counter++;
            }
        }

        if ($counter === count($this->playerHands)) {
            return;
        }

        $this->dealer->drawUntil17($this->deck);
    }


    public function payoutCalculate(Hand $hand, int $dealerValue, bool $dealerBusted): int
    {
        if ($hand->isBusted()) {
            return 0;
        }

        if ($hand->isBlackJack() && $this->dealer->getHand()->isBlackJack()) {
            return $hand->getBet();
        }

        if ($hand->isBlackJack()) {
            return (int) ($hand->getBet() * 2.5);
        }

        if ($dealerBusted) {
            return $hand->getBet() * 2;
        }

        if ($hand->getValue() > $dealerValue) {
            return $hand->getBet() * 2;
        }

        if ($hand->getValue() < $dealerValue) {

            return 0;
        }

        return  $hand->getBet();
    }

    /**
     * @return int[]
     */
    public function finishRound(): array
    {
        $dealerValue = $this->dealer->getHand()->getValue();
        $dealerBusted = $this->dealer->getHand()->isBusted();
        $wins = [];
        foreach ($this->playerHands as $hand) {
            $winnings = $this->payoutCalculate($hand, $dealerValue, $dealerBusted);
            $this->player->addWinnings($winnings);
            $wins[] = $winnings;
        }

        return $wins;
    }
}
