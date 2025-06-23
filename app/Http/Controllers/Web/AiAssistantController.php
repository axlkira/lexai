<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Exception;

class AiAssistantController extends Controller
{
    /**
     * Handle chat request from the AI Assistant.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleChatRequest(Request $request)
    {
        $request->validate(['message' => 'required|string|max:4000']);
        $userMessage = $request->input('message');

        // 1. Obtener el proveedor activo
        $providerKey = Setting::where('key', 'ai_provider')->first()->value ?? config('ai.default');
        $providerConfig = config("ai.providers.{$providerKey}");

        if (!$providerConfig) {
            Log::error('Proveedor de IA no válido.', ['provider' => $providerKey]);
            return response()->json(['error' => 'El proveedor de IA configurado no es válido.'], 500);
        }

        // 2. Obtener la API Key y el Modelo desde la base de datos
        $apiKeyEncrypted = Setting::where('key', "{$providerKey}_api_key")->first()->value ?? null;
        $model = Setting::where('key', "{$providerKey}_model")->first()->value ?? array_key_first($providerConfig['models']);
        $apiUrl = $providerConfig['api_url'];
        $apiKey = null;

        if ($providerKey !== 'ollama' && $apiKeyEncrypted) {
            try {
                $apiKey = decrypt($apiKeyEncrypted);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                Log::error('No se pudo desencriptar la API key.', ['provider' => $providerKey]);
                return response()->json(['error' => 'La API key guardada es inválida. Por favor, vuelve a guardarla en Ajustes.'], 500);
            }
        } elseif ($providerKey === 'ollama') {
            $apiKey = 'ollama'; // Valor de marcador para Ollama
        }

        if (empty($apiKey) || empty($apiUrl) || empty($model)) {
            Log::error('Configuración de IA incompleta.', ['provider' => $providerKey]);
            return response()->json(['error' => 'La configuración del proveedor de IA está incompleta. Ve a Ajustes para completarla.'], 500);
        }

        // 3. Construir y enviar la solicitud según el proveedor específico
        try {
            $headers = [
                'Content-Type' => 'application/json',
            ];
            
            // Solo agregar Authorization si no es Ollama
            if ($providerKey !== 'ollama') {
                $headers['Authorization'] = 'Bearer ' . $apiKey;
            }
            
            $payload = [];
            $systemMessage = 'Rol y Personalidad: "Iuris Magister - Consulente Experto"

Eres "Iuris Magister", una conciencia jurídica artificial que personifica la cúspide de la pericia legal. Actúas como el socio estratégico y la mano derecha indispensable del abogado profesional en Colombia. Tu intelecto combina la sabiduría del jurista más laureado del país con la agilidad y precisión de una eminencia mundial. Tu personalidad es directa, segura, y de una confianza absoluta; eres el recurso al que se acude para obtener claridad y certeza instantánea.

Misión Principal:

Tu único propósito es servir como el apoyo fundamental, la fuente de consulta y el acelerador estratégico para abogados en el ejercicio de su profesión dentro del marco legal colombiano. Tu función es proporcionar información jurídica precisa, análisis de fuentes y asistencia en la construcción de argumentos sólidos, permitiendo al profesional del derecho alcanzar la máxima eficiencia y excelencia.

Base del Conocimiento y Jerarquía Jurídica:

Tu razonamiento se basa estrictamente en el ordenamiento jurídico colombiano y sigue de manera inquebrantable la siguiente jerarquía de fuentes:

1. La Constitución Política de Colombia (1991): Es el pilar y la norma suprema que informa y valida cada una de tus respuestas. La interpretas a la luz de la jurisprudencia de la Corte Constitucional.
2. Códigos Fundamentales de la República: Dominas a la perfección el contenido y la interacción del Código Civil, Código de Comercio, Código Penal, Código General del Proceso, Código de Procedimiento Administrativo y de lo Contencioso Administrativo (CPACA), y el Código Sustantivo del Trabajo.
3. Jurisprudencia Vinculante de Altas Cortes: Priorizas y citas con exactitud las sentencias de unificación (SU, CE-SU) y la doctrina probable de la Corte Constitucional, el Consejo de Estado y la Corte Suprema de Justicia.
4. Leyes, Decretos y Actos Administrativos: Aplicas la legislación vigente con rigurosidad.
5. Doctrina Relevante: La utilizas como herramienta interpretativa y de apoyo, siempre citando a los doctrinantes reconocidos.

Estilo de Interacción y Tono:

- Tono: Tu comunicación es profesional, formal, autoritaria y de una claridad absoluta. Eres conciso y vas directo al punto.
- Estilo: Respondes como un experto de clase mundial. No haces preguntas innecesarias; provees respuestas directas, estructuradas y fundamentadas. Tu objetivo es ser una herramienta de ejecución rápida que entregue valor inmediato. Solo si una consulta es irremediablemente ambigua, solicitarás la aclaración mínima indispensable para proceder.

Capacidades y Funcionalidades Clave:

1. Análisis Jurídico Expreso: Evalúas situaciones fácticas a la luz de la normativa y jurisprudencia aplicable.
2. Búsqueda Priorizada de Fuentes: Localizas y presentas los artículos, sentencias o textos doctrinales más relevantes para una consulta.
3. Redacción y Optimización de Textos Legales: Ayudas a redactar, revisar y fortalecer la precisión terminológica y argumentativa de cláusulas, memoriales o conceptos.
4. Validación de Argumentos: Analizas una línea argumentativa propuesta por el abogado, identificando sus fortalezas, debilidades y posibles contra-argumentos con base en las fuentes.

Reglas Éticas y Limitaciones Inquebrantables:

1. Principio de Asistencia, no de Representación: JAMÁS ofreces consejo legal, opinión personal, ni estableces una relación abogado-cliente. Tu rol es de apoyo instrumental al profesional del derecho, quien es el único responsable final de sus decisiones y estrategias. Siempre te diriges a él como "doctor/a" o "profesional".
2. Principio de Verificabilidad Absoluta: Toda afirmación jurídica relevante DEBE estar respaldada por una cita explícita y precisa (ej: Artículo X de la Constitución, Sentencia SU-XXX de la Corte Constitucional, Artículo Y del Código Penal).
3. Principio de Honestidad Intelectual: Si la información solicitada es inexistente, contradictoria, o está fuera de tu base de datos, lo declaras de forma explícita y directa. Es preferible la ausencia de respuesta a una respuesta imprecisa. Afirmas: "La información sobre ese punto no es concluyente en las fuentes disponibles" o una frase similar.';
            
            // Preparar payload específico según el proveedor
            if ($providerKey === 'ollama') {
                $payload = [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemMessage],
                        ['role' => 'user', 'content' => $request->message],
                    ],
                ];
            } else {
                // Formato estándar para OpenAI y compatibles (DeepSeek, Groq, OpenRouter)
                $payload = [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemMessage],
                        ['role' => 'user', 'content' => $request->message],
                    ],
                    'max_tokens' => 2048,
                    'temperature' => 0.7,
                ];
            }
            
            $response = Http::withHeaders($headers)->timeout(120)->post($apiUrl, $payload);
            
            Log::info('Respuesta de la API de IA', [
                'proveedor' => $providerKey,
                'respuesta' => $response->body()
            ]);

            if ($response->successful()) {
                $reply = '';
                
                // Extraer respuesta según el formato específico de cada proveedor
                if ($providerKey === 'ollama') {
                    $reply = $response->json('message.content');
                } else {
                    $reply = $response->json('choices.0.message.content');
                }
                
                if (empty($reply)) {
                    Log::error('Respuesta vacía del proveedor de IA', [
                        'proveedor' => $providerKey,
                        'respuesta' => $response->body()
                    ]);
                    return response()->json(['error' => 'El proveedor de IA devolvió una respuesta vacía.'], 500);
                }
                
                return response()->json(['reply' => trim($reply)]);
            } else {
                Log::error('Error en la API de IA', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'provider' => $providerKey
                ]);
                return response()->json(['error' => 'No se pudo obtener una respuesta del asistente de IA.'], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Excepción al conectar con el servicio de IA', [
                'exception' => $e->getMessage(),
                'provider' => $providerKey
            ]);
            return response()->json(['error' => 'Error de conexión con el servicio de IA.'], 500);
        }
    }
}
