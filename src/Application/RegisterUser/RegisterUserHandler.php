<?php
namespace App\Application\RegisterUser;

use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserRepository;
use App\Application\RegisterUser\RegisterUserCommand;

class RegisterUserHandler{
    public function __construct(
        public readonly UserRepository $userRepository
    ){}
    public function handle(RegisterUserCommand $command): void
    {
        $user = new User(
            new UserId($command->userId),
            $command->name
        );

        $this->userRepository->save($user);
    }
}