<?php

namespace Tests\Domain\Course;

use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use PHPUnit\Framework\TestCase;

/**
 * TEST DE DOMINI: CourseTest
 * Proves pures de les regles de negoci del Curs.
 */
final class CourseTest extends TestCase
{
    public function test_course_can_be_created(): void
    {
        $course = new Course(new CourseId('course-1'), 'DAW 1r', 2025);

        $this->assertEquals('course-1', $course->id()->value());
        $this->assertEquals('DAW 1r', $course->name());
        $this->assertEquals(2025, $course->year());
    }

    /** Invariant: el nom no pot estar buit */
    public function test_empty_name_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Course(new CourseId('course-1'), '', 2025);
    }

    /** Invariant: l'any ha de ser entre 2000 i 2100 */
    public function test_invalid_year_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Course(new CourseId('course-1'), 'DAW', 1999); // < 2000
    }
}
