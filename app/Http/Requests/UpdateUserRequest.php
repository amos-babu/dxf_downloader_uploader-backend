<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username' . Auth::id(),
            'bio' => 'nullable|string|max:255',
            'profile_pic_path' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
        ];
    }
}
