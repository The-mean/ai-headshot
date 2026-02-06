<?php

namespace App\Actions\Testimonials;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreTestimonialMetadataAction
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function execute(array $payload): string
    {
        $testimonialId = (string) Str::uuid();

        DB::table('testimonials')->insert([
            'id' => $testimonialId,
            'campaign_id' => $payload['campaign_id'],
            'public_token' => (string) Str::uuid(),
            'author_name' => $payload['author_name'] ?? null,
            'author_email' => $payload['author_email'] ?? null,
            'author_company' => $payload['author_company'] ?? null,
            'is_consent_given' => (bool) ($payload['is_consent_given'] ?? false),
            'ip_address' => $payload['ip_address'] ?? null,
            'source' => $payload['source'] ?? 'widget_upload',
            'status' => $payload['status'] ?? 'uploaded',
            'storage_disk' => $payload['storage_disk'] ?? 'r2',
            'storage_path' => $payload['storage_path'],
            'file_size_bytes' => $payload['file_size_bytes'] ?? null,
            'duration_ms' => $payload['duration_ms'] ?? null,
            'mime_type' => $payload['mime_type'] ?? null,
            'meta' => isset($payload['meta']) ? json_encode($payload['meta'], JSON_THROW_ON_ERROR) : null,
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $testimonialId;
    }
}
