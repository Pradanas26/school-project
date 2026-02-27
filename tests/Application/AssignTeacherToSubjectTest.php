<?php

namespace Tests\Application;

use App\Application\AssignTeacherToSubject\AssignTeacherToSubjectCommand;
use App\Application\AssignTeacherToSubject\AssignTeacherToSubjectHandler;
use App\Domain\Assignment\AssignmentRepository;
use App\Domain\Course\CourseId;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;
use PHPUnit\Framework\TestCase;

/**
 * TEST D'APLICACIÓ: AssignTeacherToSubjectTest
 *
 * Cas d'ús 6: AssignTeacherToSubject
 *
 * Tests amb mocks que verifiquen:
 *  1. El flux normal (professor i assignatura existeixen)
 *  2. Error si el professor no existeix
 *  3. Error si l'assignatura no existeix
 *
 * Nota especial sobre el doble save():
 *  El handler fa save() de l'Assignment I del Subject modificat.
 *  Per tant, subjectRepository.save ha de cridar-se 1 cop.
 */
final class AssignTeacherToSubjectTest extends TestCase
{
    /**
     * TEST PRINCIPAL: professor i assignatura existents → assignació correcta
     */
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

        $teacherRepository    = $this->createMock(TeacherRepository::class);
        $subjectRepository    = $this->createMock(SubjectRepository::class);
        $assignmentRepository = $this->createMock(AssignmentRepository::class);

        $teacherRepository->method('find')->willReturn($teacher);
        $subjectRepository->method('find')->willReturn($subject);

        // Verificació: s'ha de cridar save() 1 cop al assignment repo
        $assignmentRepository->expects($this->once())->method('save');
        // Verificació: s'ha de cridar save() 1 cop al subject repo (per actualitzar el teacherId)
        $subjectRepository->expects($this->once())->method('save');

        $handler = new AssignTeacherToSubjectHandler(
            $teacherRepository,
            $subjectRepository,
            $assignmentRepository
        );

        $handler->handle(new AssignTeacherToSubjectCommand('teacher-1', 'subject-1'));

        $this->assertTrue(true);
    }

    /**
     * TEST ERROR: el professor no existeix → RuntimeException
     */
    public function test_assign_throws_if_teacher_not_found(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Teacher not found');

        $teacherRepository    = $this->createMock(TeacherRepository::class);
        $subjectRepository    = $this->createMock(SubjectRepository::class);
        $assignmentRepository = $this->createMock(AssignmentRepository::class);

        // Professor no trobat
        $teacherRepository->method('find')->willReturn(null);

        $handler = new AssignTeacherToSubjectHandler(
            $teacherRepository,
            $subjectRepository,
            $assignmentRepository
        );

        $handler->handle(new AssignTeacherToSubjectCommand('inexistent', 'subject-1'));
    }

    /**
     * TEST ERROR: l'assignatura no existeix → RuntimeException
     */
    public function test_assign_throws_if_subject_not_found(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Subject not found');

        $teacher = new Teacher(
            new TeacherId('teacher-1'),
            'Jordi López',
            'Informàtica'
        );

        $teacherRepository    = $this->createMock(TeacherRepository::class);
        $subjectRepository    = $this->createMock(SubjectRepository::class);
        $assignmentRepository = $this->createMock(AssignmentRepository::class);

        // Professor existeix, assignatura no
        $teacherRepository->method('find')->willReturn($teacher);
        $subjectRepository->method('find')->willReturn(null); // ← no trobada

        $handler = new AssignTeacherToSubjectHandler(
            $teacherRepository,
            $subjectRepository,
            $assignmentRepository
        );

        $handler->handle(new AssignTeacherToSubjectCommand('teacher-1', 'inexistent'));
    }
}
