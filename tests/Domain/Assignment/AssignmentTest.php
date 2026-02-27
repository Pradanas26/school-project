<?php

namespace Tests\Domain\Assignment;

use App\Domain\Assignment\Assignment;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Course\CourseId;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use PHPUnit\Framework\TestCase;

/**
 * TEST DE DOMINI: AssignmentTest
 *
 * Prova el Factory Method Assignment::assign() directament.
 * Verifica tant la creació de l'Assignment com l'EFECTE LATERAL
 * sobre el Subject (que ara ha de tenir professor).
 */
final class AssignmentTest extends TestCase
{
    public function test_teacher_can_be_assigned_to_subject(): void
    {
        $subject = new Subject(
            new SubjectId('subject-1'),
            'Programació',
            new CourseId('course-1')
        );

        $teacher = new Teacher(
            new TeacherId('teacher-1'),
            'Jordi López',
            'Informàtica'
        );

        // Verificació prèvia: l'assignatura NO té professor
        $this->assertFalse($subject->hasTeacher());

        // Acció: assignar (Factory Method del domini)
        $assignment = Assignment::assign($subject, $teacher);

        // Verificació 1: l'Assignment té les dades correctes
        $this->assertEquals('subject-1', $assignment->subjectId()->value());
        $this->assertEquals('teacher-1', $assignment->teacherId()->value());

        // Verificació 2: efecte lateral — l'assignatura ARA té professor
        // Assignment::assign() crida subject->assignTeacher() internament
        $this->assertTrue($subject->hasTeacher());
        $this->assertEquals('teacher-1', $subject->teacherId()->value());
    }
}
