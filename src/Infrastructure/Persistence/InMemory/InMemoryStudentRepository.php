<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;

/**
 * ADAPTADOR: InMemoryStudentRepository
 *
 * Implementació InMemory del contracte StudentRepository.
 *
 * "InMemory" vol dir que les dades viuen a la memòria RAM
 * mentre l'aplicació s'executa. Al reiniciar, es perden.
 *
 * Per a QUÈ serveix?
 *  1. TESTS: perfecta per a PHPUnit, sense BD, ultra ràpida
 *  2. DESENVOLUPAMENT: permet provar la lògica sense configurar MySQL
 *  3. PROTOTIPAT: veiem com funciona l'aplicació sense infraestructura
 *
 * Com es canvia per Doctrine?
 *  Crees DoctrineStudentRepository que implementa la mateixa interfície.
 *  Al container de dependències, canvies InMemory per Doctrine.
 *  El domini i l'aplicació NO es toquen. ← Això és DDD.
 *
 * Estructura interna:
 *  Un array associatiu indexat per l'ID (string) de l'estudiant.
 *  Ex: ['student_abc123' => Student object, ...]
 */
final class InMemoryStudentRepository implements StudentRepository
{
    /** @var array<string, Student> Emmagatzematge en memòria: id => Student */
    private array $students = [];

    /**
     * Cerca un estudiant per ID.
     * Retorna null si no existeix (no llença excepció).
     */
    public function find(StudentId $id): ?Student
    {
        // L'operador ?? retorna null si la clau no existeix
        return $this->students[$id->value()] ?? null;
    }

    /**
     * Guarda o actualitza un estudiant.
     * Com que usem l'ID com a clau, si ja existia el sobreescriu.
     */
    public function save(Student $student): void
    {
        $this->students[$student->id()->value()] = $student;
    }

    /**
     * Retorna tots els estudiants com a array indexat des de 0.
     * array_values() elimina les claus string per retornar [0,1,2,...].
     *
     * @return Student[]
     */
    public function findAll(): array
    {
        return array_values($this->students);
    }
}
