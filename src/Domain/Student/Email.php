<?php

namespace App\Domain\Student;

/**
 * VALUE OBJECT: Email
 *
 * Representa una adreça de correu electrònic vàlida.
 * És un Value Object addicional (extra de nota) que demostra que
 * les VALIDACIONS viuen al DOMINI, no al controlador ni a la vista.
 *
 * Principi: si el domini accepta un Email, ja SAPS que és vàlid.
 * No cal validar-lo en cap altre lloc del codi.
 */
final class Email
{
    public function __construct(private string $value)
    {
        // Invariant de domini: el format ha de ser un email vàlid
        // filter_var és la funció nativa de PHP per validar emails
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email invàlid: '{$value}'");
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Dos emails son iguals si el seu valor string és idèntic.
     * (Value Objects s'igualen per valor, no per referència)
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
