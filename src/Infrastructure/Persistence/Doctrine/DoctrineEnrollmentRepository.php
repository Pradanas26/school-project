<?php
namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepository;

final class DoctrineEnrollmentRepository implements EnrollmentRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Enrollment $enrollment): void
    {
        $this->em->persist($enrollment);
        $this->em->flush();
    }

    public function findAll(): array
    {
        return $this->em->createQueryBuilder()
            ->select('e')
            ->from(Enrollment::class, 'e')
            ->getQuery()
            ->getResult();
    }
}
