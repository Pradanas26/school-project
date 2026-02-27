<?php

namespace App\Domain\Book;


interface BookRepository
{
    public function find(BookId $id): ?Book;
    public function save(Book $book): void;
}
