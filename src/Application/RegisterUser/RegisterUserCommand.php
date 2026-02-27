<?php
namespace App\Application\RegisterUser;

final class RegisterUserCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly string $name
    ) {}
}
