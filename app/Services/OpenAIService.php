<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function generarMenu(string $prompt)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
        ])->post($this->apiUrl, [
            'model' => 'gpt-4o-mini', // elige el modelo que tengas disponible
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un asistente culinario que devuelve JSON válido según la estructura solicitada.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.8,
            'max_tokens' => 800,
        ]);

        return $response->json();
    }
}
