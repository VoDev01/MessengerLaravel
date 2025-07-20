<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'message' => ['string', 'min:1', 'max:1000'],
            'attachments' => ['array', 'nullable'],
            'sender' => ['string', 'min:1', 'max:100'],
            'recipient' => ['string', 'min:1', 'max:100'],
            'sent_at' => ['date'],
            'received' => ['boolean'],
            'read' => ['boolean']
        ];
    }
}
