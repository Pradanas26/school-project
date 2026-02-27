<?php

namespace Tests\Domain\Book;

use PHPUnit\Framework\TestCase;

use App\Domain\Book\Book;
use App\Domain\Book\BookId;

class BookTest extends TestCase
{
    public function test_book_can_be_borrowed(): void
    {
        $book = new Book(BookId::generate(), 'DDD in PHP');
        $book->borrow();
        $this->assertFalse($book->isAvailable());
    }

    public function test_borrowing_an_already_borrowed_book_throws_exception(): void
    {
        $this->expectException(\DomainException::class);

        $book = new Book(
            BookId::generate(),
            'DDD in PHP'
        );

        $book->borrow();
        $book->borrow(); // ❌ regla de negoci
    }
}
