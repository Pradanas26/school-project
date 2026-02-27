<?php

namespace App\Domain\Enrollment;

use App\Domain\Student\Student;
use App\Domain\Course\Course;
use App\Domain\Student\StudentId;
use App\Domain\Course\CourseId;
use Doctrine\ORM\Mapping as ORM;

/**
 * AGREGAT: Enrollment (Matrícula)
 *
 * Un Agregat és una agrupació d'entitats i value objects que
 * es tracten com una UNITAT des del punt de vista de la consistència.
 *
 * Enrollment representa el fet que UN ESTUDIANT es matricula a UN CURS.
 * És la implementació del cas d'ús 5: EnrollStudent.
 *
 * Per què és un Agregat i no simplement dues relacions?
 *  - Encapsula la REGLA DE NEGOCI: "una matrícula necessita un estudiant
 *    i un curs vàlids, i registra QUAN va passar"
 *  - El mètode factory ::enroll() conté tota la lògica, no el handler
 *
 * Patró Factory Method:
 *  El constructor és privat per forçar l'ús de ::enroll().
 *  Així mai pots crear una Enrollment "incompleta" o "inconsistent".
 */
#[ORM\Entity]
#[ORM\Table(name: 'enrollments')]
final class Enrollment
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    // Guardem els IDs com strings (referències lleugeres)
    #[ORM\Column(type: 'string', length: 36)]
    private string $studentId;

    #[ORM\Column(type: 'string', length: 36)]
    private string $courseId;

    // Registrem automàticament quan s'ha fet la matrícula
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $enrolledAt;

    /**
     * Constructor PRIVAT: ningú pot crear una Enrollment directament.
     * S'ha d'usar el mètode ::enroll() de sota.
     */
    private function __construct(
        EnrollmentId $id,
        StudentId    $studentId,
        CourseId     $courseId,
        \DateTimeImmutable $enrolledAt
    ) {
        $this->id         = $id->value();
        $this->studentId  = $studentId->value();
        $this->courseId   = $courseId->value();
        $this->enrolledAt = $enrolledAt;
    }

    /**
     * FACTORY METHOD: enroll()
     *
     * Aquesta és la porta d'entrada al domini per matricular un estudiant.
     * Rep les entitats reals (Student i Course) per assegurar que existeixen.
     *
     * El handler del cas d'ús cridarà aquest mètode:
     *   $enrollment = Enrollment::enroll($student, $course);
     *
     * Aquí podríem afegir lògica com:
     *   - "Un estudiant no pot matricular-se al mateix curs dues vegades"
     *   - "Un curs no pot tenir més de 30 alumnes"
     *   etc.
     */
    public static function enroll(Student $student, Course $course): self
    {
        return new self(
            EnrollmentId::generate(),  // ID únic automàtic
            $student->id(),            // Agafem l'ID de l'entitat Student
            $course->id(),             // Agafem l'ID de l'entitat Course
            new \DateTimeImmutable()   // Timestamp de la matrícula
        );
    }

    // ── Getters ────────────────────────────────────────────────────────────

    public function id(): EnrollmentId               { return new EnrollmentId($this->id); }
    public function studentId(): StudentId           { return new StudentId($this->studentId); }
    public function courseId(): CourseId             { return new CourseId($this->courseId); }
    public function enrolledAt(): \DateTimeImmutable { return $this->enrolledAt; }
}
