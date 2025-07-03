<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserAuthRegisterRequest extends FormRequest
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
            'name' => ['min:2', 'max:50', 'string', 'required'],
            'email' => ['min:10', 'max:100', 'email', 'required'],
            'password' => ['min:10', 'max:100', 'required', 'confirmed:password_confirm'],
            'password_confirm' => ['min:10', 'max:100', 'required']
        ];
    }
}
