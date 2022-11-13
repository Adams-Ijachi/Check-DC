<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Lending */
class LendingResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'borrowed_at' => $this->borrowed_at,
            'due_at' => $this->due_at,
            'returned_at' => $this->returned_at,
            'points' => $this->points,



            "book" => BookResource::make($this->whenLoaded('book')),
            "user" => UserResource::make($this->whenLoaded('user')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
