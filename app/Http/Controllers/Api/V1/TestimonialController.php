<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Testimonials\GeneratePresignedUploadUrlAction;
use App\Actions\Testimonials\StoreTestimonialMetadataAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PresignTestimonialUploadRequest;
use App\Http\Requests\Api\V1\StoreTestimonialRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class TestimonialController extends Controller
{
    public function presign(
        PresignTestimonialUploadRequest $request,
        GeneratePresignedUploadUrlAction $generatePresignedUploadUrlAction,
    ): JsonResponse {
        if ($limitedResponse = $this->checkRateLimit($request, 'presign')) {
            return $limitedResponse;
        }

        $data = $request->validated();

        $result = $generatePresignedUploadUrlAction->execute(
            $data['campaign_id'],
            $data['extension'] ?? 'webm',
            $data['expires_in_minutes'] ?? 15,
        );

        return response()->json([
            'data' => $result,
        ]);
    }

    public function store(
        StoreTestimonialRequest $request,
        StoreTestimonialMetadataAction $storeTestimonialMetadataAction,
    ): JsonResponse {
        if ($limitedResponse = $this->checkRateLimit($request, 'store')) {
            return $limitedResponse;
        }

        $payload = $request->validated();
        $payload['status'] ??= 'pending_review';
        $payload['ip_address'] = $request->ip();

        $testimonialId = $storeTestimonialMetadataAction->execute($payload);

        return response()->json([
            'data' => [
                'id' => $testimonialId,
                'status' => $payload['status'],
            ],
        ], 201);
    }

    private function checkRateLimit(Request $request, string $endpoint): ?JsonResponse
    {
        $key = sprintf('testimonial:%s:%s', $endpoint, (string) $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Too many requests. Please try again in a minute.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        return null;
    }
}
