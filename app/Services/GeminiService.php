<?php

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/'; // Ejemplo base URL

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function generateRecommendations(array $menu, array $preferences)
    {
        try {
            $response = Http::post($this->baseUrl . 'gemini-pro:generateContent', [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => 'Recomienda productos del siguiente menú: ' . json_encode($menu) .
                                          ' Basado en las preferencias: ' . json_encode($preferences) .
                                          ' Devuelve solo los nombres de los productos recomendados.'
                            ]
                        ]
                    ]
                ],
                'safetySettings' => [ // Ejemplo de ajustes de seguridad
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ],
            ], [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'key' => $this->apiKey,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Adaptar la extracción de las recomendaciones según la estructura de la respuesta de la API
                $recommendations = $data['candidates'][0]['content']['parts'][0]['text'] ?? [];
                return $recommendations;
            } else {
                // Log el error para depuración
                \Log::error('Error al llamar a la API de Gemini: ' . $response->status() . ' - ' . $response->body());
                return null; // O lanza una excepción
            }

        } catch (\Exception $e) {
            \Log::error('Excepción al llamar a la API de Gemini: ' . $e->getMessage());
            return null; // O lanza una excepción
        }
    }
}