<?php

namespace App\Application\CreateTeacher;

/**
 * COMMAND: CreateTeacherCommand
 * Dades per crear un nou professor.
 */
final class CreateTeacherCommand
{
    public function __construct(
        public readonly string $teacherId,
        public readonly string $name,
        public readonly string $specialty
    ) {}
}
