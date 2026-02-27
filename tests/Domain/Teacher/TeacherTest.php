<?php

namespace Tests\Domain\Teacher;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use PHPUnit\Framework\TestCase;

/**
 * TEST DE DOMINI: TeacherTest
 * Proves pures de les regles de negoci del Professor.
 */
final class TeacherTest extends TestCase
{
    public function test_teacher_can_be_created(): void
    {
        $teacher = new Teacher(
            new TeacherId('teacher-1'),
            'Jordi López',
            'Informàtica'
        );

        $this->assertEquals('teacher-1', $teacher->id()->value());
        $this->assertEquals('Jordi López', $teacher->name());
        $this->assertEquals('Informàtica', $teacher->specialty());
    }

    /** Invariant: nom obligatori */
    public function test_empty_name_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Teacher(new TeacherId('t-1'), '', 'Informàtica');
    }

    /** Invariant: especialitat obligatòria */
    public function test_empty_specialty_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Teacher(new TeacherId('t-1'), 'Jordi', '');
    }
}
