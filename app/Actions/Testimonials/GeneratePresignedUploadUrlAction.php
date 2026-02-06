<?php

namespace App\Actions\Testimonials;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class GeneratePresignedUploadUrlAction
{
    /**
     * @return array{path:string,url:string,headers:array<string,string>,expires_at:string}
     */
    public function execute(string $campaignId, string $extension = 'webm', int $expiresInMinutes = 15): array
    {
        $extension = trim(strtolower($extension), '.');
        $path = sprintf('campaigns/%s/testimonials/%s.%s', $campaignId, (string) Str::uuid(), $extension);
        $expiresAt = now()->addMinutes($expiresInMinutes);

        $disk = Storage::disk('r2');

        if (! method_exists($disk, 'temporaryUploadUrl')) {
            throw new RuntimeException('Configured disk does not support temporary upload URLs.');
        }

        $upload = $disk->temporaryUploadUrl(
            $path,
            $expiresAt,
            [
                'ContentType' => $this->resolveContentType($extension),
                'ACL' => 'public-read',
            ]
        );

        return [
            'path' => $path,
            'url' => $upload['url'],
            'headers' => $upload['headers'] ?? [],
            'expires_at' => $expiresAt->toIso8601String(),
        ];
    }

    private function resolveContentType(string $extension): string
    {
        return match ($extension) {
            'mp4' => 'video/mp4',
            'mov' => 'video/quicktime',
            'webm' => 'video/webm',
            default => 'application/octet-stream',
        };
    }
}
