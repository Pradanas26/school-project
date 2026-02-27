<?php
namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;

final class DoctrineCourseRepository implements CourseRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function find(CourseId $id): ?Course
    {
        return $this->em->find(Course::class, $id->value());
    }

    public function save(Course $course): void
    {
        $this->em->persist($course);
        $this->em->flush();
    }

    public function findAll(): array
    {
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from(Course::class, 'c')
            ->getQuery()
            ->getResult();
    }
}
