<?php

namespace App\Application\EnrollStudent;

use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepository;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;

/**
 * HANDLER: EnrollStudentHandler
 *
 * CAS D'ÚS 5: Matricular un estudiant en un curs.
 *
 * Flux complet:
 *  1. Rep EnrollStudentCommand (studentId + courseId)
 *  2. Carrega l'entitat Student del repositori → si no existeix, error
 *  3. Carrega l'entitat Course del repositori → si no existeix, error
 *  4. DELEGA la creació al domini: Enrollment::enroll($student, $course)
 *     ↑ Aquí és on viu la lògica de negoci, NO aquí al handler
 *  5. Persisteix l'enrollment nou
 *
 * Per testejar:
 *  - Podem mockejar els 3 repositoris
 *  - No necessitem BD ni Doctrine
 *  - El test verifica comportament, no implementació
 */
final class EnrollStudentHandler
{
    public function __construct(
        private StudentRepository    $studentRepository,
        private CourseRepository     $courseRepository,
        private EnrollmentRepository $enrollmentRepository
    ) {}

    public function handle(EnrollStudentCommand $command): void
    {
        // Pas 1: Carregar i verificar que l'estudiant existeix
        $student = $this->studentRepository->find(new StudentId($command->studentId));
        if (!$student) {
            throw new \RuntimeException('Student not found');
        }

        // Pas 2: Carregar i verificar que el curs existeix
        $course = $this->courseRepository->find(new CourseId($command->courseId));
        if (!$course) {
            throw new \RuntimeException('Course not found');
        }

        // Pas 3: Delegar la creació al domini (Enrollment::enroll és el Factory Method)
        // La regla de negoci viu a Enrollment, no aquí.
        $enrollment = Enrollment::enroll($student, $course);

        // Pas 4: Persistir el nou enrollment
        $this->enrollmentRepository->save($enrollment);
    }
}
