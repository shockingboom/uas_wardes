<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public function sendMessageToAdmin(string $message)
    {
        try {
            $apiKey = env('WHATSAPP_API_KEY');
            $apiUrl = env('WHATSAPP_API_URL');
            $adminNumber = env('ADMIN_NUMBER');
            return Http::withHeaders([
                'x-api-key' => $apiKey,
                'Accept' => 'application/json',
            ])

                ->post("{$apiUrl}/api/send-message", [
                    'number' => $adminNumber,
                    'message' => $message,
                ])
                ->json();
        } catch (\Exception $e) {
            Log::error('WhatsApp send to admin failed: ' . $e->getMessage());
            return null;
        }
    }

    public function sendMessageToCustomer(string $phone, string $message)
    {
        try {
            $apiKey = env('WHATSAPP_API_KEY');
            $apiUrl = env('WHATSAPP_API_URL');
            
            return Http::withHeaders([
                'x-api-key' => $apiKey,
                'Accept' => 'application/json',
            ])
                ->post("{$apiUrl}/api/send-message", [
                    'number' => $phone,
                    'message' => $message,
                ])
                ->json();
        } catch (\Exception $e) {
            Log::error('WhatsApp send to customer failed: ' . $e->getMessage());
            return null;
        }
    }
}
