<?php

namespace App\Domain\Course;

/**
 * PORT: CourseRepository
 *
 * Contracte del domini per accedir a cursos.
 * La implementació real (InMemory o Doctrine) és a la infraestructura.
 */
interface CourseRepository
{
    public function find(CourseId $id): ?Course;
    public function save(Course $course): void;

    /** @return Course[] */
    public function findAll(): array;
}
