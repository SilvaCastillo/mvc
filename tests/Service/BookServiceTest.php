<?php

namespace App\tests\Service;

use App\Entity\Book;
use App\Service\BookService;
use App\Service\ImageUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class BookServiceTest extends TestCase
{
    /**
     * @var EntityManagerInterface&MockObject
     */
    private EntityManagerInterface  $entityMan;
    private ManagerRegistry $doctrine;
    /**
     * @var ImageUploadService&MockObject
     */
    private ImageUploadService $imageService;
    private UploadedFile $uploadedFile;

    protected function setUp(): void
    {
        $this->entityMan = $this->createMock(EntityManagerInterface::class);

        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->doctrine->method('getManager')->willReturn($this->entityMan);

        $this->imageService = $this->createMock(ImageUploadService::class);
        $this->uploadedFile = $this->createMock(UploadedFile::class);

    }
    public function testAddBook(): void
    {
        $this->entityMan->expects($this->once())->method('persist');
        $this->entityMan->expects($this->once())->method('flush');

        $this->imageService->method('uploadCover')->willReturn('test-name.jpg');


        $service = new BookService($this->doctrine, $this->imageService);
        $book = $service->addBook('Min tid i Nato', 'Jens Stoltenberg', '9789100809041', $this->uploadedFile);

        $this->assertSame('Min tid i Nato', $book->getTitle());
        $this->assertSame('Jens Stoltenberg', $book->getAuthor());
        $this->assertSame('9789100809041', $book->getIsbn());
        $this->assertSame('test-name.jpg', $book->getCoverUrl());
    }


    public function testUpdateBook(): void
    {
        $this->entityMan->expects($this->never())->method('persist');
        $this->entityMan->expects($this->once())->method('flush');

        $book = new Book();
        $book->setTitle('Old title');
        $book->setAuthor('Old author');
        $book->setIsbn('9799999999999');
        $book->setCoverUrl('old-cover.jpg');


        $service = new BookService($this->doctrine, $this->imageService);
        $service->updateBook($book, 'Letters to a Young Poet', 'Rainer Maria Rilke', '9780241252055', $this->uploadedFile);

        $this->assertInstanceOf(Book::class, $book);

        $this->assertSame('Letters to a Young Poet', $book->getTitle());
        $this->assertSame('Rainer Maria Rilke', $book->getAuthor());
        $this->assertSame('9780241252055', $book->getIsbn());
        $this->assertSame('old-cover.jpg', $book->getCoverUrl());
    }

}
