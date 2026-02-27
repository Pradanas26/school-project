<?php

namespace App\Domain\Course;

/**
 * VALUE OBJECT: CourseId
 *
 * Identificador únic d'un Curs. Segueix el mateix patró que StudentId:
 * embolcalla un string, valida que no sigui buit, i permet generar-ne de nous.
 *
 * Tenir tipus separats (CourseId, StudentId, TeacherId...) evita
 * errors clàssics com passar l'ID d'un estudiant on s'espera un curs.
 * El compilador (o PHP en temps d'execució) ho detectarà.
 */
final class CourseId
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('CourseId no pot estar buit');
        }
    }

    public static function generate(): self
    {
        return new self(uniqid('course_', true));
    }

    public function value(): string { return $this->value; }
    public function __toString(): string { return $this->value; }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
