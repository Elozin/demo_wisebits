<?php

namespace App\User\Infrastructure\Entities;

class UserEntity
{
    public ?int $id;
    public ?string $username;
    public ?string $email;
    public ?string $createdAt;
    public ?string $notes;
    public ?string $deletedAt;
}