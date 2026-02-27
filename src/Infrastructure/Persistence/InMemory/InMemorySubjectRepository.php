<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;

/**
 * ADAPTADOR: InMemorySubjectRepository
 * Implementació InMemory del contracte SubjectRepository.
 *
 * Nota important: quan el handler fa save($subject) després de AssignTeacher,
 * aquest mètode actualitza l'objecte existent a l'array perquè l'ID és el mateix.
 * Així el canvi de teacherId es "persisted" correctament.
 */
final class InMemorySubjectRepository implements SubjectRepository
{
    /** @var array<string, Subject> */
    private array $subjects = [];

    public function find(SubjectId $id): ?Subject
    {
        return $this->subjects[$id->value()] ?? null;
    }

    /** Guarda o actualitza. Si ja existia (per exemple, s'ha assignat professor), sobreescriu. */
    public function save(Subject $subject): void
    {
        $this->subjects[$subject->id()->value()] = $subject;
    }

    /** @return Subject[] */
    public function findAll(): array
    {
        return array_values($this->subjects);
    }
}
