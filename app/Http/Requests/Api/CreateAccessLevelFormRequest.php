<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccessLevelFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => "required|string|unique:access_levels,name",
            "min_age" => "required|integer",
            "max_age" => "nullable|integer",
            "borrowing_points" => "nullable|integer"
        ];
    }

    public function authorize()
    {
        return true;
    }
}
