<?php

namespace App\Service;

use App\Entity\Book;
use App\Service\ImageUploadService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BookService
{
    private ManagerRegistry $doctrine;
    private ImageUploadService $imageService;

    public function __construct(
        ManagerRegistry $doctrine,
        ImageUploadService $imageService
    )
    {
        $this->doctrine = $doctrine;
        $this->imageService = $imageService;
    }

    public function addBook(string $title, string $author, string $isbn, ?UploadedFile $coverFile): Book
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);


        if ($coverFile instanceof UploadedFile) {
                $coverName = $this->imageService->uploadCover($coverFile);
                if ($coverName !== null) {
                    $book->setCoverUrl($coverName);
                }
            }

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($book);
        $entityManager->flush();

        return $book;
    }

    public function updateBook(Book $book, string $title, string $author, string $isbn, ?UploadedFile $coverFile): void
    {
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);


        if ($coverFile instanceof UploadedFile) {
                $coverName = $this->imageService->uploadCover($coverFile);
                if ($coverName !== null) {
                    $book->setCoverUrl($coverName);
                }
            }

        $entityManager = $this->doctrine->getManager();
        $entityManager->flush();
    }
}