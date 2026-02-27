<?php

namespace App\Domain\Enrollment;

/**
 * VALUE OBJECT: EnrollmentId
 * Identificador únic d'una Matrícula.
 */
final class EnrollmentId
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('EnrollmentId no pot estar buit');
        }
    }

    public static function generate(): self
    {
        return new self(uniqid('enrollment_', true));
    }

    public function value(): string { return $this->value; }
    public function __toString(): string { return $this->value; }
}
