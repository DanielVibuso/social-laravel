<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * @param Permission $permission
     */
    public function __construct(protected Permission $permission)
    {
    }

    /**
     * @return LengthAwarePaginator retorna as permissões de forma paginada
     */
    public function index(): LengthAwarePaginator
    {
        return $this->permission->paginate();
    }

    /**
     * @param array $newPermission
     *
     * @return Permission
     */
    public function store(array $newPermission): Permission
    {
        return $this->permission->create($newPermission);
    }

    /**
     * @param string $id
     * @param array $newPermissionData
     *
     * @return Permission
     */
    public function update(string $id, array $newPermissionData): Permission
    {
        $permission = $this->getPermissionById($id);
        $permission->update($newPermissionData);

        return $permission;
    }

    /**
     * @param string $id
     *
     * @return Permission
     */
    public function getPermissionById(string $id): Permission
    {
        return $this->permission->where('id', $id)->firstOrFail();
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function delete(string $id): void
    {
        $this->getPermissionById($id)->delete();
    }

    public function permissionsAssociatedByProfileId(string $profileId)
    {
        return $this->permission->select(
            'permissions.id',
            'permissions.name',
            'permissions.description',
            DB::raw("CASE WHEN permission_profile.profile_id IS NOT NULL THEN '1' ELSE '0' END AS is_associated")
        )
        ->leftJoin('permission_profile', function ($join) use ($profileId) {
            $join->on('permission_profile.permission_id', '=', 'permissions.id')
                 ->where('permission_profile.profile_id', '=', $profileId);
        })
        ->get();
    }
}
