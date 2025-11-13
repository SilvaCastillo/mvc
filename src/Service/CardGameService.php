<?php

namespace App\Service;

use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CardGameService
{
    private SessionInterface $session;
    public function __construct(
        RequestStack $requestStack
    ) {
        $this->session = $requestStack->getSession();
    }

    public function getDeck(): DeckOfCards
    {
        $deck = $this->session->get("deck");
        if (!$deck instanceof DeckOfCards) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $this->session->set('deck', $deck);
        }

        return $deck;

    }

    /**
     * @return list<string>
     */
    public function getDeckAsString(DeckOfCards $deck): array
    {
        $cardsAsString = array();
        foreach ($deck->getCards() as $card) {
            $cardsAsString[] = $card->getAsString();
        }

        return $cardsAsString;
    }

}
