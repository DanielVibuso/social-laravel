<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    /**
     * Display all data from the logged user.
     */
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection($this->userService->getAllUsers());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return new UserResource($this->userService->store($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new UserResource($this->userService->getUserById($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        return new UserResource($this->userService->update($id, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($this->userService->delete($id)) {
            return response()->json('deleted', 204);
        }
    }
}
