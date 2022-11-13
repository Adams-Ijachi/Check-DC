<?php

namespace App\Http\Controllers\Api\Admin\AccessLevel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateAccessLevelFormRequest;
use App\Http\Requests\Api\UpdateAccessLevelFormRequest;
use App\Http\Resources\AccessLevelResource;
use App\Models\AccessLevel;

class ManageAccessLevelController extends Controller
{
    public function index()
    {
        return AccessLevelResource::collection(AccessLevel::all());
    }

    public function show(AccessLevel $accessLevel)
    {
        return AccessLevelResource::make($accessLevel);
    }

    public function store(CreateAccessLevelFormRequest $request)
    {
        $accessLevel = AccessLevel::create($request->validated());
        return AccessLevelResource::make($accessLevel);
    }


    public function update(UpdateAccessLevelFormRequest $request,AccessLevel $accessLevel)
    {
        $accessLevel->update($request->validated());
        return AccessLevelResource::make($accessLevel);
    }

    public function destroy(AccessLevel $accessLevel)
    {
        $accessLevel->delete();
        return response()->json(['message' => "Deleted Successfully"], 204);
    }
}
