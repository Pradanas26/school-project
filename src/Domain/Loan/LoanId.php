<?php

namespace App\Domain\Loan;


final class LoanId
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('LoanId cannot be empty');
        }
    }

    public static function generate(): self
    {
        return new self(
            uniqid('loan_', true)
        );
    }

    public function value(): string
    {
        return $this->value;
    }


    public function __toString(): string
    {
        return $this->value;
    }
}
