<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageCreateRequest extends FormRequest
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
            'message' => 'nullable|string|max:200|required_without:attachment',
            'attachment' => 'nullable|mimes:png,jpg,jpeg,doc,docx,pdf|max:5120|required_without:message',
            'channel_id' => 'required|integer|exists:channels,id',
            'company_id' => 'required|integer|exists:companies,id',
        ];
    }
    public function messages(): array
    {
        return [
            'message.required_without' => 'Either a message text or attachment is required',
            'attachment.required_without' => 'Either an attachment or Message text is required'
        ];
    }
}
