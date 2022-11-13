<?php

namespace App\Http\Controllers\Api\Admin\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreatePlanFormRequest;
use App\Http\Requests\Api\UpdatePlanFormRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use GuzzleHttp\Promise\Create;

class ManagePlanController extends Controller
{
    public function index()
    {
       return PlanResource::collection(Plan::with('status')->get());
    }

    public function show(Plan $plan)
    {
        return PlanResource::make($plan->load('status'));
    }

    public function store(CreatePlanFormRequest $request)
    {
        $plan = Plan::create($request->validated());
        return PlanResource::make($plan);
    }

    public function update(UpdatePlanFormRequest $request,Plan $plan)
    {
        $plan->update($request->validated());
        return PlanResource::make($plan);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->json(['message' => 'Plan deleted successfully']);
    }



}
