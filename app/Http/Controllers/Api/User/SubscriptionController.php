<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\ActiveSubscriptionExists;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Plan;
use App\Models\Status;
use App\Models\Subscription;
use App\Services\SubscriptionService;

class SubscriptionController extends Controller
{
    public function index()
    {
        return SubscriptionResource::collection(
            Subscription::with('status','plan')
                ->where('user_id', \Auth::id())
                ->latest("created_at")->paginate()
        );

    }

    /**
     */
    public function subscribe(Plan $plan)
    {
        try {
            // if plan is active
            if(!$plan->isActive()){
                return response()->json(['message' => 'Plan is not active'], 400);
            }



            app(SubscriptionService::class)->createSubscription([
                'plan_id' => $plan->id,
            ]);



            return response()->json(['message' => 'User subscribed successfully']);
        }catch (ActiveSubscriptionExists $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
