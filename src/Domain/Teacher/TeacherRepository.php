<?php

namespace App\Domain\Teacher;

/**
 * PORT: TeacherRepository
 * Contracte per accedir i persistir professors.
 */
interface TeacherRepository
{
    public function find(TeacherId $id): ?Teacher;
    public function save(Teacher $teacher): void;

    /** @return Teacher[] */
    public function findAll(): array;
}
