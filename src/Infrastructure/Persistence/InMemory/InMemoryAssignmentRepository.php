<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Assignment\Assignment;
use App\Domain\Assignment\AssignmentRepository;

/**
 * ADAPTADOR: InMemoryAssignmentRepository
 * Implementació InMemory del contracte AssignmentRepository.
 *
 * Cada assignació professor→assignatura es guarda amb el seu ID únic.
 */
final class InMemoryAssignmentRepository implements AssignmentRepository
{
    /** @var array<string, Assignment> */
    private array $assignments = [];

    public function save(Assignment $assignment): void
    {
        $this->assignments[$assignment->id()->value()] = $assignment;
    }

    /** @return Assignment[] */
    public function findAll(): array
    {
        return array_values($this->assignments);
    }
}
