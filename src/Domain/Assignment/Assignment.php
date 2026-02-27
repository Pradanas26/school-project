<?php

namespace App\Domain\Assignment;

use App\Domain\Subject\Subject;
use App\Domain\Teacher\Teacher;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;
use Doctrine\ORM\Mapping as ORM;

/**
 * AGREGAT: Assignment (Assignació professor → assignatura)
 *
 * Representa el fet que UN PROFESSOR s'assigna a UNA ASSIGNATURA.
 * És la implementació del cas d'ús 6: AssignTeacherToSubject.
 *
 * Igual que Enrollment, fa servir el patró Factory Method:
 *  - Constructor privat
 *  - Mètode estàtic ::assign() com a porta d'entrada
 *
 * EFECTE LATERAL IMPORTANT:
 *  Quan creem un Assignment, cridem subject->assignTeacher().
 *  Això vol dir que l'assignatura SAP que té un professor.
 *  Dos objectes s'actualitzen: Assignment es crea, Subject es modifica.
 *  El handler ha de persistir AMBDÓS.
 */
#[ORM\Entity]
#[ORM\Table(name: 'assignments')]
final class Assignment
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string', length: 36)]
    private string $subjectId;

    #[ORM\Column(type: 'string', length: 36)]
    private string $teacherId;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $assignedAt;

    /** Constructor privat: usa ::assign() */
    private function __construct(
        AssignmentId $id,
        SubjectId    $subjectId,
        TeacherId    $teacherId,
        \DateTimeImmutable $assignedAt
    ) {
        $this->id         = $id->value();
        $this->subjectId  = $subjectId->value();
        $this->teacherId  = $teacherId->value();
        $this->assignedAt = $assignedAt;
    }

    /**
     * FACTORY METHOD: assign()
     *
     * Crea una nova assignació i, com a efecte lateral de domini,
     * notifica a l'assignatura que ja té professor.
     *
     * Patró: la REGLA DE NEGOCI viu aquí, no al handler ni al controlador.
     *
     * Ús:
     *   $assignment = Assignment::assign($subject, $teacher);
     *   // Ara $subject->hasTeacher() === true
     *   // Persistim assignment I subject al handler
     */
    public static function assign(Subject $subject, Teacher $teacher): self
    {
        // Efecte lateral de domini: l'assignatura ara coneix el seu professor
        $subject->assignTeacher($teacher->id());

        // Creem el registre de l'assignació
        return new self(
            AssignmentId::generate(),
            $subject->id(),
            $teacher->id(),
            new \DateTimeImmutable()
        );
    }

    // ── Getters ────────────────────────────────────────────────────────────

    public function id(): AssignmentId               { return new AssignmentId($this->id); }
    public function subjectId(): SubjectId           { return new SubjectId($this->subjectId); }
    public function teacherId(): TeacherId           { return new TeacherId($this->teacherId); }
    public function assignedAt(): \DateTimeImmutable { return $this->assignedAt; }
}
