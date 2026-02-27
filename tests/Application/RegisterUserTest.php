<?php
namespace Tests\Application;

use App\Application\RegisterUser\RegisterUserCommand;
use App\Application\RegisterUser\RegisterUserHandler;
use App\Domain\User\UserRepository;
use PHPUnit\Framework\TestCase;

final class RegisterUserTest extends TestCase
{
    public function test_user_can_be_registered(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())->method('save');

        $handler = new RegisterUserHandler($userRepository);

        $command = new RegisterUserCommand('user-1', 'Bob');

        $handler->handle($command);

        $this->assertTrue(true);
    }
}
