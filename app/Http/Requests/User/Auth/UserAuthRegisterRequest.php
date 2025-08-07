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
            'phone' => ['min:6', 'max:20', 'required', 'regex:/^\+?\d{0,3}[\s-]?\(?\d{3}\)?[\s-]?\d{2}[\s-]?\d{2}[\s-]?\d{3}$/'],
            'password' => ['min:10', 'max:100', 'string', 'required', 'confirmed:password_confirm'],
            'password_confirm' => ['min:10', 'max:100', 'string', 'required']
        ];
    }
}
