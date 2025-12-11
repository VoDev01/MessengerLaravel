<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class APILoginRequest extends FormRequest
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
            "app_id" => ["string", "min:10", "max:26", "required", "exists:app_api_consumers,app_id"],
            "app_url" => ["min:10", "max:150", "string", "unique:app_api_consumers,app_url"]            
        ];
    }
}
