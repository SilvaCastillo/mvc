<?php

namespace App\blackjack;

use App\blackjack\BankAccount;
use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    private BankAccount $bankAc;

    protected function setUp(): void
    {
        $this->bankAc = new BankAccount("Per", 100);
    }

    public function testCreateAccount(): void
    {
        $this->assertEquals("Per", $this->bankAc->getName());
        $this->assertEquals(100, $this->bankAc->getBalance());
    }

    public function testPlaceBets(): void
    {
        $this->bankAc->placeBets(50);
        $this->assertEquals(50, $this->bankAc->getBalance());
    }

    public function testAddWinnings(): void
    {
        $this->bankAc->addWinnings(150);
        $this->assertEquals(250, $this->bankAc->getBalance());
    }
}
