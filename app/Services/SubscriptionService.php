<?php


namespace App\Services;


use App\Exceptions\ActiveSubscriptionExists;
use App\Models\Status;
use App\Models\Subscription;

class SubscriptionService
{


    /**
     * @throws ActiveSubscriptionExists
     */
    public function createSubscription(array $data): Subscription
    {
        $this->checkActiveSubscriptionExists();
        return Subscription::create([
            'plan_id' => $data['plan_id'],
            'user_id' => \Auth::id(),
        ]);
    }


    // user has active subscription

    /**
     * @throws ActiveSubscriptionExists
     */
    private function checkActiveSubscriptionExists()
    {
        if((bool)\Auth::user()->activeSubscriptions()->count()){
            throw new ActiveSubscriptionExists('User already has active subscription');
        }
    }



}
