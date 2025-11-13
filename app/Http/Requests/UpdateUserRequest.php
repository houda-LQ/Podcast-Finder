<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
       $userId = $this->route('id'); 

    return [
        'name' => 'sometimes|required|string|max:50',
        'email' => 'sometimes|required|string|email|unique:users,email,' . $userId,
        'password' => 'sometimes|required|string|min:8|confirmed',
         "role" => "required|in:utilisateur,Animateur",

    ];
    }
}
