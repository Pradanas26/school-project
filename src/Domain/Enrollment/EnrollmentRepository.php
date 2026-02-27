<?php

namespace App\Domain\Enrollment;

/**
 * PORT: EnrollmentRepository
 * Contracte per guardar i recuperar matrícules.
 */
interface EnrollmentRepository
{
    /** Guarda una nova matrícula */
    public function save(Enrollment $enrollment): void;

    /** @return Enrollment[] Totes les matrícules */
    public function findAll(): array;
}
