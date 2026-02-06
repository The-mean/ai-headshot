<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WidgetController extends Controller
{
    public function show(string $campaignId): JsonResponse
    {
        $testimonials = DB::table('testimonials')
            ->select(
                'id',
                'campaign_id',
                'author_name',
                'storage_disk',
                'storage_path',
                'display_order',
                'meta'
            )
            ->where('campaign_id', $campaignId)
            ->where('status', 'approved')
            ->whereNull('deleted_at')
            ->orderBy('display_order')
            ->orderByDesc('approved_at')
            ->limit(30)
            ->get()
            ->map(function (object $row): array {
                $disk = $row->storage_disk ?: 'r2';
                $meta = is_string($row->meta) ? (json_decode($row->meta, true) ?: []) : [];

                $thumbnailUrl = $meta['thumbnail_url'] ?? null;

                if ($thumbnailUrl === null && isset($meta['thumbnail_path']) && is_string($meta['thumbnail_path'])) {
                    $thumbnailUrl = Storage::disk($disk)->url($meta['thumbnail_path']);
                }

                if ($thumbnailUrl === null) {
                    $thumbnailPathGuess = preg_replace('/\.[a-zA-Z0-9]+$/', '.jpg', $row->storage_path) ?: $row->storage_path;
                    $thumbnailUrl = Storage::disk($disk)->url($thumbnailPathGuess);
                }

                return [
                    'id' => $row->id,
                    'campaign_id' => $row->campaign_id,
                    'author_name' => $row->author_name,
                    'display_order' => (int) $row->display_order,
                    'thumbnail_url' => $thumbnailUrl,
                    'video_url' => Storage::disk($disk)->url($row->storage_path),
                ];
            })
            ->values();

        return response()->json([
            'data' => [
                'campaign_id' => $campaignId,
                'testimonials' => $testimonials,
            ],
        ]);
    }
}
