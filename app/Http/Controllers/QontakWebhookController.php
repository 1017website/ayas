<?php

namespace App\Http\Controllers;

use App\Models\QontakWebhookEvent;
use App\Services\QontakService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QontakWebhookController extends Controller
{
    public function __invoke(Request $request, QontakService $qontak): JsonResponse
    {
        $expected = $qontak->webhookSecret();
        $provided = $request->query('secret') ?: $request->header('X-Webhook-Secret');

        if (! $expected || ! $provided || ! hash_equals($expected, $provided)) {
            return response()->json(['message' => 'Unauthorized webhook.'], Response::HTTP_UNAUTHORIZED);
        }

        $payload = $request->all();
        QontakWebhookEvent::create([
            'event_name' => data_get($payload, 'event') ?? data_get($payload, 'event_name'),
            'external_id' => data_get($payload, 'data.id') ?? data_get($payload, 'id'),
            'payload' => $payload,
            'received_at' => now(),
        ]);

        return response()->json(['received' => true]);
    }
}
