<?php

namespace App\Domain\User;


final class UserId
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('UserId cannot be empty');
        }
    }

    public static function generate(): self
    {
        return new self(
            uniqid('user_', true)
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
