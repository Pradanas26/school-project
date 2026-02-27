<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;

/**
 * ADAPTADOR: InMemoryTeacherRepository
 * Implementació InMemory del contracte TeacherRepository.
 */
final class InMemoryTeacherRepository implements TeacherRepository
{
    /** @var array<string, Teacher> */
    private array $teachers = [];

    public function find(TeacherId $id): ?Teacher
    {
        return $this->teachers[$id->value()] ?? null;
    }

    public function save(Teacher $teacher): void
    {
        $this->teachers[$teacher->id()->value()] = $teacher;
    }

    /** @return Teacher[] */
    public function findAll(): array
    {
        return array_values($this->teachers);
    }
}
