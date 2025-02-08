<?php

namespace App\Repositories\Contracts;

use App\Models\Profile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProfileRepositoryInterface
{
    public function index(array $options): LengthAwarePaginator;

    public function getById(string $id): ?Profile;

    public function getByName(string $name): ?Profile;

    public function store(array $rule): Profile;

    public function update(string $id, array $newPermissionData): Profile;

    public function updatePermissions(string $id, array $permissions): Profile;

    public function delete(string $id): void;
}
