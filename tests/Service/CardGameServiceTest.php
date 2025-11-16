<?php

namespace App\tests\Service;
use App\Service\CardGameService;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class CardGameServiceTest extends TestCase
{
    private CardGameService $cardGame;
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

        $this->cardGame = new CardGameService($requestStack);

    }

    public function testGetDeckWithNoExistingDeck(): void
    {
        $deck = $this->cardGame->getDeck();

        $this->assertInstanceOf(DeckOfCards::class, $deck);
        $this->assertArrayHasKey('deck', $this->sessionStorage);
        $this->assertCount(52, $deck->getCards());
    }

}