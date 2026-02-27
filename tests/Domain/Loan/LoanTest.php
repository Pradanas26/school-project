<?php
namespace Tests\Domain\Loan;

use PHPUnit\Framework\TestCase;
use App\Domain\Book\Book;
use App\Domain\Book\BookId;
use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\Loan\Loan;

final class LoanTest extends TestCase
{
    public function test_book_can_be_borrowed(): void
    {
        
        $user = new User(UserId::generate(), 'Alice');
        $book = new Book(BookId::generate(), 'DDD in PHP');
        

        $loan = Loan::borrow($book, $user);

       $this->assertFalse($book->isAvailable());
       $this->assertEquals($book->id(), $loan->bookId());
       $this->assertEquals($user->id(), $loan->userId());
    }
}
