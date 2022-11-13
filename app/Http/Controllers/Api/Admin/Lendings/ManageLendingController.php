<?php

namespace App\Http\Controllers\Api\Admin\Lendings;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLendingFormRequest;
use App\Http\Requests\UpdateLendingFormRequest;
use App\Http\Resources\LendingResource;
use App\Models\Lending;

class ManageLendingController extends Controller
{
    public function index()
    {
        return LendingResource::collection(Lending::with('book','user')
            ->latest()->paginate());
    }

    public function show(Lending $lending)
    {
        return LendingResource::make($lending->load('book','user'));
    }



    public function store(CreateLendingFormRequest $request)
    {
        $lending = Lending::create($request->validated());

        return LendingResource::make($lending->load('book','user'));
    }

    public function update(UpdateLendingFormRequest $request, Lending $lending)
    {
        $lending->update($request->validated());

        return LendingResource::make($lending->load('book','user'));
    }


    public function destroy(Lending $lending)
    {
        $lending->delete();
        return response()->json(['message' => 'Lending deleted successfully']);
    }
}
