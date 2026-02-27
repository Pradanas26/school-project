<?php

/**
 * FRONT CONTROLLER: index.php
 * Versió amb gestió d'errors visible per diagnosi
 */

// Mostrar TOTS els errors mentre depurem
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

// ── 1. Inicialitzar Doctrine amb SQLite ────────────────────────────────────
try {
    $em = require __DIR__ . '/config/doctrine.php';
} catch (\Throwable $e) {
    die('<h2 style="color:red;font-family:sans-serif;padding:2rem">
        ERROR al carregar Doctrine:<br><br>' . htmlspecialchars($e->getMessage()) . '
        <br><br><small>' . htmlspecialchars($e->getFile() . ':' . $e->getLine()) . '</small>
    </h2>');
}

// ── 2. Crear les taules automàticament si no existeixen ───────────────────
try {
    $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
    $allClasses = [
        $em->getClassMetadata(\App\Domain\Student\Student::class),
        $em->getClassMetadata(\App\Domain\Course\Course::class),
        $em->getClassMetadata(\App\Domain\Subject\Subject::class),
        $em->getClassMetadata(\App\Domain\Teacher\Teacher::class),
        $em->getClassMetadata(\App\Domain\Enrollment\Enrollment::class),
        $em->getClassMetadata(\App\Domain\Assignment\Assignment::class),
    ];
    $schemaTool->updateSchema($allClasses, true);
} catch (\Throwable $e) {
    die('<h2 style="color:red;font-family:sans-serif;padding:2rem">
        ERROR al crear les taules:<br><br>' . htmlspecialchars($e->getMessage()) . '
        <br><br><small>' . htmlspecialchars($e->getFile() . ':' . $e->getLine()) . '</small>
    </h2>');
}

// ── 3. Repositoris Doctrine ───────────────────────────────────────────────
use App\Infrastructure\Persistence\Doctrine\DoctrineStudentRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineCourseRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineSubjectRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineTeacherRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineEnrollmentRepository;
use App\Infrastructure\Persistence\Doctrine\DoctrineAssignmentRepository;

use App\Application\CreateStudent\CreateStudentHandler;
use App\Application\CreateCourse\CreateCourseHandler;
use App\Application\CreateSubject\CreateSubjectHandler;
use App\Application\CreateTeacher\CreateTeacherHandler;
use App\Application\EnrollStudent\EnrollStudentHandler;
use App\Application\AssignTeacherToSubject\AssignTeacherToSubjectHandler;
use App\Http\Controllers\SchoolController;

$studentRepo    = new DoctrineStudentRepository($em);
$courseRepo     = new DoctrineCourseRepository($em);
$subjectRepo    = new DoctrineSubjectRepository($em);
$teacherRepo    = new DoctrineTeacherRepository($em);
$enrollmentRepo = new DoctrineEnrollmentRepository($em);
$assignmentRepo = new DoctrineAssignmentRepository($em);

$controller = new SchoolController(
    new CreateStudentHandler($studentRepo),
    new CreateCourseHandler($courseRepo),
    new CreateSubjectHandler($subjectRepo, $courseRepo),
    new CreateTeacherHandler($teacherRepo),
    new EnrollStudentHandler($studentRepo, $courseRepo, $enrollmentRepo),
    new AssignTeacherToSubjectHandler($teacherRepo, $subjectRepo, $assignmentRepo),
    $studentRepo,
    $courseRepo,
    $subjectRepo,
    $teacherRepo,
    $enrollmentRepo,
    $assignmentRepo
);

// ── 4. Router ─────────────────────────────────────────────────────────────
$route  = $_GET['route'] ?? 'student';
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    'GET' => [
        'student'        => fn() => $controller->student(),
        'student/create' => fn() => $controller->studentCreate(),
        'teacher'        => fn() => $controller->teacher(),
        'teacher/create' => fn() => $controller->teacherCreate(),
        'course'         => fn() => $controller->course(),
        'course/create'  => fn() => $controller->courseCreate(),
        'subject'        => fn() => $controller->subject(),
        'subject/create' => fn() => $controller->subjectCreate(),
        'enroll'         => fn() => $controller->enroll(),
        'assign'         => fn() => $controller->assign(),
    ],
    'POST' => [
        'student/store'  => fn() => $controller->studentStore(),
        'teacher/store'  => fn() => $controller->teacherStore(),
        'course/store'   => fn() => $controller->courseStore(),
        'subject/store'  => fn() => $controller->subjectStore(),
        'enroll/store'   => fn() => $controller->enrollStore(),
        'assign/store'   => fn() => $controller->assignStore(),
    ],
];

if (isset($routes[$method][$route])) {
    ($routes[$method][$route])();
} else {
    http_response_code(404);
    echo "<h1 style='font-family:sans-serif;padding:2rem'>404 — Ruta no trobada: <em>{$route}</em></h1>";
    echo "<p style='font-family:sans-serif;padding:0 2rem'><a href='index.php'>Torna a l'inici</a></p>";
}
