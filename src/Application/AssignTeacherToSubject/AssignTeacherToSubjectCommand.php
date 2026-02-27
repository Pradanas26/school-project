<?php

namespace App\Application\AssignTeacherToSubject;

/**
 * COMMAND: AssignTeacherToSubjectCommand
 *
 * Dades per assignar un professor a una assignatura.
 * Cas d'ús 6: AssignTeacherToSubject.
 */
final class AssignTeacherToSubjectCommand
{
    public function __construct(
        public readonly string $teacherId,
        public readonly string $subjectId
    ) {}
}
