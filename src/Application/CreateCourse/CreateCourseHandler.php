<?php

namespace App\Application\CreateCourse;

use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;

/**
 * HANDLER: CreateCourseHandler
 * Cas d'ús: crear un nou curs acadèmic.
 */
final class CreateCourseHandler
{
    public function __construct(
        private CourseRepository $courseRepository
    ) {}

    public function handle(CreateCourseCommand $command): void
    {
        // Creem l'entitat Course. Si l'any és invàlid (< 2000 o > 2100),
        // el constructor de Course llençarà una InvalidArgumentException.
        $course = new Course(
            new CourseId($command->courseId),
            $command->name,
            $command->year
        );

        $this->courseRepository->save($course);
    }
}
