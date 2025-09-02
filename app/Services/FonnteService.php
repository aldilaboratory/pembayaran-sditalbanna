<?php

// app/Services/FonnteService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function send(string $target, string $message, array $opts = []): array
    {
        $endpoint = config('services.fonnte.endpoint', 'https://api.fonnte.com/send');
        $token    = config('services.fonnte.token');

        $payload = array_filter([
            'target'      => $target,
            'message'     => $message,
            'countryCode' => $opts['countryCode'] ?? '62',
            'typing'      => $opts['typing'] ?? true,
            'preview'     => $opts['preview'] ?? true,
            'delay'       => $opts['delay'] ?? null,
            'schedule'    => $opts['schedule'] ?? null,
        ], fn($v) => $v !== null && $v !== '');

        $res  = Http::asForm()->withHeaders(['Authorization' => $token])->post($endpoint, $payload);
        $json = $res->json() ?? [];
        Log::info('Fonnte response', ['status'=>$res->status(), 'body'=>$json]);

        if (!$res->successful() || empty($json['status'])) {
            throw new \RuntimeException('Gagal kirim WA via Fonnte: '.json_encode($json));
        }
        return $json;
    }
}
