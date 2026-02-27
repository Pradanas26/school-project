<?php

namespace App\Domain\Course;

use Doctrine\ORM\Mapping as ORM;

/**
 * ENTITAT: Course (Curs)
 *
 * Representa un curs acadèmic (ex: "DAW 1r, 2025").
 * Té identitat pròpia: dos cursos amb el mateix nom i any però
 * IDs diferents son cursos DIFERENTS.
 *
 * Invariants del domini:
 *  - El nom no pot estar buit
 *  - L'any ha de ser raonable (entre 2000 i 2100)
 */
#[ORM\Entity]
#[ORM\Table(name: 'courses')]
final class Course
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $year;

    /**
     * @param CourseId $id   – Identificador únic del curs
     * @param string   $name – Nom del curs (ex: "DAW 1r", "SMX 2n")
     * @param int      $year – Any acadèmic (ex: 2025)
     */
    public function __construct(CourseId $id, string $name, int $year)
    {
        // Invariant 1: nom no buit
        if (trim($name) === '') {
            throw new \InvalidArgumentException('El nom del curs no pot estar buit');
        }

        // Invariant 2: any dins d'un rang raonable
        if ($year < 2000 || $year > 2100) {
            throw new \InvalidArgumentException('L\'any ha de ser entre 2000 i 2100');
        }

        $this->id   = $id->value();
        $this->name = $name;
        $this->year = $year;
    }

    public function id(): CourseId { return new CourseId($this->id); }
    public function name(): string { return $this->name; }
    public function year(): int    { return $this->year; }
}
