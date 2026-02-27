<?php

namespace App\Http\Controllers;

use App\Application\CreateStudent\CreateStudentCommand;
use App\Application\CreateStudent\CreateStudentHandler;
use App\Application\CreateCourse\CreateCourseCommand;
use App\Application\CreateCourse\CreateCourseHandler;
use App\Application\CreateSubject\CreateSubjectCommand;
use App\Application\CreateSubject\CreateSubjectHandler;
use App\Application\CreateTeacher\CreateTeacherCommand;
use App\Application\CreateTeacher\CreateTeacherHandler;
use App\Application\EnrollStudent\EnrollStudentCommand;
use App\Application\EnrollStudent\EnrollStudentHandler;
use App\Application\AssignTeacherToSubject\AssignTeacherToSubjectCommand;
use App\Application\AssignTeacherToSubject\AssignTeacherToSubjectHandler;
use App\Domain\Student\StudentId;
use App\Domain\Course\CourseId;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseRepository;
use App\Domain\Subject\SubjectRepository;
use App\Domain\Teacher\TeacherRepository;
use App\Domain\Enrollment\EnrollmentRepository;
use App\Domain\Assignment\AssignmentRepository;

/**
 * CONTROLADOR MVC: SchoolController
 *
 * En MVC (Model-Vista-Controlador), el Controlador és l'intermediari:
 *  - REP la petició HTTP (GET/POST, paràmetres, formularis)
 *  - CRIDA el cas d'ús adequat (Application Service / Handler)
 *  - PASSA les dades a la Vista per mostrar-les
 *
 * El Controlador NO conté lògica de negoci.
 * La lògica de negoci viu al Domini.
 * Els casos d'ús s'orquestren als Handlers.
 *
 * Flux d'una petició típica:
 *   1. Usuari fa clic a un botó → POST a index.php?route=student/store
 *   2. index.php identifica la ruta i crida $controller->studentStore()
 *   3. studentStore() crea un Command amb les dades del formulari ($_POST)
 *   4. studentStore() passa el Command al Handler
 *   5. Handler executa el cas d'ús (crea l'estudiant, el guarda)
 *   6. Redirigim a la llista d'estudiants
 *
 * Injecció de dependències:
 *  El controlador rep tots els handlers i repositoris al constructor.
 *  Això fa que sigui fàcil de testejar i de canviar implementacions.
 */
final class SchoolController
{
    public function __construct(
        // Handlers dels casos d'ús
        private CreateStudentHandler          $createStudentHandler,
        private CreateCourseHandler           $createCourseHandler,
        private CreateSubjectHandler          $createSubjectHandler,
        private CreateTeacherHandler          $createTeacherHandler,
        private EnrollStudentHandler          $enrollStudentHandler,
        private AssignTeacherToSubjectHandler $assignTeacherHandler,
        // Repositoris per llegir dades i mostrar-les a les vistes
        private StudentRepository             $studentRepo,
        private CourseRepository              $courseRepo,
        private SubjectRepository             $subjectRepo,
        private TeacherRepository             $teacherRepo,
        private EnrollmentRepository          $enrollmentRepo,
        private AssignmentRepository          $assignmentRepo
    ) {}

    // ═══════════════════════════════════════════════════════════════════════
    // ESTUDIANTS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * GET /index.php?route=student
     * Mostra la llista de tots els estudiants.
     */
    public function student(): void
    {
        // Llegim tots els estudiants del repositori i els passem a la vista
        $students = $this->studentRepo->findAll();
        require __DIR__ . '/../Views/student/index.php';
    }

    /**
     * GET /index.php?route=student/create
     * Mostra el formulari per crear un nou estudiant.
     */
    public function studentCreate(): void
    {
        require __DIR__ . '/../Views/student/create.php';
    }

