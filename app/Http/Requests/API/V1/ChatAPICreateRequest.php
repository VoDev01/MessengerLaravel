<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class ChatAPICreateRequest extends FormRequest
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
            'name' => ["min:5", "max:100", "string", "required"],
            'link_name' => ["min:5", "max:100", "string", "required", "unique:chats,link_name"],
            'visibility' => ['in:PUBLIC,PRIVATE,PRESENCE'],
            'type' => ['in:GROUP,DIRECT']
        ];
    }
}
