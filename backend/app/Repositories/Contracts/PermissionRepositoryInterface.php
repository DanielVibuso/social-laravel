<?php

namespace App\Repositories\Contracts;

use App\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PermissionRepositoryInterface
{
    public function index(): LengthAwarePaginator;

    public function store(array $rule): Permission;

    public function update(string $id, array $newPermissionData): Permission;

    public function delete(string $id): void;

    public function getPermissionById(string $id): Permission;

    public function permissionsAssociatedByProfileId(string $profileId);
}
