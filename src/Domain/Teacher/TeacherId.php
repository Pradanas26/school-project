<?php

namespace App\Domain\Teacher;

/**
 * VALUE OBJECT: TeacherId
 * Identificador únic d'un Professor. Igual patró que la resta d'IDs.
 */
final class TeacherId
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('TeacherId no pot estar buit');
        }
    }

    public static function generate(): self
    {
        return new self(uniqid('teacher_', true));
    }

    public function value(): string { return $this->value; }
    public function __toString(): string { return $this->value; }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
