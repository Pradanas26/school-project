<?php

namespace App\Application\AssignTeacherToSubject;

use App\Domain\Assignment\Assignment;
use App\Domain\Assignment\AssignmentRepository;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;

/**
 * HANDLER: AssignTeacherToSubjectHandler
 *
 * CAS D'ÚS 6: Assignar un professor a una assignatura.
 *
 * Flux complet:
 *  1. Rep AssignTeacherToSubjectCommand (teacherId + subjectId)
 *  2. Carrega Teacher → si no existeix, error
 *  3. Carrega Subject → si no existeix, error
 *  4. DELEGA al domini: Assignment::assign($subject, $teacher)
 *     Aquest mètode:
 *       a) Crida subject->assignTeacher(teacher->id()) — efecte lateral!
 *       b) Crea i retorna el nou Assignment
 *  5. Persisteix l'Assignment NOU
 *  6. Persisteix el Subject MODIFICAT (ja té teacherId)
 *     ↑ Important! Si no fem el save del subject, perdem el canvi
 *
 * Nota sobre el doble save:
 *  En Doctrine amb Unit of Work, el flush() detectaria el canvi automàticament.
 *  En InMemory, hem de fer save() explícitament perquè els objectes son independents.
 */
final class AssignTeacherToSubjectHandler
{
    public function __construct(
        private TeacherRepository    $teacherRepository,
        private SubjectRepository    $subjectRepository,
        private AssignmentRepository $assignmentRepository
    ) {}

    public function handle(AssignTeacherToSubjectCommand $command): void
    {
        // Pas 1: Verificar que el professor existeix
        $teacher = $this->teacherRepository->find(new TeacherId($command->teacherId));
        if (!$teacher) {
            throw new \RuntimeException('Teacher not found');
        }

        // Pas 2: Verificar que l'assignatura existeix
        $subject = $this->subjectRepository->find(new SubjectId($command->subjectId));
        if (!$subject) {
            throw new \RuntimeException('Subject not found');
        }

        // Pas 3: Delegar al domini
        // Assignment::assign() fa dues coses:
        //   - Modifica $subject (subject->assignTeacher)
        //   - Crea i retorna un nou Assignment
        $assignment = Assignment::assign($subject, $teacher);

        // Pas 4: Persistir l'Assignment nou
        $this->assignmentRepository->save($assignment);

        // Pas 5: Persistir el Subject modificat
        // (ara té el teacherId assignat gràcies a subject->assignTeacher)
        $this->subjectRepository->save($subject);
    }
}
