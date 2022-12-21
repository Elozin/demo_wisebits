<?php

namespace App\User\Application\Validators\Email;

use App\User\Application\BlockedEmailDomains\BlockedEmailDomainCollection;
use App\User\Application\BlockedEmailDomains\BlockedEmailDomainProvider;
use App\User\Application\Repositories\UserRepository;
use App\User\Infrastructure\Exceptions\UserNotFountException;
use Assert\Assertion;
use Assert\AssertionFailedException;

class EmailValidator
{
    public const MAX_LENGTH = 256;

    private BlockedEmailDomainProvider $blockedEmailDomainProvider;

    private UserRepository $userRepository;

    public function __construct(
        BlockedEmailDomainProvider $blockedEmailDomainProvider,
        UserRepository $userRepository
    ) {
        $this->blockedEmailDomainProvider = $blockedEmailDomainProvider;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws InvalidEmailException
     */
    public function validate(string $email): bool
    {
        try {
            Assertion::email($email);
        } catch (AssertionFailedException) {
            throw new InvalidEmailException('Invalid email format');
        }

        try {
            Assertion::maxLength($email, self::MAX_LENGTH);
        } catch (AssertionFailedException) {
            throw new InvalidEmailException('Invalid username length');
        }

        $blockedEmailDomainsCollection = $this->blockedEmailDomainProvider->getBlockedEmailDomainCollection();

        if ($this->isEmailDomainBlocked($email, $blockedEmailDomainsCollection)) {
            throw new InvalidEmailException('Email domain is blocked');
        }

        if ($this->isEmailExist($email)) {
            throw new InvalidEmailException('Email already exist');
        }

        return true;
    }

    private function isEmailDomainBlocked(
        string $email,
        BlockedEmailDomainCollection $blockedEmailDomainCollection
    ): bool {
        $parsedEmail = explode('@', $email);
        $emailDomain = array_pop($parsedEmail);

        foreach ($blockedEmailDomainCollection->toArray() as $blockedDomain) {
            if (mb_strtolower($emailDomain) == strtolower($blockedDomain)) {
                return true;
            }
        }

        return false;
    }

    private function isEmailExist(string $email): bool
    {
        try {
            if ($this->userRepository->findByEmail($email)) {
                return true;
            }
            return false;
        } catch (UserNotFountException) {
            return false;
        }
    }
}
