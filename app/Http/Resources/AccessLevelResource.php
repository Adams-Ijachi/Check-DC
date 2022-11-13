<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\AccessLevel */
class AccessLevelResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'borrowing_points' => $this->borrowing_points,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
