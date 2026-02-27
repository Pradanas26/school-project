<?php
namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;

final class DoctrineSubjectRepository implements SubjectRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function find(SubjectId $id): ?Subject
    {
        return $this->em->find(Subject::class, $id->value());
    }

    public function save(Subject $subject): void
    {
        $this->em->persist($subject);
        $this->em->flush();
    }

    public function findAll(): array
    {
        return $this->em->createQueryBuilder()
            ->select('s')
            ->from(Subject::class, 's')
            ->getQuery()
            ->getResult();
    }
}
