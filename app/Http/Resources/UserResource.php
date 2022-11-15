<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'age' => $this->age,
            'address' => $this->address,
            'username' => $this->username,
            'email' => $this->email,

            'status' => $this->whenLoaded('status', function () {
                return $this->status->name;
            }),
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->pluck('name');
            }),



        ];
    }
}
