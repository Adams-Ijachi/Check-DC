<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccessLevelFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            "name" => "required|string|unique:access_levels,name," . $this->route('access_level')->id,
            "min_age" => "required|integer",
            "max_age" => "nullable|integer",
            "borrowing_points" => "nullable|integer"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
