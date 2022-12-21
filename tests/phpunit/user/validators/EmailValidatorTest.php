<?php

namespace phpunit\user\validators;

use App\User\Application\BlockedEmailDomains\BlockedEmailDomainCollection;
use App\User\Application\BlockedEmailDomains\BlockedEmailDomainProvider;
use App\User\Application\Repositories\UserRepository;
use App\User\Application\Validators\Email\EmailValidator;
use App\User\Application\Validators\Email\InvalidEmailException;
use App\User\Infrastructure\Entities\UserEntity;
use App\User\Infrastructure\Exceptions\UserNotFountException;
use PHPUnit\Framework\TestCase;

class EmailValidatorTest extends TestCase
{
    private EmailValidator $emailValidator;

    private BlockedEmailDomainProvider $blockedEmailDomainProvider;

    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->blockedEmailDomainProvider = $this->createMock(BlockedEmailDomainProvider::class);

        $this->emailValidator = new EmailValidator(
            $this->blockedEmailDomainProvider,
            $this->userRepository
        );
    }

    /**
     * @dataProvider providerGetDataWithInvalidDomain
     */
    public function testValidateWithInvalidDomain(
        string $email
    ) {
        $this->blockedEmailDomainProvider
            ->method('getBlockedEmailDomainCollection')
            ->willReturn($this->getTestDomainCollection());

        $this->expectException(InvalidEmailException::class);

        $this->emailValidator->validate($email);
    }

    /**
     * @dataProvider providerGetValidEmails
     */
    public function testValidateWithEmailAlreadyExist(
        string $email
    ) {
        $this->blockedEmailDomainProvider
            ->method('getBlockedEmailDomainCollection')
            ->willReturn(
                $this->getTestDomainCollection()
            );

        $this->userRepository
            ->method('findByEmail')
            ->willReturn(
                $this->getTestUserEntity()
            );

        $this->expectException(InvalidEmailException::class);

        $this->emailValidator->validate($email);
    }

    /**
     * @dataProvider providerGetValidEmails
     */
    public function testValidateValidEmail(
        string $email
    ) {
        $this->blockedEmailDomainProvider
            ->method('getBlockedEmailDomainCollection')
            ->willReturn(
                $this->getTestDomainCollection()
            );

        $this->userRepository
            ->method('findByEmail')
            ->willThrowException(new UserNotFountException());

        $result = $this->emailValidator->validate($email);

        TestCase::assertTrue($result);
    }

    /**
     * @dataProvider providerGetInvalidEmails
     */
    public function testValidateInvalidEmail(
        string $email,
    ) {
        $this->expectException(InvalidEmailException::class);

        $this->emailValidator->validate($email);
    }

    public function providerGetDataWithInvalidDomain(): array
    {
        return [
            ['test@blocked.com'],
            ['test@bLoCked.com'],
        ];
    }

    public function providerGetInvalidEmails(): array
    {
        return [
            ['test'],
            ['@domain.com'],
        ];
    }

    public function providerGetValidEmails(): array
    {
        return [
            ['goodemail@good.com']
        ];
    }

    private function getTestDomainCollection(): BlockedEmailDomainCollection
    {
        return new BlockedEmailDomainCollection(
            [
                'blocked.com'
            ]
        );
    }

    private function getTestUserEntity(): UserEntity
    {
        $user = new UserEntity();

        return $user;
    }
}
