<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|unique:plans,name,' . $this->route('plan')->id,
            'duration' => 'required|integer',
            'status_id' => 'required|integer|exists:statuses,id',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
