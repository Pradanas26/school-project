<?php


namespace App\Domain\Loan;


interface LoanRepository
{
    public function save(Loan $loan): void;
}
