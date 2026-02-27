<?php

namespace App\Application\EnrollStudent;

/**
 * COMMAND: EnrollStudentCommand
 *
 * Dades per matricular un estudiant en un curs.
 * Cas d'ús 5: EnrollStudent.
 *
 * Contenim els IDs com a strings simples.
 * El Handler s'encarregarà de:
 *  1. Verificar que l'estudiant existeix
 *  2. Verificar que el curs existeix
 *  3. Crear la matrícula
 */
final class EnrollStudentCommand
{
    public function __construct(
        public readonly string $studentId,
        public readonly string $courseId
    ) {}
}
