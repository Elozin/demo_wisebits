<?php

namespace App\User\Application\Repositories;

use App\User\Infrastructure\Entities\UserEntity;
use App\User\Infrastructure\Exceptions\UserNotFountException;

interface UserRepository
{
    public function create(UserEntity $userEntity);

    /**
     * @throws UserNotFountException
     */
    public function update(UserEntity $userEntity);

    /**
     * @throws UserNotFountException
     */
    public function delete(UserEntity $userEntity): bool;

    /**
     * @throws UserNotFountException
     */
    public function findById(int $id): UserEntity;

    /**
     * @throws UserNotFountException
     */
    public function findByUsername(): UserEntity;

    /**
     * @throws UserNotFountException
     */
    public function findByEmail(): UserEntity;
}