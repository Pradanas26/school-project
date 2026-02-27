<?php

namespace App\Application\CreateTeacher;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;

/**
 * HANDLER: CreateTeacherHandler
 * Cas d'ús: registrar un nou professor al sistema.
 */
final class CreateTeacherHandler
{
    public function __construct(
        private TeacherRepository $teacherRepository
    ) {}

    public function handle(CreateTeacherCommand $command): void
    {
        // Si el nom o l'especialitat estan buits, Teacher llença excepció
        $teacher = new Teacher(
            new TeacherId($command->teacherId),
            $command->name,
            $command->specialty
        );

        $this->teacherRepository->save($teacher);
    }
}
