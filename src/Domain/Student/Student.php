<?php

namespace App\Domain\Student;

use Doctrine\ORM\Mapping as ORM;

/**
 * ENTITAT: Student
 *
 * Una Entitat és un objecte del domini que:
 *  - Té IDENTITAT pròpia (StudentId): dos estudiants amb el mateix nom
 *    NO son el mateix estudiant si tenen IDs diferents.
 *  - Té ESTAT intern (name, email)
 *  - Té COMPORTAMENT (els seus mètodes defineixen el que pot fer)
 *  - Conté les seves INVARIANTS (regles que sempre han de complir-se)
 *
 * Els atributs #[ORM\...] son per a Doctrine (persistència a BD).
 * El DOMINI en si no depèn de Doctrine: si treus els atributs,
 * la classe segueix funcionant perfectament.
 *
 * Relació amb Value Objects:
 *  - StudentId: embolcalla l'ID per evitar errors de tipus
 *  - Email: garanteix que el correu sempre sigui vàlid
 */
#[ORM\Entity]
#[ORM\Table(name: 'students')]
final class Student
{
    // L'ID s'emmagatzema com a string per Doctrine, però s'exposa com a StudentId
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    // El email es desa com a string (Doctrine no sap que és un Email VO)
    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    /**
     * Constructor: l'únic punt d'entrada per crear un Student vàlid.
     * Si arriba fins aquí sense excepció, l'estudiant ES VÀLID.
     *
     * @param StudentId $id    – Value Object amb l'identificador únic
     * @param string    $name  – Nom complet de l'estudiant
     * @param Email     $email – Value Object que garanteix format correcte
     */
    public function __construct(StudentId $id, string $name, Email $email)
    {
        // Invariant: el nom no pot estar buit ni ser espais en blanc
        if (trim($name) === '') {
            throw new \InvalidArgumentException('El nom de l\'estudiant no pot estar buit');
        }

        // Guardem el valor string intern (Doctrine no gestiona Value Objects directament)
        $this->id    = $id->value();
        $this->name  = $name;
        $this->email = $email->value();
    }

    // ── Getters (accés controlat a l'estat intern) ─────────────────────────

    /**
     * Retorna l'ID com a Value Object (no com a string nu)
     * Així qui rep l'ID sap exactament de quin tipus és.
     */
    public function id(): StudentId
    {
        return new StudentId($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * Retorna el correu com a Value Object Email.
     * Garanteix que sempre és un email vàlid.
     */
    public function email(): Email
    {
        return new Email($this->email);
    }
}
