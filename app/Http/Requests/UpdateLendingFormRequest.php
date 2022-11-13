<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLendingFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id|unique:lendings,book_id,' . $this->route('lending')->id,
            'borrowed_at' => 'required|date|date_format:Y-m-d',
            'due_at' => 'required|date|after:borrowed_at|date_format:Y-m-d',
            'returned_at' => 'nullable|date|date_format:Y-m-d',
            'points' => 'required|integer',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