    /**
     * POST /index.php?route=student/store
     * Processa el formulari de creació d'estudiant.
     */
    public function studentStore(): void
    {
        try {
            // Creem el Command amb les dades del formulari
            // StudentId::generate() crea un ID únic nou
            $this->createStudentHandler->handle(new CreateStudentCommand(
                StudentId::generate()->value(),
                $_POST['name']  ?? '',
                $_POST['email'] ?? ''
            ));
            // Redirigim a la llista si tot ha anat bé
            header('Location: index.php?route=student');
            exit;
        } catch (\Throwable $e) {
            // Si hi ha error (email invàlid, nom buit...), mostrem el formulari amb l'error
            $error = $e->getMessage();
            require __DIR__ . '/../Views/student/create.php';
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PROFESSORS
    // ═══════════════════════════════════════════════════════════════════════

    /** GET /index.php?route=teacher — Llista de professors */
    public function teacher(): void
    {
        $teachers = $this->teacherRepo->findAll();
        require __DIR__ . '/../Views/teacher/index.php';
    }

    /** GET /index.php?route=teacher/create — Formulari nou professor */
    public function teacherCreate(): void
    {
        require __DIR__ . '/../Views/teacher/create.php';
    }

    /** POST /index.php?route=teacher/store — Guarda nou professor */
    public function teacherStore(): void
    {
        try {
            $this->createTeacherHandler->handle(new CreateTeacherCommand(
                TeacherId::generate()->value(),
                $_POST['name']      ?? '',
                $_POST['specialty'] ?? ''
            ));
            header('Location: index.php?route=teacher');
            exit;
        } catch (\Throwable $e) {
            $error = $e->getMessage();
            require __DIR__ . '/../Views/teacher/create.php';
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // CURSOS
    // ═══════════════════════════════════════════════════════════════════════

    /** GET /index.php?route=course — Llista de cursos */
    public function course(): void
    {
        $courses = $this->courseRepo->findAll();
        require __DIR__ . '/../Views/course/index.php';
    }

    /** GET /index.php?route=course/create — Formulari nou curs */
    public function courseCreate(): void
    {
        require __DIR__ . '/../Views/course/create.php';
    }

    /** POST /index.php?route=course/store — Guarda nou curs */
    public function courseStore(): void
    {
        try {
            $this->createCourseHandler->handle(new CreateCourseCommand(
                CourseId::generate()->value(),
                $_POST['name'] ?? '',
                (int)($_POST['year'] ?? date('Y'))
            ));
            header('Location: index.php?route=course');
            exit;
        } catch (\Throwable $e) {
            $error = $e->getMessage();
            require __DIR__ . '/../Views/course/create.php';
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ASSIGNATURES
    // ═══════════════════════════════════════════════════════════════════════

    /** GET /index.php?route=subject — Llista d'assignatures */
    public function subject(): void
    {
        $subjects = $this->subjectRepo->findAll();
        $courses  = $this->courseRepo->findAll();
        require __DIR__ . '/../Views/subject/index.php';
    }

    /** GET /index.php?route=subject/create — Formulari nova assignatura */
    public function subjectCreate(): void
    {
        // Necessitem els cursos per mostrar-los al selector del formulari
        $courses = $this->courseRepo->findAll();
        require __DIR__ . '/../Views/subject/create.php';
    }

    /** POST /index.php?route=subject/store — Guarda nova assignatura */
    public function subjectStore(): void
    {
        $courses = $this->courseRepo->findAll(); // Per si cal tornar al formulari
        try {
            $this->createSubjectHandler->handle(new CreateSubjectCommand(
                SubjectId::generate()->value(),
                $_POST['name']     ?? '',
                $_POST['courseId'] ?? ''
            ));
            header('Location: index.php?route=subject');
            exit;
        } catch (\Throwable $e) {
            $error = $e->getMessage();
            require __DIR__ . '/../Views/subject/create.php';
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // MATRICULACIÓ (Cas d'ús 5: EnrollStudent)
    // ═══════════════════════════════════════════════════════════════════════

    /** GET /index.php?route=enroll — Pàgina de matriculació */
    public function enroll(): void
    {
        // Necessitem estudiants, cursos i matrícules existents per a la vista
        $students    = $this->studentRepo->findAll();
        $courses     = $this->courseRepo->findAll();
        $enrollments = $this->enrollmentRepo->findAll();
        require __DIR__ . '/../Views/enroll/index.php';
    }

    /** POST /index.php?route=enroll/store — Executa la matriculació */
    public function enrollStore(): void
    {
        try {
            // Cas d'ús 5: EnrollStudent
            $this->enrollStudentHandler->handle(new EnrollStudentCommand(
                $_POST['studentId'] ?? '',
                $_POST['courseId']  ?? ''
            ));
            header('Location: index.php?route=enroll');
            exit;
        } catch (\Throwable $e) {
            $error    = $e->getMessage();
            $students    = $this->studentRepo->findAll();
            $courses     = $this->courseRepo->findAll();
            $enrollments = $this->enrollmentRepo->findAll();
            require __DIR__ . '/../Views/enroll/index.php';
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ASSIGNACIÓ PROFESSOR (Cas d'ús 6: AssignTeacherToSubject)
    // ═══════════════════════════════════════════════════════════════════════

    /** GET /index.php?route=assign — Pàgina d'assignació professor */
    public function assign(): void
    {
        $teachers    = $this->teacherRepo->findAll();
        $subjects    = $this->subjectRepo->findAll();
        $assignments = $this->assignmentRepo->findAll();
        require __DIR__ . '/../Views/assign/index.php';
    }

    /** POST /index.php?route=assign/store — Executa l'assignació */
    public function assignStore(): void
    {
        try {
            // Cas d'ús 6: AssignTeacherToSubject
            $this->assignTeacherHandler->handle(new AssignTeacherToSubjectCommand(
                $_POST['teacherId'] ?? '',
                $_POST['subjectId'] ?? ''
            ));
            header('Location: index.php?route=assign');
            exit;
        } catch (\Throwable $e) {
            $error       = $e->getMessage();
            $teachers    = $this->teacherRepo->findAll();
            $subjects    = $this->subjectRepo->findAll();
            $assignments = $this->assignmentRepo->findAll();
            require __DIR__ . '/../Views/assign/index.php';
        }
    }
}
