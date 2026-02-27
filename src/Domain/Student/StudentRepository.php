<?php

namespace App\Domain\Student;

/**
 * PORT / CONTRACTE: StudentRepository
 *
 * Aquesta interfície és el CONTRACTE que defineix el domini:
 * "Necessito poder guardar i trobar estudiants, però NO m'importa COM".
 *
 * Patró Repository: amaga com es persisteixen les dades.
 *
 * Separació de responsabilitats:
 *  - El DOMINI defineix la interfície (aquí)
 *  - La INFRAESTRUCTURA implementa la interfície
 *    (InMemoryStudentRepository per ara, DoctrineStudentRepository en producció)
 *
 * El codi de domini/aplicació MAI sap si hi ha una base de dades,
 * un fitxer, o memòria. Només sap que existeix aquest contracte.
 */
interface StudentRepository
{
    /**
     * Busca un estudiant pel seu ID.
     * Retorna null si no existeix (no llença excepció).
     */
    public function find(StudentId $id): ?Student;

    /**
     * Guarda o actualitza un estudiant.
     * Si ja existia (mateix ID), el sobreescriu. Si no, el crea.
     */
    public function save(Student $student): void;

    /**
     * Retorna TOTS els estudiants.
     * Útil per llistar a les vistes.
     *
     * @return Student[]
     */
    public function findAll(): array;
}
