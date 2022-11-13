<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Book */
class BookResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'edition' => $this->edition,
            'description' => $this->description,
            'prologue' => $this->prologue,

            'status' => $this->whenLoaded('status', function () {
                return $this->status->name;
            }),

            'plans' => $this->whenLoaded('plans', function () {
                return $this->plans->pluck('name');
            }),

            'access_levels' => $this->whenLoaded('accessLevels', function () {
                return $this->accessLevels->pluck('name');
            }),

            "authors" => $this->whenLoaded('authors', function () {
                return $this->authors->pluck('fullname');
            }),

            "tags" => $this->whenLoaded('tags', function () {
                return $this->tags->pluck('name');
            }),

            "categories" => $this->whenLoaded('categories', function () {
                return $this->categories->pluck('name');
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
