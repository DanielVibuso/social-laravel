<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param User $user
     */
    public function __construct(protected User $user)
    {
    }

    public function getAllUsers()
    {
        return $this->user->whereHas('profile', function ($query) {
            $query->whereNotIn('name', ['Admin']);
        })->paginate();
    }

    /**
     * @param array $newUserData
     *
     * @return bool
     */
    public function store(array $newUserData, Model $model = null): User
    {
        return !is_null($model) ? $model->user()->create($newUserData) : $this->user->create($newUserData);
    }

    /**
     * @param int $id
     * @param array $newUserData
     *
     * @return bool
     */
    public function update(string $id, array $newUserData): bool
    {
        return $this->user->findOrFail($id)->update($newUserData);
    }

    /**
     * @param string $id
     *
     * @return User
     */
    public function getUserById(string $id): User
    {
        return $this->user->where('id', $id)->firstOrFail();
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->getUserById($id)->delete();
    }

    public function emailAlreadyExists($email, $id): bool
    {
        return (bool) $this->user->where('email', $email)->where('id', '<>', $id)->first();
    }
}
