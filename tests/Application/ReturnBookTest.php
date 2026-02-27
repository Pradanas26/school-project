<?php

namespace Tests\Application;

use App\Domain\Book\Book;

use App\Domain\Book\BookId;
use PHPUnit\Framework\TestCase;
use App\Domain\Book\BookRepository;
use App\Application\ReturnBook\ReturnBookCommand;
use App\Application\ReturnBook\ReturnBookHandler;





final class ReturnBookTest extends TestCase
{
    public function test_book_can_be_returned(): void
    {
        $book = new Book(new BookId('book-1'), 'Clean Architecture');
        $book->borrow();

        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->method('find')->willReturn($book);
        $bookRepository->expects($this->once())->method('save');

        $handler = new ReturnBookHandler($bookRepository);

        $handler->handle(new ReturnBookCommand('book-1'));

        $this->assertTrue(true);
    }
}



