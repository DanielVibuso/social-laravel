<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\StoreRequest;
use App\Http\Requests\Permission\UpdateRequest;
use App\Http\Resources\PermissionResource;
use App\Services\PermissionService;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PermissionResource::collection($this->permissionService->getAllPermissions());
    }

    public function profileBinded(string $id)
    {
        return PermissionResource::collection($this->permissionService->permissionsAssociatedByProfileId($id));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return new PermissionResource($this->permissionService->store($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new PermissionResource($this->permissionService->getPermissionById($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        return new PermissionResource($this->permissionService->update($id, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->permissionService->delete($id);

        return response()->json(['data' => ['message' => 'deleted']], 204);
    }
}
