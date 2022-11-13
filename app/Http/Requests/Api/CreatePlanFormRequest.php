<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlanFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:plans,name',
            'duration' => 'required|integer',
            'status_id' => 'required|integer|exists:statuses,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
