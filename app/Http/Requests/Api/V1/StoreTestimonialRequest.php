<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
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
            'storage_path' => ['required', 'string', 'max:1024'],
            'storage_disk' => ['nullable', 'string', 'max:50'],
            'source' => ['nullable', 'string', 'in:widget_record,widget_upload,admin_upload'],
            'status' => ['nullable', 'string', 'in:uploaded,pending_review,approved,rejected'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'author_company' => ['nullable', 'string', 'max:255'],
            'is_consent_given' => ['required', 'accepted'],
            'ip_address' => ['nullable', 'ip'],
            'file_size_bytes' => ['nullable', 'integer', 'min:1', 'max:52428800'],
            'duration_ms' => ['nullable', 'integer', 'min:1'],
            'mime_type' => ['nullable', 'string', 'max:100'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
