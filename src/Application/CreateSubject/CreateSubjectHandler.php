<?php

namespace App\Application\CreateSubject;

use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;

/**
 * HANDLER: CreateSubjectHandler
 *
 * Cas d'ús: crear una nova assignatura dins d'un curs existent.
 *
 * Nota: Aquest handler rep DOS repositoris.
 *  - SubjectRepository: per guardar l'assignatura nova
 *  - CourseRepository:  per VERIFICAR que el curs existeix
 *
 * Regla: no podem crear una assignatura per a un curs que no existeix.
 * Aquesta validació és a nivell d'aplicació (cross-aggregate validation).
 */
final class CreateSubjectHandler
{
    public function __construct(
        private SubjectRepository $subjectRepository,
        private CourseRepository  $courseRepository   // Necessitem verificar el curs
    ) {}

    public function handle(CreateSubjectCommand $command): void
    {
        $courseId = new CourseId($command->courseId);

        // Validació cross-agregat: el curs ha d'existir
        $course = $this->courseRepository->find($courseId);
        if (!$course) {
            throw new \RuntimeException('Curs no trobat amb ID: ' . $command->courseId);
        }

        // Creem l'assignatura vinculada al curs
        $subject = new Subject(
            new SubjectId($command->subjectId),
            $command->name,
            $courseId
        );

        $this->subjectRepository->save($subject);
    }
}
