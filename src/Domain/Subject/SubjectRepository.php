<?php

namespace App\Domain\Subject;

/**
 * PORT: SubjectRepository
 * Contracte per accedir i persistir assignatures.
 */
interface SubjectRepository
{
    public function find(SubjectId $id): ?Subject;
    public function save(Subject $subject): void;

    /** @return Subject[] */
    public function findAll(): array;
}
