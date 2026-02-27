<?php
namespace Tests\Domain\User;

use App\Domain\Book\Book;
use App\Domain\Loan\Loan;
use App\Domain\User\User;
use App\Domain\Book\BookId;
use App\Domain\Loan\LoanId;
use App\Domain\User\UserId;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
   public function test_user_can_have_loans(): void
    {
        $user = new User(
            UserId::generate(),
            'Alice'
        );

        $book = new Book(
            BookId::generate(),
            'Clean Architecture'
        );

        $loan = new Loan(
            LoanId::generate(),
            $book,
            $user,
            new \DateTimeImmutable()
        );

        $user->addLoan($loan);

        $this->assertCount(1, $user->loans());
    }
}