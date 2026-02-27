<?php

namespace App\Domain\Teacher;

use Doctrine\ORM\Mapping as ORM;

/**
 * ENTITAT: Teacher (Professor)
 *
 * Representa un professor de l'escola.
 * A diferència dels usuaris del projecte del professor (User),
 * un Teacher té una especialitat (la seva matèria principal).
 *
 * Invariants:
 *  - El nom no pot estar buit
 *  - L'especialitat no pot estar buida
 *    (tot professor ha de tenir una àrea de coneixement)
 */
#[ORM\Entity]
#[ORM\Table(name: 'teachers')]
final class Teacher
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    // L'especialitat és el camp extra que diferencia Teacher de User
    #[ORM\Column(type: 'string')]
    private string $specialty;

    /**
     * @param TeacherId $id        – ID únic
     * @param string    $name      – Nom complet del professor
     * @param string    $specialty – Àrea d'especialitat (ex: "Informàtica", "Matemàtiques")
     */
    public function __construct(TeacherId $id, string $name, string $specialty)
    {
        // Invariant 1: nom obligatori
        if (trim($name) === '') {
            throw new \InvalidArgumentException('El nom del professor no pot estar buit');
        }

        // Invariant 2: especialitat obligatòria
        if (trim($specialty) === '') {
            throw new \InvalidArgumentException('L\'especialitat del professor no pot estar buida');
        }

        $this->id        = $id->value();
        $this->name      = $name;
        $this->specialty = $specialty;
    }

    public function id(): TeacherId      { return new TeacherId($this->id); }
    public function name(): string       { return $this->name; }
    public function specialty(): string  { return $this->specialty; }
}
