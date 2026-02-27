<?php

namespace App\Application\CreateSubject;

/**
 * COMMAND: CreateSubjectCommand
 * Dades per crear una nova assignatura.
 * Inclou courseId perquè una assignatura sempre pertany a un curs.
 */
final class CreateSubjectCommand
{
    public function __construct(
        public readonly string $subjectId,
        public readonly string $name,
        public readonly string $courseId  // L'assignatura ha de pertànyer a un curs existent
    ) {}
}
