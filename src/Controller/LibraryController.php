<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Service\BookService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route("/library/add_book", name: "add_book", methods: ['GET','POST'])]
    public function addBook(Request $request, BookService $bookService): Response
    {

        if ($request->isMethod('POST')) {

            $title  = trim((string) $request->request->get('book-title'));
            $author = trim((string) $request->request->get('author'));
            $isbn  = trim((string) $request->request->get('book-isbn'));

            /** @var UploadedFile|null $coverFile */
            $coverFile = $request->files->get('file-upload');


            // // upload works locally but is blocked on the student server.
            // //  Temporarily disabled. Re-enable by removing the comment.
            $bookService->addBook($title, $author, $isbn, $coverFile);

            return $this->redirectToRoute('library_books');

        }

        $data = [
            'name' => "Add book"
        ];

        return $this->render('library/addBook.html.twig', $data);
    }

    #[Route("/library/books", name: "library_books")]
    public function books(BookRepository $bookRepository): Response
    {
        $books = $bookRepository
            ->findALL();

        $data = [
            'name' => 'Books',
            'books' => $books,
        ];

        return $this->render('library/allBooks.html.twig', $data);
    }

    #[Route("/library/book/{id<\d+>}", name: "get_book_by_id")]
    public function getBookById(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id '.$id
            );
        }

        $data = [
            'name' => 'Book',
            'book' => $book,
        ];

        return $this->render('library/book.html.twig', $data);
    }


    #[Route("/library/book/{id<\d+>}/edit", name: "update_book", methods: ['GET','POST'])]
    public function updateBook(BookRepository $bookRepository, int $id, Request $request, BookService $bookService): Response
    {
        $book = $bookRepository
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id '.$id
            );
        }

        if ($request->isMethod('POST')) {
            $title  = trim((string) $request->request->get('title'));
            $author = trim((string) $request->request->get('author'));
            $isbn  = trim((string) $request->request->get('isbn'));

            /** @var UploadedFile|null $coverFile */
            $coverFile = $request->files->get('file-upload');


            // upload works locally but is blocked on the student server.
            //  Temporarily disabled. Re-enable by removing the comment.
            $bookService->updateBook($book, $title, $author, $isbn, $coverFile);

            return $this->redirectToRoute('get_book_by_id', ['id' => $id]);
        }

        $data = [
            'name' => 'Book',
            'book' => $book,
        ];

        return $this->render('library/editBook.html.twig', $data);
    }


    #[Route("/library/delete/{id<\d+>}", name: "delete_book_by_id")]
    public function deleteBookById(BookRepository $bookRepository, int $id, BookService $bookService): Response
    {
        $book = $bookRepository
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id '.$id
            );
        }

        $bookService->deleteBook($book);

        return $this->redirectToRoute('library_books');
    }
}
