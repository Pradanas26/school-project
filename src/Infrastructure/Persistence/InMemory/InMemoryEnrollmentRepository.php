<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepository;

/**
 * ADAPTADOR: InMemoryEnrollmentRepository
 * Implementació InMemory del contracte EnrollmentRepository.
 *
 * Les matrícules nomes s'afegeixen (no s'actualitzen).
 * Cada Enrollment té un ID únic generat automàticament.
 */
final class InMemoryEnrollmentRepository implements EnrollmentRepository
{
    /** @var array<string, Enrollment> */
    private array $enrollments = [];

    public function save(Enrollment $enrollment): void
    {
        $this->enrollments[$enrollment->id()->value()] = $enrollment;
    }

    /** @return Enrollment[] */
    public function findAll(): array
    {
        return array_values($this->enrollments);
    }
}
