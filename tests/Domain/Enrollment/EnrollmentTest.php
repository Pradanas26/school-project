<?php

namespace Tests\Domain\Enrollment;

use App\Domain\Enrollment\Enrollment;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\Email;
use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use PHPUnit\Framework\TestCase;

/**
 * TEST DE DOMINI: EnrollmentTest
 *
 * Prova el Factory Method Enrollment::enroll() directament,
 * sense cap mock ni repositori.
 * Verifica que l'agregat es crea correctament amb les dades correctes.
 */
final class EnrollmentTest extends TestCase
{
    public function test_student_can_be_enrolled_in_course(): void
    {
        // Preparem les entitats del domini directament (sense repositoris)
        $student = new Student(
            new StudentId('student-1'),
            'Anna Garcia',
            new Email('anna@school.com')
        );

        $course = new Course(
            new CourseId('course-1'),
            'DAW 1r',
            2025
        );

        // Acció: matricular (Factory Method del domini)
        $enrollment = Enrollment::enroll($student, $course);

        // Verificacions: l'enrollment conté les dades correctes
        $this->assertEquals('student-1', $enrollment->studentId()->value());
        $this->assertEquals('course-1', $enrollment->courseId()->value());
        $this->assertInstanceOf(\DateTimeImmutable::class, $enrollment->enrolledAt());
    }
}
