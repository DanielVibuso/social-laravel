<?php

namespace App\Services;

use App\Models\Profile;
use App\Repositories\Contracts\ProfileRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProfileService
{
    public function __construct(protected ProfileRepositoryInterface $profileRepository)
    {
    }

    public function index(array $options): LengthAwarePaginator
    {
        return $this->profileRepository->index($options);
    }

    /**
     * @param array $newProfile
     *
     * @return bool
     */
    public function store(array $newProfile)
    {
        return $this->profileRepository->store($newProfile);
    }

    /**
     * @param string $id
     * @param array $newProfile
     *
     * @return bool
     */
    public function update(string $id, array $newProfileData)
    {
        return $this->profileRepository->update($id, $newProfileData);
    }

    /**
     * @param string $id
     *
     * @return Profile|null
     */
    public function getPermissionById(string $id): Profile|null
    {
        return $this->profileRepository->getById($id);
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function delete(string $id)
    {
        $this->profileRepository->delete($id);
    }

    /**
     * @param string $id
     *
     * @return Profile|null
     */
    public function getById(string $id): Profile|null
    {
        return $this->profileRepository->getById($id);
    }

    public function updatePermissions(string $id, array $permissions)
    {
        return $this->profileRepository->updatePermissions($id, $permissions);
    }
}
