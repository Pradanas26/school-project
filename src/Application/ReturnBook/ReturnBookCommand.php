<?php

namespace App\Application\ReturnBook;

final class ReturnBookCommand
{
    public function __construct(
        public readonly string $bookId
    ) {}
}
