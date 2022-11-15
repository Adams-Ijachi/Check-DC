<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'age' => 'required|integer' ,
            'address' => 'required|string',
            'username' => 'required|string|unique:users,username',

        ];
    }

    public function authorize()
    {
        return true;
    }
}
