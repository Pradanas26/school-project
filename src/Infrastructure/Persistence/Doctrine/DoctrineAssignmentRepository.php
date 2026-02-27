<?php
namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Assignment\Assignment;
use App\Domain\Assignment\AssignmentRepository;

final class DoctrineAssignmentRepository implements AssignmentRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Assignment $assignment): void
    {
        $this->em->persist($assignment);
        $this->em->flush();
    }

    public function findAll(): array
    {
        return $this->em->createQueryBuilder()
            ->select('a')
            ->from(Assignment::class, 'a')
            ->getQuery()
            ->getResult();
    }
}
