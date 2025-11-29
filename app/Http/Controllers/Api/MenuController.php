<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use OpenAI;

class MenuController extends Controller
{
    public function generarMenu(Request $request)
    {
        $ingredientes = $request->input('ingredientes');

        if (!$ingredientes) {
            return response()->json(['error' => 'Faltan los ingredientes'], 400);
        }

        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $prompt = <<<EOT
Eres CoockGenius, un asistente culinario inteligente.
El usuario te proporcionará una lista de ingredientes disponibles.
Tu tarea es crear un menú completo (desayuno, comida y cena) adaptado a esos ingredientes.

Cada platillo debe ser realista, equilibrado y aprovechar los ingredientes proporcionados.
Evita inventar ingredientes fuera de la lista a menos que sean condimentos o básicos de cocina (sal, agua, aceite, etc.).

Devuelve **únicamente** un JSON con esta estructura:

{
  "menus": [
    {
      "tipo": "Desayuno",
      "platillo": "Nombre del platillo",
      "porciones": "Número de porciones",
      "calorias": "Aproximado de calorías por porción",
      "tiempo": "Tiempo estimado de preparación (en minutos)",
      "ingredientes": ["lista de ingredientes utilizados"],
      "pasos": ["paso 1", "paso 2", "paso 3", ...]
    }
  ]
}

Ejemplo de respuesta válida:
{
  "menus": [
    {
      "tipo": "Comida",
      "platillo": "Pollo al arroz",
      "porciones": "2",
      "calorias": "450 kcal",
      "tiempo": "35 minutos",
      "ingredientes": ["pollo", "arroz", "jitomate", "cebolla"],
      "pasos": [
        "Corta el pollo en cubos.",
        "Sofríe con cebolla y jitomate.",
        "Agrega el arroz y agua suficiente para cocinarlo."
      ]
    }
  ]
}

Ingredientes del usuario: {$ingredientes}
EOT;

        $result = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un experto en cocina saludable y eficiente.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 600,
        ]);

        $responseText = $result['choices'][0]['message']['content'] ?? '';

        return response()->json(json_decode($responseText, true));
    }
}
