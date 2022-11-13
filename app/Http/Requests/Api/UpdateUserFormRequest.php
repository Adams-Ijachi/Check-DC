<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->route('user')->id,
            'password' => 'required|string|min:6',
            'age' => 'required|integer' ,
            'address' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $this->route('user')->id,
            'status_id' => 'required|integer|exists:statuses,id',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
