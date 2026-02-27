<?php

namespace App\Application\ReturnBook;

use App\Domain\Book\BookId;
use App\Domain\Book\BookRepository;

final class ReturnBookHandler
{
    public function __construct(
        private BookRepository $bookRepository
    ) {}


    public function handle(ReturnBookCommand $command): void
    {
        $book = $this->bookRepository->find(new BookId($command->bookId));


        if (!$book) {
            throw new \RuntimeException('Book not found');
        }

        $book->return();

        $this->bookRepository->save($book);
    }
}
