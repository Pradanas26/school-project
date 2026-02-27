<?php

namespace App\Domain\User;


use App\Domain\User\UserId;
use App\Domain\Loan\Loan;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;


    #[ORM\Column(type: 'string')]
    private string $name;


    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Loan::class,
        cascade: ['persist'],
        orphanRemoval: true
    )]
    private iterable $loans;
    public function __construct(UserId $id, string $name)
    {
        $this->id = $id->value();
        $this->name = $name;
        $this->loans = [];
    }
    public function id(): UserId
    {
        return new UserId($this->id);   
    }

    public function addLoan(Loan $loan): void
    {
        $this->loans[] = $loan;
    }
    public function name(): string
    {
        return $this->name;
    }
    function loans(): iterable
    {
        return $this->loans;
    }
}
