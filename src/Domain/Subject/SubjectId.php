<?php

namespace App\Domain\Subject;

/**
 * VALUE OBJECT: SubjectId
 * Identificador únic d'una Assignatura. Mateix patró que la resta d'IDs.
 */
final class SubjectId
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('SubjectId no pot estar buit');
        }
    }

    public static function generate(): self
    {
        return new self(uniqid('subject_', true));
    }

    public function value(): string { return $this->value; }
    public function __toString(): string { return $this->value; }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
