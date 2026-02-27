<?php

namespace App\Domain\Loan;

use App\Domain\Book\BookId;
use App\Domain\User\UserId;
use App\Domain\User\User;
use App\Domain\Book\Book;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity]
#[ORM\Table(name: 'loans')]
class Loan
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;


    #[ORM\ManyToOne(targetEntity: Book::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Book $book;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'loans')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;


    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $borrowedAt;

    public function __construct(
        LoanId $id,
        Book $book,
        User $user,
        \DateTimeImmutable $borrowedAt
    ) {
        $this->id = $id->value();
        $this->book = $book;
        $this->user = $user;
        $this->borrowedAt = $borrowedAt;
    }
    public static function borrow(Book $book, User $user): self
    {
        // regla de negoci
        $book->borrow();

        return new self(
            LoanId::generate(),
            $book,
            $user,
            new \DateTimeImmutable()
        );
    }
    public function isActive(): bool
    {
        return $this->book->isAvailable();
    }   
    public function bookId(): BookId
    {
        return $this->book->id();
    }
    public function userId(): UserId
    {
        return $this->user->id();
    }
    
}
