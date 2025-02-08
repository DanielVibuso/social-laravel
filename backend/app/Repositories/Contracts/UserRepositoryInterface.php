<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function getAllUsers();

    public function store(array $newUserData, Model $model = null): User;

    public function update(string $id, array $newUserData): bool;

    public function delete(string $id): bool;

    public function getUserById(string $id): User;

    public function emailAlreadyExists($email, $id): bool;
}
