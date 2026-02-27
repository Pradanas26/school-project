<?php
namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;

final class DoctrineTeacherRepository implements TeacherRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function find(TeacherId $id): ?Teacher
    {
        return $this->em->find(Teacher::class, $id->value());
    }

    public function save(Teacher $teacher): void
    {
        $this->em->persist($teacher);
        $this->em->flush();
    }

    public function findAll(): array
    {
        return $this->em->createQueryBuilder()
            ->select('t')
            ->from(Teacher::class, 't')
            ->getQuery()
            ->getResult();
    }
}
