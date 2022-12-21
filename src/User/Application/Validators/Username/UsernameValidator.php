<?php

namespace App\User\Application\Validators\Username;

use App\User\Application\BadWord\BadWordCollection;
use App\User\Application\BadWord\BadWordProvider;
use App\User\Application\Repositories\UserRepository;
use App\User\Infrastructure\Exceptions\UserNotFountException;
use Assert\Assertion;
use Assert\AssertionFailedException;

class UsernameValidator
{
    public const MAX_LENGTH = 64;
    public const MIN_LENGTH = 8;

    private BadWordProvider $badWordProvider;

    private UserRepository $userRepository;

    public function __construct(
        BadWordProvider $badWordProvider,
        UserRepository $userRepository
    ) {
        $this->badWordProvider = $badWordProvider;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws InvalidUsernameException
     */
    public function validate(string $username): bool
    {
        try {
            Assertion::maxLength($username, self::MAX_LENGTH);
            Assertion::minLength($username, self::MIN_LENGTH);
        } catch (AssertionFailedException) {
            throw new InvalidUsernameException('Invalid username length');
        }

        try {
            Assertion::regex($username, '/^[a-zA-Z\d]+$/');
        } catch (AssertionFailedException) {
            throw new InvalidUsernameException('Invalid username format. Only numbers and letters');
        }

        $badWordCollection = $this->badWordProvider->getBadWordCollection();

        if ($this->hasBadWordInUsername($username, $badWordCollection)) {
            throw new InvalidUsernameException('Username contains bad word');
        }

        if ($this->isUsernameExist($username)) {
            throw new InvalidUsernameException('Username already exist');
        }

        return true;
    }

    private function hasBadWordInUsername(string $username, BadWordCollection $badWordCollection): bool
    {
        foreach ($badWordCollection->toArray() as $badWord) {
            if (mb_stripos($username, $badWord)) {
                return true;
            }
        }
        return false;
    }

    private function isUsernameExist(string $username): bool
    {
        try {
            $this->userRepository->findByUsername($username);
            return true;
        } catch (UserNotFountException) {
            return false;
        }
    }
}
