<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WidgetController extends Controller
{
    private const FREE_PLAN_VIDEO_LIMIT = 5;

    public function show(string $campaignId): JsonResponse
    {
        $campaign = DB::table('campaigns')
            ->join('users', 'users.id', '=', 'campaigns.user_id')
            ->select('campaigns.id', 'users.plan_status', 'users.plan_name')
            ->where('campaigns.id', $campaignId)
            ->whereNull('campaigns.deleted_at')
            ->first();

        if ($campaign === null) {
            return response()->json([
                'message' => 'Campaign not found.',
                'data' => [
                    'campaign_id' => $campaignId,
                    'testimonials' => [],
                    'subscription_required' => true,
                    'branding_required' => true,
                ],
            ], 404);
        }

        $isPaidPlan = $campaign->plan_status === 'active';

        if (! $isPaidPlan && $campaign->plan_status !== 'free') {
            return response()->json([
                'message' => 'Subscription Required',
                'data' => [
                    'campaign_id' => $campaignId,
                    'testimonials' => [],
                    'subscription_required' => true,
                    'branding_required' => true,
                ],
            ], 402);
        }

        $limit = $isPaidPlan ? 30 : self::FREE_PLAN_VIDEO_LIMIT;

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
            ->limit($limit)
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
                'subscription_required' => false,
                'branding_required' => ! $isPaidPlan,
                'plan' => $campaign->plan_name,
            ],
        ]);
    }
}
