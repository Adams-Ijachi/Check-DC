<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Subscription */
class SubscriptionResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'plan' => $this->whenLoaded('plan', function () {
                return $this->plan->only('id', 'name','duration');
            }),

            'status' => $this->whenLoaded('status', function () {
                return $this->status->name;
            }),
        ];
    }
}
