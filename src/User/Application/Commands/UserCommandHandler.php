<?php

namespace App\User\Application\Commands;

use App\User\Application\Repositories\UserRepository;
use App\User\Application\Validators\Email\EmailValidator;
use App\User\Application\Validators\Email\InvalidEmailException;
use App\User\Application\Validators\Username\InvalidUsernameException;
use App\User\Application\Validators\Username\UsernameValidator;
use App\User\Infrastructure\Entities\UserEntity;
use App\User\Infrastructure\Exceptions\UserNotFountException;
use Psr\Log\LoggerInterface;

class UserCommandHandler
{
    private UserRepository $userRepository;

    private EmailValidator $emailValidator;

    private UsernameValidator $usernameValidator;

    private LoggerInterface $logger;

    public function __construct(
        UserRepository $userRepository,
        EmailValidator $emailValidator,
        UsernameValidator $usernameValidator,
        LoggerInterface $logger
    ) {
        $this->userRepository = $userRepository;
        $this->emailValidator = $emailValidator;
        $this->usernameValidator = $usernameValidator;
        $this->logger = $logger;
    }

    /**
     * @throws InvalidEmailException
     * @throws InvalidUsernameException
     */
    public function handleCreateUserCommand(CreateUserCommand $command): UserEntity
    {
        $this->emailValidator->validate($command->getEmail());
        $this->usernameValidator->validate($command->getUsername());

        $user = new UserEntity();
        $user->email = $command->getEmail();
        $user->username = $command->getUsername();
        $user->notes = $command->getNotes();

        $this->userRepository->create($user);

        $this->logger->info('User was created');

        return $user;
    }

    /**
     * @throws InvalidEmailException
     * @throws InvalidUsernameException
     * @throws UserNotFountException
     */
    public function handleUpdateUserCommand(UpdateUserCommand $command): UserEntity
    {
        $user = $this->userRepository->findById($command->getId());

        $email = $command->getEmail();
        $username = $command->getUsername();
        $notes = $command->getNotes();

        if ($email) {
            $this->emailValidator->validate($email);
            $user->email = $email;
        }

        if ($username) {
            $this->usernameValidator->validate($username);
            $user->email = $username;
        }

        if ($notes != $user->notes) {
            $user->notes = $notes;
        }

        $this->userRepository->create($user);

        $this->logger->info('User was updated');

        return $user;
    }

    /**
     * @throws UserNotFountException
     */
    public function handleDeleteUserCommand(UpdateUserCommand $command): bool
    {
        $user = $this->userRepository->findById($command->getId());

        $this->logger->info('User was deleted');

        return $this->userRepository->delete($user);
    }
}
