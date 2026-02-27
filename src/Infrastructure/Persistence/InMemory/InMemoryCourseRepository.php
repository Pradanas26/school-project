<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;

/**
 * ADAPTADOR: InMemoryCourseRepository
 * Implementació InMemory del contracte CourseRepository.
 * Mateixa estructura que InMemoryStudentRepository.
 */
final class InMemoryCourseRepository implements CourseRepository
{
    /** @var array<string, Course> */
    private array $courses = [];

    public function find(CourseId $id): ?Course
    {
        return $this->courses[$id->value()] ?? null;
    }

    public function save(Course $course): void
    {
        $this->courses[$course->id()->value()] = $course;
    }

    /** @return Course[] */
    public function findAll(): array
    {
        return array_values($this->courses);
    }
}
