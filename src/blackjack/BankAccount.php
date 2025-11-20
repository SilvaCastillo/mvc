<?php

namespace App\blackjack;

class BankAccount
{
    private string $name;
    private int $balance;


    public function __construct(string $name, int $balance)
    {
        $this->name = $name;
        $this->balance = $balance;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function placeBets(int $amount): void
    {
        $this->balance -= $amount;
    }

    public function addWinnings(int $amount): void
    {
        $this->balance += $amount;
    }
}
