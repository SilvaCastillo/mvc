<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\blackjack\BlackJackGame;

class ProjectController extends AbstractController
{
    #[Route("/proj", name: 'proj')]
    public function projIndex(SessionInterface $session): Response
    {
        $session->clear();

        $data = [
            'name' => 'Black Jack'
        ];

        return $this->render('project/home.html.twig', $data);
    }

    #[Route("/proj/about", name: 'proj_about')]
    public function projAbout(): Response
    {

        $data = [
            'name' => 'About The Project'
        ];

        return $this->render('project/about.html.twig', $data);
    }

    #[Route("/proj/game", name: 'blackjack_form')]
    public function projGame(): Response
    {

        $data = [
            'name' => 'Black Jack'
        ];

        return $this->render('project/playerInfo.html.twig', $data);
    }

    #[Route("/proj/game_bet", name: 'blackjack_bet')]
    public function projGameBet(SessionInterface $session, Request $request): Response
    {
        $name  = trim((string) $request->request->get('blackjack-name'));
        $amountOfHands = (int) $request->request->get('blackjack-playerhands');

        if ($session->has('blackjgame')) {
            /** @var BlackJackGame $blackjgame */
            $blackjgame = $session->get('blackjgame');
            $amountOfHands = $session->get('amountofHands');
        }

        if (!isset($blackjgame)) {
            $blackjgame = new BlackJackGame($name);
        }

        $balance = $blackjgame->getBalance();

        $session->set('blackjgame', $blackjgame);
        $session->set('amountofHands', $amountOfHands);

        $data = [
            'name' => 'Black Jack',
            'balance' => $balance,
            'amountOfHands' => $amountOfHands
        ];

        return $this->render('project/bets.html.twig', $data);
    }


    #[Route("/proj/card_drawn", name: 'card_drawn', methods: ['POST'])]
    public function projGameDraw(SessionInterface $session, Request $request): Response
    {
        /** @var BlackJackGame $blackjgame */
        $blackjgame = $session->get("blackjgame");
        $amountOfHands = $session->get("amountofHands");
        $isNewRound = $request->request->get('new_round');

        $bets = [];

        if ($isNewRound) {
            $playerHands = $blackjgame->getPlayerHands();

            foreach ($playerHands as $hand) {
                $bets[] = $hand->getBet();
            }
        }

        if (!$isNewRound) {
            for ($i = 1; $i <= $amountOfHands; $i++) {
                $bets[] = (int)$request->request->get("bet-hand-$i");
            }
        }

        $blackjgame->startRound($bets);
        $blackjgame->drawStartCardsForTable();

        $playerHands = $blackjgame->getPlayerHands();
        $dealerHands = $blackjgame->getDealer();

        $session->set("currentHandIndex", 0);

        $data = [
            'name' => 'Black Jack',
            'playerHands' => $playerHands,
            'dealerHands' => $dealerHands,
            'currentHandIndex' => 0,
            'gameOver' => false,
            'showDealerCards' => false

        ];

        return $this->render('project/draw.html.twig', $data);
    }


    #[Route("/proj/play_hand", name: 'play_hand', methods: ['POST'])]
    public function projPlayHand(SessionInterface $session, Request $request): Response
    {
        $action = $request->request->get('action');
        /** @var BlackJackGame $blackjgame */
        $blackjgame = $session->get("blackjgame");
        /** @var int $currentHandIndex */
        $currentHandIndex = $session->get("currentHandIndex", 0);
        $playerHands = $blackjgame->getPlayerHands();
        $balance = $blackjgame->getBalance();

        if ($action === 'hit') {
            $blackjgame->actionByPlayer($currentHandIndex, $action);
            if ($playerHands[$currentHandIndex]->getValue() > 21) {
                $currentHandIndex++;
            }
        }

        if ($action === 'stand') {
            $currentHandIndex++;
        }

        $gameOver = false;
        $showDealerCards = false;
        $results = null;

        if ($currentHandIndex >= count($playerHands)) {
            $blackjgame->actionByDealer();
            $results = $blackjgame->finishRound();
            $gameOver = true;
            $showDealerCards = true;
            $currentHandIndex = -1;
        }

        $session->set("currentHandIndex", $currentHandIndex);
        $session->set("blackjgame", $blackjgame);


        $data = [
            'name' => 'Black Jack',
            'playerHands' => $playerHands,
            'balance' => $balance,
            'dealerHands' => $blackjgame->getDealer(),
            'currentHandIndex' => $currentHandIndex,
            'gameOver' => $gameOver,
            'showDealerCards' => $showDealerCards,
            'results' => $results
        ];

        return $this->render('project/draw.html.twig', $data);
    }
}
