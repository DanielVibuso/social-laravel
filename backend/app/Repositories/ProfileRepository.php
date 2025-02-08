<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Repositories\Contracts\ProfileRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function __construct(protected Profile $profile)
    {
    }

    public function index(array $options): LengthAwarePaginator
    {
        return $this->profile->userRightAccess()->when(isset($options['sort']), function ($query) use ($options) {
            $query->orderBy($options['sort'], $options['order'] ?? 'asc');
        })->paginate($options['perPage'] ?? 15);
    }

    /**
     * @param string $id
     *
     * @return Profile
     */
    public function getById(string $id): ?Profile
    {
        return $this->profile->find($id);
    }

    public function getByName(string $name): ?Profile
    {
        return $this->profile->where('name', $name)->first();
    }

    /**
     * @param array $newProfile
     *
     * @return Profile
     */
    public function store(array $newProfile): Profile
    {
        return $this->profile->create($newProfile);
    }

    /**
     * @param string $id
     * @param array $newProfileData
     *
     * @return Profile
     */
    public function update(string $id, array $newProfileData): Profile
    {
        $profile = $this->getById($id);
        $profile->update($newProfileData);

        return $profile;
    }

    /**
     * @param string $id do profile
     * @param array $permissions ids
     *
     * @return Profile
     */
    public function updatePermissions(string $id, array $permissions): Profile
    {
        $profile = $this->getById($id);
        $profile->permissions()->sync($permissions);

        return $profile;
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function delete(string $id): void
    {
        $this->getById($id)->delete();
    }
}
