<?php
namespace App\Services;


namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1/';
    protected $model = 'gpt-4.1-mini';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function generateRecommendations(array $menu, array $preferences)
    {
        try {
            $prompt = 'Recomienda productos del siguiente menú: ' . json_encode($menu) .
            ' Basado en las preferencias: ' . json_encode($preferences) .
            '¿Qué 5 productos recomendarías? Devuelve solo los nombres de los productos en una lista separada por comas.';            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 150, // Ajusta según la longitud esperada de la respuesta
                'n' => 1, // Número de respuestas a generar
                'stop' => null, // Opcional: secuencia para detener la generación
                'temperature' => 0.7, // Ajusta para controlar la aleatoriedad de la respuesta
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $recommendationText = $data['choices'][0]['message']['content'] ?? '';
                $recommendations = array_map('trim', explode(',', $recommendationText));
                return $recommendations;
            } else {
                \Log::error('Error al llamar a la API de OpenAI: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

        } catch (\Exception $e) {
            \Log::error('Excepción al llamar a la API de OpenAI: ' . $e->getMessage());
            return null;
        }
    }
}