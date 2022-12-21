<?php

namespace App\User\Application\Commands;

class UpdateUserCommand
{
    private int $id;

    private ?string $username;

    private ?string $email;

    private ?string $notes;

    public function __construct(
        int $id,
        ?string $username,
        ?string $email,
        ?string $notes
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->notes = $notes;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
