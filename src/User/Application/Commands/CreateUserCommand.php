<?php

namespace App\User\Application\Commands;

class CreateUserCommand
{
    private string $username;

    private string $email;

    private ?string $notes;

    public function __construct(
        string $username,
        string $email,
        ?string $notes
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->notes = $notes;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}