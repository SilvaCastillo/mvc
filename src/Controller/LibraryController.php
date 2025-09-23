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
            ['id'=>1,'title'=>'Sagan om ringen','author'=>'J.R.R. Tolkien','coverUrl'=> 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-04-image-card-01.jpg'],
            ['id'=>2,'title'=>'Clean Code','author'=>'Robert C. Martin','coverUrl'=> 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-04-image-card-01.jpg'],
            ['id'=>3,'title'=>'Atomic Habits','author'=>'James Clear'],
        ];


        $data = [
            'name' => 'Books',
            'books' => $books,
        ];

        return $this->render('library/allBooks.html.twig', $data);
    }

    #[Route("/library/book/{id<\d+>}", name: "get_book_by_id")]
    public function get_book_by_id(): Response
    {

        $books = [
            ['id'=>1,'title'=>'Sagan om ringen','author'=>'J.R.R. Tolkien', 'isbn'=>'12345214', 'coverUrl'=> 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-04-image-card-01.jpg'],
        ];


        $data = [
            'name' => 'Book',
            'book' => $books,
        ];

        return $this->render('library/book.html.twig', $data);
    }
}