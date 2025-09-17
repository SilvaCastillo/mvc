<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route("/library", name: "library_start")]
    public function library(): Response
    {
        $data = [
            'name' => 'Library Page'
        ];

        return $this->render('library/home.html.twig', $data);
    }

    #[Route("/library/add_book", name: "add_book")]
    public function add_book(): Response
    {
        $data = [
            'name' => 'Add book'
        ];

        return $this->render('library/addBook.html.twig', $data);
    }

    #[Route("/library/books", name: "library_books")]
    public function books(): Response
    {

        $books = [
            ['id'=>1,'title'=>'Sagan om ringen','author'=>'J.R.R. Tolkien'],
            ['id'=>2,'title'=>'Clean Code','author'=>'Robert C. Martin'],
            ['id'=>3,'title'=>'Atomic Habits','author'=>'James Clear'],
        ];


        $data = [
            'name' => 'Books',
            'books' => $books,
        ];

        return $this->render('library/allBooks.html.twig', $data);
    }
}