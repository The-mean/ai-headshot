<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class PresignTestimonialUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'uuid'],
            'extension' => ['nullable', 'string', 'in:webm,mp4,mov'],
            'expires_in_minutes' => ['nullable', 'integer', 'min:1', 'max:60'],
        ];
    }
}
