<?php

namespace Tests\Domain\Student;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\Email;
use PHPUnit\Framework\TestCase;

/**
 * TEST DE DOMINI: StudentTest
 *
 * Tests PURS de domini: sense mocks, sense repositoris, sense Doctrine.
 * Només provem les REGLES DE NEGOCI i les INVARIANTS de l'entitat Student.
 *
 * Per què son "purs"?
 *  - No depenen d'infraestructura (BD, HTTP, etc.)
 *  - Execució ultra ràpida (ms)
 *  - Si fallen, el problema és al DOMINI, no a la infraestructura
 */
final class StudentTest extends TestCase
{
    /** Un estudiant vàlid es pot crear correctament */
    public function test_student_can_be_created(): void
    {
        $student = new Student(
            new StudentId('student-1'),
            'Anna Garcia',
            new Email('anna@school.com')
        );

        // Verificam que els getters retornen el que hem posat
        $this->assertEquals('student-1', $student->id()->value());
        $this->assertEquals('Anna Garcia', $student->name());
        $this->assertEquals('anna@school.com', $student->email()->value());
    }

    /** Invariant: el nom no pot estar buit */
    public function test_empty_name_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // Intentar crear un Student amb nom buit ha de fallar
        new Student(
            new StudentId('student-1'),
            '',                         // ← nom buit: ha de llençar excepció
            new Email('anna@school.com')
        );
    }

    /** Invariant del Value Object Email: format ha de ser vàlid */
    public function test_invalid_email_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // El Value Object Email valida el format automàticament
        new Email('no-es-un-email');    // ← ha de llençar excepció
    }

    /** Invariant del Value Object StudentId: no pot estar buit */
    public function test_empty_student_id_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new StudentId('');              // ← ha de llençar excepció
    }

    /** Els IDs generats automàticament son únics */
    public function test_generated_ids_are_unique(): void
    {
        $id1 = StudentId::generate();
        $id2 = StudentId::generate();

        // Dos IDs generats no poden ser iguals
        $this->assertFalse($id1->equals($id2));
    }
}
