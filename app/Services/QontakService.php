<?php

namespace App\Services;

use App\Models\Inquiry;
use App\Models\Setting;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class QontakService
{
    public function isReady(): bool
    {
        return $this->value('qontak_enabled') === '1'
            && filled(Setting::secret('qontak_access_token'))
            && filled($this->value('qontak_channel_integration_id'))
            && filled($this->value('qontak_message_template_id'));
    }

    public function sendInquiryReply(Inquiry $inquiry): Response
    {
        $token = Setting::secret('qontak_access_token');
        abort_unless($this->isReady() && $token, 503, 'Integrasi Mekari Qontak belum lengkap.');

        return Http::acceptJson()
            ->asJson()
            ->withToken($token)
            ->timeout(20)
            ->retry(2, 500)
            ->post(rtrim($this->value('qontak_base_url'), '/').'/broadcasts/whatsapp/direct', [
                'to_name' => $inquiry->name,
                'to_number' => $this->internationalNumber($inquiry->phone),
                'message_template_id' => $this->value('qontak_message_template_id'),
                'channel_integration_id' => $this->value('qontak_channel_integration_id'),
                'language' => ['code' => $this->value('qontak_template_language') ?: 'id'],
                'parameters' => [
                    'body' => [
                        ['key' => '1', 'value' => 'name', 'value_text' => $inquiry->name],
                        ['key' => '2', 'value' => 'company', 'value_text' => $inquiry->company ?: '-'],
                        ['key' => '3', 'value' => 'product', 'value_text' => $inquiry->product ?: '-'],
                    ],
                ],
            ])->throw();
    }

    public function webhookSecret(): ?string
    {
        return Setting::secret('qontak_webhook_secret');
    }

    private function value(string $key): string
    {
        $field = config("ayas.qontak.{$key}");

        return (string) (Setting::query()->where('key', $key)->value('value') ?? ($field['default'] ?? ''));
    }

    private function internationalNumber(string $phone): string
    {
        $number = preg_replace('/\D+/', '', $phone) ?: '';

        return str_starts_with($number, '0') ? '62'.substr($number, 1) : $number;
    }
}
