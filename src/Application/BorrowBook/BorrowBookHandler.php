<?php
namespace App\Application\BorrowBook;


use App\Domain\Loan\Loan;
use App\Domain\Book\BookId;
use App\Domain\Loan\LoanId;
use App\Domain\User\UserId;
use App\Domain\Book\BookRepository;
use App\Domain\Loan\LoanRepository;
use App\Domain\User\UserRepository;
use App\Application\BorrowBook\BorrowBookCommand;


final class BorrowBookHandler
{
    public function __construct(
        private BookRepository $bookRepository,
        private UserRepository $userRepository,
        private LoanRepository $loanRepository
    ) {}


    public function handle(BorrowBookCommand $command): void
    {
        $book = $this->bookRepository->find(new BookId($command->bookId));
        if (!$book) {
            throw new \RuntimeException('Book not found');
        }


        $user = $this->userRepository->find(new UserId($command->userId));
        if (!$user) {
            throw new \RuntimeException('User not found');
        }


        // Regla de negoci delegada al domini
        $book->borrow();
        $loan = new Loan(
            new LoanId(uniqid('loan_', true)),
            $book,
            $user,
            new \DateTimeImmutable()
        );


        $user->addLoan($loan);


        // Persistència (Unit of Work)
        $this->loanRepository->save($loan);
        $this->bookRepository->save($book);
        $this->userRepository->save($user);
    }
}
