<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'age' => 'required|integer' ,
            'address' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'status_id' => 'required|integer|exists:statuses,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
