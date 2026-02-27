<?php

namespace App\Domain\Book;

final class BookId
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('BookId cannot be empty');
        }
    }
    
    public static function generate(): self
    {
        return new self(
            uniqid('book_', true)
        );
    }

    public function value(): string
    {
        return $this->value;
    }


    public function __toString(): string
    {
        return $this->value;
    }
}
