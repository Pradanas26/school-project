<?php

use App\Domain\Book\Book;
use App\Domain\User\User;
use App\Domain\Book\BookId;
use App\Domain\User\UserId;
use PHPUnit\Framework\TestCase;
use App\Domain\Book\BookRepository;
use App\Domain\Loan\LoanRepository;
use App\Domain\User\UserRepository;
use App\Application\BorrowBook\BorrowBookHandler;
use App\Application\BorrowBook\BorrowBookCommand;




final class BorrowBookTest extends TestCase
{
    public function test_user_can_borrow_a_book(): void
    {
        $book = new Book(new BookId('book-1'), 'DDD in PHP');
        $user = new User(new UserId('user-1'), 'Alice');

        $bookRepository = $this->createMock(BookRepository::class);
        $userRepository = $this->createMock(UserRepository::class);
        $loanRepository = $this->createMock(LoanRepository::class);

        $bookRepository->method('find')->willReturn($book);
        $userRepository->method('find')->willReturn($user);

        $loanRepository->expects($this->once())->method('save');
        $bookRepository->expects($this->once())->method('save');
        $userRepository->expects($this->once())->method('save');

        $handler = new BorrowBookHandler(
            $bookRepository,
            $userRepository,
            $loanRepository
        );

        $command = new BorrowBookCommand('book-1', 'user-1');

        $handler->handle($command);

        $this->assertTrue(true); // no exception thrown
    }

    public function test_book_not_found_throws_exception(): void
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $userRepository = $this->createMock(UserRepository::class);
        $loanRepository = $this->createMock(LoanRepository::class);

        $bookRepository->method('find')->willReturn(null);

        $handler = new BorrowBookHandler(
            $bookRepository,
            $userRepository,
            $loanRepository
        );

        $this->expectException(RuntimeException::class);

        $handler->handle(new BorrowBookCommand('missing', 'user-1'));
    }
}

