<?php

namespace App\Application\CreateCourse;

/**
 * COMMAND: CreateCourseCommand
 * Dades per crear un nou curs. Veure CreateStudentCommand per la descripció del patró.
 */
final class CreateCourseCommand
{
    public function __construct(
        public readonly string $courseId,
        public readonly string $name,
        public readonly int    $year
    ) {}
}
