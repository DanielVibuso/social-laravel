<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\StoreRequest;
use App\Http\Requests\Profile\SyncPermissionsRequest;
use App\Http\Requests\Profile\UpdatePermissionsRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profileService)
    {
    }

    public function index(Request $request)
    {
        return ProfileResource::collection($this->profileService->index($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return new ProfileResource($this->profileService->store($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new ProfileResource($this->profileService->getById($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        return new ProfileResource($this->profileService->update($id, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->profileService->delete($id);

        return response()->json(['data' => ['message' => 'deleted']], 204);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePermissions(UpdatePermissionsRequest $request, string $id)
    {
        return new ProfileResource($this->profileService->updatePermissions($id, $request->permissions));
    }
}
