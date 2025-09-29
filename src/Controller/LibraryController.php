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
    public function add_book(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
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
                new \Symfony\Component\Validator\Constraints\Image([
                    'maxSize' => '5M',
                    'mimeTypes' => ['image/jpeg', 'image/png'],
                    'detectCorrupted' => true,
                ])
                );

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