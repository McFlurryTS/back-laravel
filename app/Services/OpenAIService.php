<?php
namespace App\Services;


namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1/';
    //protected $model = 'gpt-4.1-mini'; 
    protected $model = 'gpt-4.1-nano'; // Cambia a gpt-4 si es necesario

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function generateRecommendations(array $menu, array $preferences)
    {
        $now = now()->setTimezone('America/Mexico_City');
        //$now = now()->setTimezone('Europe/London');

        try {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'chat/completions', [
                'model' => $this->model, 
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un asistente creativo y experto en menús de McDonald\'s. Tu tarea es seleccionar productos del menú y presentarlos de forma atractiva para ayudar al usuario a hacer un pedido.'
                    ],
                    [
                        'role' => 'user',
                        'content' =>
                            "Hora actual: $now\n" .
                            "Preferencias del usuario: \"No soy tan dulcero\"\n\n" .
                            "Aquí tienes el menú completo en formato JSON:\n" . json_encode($menu) . "\n\n" .//pendiente
                            "Basado en la hora actual:\n" .
                            "- Si la hora está entre **08:00 y 12:00**, la categoría inicial debe ser **'Desayunos'**.\n" .
                            "- En cualquier otro horario, reemplaza esa categoría por **'Hamburguesas'**.\n\n" .
                            "Selecciona entre 3 y 5 categorías en el siguiente orden:\n" .
                            "1. Desayunos o Hamburguesas (según la hora)\n" .
                            "2. Bebidas\n" .
                            "3. Complementos\n" .
                            "4. Postres (opcional)\n\n" .
                            "Si el usuario dice algo como 'No soy tan dulcero', entonces omite la categoría 'Postres'.\n\n" .
                            "Para cada categoría seleccionada:\n" .
                            "- Incluye un mensaje creativo y amigable para presentar esa sección (puede variar el tono entre divertido, casual o sugerente).\n" .
                            "- Muestra entre 3 y 4 productos reales de esa categoría del menú.\n" .
                            "- Cada producto debe incluir solo su `id` y `nombre`.\n\n" .
                            "Devuelve únicamente un array JSON con objetos como este:\n" .
                            "[\n" .
                            "  {\n" .
                            "    \"message\": \"Hora de un buen desayuno, ¿te animás con algo clásico?\",\n" .
                            "    \"category\": \"Desayunos\",\n" .
                            "    \"options\": [\n" .
                            "      { \"id\": \"001\", \"nombre\": \"McMuffin con huevo\" },\n" .
                            "      { \"id\": \"002\", \"nombre\": \"Hotcakes\" },\n" .
                            "      { \"id\": \"003\", \"nombre\": \"McGriddles\" }\n" .
                            "    ]\n" .
                            "  }\n" .
                            "]\n\n" .
                            "No incluyas texto fuera del array JSON."
                    ]                    
                    
                ],
                'max_tokens' => 1000, // Ajusta según la longitud esperada de la respuesta
                'n' => 1, // Número de respuestas a generar
                'stop' => null, // Opcional: secuencia para detener la generación
                'temperature' => 0.7, // Ajusta para controlar la aleatoriedad de la respuesta
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $recommendationText = $data['choices'][0]['message']['content'] ?? '';
                //$recommendations = array_map('trim', explode(',', $recommendationText));
                return $recommendationText;
            } else {
                \Log::error('Error al llamar a la API de OpenAI: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

        } catch (\Exception $e) {
            \Log::error('Excepción al llamar a la API de OpenAI: ' . $e->getMessage());
            return null;
        }
    }
    public function generateCombosRecommendations(array $menu, array $preferences)
    {
        $now = now()->setTimezone('America/Mexico_City');
        //$now = now()->setTimezone('Europe/London');

        try {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'chat/completions', [
                'model' => $this->model, 
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un asistente experto en combos del menú de McDonald\'s. Tu tarea es recomendar combos personalizados según el perfil del usuario y el momento del día. Tus recomendaciones siempre consideran tanto las preferencias como la hora actual, y usas un tono amigable y conversacional.'
                    ],
                    [
                        'role' => 'user',
                        'content' =>
                            "Hora actual: $now\n\n" .
                            "Preferencias del usuario:\n" . json_encode($preferences) . "\n\n" .
                            "Aquí tienes el menú completo en formato JSON:\n" . json_encode($menu) . "\n\n" .
                            "En base a las preferencias del usuario —especialmente si indica para quién suele comprar (por ejemplo: 'para mis hijos', 'para mi pareja', 'solo para mí')— selecciona **entre 3 y 4 combos** del menú que mejor se adapten a ese perfil.\n\n" .
                            "Además, si la hora está entre **08:00 y 12:00**, incluye combos de desayuno (como hotcakes, McMuffins, jugo, café, etc). Si es después de las 12:00, sugiere combos normales o hamburguesas.\n\n" .
                            "Cada combo debe tener:\n" .
                            "- Un `id`\n" .
                            "- Un `nombre`\n\n" .
                            "Incluye también un `message` inicial personalizado según el perfil del usuario. Por ejemplo: '¿Qué tal algo para tus hijos?' o '¿Hora de consentirte con algo clásico?'\n\n" .
                            "Devuelve exactamente este formato JSON:\n\n" .
                            "{\n" .
                            "  \"message\": \"¿Qué tal algo para tus hijos?\",\n" .
                            "  \"combos\": [\n" .
                            "    { \"id\": \"xyz123\", \"nombre\": \"Cajita Feliz Hamburguesa\" },\n" .
                            "    { \"id\": \"xyz456\", \"nombre\": \"Cajita Feliz McNuggets\" },\n" .
                            "    { \"id\": \"xyz789\", \"nombre\": \"Hotcakes con jugo de naranja\" }\n" .
                            "  ]\n" .
                            "}\n\n" .
                            "No agregues texto fuera de ese JSON."
                    ]
                                        
                ],
                'max_tokens' => 1000, // Ajusta según la longitud esperada de la respuesta
                'n' => 1, // Número de respuestas a generar
                'stop' => null, // Opcional: secuencia para detener la generación
                'temperature' => 0.7, // Ajusta para controlar la aleatoriedad de la respuesta
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $recommendationText = $data['choices'][0]['message']['content'] ?? '';
                //$recommendations = array_map('trim', explode(',', $recommendationText));
                return $recommendationText;
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
