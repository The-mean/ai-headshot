<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DodoWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        /** @var array<string, mixed> $payload */
        $payload = $request->all();

        $eventType = (string) ($payload['type'] ?? '');
        $data = is_array($payload['data'] ?? null) ? $payload['data'] : [];
        $customerId = (string) ($data['customer_id'] ?? $data['customer']['id'] ?? '');

        if ($customerId === '' || $eventType === '') {
            return response()->json(['message' => 'Invalid webhook payload.'], 422);
        }

        match ($eventType) {
            'subscription.created',
            'subscription.renewed',
            'payment.succeeded' => $this->markActive($customerId, $data),
            'payment.failed',
            'subscription.canceled',
            'subscription.expired' => $this->markPastDue($customerId, $data),
            default => null,
        };

        return response()->json(['received' => true]);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function markActive(string $customerId, array $data): void
    {
        DB::table('users')
            ->where('dodo_customer_id', $customerId)
            ->update([
                'plan_status' => 'active',
                'plan_name' => (string) ($data['plan_name'] ?? 'pro'),
                'subscription_ends_at' => isset($data['current_period_end']) ? Carbon::parse((string) $data['current_period_end']) : null,
                'updated_at' => now(),
            ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function markPastDue(string $customerId, array $data): void
    {
        DB::table('users')
            ->where('dodo_customer_id', $customerId)
            ->update([
                'plan_status' => 'past_due',
                'plan_name' => (string) ($data['plan_name'] ?? 'free'),
                'updated_at' => now(),
            ]);
    }
}
