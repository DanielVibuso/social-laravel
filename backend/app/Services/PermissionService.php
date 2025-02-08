<?php

namespace App\Services;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionService
{
    public function __construct(protected PermissionRepositoryInterface $permissionRepository)
    {
    }

    /**
     * @param array $newPermission
     *
     * @return bool
     */
    public function store(array $newPermissionData)
    {
        return $this->permissionRepository->store($newPermissionData);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllPermissions(): LengthAwarePaginator
    {
        return $this->permissionRepository->index();
    }

    /**
     * @return Collection
     */
    public function permissionsAssociatedByProfileId(string $id): Collection
    {
        return $this->permissionRepository->permissionsAssociatedByProfileId($id);
    }

    /**
     * @param string $id
     * @param array $newPermission
     *
     * @return bool
     */
    public function update(string $id, array $newPermissionData)
    {
        return $this->permissionRepository->update($id, $newPermissionData);
    }

    /**
     * @param string $id
     *
     * @return Permission|null
     */
    public function getPermissionById(string $id): Permission|null
    {
        return $this->permissionRepository->getPermissionById($id);
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function delete(string $id)
    {
        $this->permissionRepository->delete($id);
    }
}
