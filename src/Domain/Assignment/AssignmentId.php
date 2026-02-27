<?php

namespace App\Domain\Assignment;

/**
 * VALUE OBJECT: AssignmentId
 * Identificador únic d'una Assignació professor-assignatura.
 */
final class AssignmentId
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('AssignmentId no pot estar buit');
        }
    }

    public static function generate(): self
    {
        return new self(uniqid('assignment_', true));
    }

    public function value(): string { return $this->value; }
    public function __toString(): string { return $this->value; }
}
