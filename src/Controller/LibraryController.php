<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
    public function addBook(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();

        if ($request->isMethod('POST')) {

            $title  = trim((string) $request->request->get('book-title'));
            $author = trim((string) $request->request->get('author'));
            $isbn  = $request->request->get('book-isbn');
            $coverFile = $request->files->get('file-upload');

            $book = new Book();
            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setIsbn($isbn);

            if ($coverFile instanceof UploadedFile) {
                $violations = $validator->validate(
                    $coverFile,
                    new Image([
                    'maxSize' => '5M',
                    'mimeTypes' => ['image/jpeg', 'image/png'],
                    'detectCorrupted' => true,
                ])
                );

                if ($violations->count() > 0) {
                    return $this->render('validator.html.twig', [
                        'name' => 'Error'
                    ]);
                }

                $mime = $coverFile->getMimeType();
                $ext  = match ($mime) {
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    default      => null,
                };

                $coverName = bin2hex(random_bytes(8)).'.'.$ext;
                $coverFile->move($this->getParameter('covers_dir'), $coverName);
                $book->setCoverUrl('/img/covers/'.$coverName);
            }

            $entityManager->persist($book);
            $entityManager->flush();

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
    public function updateBook(BookRepository $bookRepository, int $id, Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $book = $bookRepository
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id '.$id
            );
        }

        if ($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();
            $title  = trim((string) $request->request->get('title'));
            $author = trim((string) $request->request->get('author'));
            $isbn  = $request->request->get('isbn');
            $coverFile = $request->files->get('file-upload');

            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setIsbn($isbn);


            if ($coverFile instanceof UploadedFile) {
                $violations = $validator->validate(
                    $coverFile,
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'detectCorrupted' => true,
                    ])
                );

                if ($violations->count() > 0) {
                    return $this->render('validator.html.twig', [
                        'name' => 'Error'
                    ]);
                }


                $mime = $coverFile->getMimeType();
                $ext  = match ($mime) {
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    default      => null,
                };

                $coverName = bin2hex(random_bytes(8)).'.'.$ext;
                $coverFile->move($this->getParameter('covers_dir'), $coverName);
                $book->setCoverUrl('/img/covers/'.$coverName);
            }

            $entityManager->flush();


            return $this->redirectToRoute('get_book_by_id', ['id' => $id]);
        }


        $data = [
            'name' => 'Book',
            'book' => $book,
        ];

        return $this->render('library/editBook.html.twig', $data);
    }


    #[Route("/library/delete/{id<\d+>}", name: "delete_book_by_id")]
    public function deleteBookById(BookRepository $bookRepository, int $id, ManagerRegistry $doctrine): Response
    {

        $book = $bookRepository
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found with id '.$id
            );
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('library/allBooks.html.twig');
    }

}
