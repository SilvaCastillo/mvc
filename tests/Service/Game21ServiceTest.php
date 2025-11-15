<?php

namespace App\tests\Service;

use App\Service\Game21Service;
use App\Card\DeckOfCards;
use App\Card\Card;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Game21ServiceTest extends TestCase
{
    private Game21Service $game21;
    /** @phpstan-ignore-next-line */
    private array $sessionStorage;

    protected function setUp(): void
    {
        $this->sessionStorage = [];
        $session = $this->createMock(SessionInterface::class);

        $session->method('get')
            ->willReturnCallback(function (string $key, $value = null) {
                return $this->sessionStorage[$key] ?? $value;
            });

        $session->method('set')
            ->willReturnCallback(function (string $key, $value = null) {
                $this->sessionStorage[$key] = $value;
            });

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getSession')->willReturn($session);

        $this->game21 = new Game21Service($requestStack);

    }


    public function testCheckWinnerBankBustPlayerWins(): void
    {
        $ref = new ReflectionClass(Game21Service::class);
        $game21 = $ref->newInstanceWithoutConstructor();

        $result = $game21->checkWinner(15, 22);
        $this->assertSame("Player wins with 15 vs 22", $result);
    }

    public function testCheckWinnerPlayerBustBankerWins(): void
    {
        $ref = new ReflectionClass(Game21Service::class);
        $game21 = $ref->newInstanceWithoutConstructor();

        $result = $game21->checkWinner(25, 18);
        $this->assertSame("Bank wins with 18 vs 25", $result);
    }

    public function testCheckWinnerPlayerWins(): void
    {
        $ref = new ReflectionClass(Game21Service::class);
        $game21 = $ref->newInstanceWithoutConstructor();

        $result = $game21->checkWinner(20, 18);
        $this->assertSame("Player wins with 20 vs 18", $result);
    }

    public function testCheckWinnerBankerWins(): void
    {
        $ref = new ReflectionClass(Game21Service::class);
        $game21 = $ref->newInstanceWithoutConstructor();

        $result = $game21->checkWinner(15, 17);
        $this->assertSame("Bank wins with 17 vs 15", $result);
    }

    public function testDrawCardForPlayer(): void
    {
        $card = $this->createMock(Card::class);
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('draw')->willReturn([$card]);

        $this->sessionStorage['sharedDeck'] = $deck;
        $this->sessionStorage['drawnPlayerCards'] = [];

        $this->game21->drawCardForPlayer('player');

        $this->assertCount(1, $this->sessionStorage['drawnPlayerCards']);
        $this->assertSame($card, $this->sessionStorage['drawnPlayerCards'][0]);
    }

    public function testDrawCardForBanker(): void
    {
        $card = $this->createMock(Card::class);
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('draw')->willReturn([$card]);

        $this->sessionStorage['sharedDeck'] = $deck;
        $this->sessionStorage['drawnBankCards'] = [];

        $this->game21->drawCardForPlayer('banker');

        $this->assertCount(1, $this->sessionStorage['drawnBankCards']);
        $this->assertSame($card, $this->sessionStorage['drawnBankCards'][0]);
    }

    public function testGetPlayerScoreForPlayer(): void
    {
        $card1 = new Card('2', 'H');
        $card2 = new Card('A', 'D');
        $this->sessionStorage['drawnPlayerCards'] = [$card1, $card2];

        $score = $this->game21->getPlayerScore('player');

        $this->assertSame(16, $score);
    }

    public function testGetPlayerScoreForBanker(): void
    {
        $card1 = new Card('A', 'H');
        $card2 = new Card('2', 'D');
        $this->sessionStorage['drawnBankCards'] = [$card1, $card2];

        $score = $this->game21->getPlayerScore('banker');

        $this->assertSame(16, $score);
    }

    public function testGetDrawnCardsAsStringPlayer(): void
    {
        $card = new Card('A', 'H');
        $this->sessionStorage['drawnPlayerCards'] = [$card];

        $score = $this->game21->getDrawnCardsAsString('player');

        $this->assertSame('AH', $score[0]);
    }

    public function testGetDrawnCardsAsStringBank(): void
    {
        $card = new Card('10', 'D');
        $this->sessionStorage['drawnBankCards'] = [$card];

        $score = $this->game21->getDrawnCardsAsString('banker');

        $this->assertSame('10D', $score[0]);
    }

}
