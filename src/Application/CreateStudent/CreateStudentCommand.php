<?php

namespace App\Application\CreateStudent;

/**
 * COMMAND: CreateStudentCommand
 *
 * Un Command és un DTO (Data Transfer Object) immutable que conté
 * totes les dades necessàries per executar un cas d'ús.
 *
 * Principis:
 *  - Només dades, sense lògica
 *  - Tots els camps readonly (immutable)
 *  - Tipus simples (strings, ints): el handler convertirà a Value Objects
 *
 * Flux: Controlador → crea Command → passa al Handler → Handler executa
 */
final class CreateStudentCommand
{
    public function __construct(
        public readonly string $studentId, // ID pre-generat (o generat al controlador)
        public readonly string $name,
        public readonly string $email
    ) {}
}
