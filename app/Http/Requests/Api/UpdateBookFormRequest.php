<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'prologue' => 'required|string',
            'edition' => 'required|string',
            'description' => 'required|string',

            'status_id' => 'required|integer|exists:statuses,id',

            'access_level_ids' => 'required|array',
            'access_level_ids.*' => 'integer|exists:access_levels,id',

            'plan_ids' => 'required|array',
            'plan_ids.*' => 'integer|exists:plans,id',

            'author_ids' => 'required|array',
            'author_ids.*' => 'integer|exists:users,id',

            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',

            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'integer|exists:tags,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [

            'access_level_ids.*.exists' => 'Invalid Access Level provided at index :index',
            'plan_ids.*.exists' => 'Invalid plan id provided at index :index',
            'author_ids.*.exists' => 'Invalid author id provided at index :index',

            'category_ids.*.exists' => 'Invalid category id provided at index :index',
            'tag_ids.*.exists' => 'Invalid tag id provided at index :index',
        ];
    }
}
