<?php

namespace Tests\Application;

use App\Application\EnrollStudent\EnrollStudentCommand;
use App\Application\EnrollStudent\EnrollStudentHandler;
use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use App\Domain\Enrollment\EnrollmentRepository;
use App\Domain\Student\Email;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use PHPUnit\Framework\TestCase;

/**
 * TEST D'APLICACIÓ: EnrollStudentTest
 *
 * Cas d'ús 5: EnrollStudent
 *
 * Usem MOCKS per substituir els repositoris. Això vol dir:
 *  - No hi ha base de dades
 *  - No hi ha fitxers
 *  - Els repositoris son "falsos" que retornen el que nosaltres diem
 *
 * Per a QUÈ serveixen els mocks aquí?
 *  1. Aïllar el test: si falla, és culpa del Handler, no de la BD
 *  2. Velocitat: s'executa en mil·lisegons
 *  3. Control: podem simular qualsevol escenari (estudiant no trobat, etc.)
 *
 * createMock(): PHPUnit crea una implementació buida de la interfície
 * method('find')->willReturn($obj): quan es cridi find(), retorna $obj
 * expects($this->once()): verifica que el mètode es crida exactament 1 cop
 */
final class EnrollStudentTest extends TestCase
{
    /**
     * TEST PRINCIPAL: un estudiant vàlid es pot matricular en un curs vàlid
     */
    public function test_student_can_be_enrolled_in_course(): void
    {
        // Preparem les entitats de domini reals (no mockejades)
        $student = new Student(
            new StudentId('student-1'),
            'Anna Garcia',
            new Email('anna@school.com')
        );
        $course = new Course(new CourseId('course-1'), 'DAW 1r', 2025);

        // Creem mocks dels repositoris (implementacions buides de les interfícies)
        $studentRepository    = $this->createMock(StudentRepository::class);
        $courseRepository     = $this->createMock(CourseRepository::class);
        $enrollmentRepository = $this->createMock(EnrollmentRepository::class);

        // Configurem el comportament dels mocks:
        //   "quan cerquis l'estudiant, retorna $student"
        $studentRepository->method('find')->willReturn($student);
        //   "quan cerquis el curs, retorna $course"
        $courseRepository->method('find')->willReturn($course);

        // Verificació: s'ha de cridar save() exactament 1 cop a enrollmentRepository
        $enrollmentRepository->expects($this->once())->method('save');

        // Creem el handler injectant els mocks
        $handler = new EnrollStudentHandler(
            $studentRepository,
            $courseRepository,
            $enrollmentRepository
        );

        // Executem el cas d'ús
        $handler->handle(new EnrollStudentCommand('student-1', 'course-1'));

        // Si hem arribat aquí sense excepció, el test passa
        $this->assertTrue(true);
    }

    /**
     * TEST ERROR: l'estudiant no existeix → ha de llençar RuntimeException
     */
    public function test_enroll_throws_if_student_not_found(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Student not found');

        $studentRepository    = $this->createMock(StudentRepository::class);
        $courseRepository     = $this->createMock(CourseRepository::class);
        $enrollmentRepository = $this->createMock(EnrollmentRepository::class);

        // El repositori retorna null → estudiant no trobat
        $studentRepository->method('find')->willReturn(null);

        $handler = new EnrollStudentHandler(
            $studentRepository,
            $courseRepository,
            $enrollmentRepository
        );

        // Això ha de llençar l'excepció configurada a expectException
        $handler->handle(new EnrollStudentCommand('inexistent', 'course-1'));
    }

    /**
     * TEST ERROR: el curs no existeix → ha de llençar RuntimeException
     */
    public function test_enroll_throws_if_course_not_found(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Course not found');

        $student = new Student(
            new StudentId('student-1'),
            'Anna Garcia',
            new Email('anna@school.com')
        );

        $studentRepository    = $this->createMock(StudentRepository::class);
        $courseRepository     = $this->createMock(CourseRepository::class);
        $enrollmentRepository = $this->createMock(EnrollmentRepository::class);

        // Estudiant existeix, però el curs no
        $studentRepository->method('find')->willReturn($student);
        $courseRepository->method('find')->willReturn(null); // ← curs no trobat

        $handler = new EnrollStudentHandler(
            $studentRepository,
            $courseRepository,
            $enrollmentRepository
        );

        $handler->handle(new EnrollStudentCommand('student-1', 'inexistent'));
    }
}
