<?php

namespace Tests\Domain\Subject;

use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Course\CourseId;
use App\Domain\Teacher\TeacherId;
use PHPUnit\Framework\TestCase;

/**
 * TEST DE DOMINI: SubjectTest
 * Proves pures de l'assignatura i el seu comportament.
 */
final class SubjectTest extends TestCase
{
    /** Una assignatura nova no té professor */
    public function test_subject_starts_without_teacher(): void
    {
        $subject = new Subject(
            new SubjectId('subject-1'),
            'Programació',
            new CourseId('course-1')
        );

        $this->assertNull($subject->teacherId());
        $this->assertFalse($subject->hasTeacher());
    }

    /** Es pot assignar un professor a una assignatura */
    public function test_subject_can_assign_teacher(): void
    {
        $subject = new Subject(
            new SubjectId('subject-1'),
            'Programació',
            new CourseId('course-1')
        );

        // Acció: assignar professor
        $subject->assignTeacher(new TeacherId('teacher-1'));

        // Verificació: ara té professor
        $this->assertTrue($subject->hasTeacher());
        $this->assertEquals('teacher-1', $subject->teacherId()->value());
    }

    /** Invariant: el nom no pot estar buit */
    public function test_empty_name_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Subject(new SubjectId('s-1'), '', new CourseId('c-1'));
    }
}
