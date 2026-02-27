<?php
namespace App\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;

/**
 * DoctrineStudentRepository
 *
 * Guarda les dades a var/database.sqlite via Doctrine ORM.
 *
 * Per què NO fem clear() al save()?
 *  Alguns casos d'ús fan múltiples save() seguits (ex: AssignTeacherToSubject
 *  guarda l'Assignment i el Subject modificat). Si fem clear() al primer save(),
 *  el segon objecte queda "detached" i Doctrine no el pot persistir.
 *
 * Solució: usem createQueryBuilder al findAll() per fer sempre una
 * consulta fresca a la BD, ignorant la caché interna (Identity Map).
 */
final class DoctrineStudentRepository implements StudentRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function find(StudentId $id): ?Student
    {
        return $this->em->find(Student::class, $id->value());
    }

    public function save(Student $student): void
    {
        $this->em->persist($student);
        $this->em->flush();
    }

    public function findAll(): array
    {
        // createQueryBuilder força una SELECT real a la BD (no usa caché)
        return $this->em->createQueryBuilder()
            ->select('s')
            ->from(Student::class, 's')
            ->getQuery()
            ->getResult();
    }
}
