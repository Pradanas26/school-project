<?php

namespace App\Application\BorrowBook;

final class BorrowBookCommand
{
    public function __construct(
        public readonly string $bookId,
        public readonly string $userId
    ) {}
}
