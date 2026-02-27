<?php

namespace App\Domain\Student;

/**
 * VALUE OBJECT: StudentId
 *
 * Un Value Object NO té identitat pròpia: dos StudentId amb el mateix valor
 * string es consideren IGUALS (a diferència de les Entitats).
 *
 * Responsabilitats:
 *  - Embolcallar l'string de l'ID i garantir que mai sigui buit
 *  - Generar nous IDs únics amb ::generate()
 *  - Comparar-se amb altres StudentId mitjançant equals()
 *
 * Per què existeix en lloc d'un simple string?
 *  - Evita passar IDs d'altres tipus per error (ex: CourseId on s'espera StudentId)
 *  - Centralitza la validació en un sol lloc
 *  - Fa el codi expressiu: el tipus diu exactament QUÈ és
 */
final class StudentId
{
    /**
     * Constructor privat per forçar l'ús de ::generate() o new StudentId($valor)
     * amb validació garantida.
     */
    public function __construct(private string $value)
    {
        // Invariant: un ID no pot ser buit mai
        if ($value === '') {
            throw new \InvalidArgumentException('StudentId no pot estar buit');
        }
    }

    /**
     * Genera un ID únic nou.
     * S'utilitza quan creem un estudiant NOU (no quan el carreguem de la BD).
     */
    public static function generate(): self
    {
        return new self(uniqid('student_', true));
    }

    /** Retorna el valor string intern per persistir o comparar */
    public function value(): string
    {
        return $this->value;
    }

    /** Permet usar l'ID directament en contexts de string (echo, concatenació...) */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Comparació de Value Objects: dos StudentId son iguals si tenen el mateix valor.
     * IMPORTANT: no fem == ni === entre objectes, usem aquest mètode.
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
