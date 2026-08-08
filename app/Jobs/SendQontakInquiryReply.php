<?php

namespace App\Jobs;

use App\Models\Inquiry;
use App\Services\QontakService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SendQontakInquiryReply implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [30, 120, 300];

    public function __construct(public int $inquiryId) {}

    public function handle(QontakService $qontak): void
    {
        $inquiry = Inquiry::find($this->inquiryId);
        if (! $inquiry || ! $qontak->isReady()) {
            return;
        }

        $inquiry->update(['qontak_status' => 'processing', 'qontak_error' => null]);
        $response = $qontak->sendInquiryReply($inquiry);
        $reference = data_get($response->json(), 'data.id')
            ?? data_get($response->json(), 'data.broadcast_id')
            ?? data_get($response->json(), 'id');

        $inquiry->update([
            'qontak_status' => 'sent',
            'qontak_reference' => $reference,
            'qontak_synced_at' => now(),
        ]);
    }

    public function failed(Throwable $exception): void
    {
        Inquiry::whereKey($this->inquiryId)->update([
            'qontak_status' => 'failed',
            'qontak_error' => mb_substr($exception->getMessage(), 0, 2000),
        ]);
    }
}
