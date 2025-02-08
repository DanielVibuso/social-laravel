<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @param array $userData
     *
     * @return User
     */
    public function store(array $userData): User
    {
        return $this->userRepository->store($userData);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllUsers(): LengthAwarePaginator
    {
        return $this->userRepository->getAllUsers();
    }

    /**
     * @param string $id
     * @param array $userNewData
     *
     * @return User
     */
    public function update(string $id, array $userNewData): User
    {
        $this->userRepository->update($id, $userNewData);

        return $this->userRepository->getUserById($id);
    }

    /**
     * @param string $id
     *
     * @return User|null
     */
    public function getUserById(string $id): User|null
    {
        return $this->userRepository->getUserById($id);
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function delete(string $id)
    {
        return $this->userRepository->delete($id);
    }
}
