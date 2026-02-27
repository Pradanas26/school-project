<?php

namespace App\Domain\Subject;

use App\Domain\Course\CourseId;
use App\Domain\Teacher\TeacherId;
use Doctrine\ORM\Mapping as ORM;

/**
 * ENTITAT: Subject (Assignatura)
 *
 * Representa una assignatura dins d'un curs (ex: "Programació" dins "DAW 1r").
 *
 * Relacions:
 *  - Pertany a un Course (via courseId)
 *  - Pot tenir un Teacher assignat (via teacherId, opcional)
 *
 * Invariants:
 *  - El nom no pot estar buit
 *  - Un teacher s'assigna mitjançant assignTeacher(), que és la REGLA DE NEGOCI
 *
 * Nota sobre el teacherId nullable:
 *  - null = l'assignatura encara no té professor
 *  - Quan s'assigna un professor, s'usa el cas d'ús AssignTeacherToSubject
 */
#[ORM\Entity]
#[ORM\Table(name: 'subjects')]
final class Subject
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    // Guardem l'ID del curs com a string (referència lleugera, sense JOIN)
    #[ORM\Column(type: 'string', length: 36)]
    private string $courseId;

    // null = sense professor assignat encara
    #[ORM\Column(type: 'string', length: 36, nullable: true)]
    private ?string $teacherId = null;

    /**
     * @param SubjectId $id       – ID únic
     * @param string    $name     – Nom de l'assignatura
     * @param CourseId  $courseId – A quin curs pertany
     */
    public function __construct(SubjectId $id, string $name, CourseId $courseId)
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('El nom de l\'assignatura no pot estar buit');
        }

        $this->id       = $id->value();
        $this->name     = $name;
        $this->courseId = $courseId->value();
        // teacherId comença com a null: cap professor assignat
    }

    /**
     * COMPORTAMENT DE DOMINI: assignar un professor a aquesta assignatura.
     *
     * Aquesta és la REGLA DE NEGOCI: l'entitat controla el seu propi estat.
     * El controlador o el handler NO modifiquen $teacherId directament,
     * sinó que criden aquest mètode que encapsula la lògica.
     *
     * Podríem afegir aquí: "no pots reassignar si ja en té un", etc.
     */
    public function assignTeacher(TeacherId $teacherId): void
    {
        $this->teacherId = $teacherId->value();
    }

    /** Comprova si l'assignatura ja té professor assignat */
    public function hasTeacher(): bool
    {
        return $this->teacherId !== null;
    }

    // ── Getters ────────────────────────────────────────────────────────────

    public function id(): SubjectId { return new SubjectId($this->id); }
    public function name(): string  { return $this->name; }

    public function courseId(): CourseId { return new CourseId($this->courseId); }

    /**
     * Retorna el TeacherId si n'hi ha, o null si no.
     * Nota: retornem el Value Object, no el string intern.
     */
    public function teacherId(): ?TeacherId
    {
        return $this->teacherId !== null
            ? new TeacherId($this->teacherId)
            : null;
    }
}
