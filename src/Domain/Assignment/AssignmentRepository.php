<?php

namespace App\Domain\Assignment;

/**
 * PORT: AssignmentRepository
 * Contracte per guardar i recuperar assignacions professor-assignatura.
 */
interface AssignmentRepository
{
    public function save(Assignment $assignment): void;

    /** @return Assignment[] */
    public function findAll(): array;
}
