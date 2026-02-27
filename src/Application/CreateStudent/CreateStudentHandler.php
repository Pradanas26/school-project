<?php

namespace App\Application\CreateStudent;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\Email;
use App\Domain\Student\StudentRepository;

/**
 * HANDLER (Application Service): CreateStudentHandler
 *
 * Un Handler orquestra un cas d'ús. Les seves responsabilitats:
 *  1. Convertir les dades del Command en Value Objects del domini
 *  2. Crear l'entitat de domini
 *  3. Persistir-la via el repositori
 *
 * El que un Handler NO fa:
 *  - NO conté regles de negoci complexes (això és del domini)
 *  - NO parla directament amb Doctrine o la BD
 *  - NO sap res de HTTP, GET, POST, sessions, etc.
 *
 * Injecció de dependències:
 *  Rep StudentRepository (la interfície/port), no la implementació concreta.
 *  Així podem injectar InMemory en tests i Doctrine en producció.
 */
final class CreateStudentHandler
{
    public function __construct(
        private StudentRepository $studentRepository
    ) {}

    public function handle(CreateStudentCommand $command): void
    {
        // 1. Convertim strings del Command a Value Objects del domini
        //    (aquí és on les validacions dels VO s'executen: email invàlid → excepció)
        $student = new Student(
            new StudentId($command->studentId),
            $command->name,
            new Email($command->email)         // Email VO valida el format
        );

        // 2. Persistim via el repositori (InMemory o Doctrine, tant és)
        $this->studentRepository->save($student);
    }
}
