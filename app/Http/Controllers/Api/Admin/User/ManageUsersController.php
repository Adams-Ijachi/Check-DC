<?php

namespace App\Http\Controllers\Api\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateUserFormRequest;
use App\Http\Requests\Api\UpdateUserFormRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class ManageUsersController extends Controller
{
    public function index()
    {
        return UserResource::collection(
            User::with('status','roles')->latest("created_at")->paginate()
        );
    }

    public function show(User $user)
    {
        return UserResource::make($user->load('status','roles'));
    }

    public function store(CreateUserFormRequest $request)
    {
        $user = app(UserService::class)->createUser($request->validated());
        return UserResource::make($user);
    }

    public function update(UpdateUserFormRequest $request, User $user)
    {
        $user = app(UserService::class)->updateUser($request->validated(), $user);
        return UserResource::make($user);
    }


    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

}
