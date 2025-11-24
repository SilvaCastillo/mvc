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
    private array $playerHands;

    public function __construct(string $playerName, int $startBalance = 1000)
    {

        $this->player = new BankAccount($playerName, $startBalance);
        $this->dealer = new Dealer();
        $this->deck = new DeckOfCards();
        $this->deck->shuffle();
        $this->playerHands = [];
    }

    public function getPlayerHands(): array
    {
        return $this->playerHands;
    }

    public function getDealer(): Hand
    {
        return $this->dealer->getHand();
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function startRound(array $bets): void
    {
        foreach ($bets as $bet) {
            $this->playerHands[] = new Hand([], $bet);
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
            $this->dealer->addCards($dealerCards);
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
}
