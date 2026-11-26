<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected string $bridgeUrl = 'http://127.0.0.1:3001';

    public function send(string $phone, string $message): bool
    {
        $response = Http::timeout(10)->post(
            $this->bridgeUrl . '/send-message',
            [
                'phone' => $phone,
                'message' => $message,
            ]
        );

        return $response->successful();
    }
}