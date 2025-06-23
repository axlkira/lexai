<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LegalCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cases = LegalCase::with(['client', 'documents'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('legal-cases.index', compact('cases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener solo los clientes del abogado autenticado
        $clients = Client::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        // Cargar proveedores de IA desde la configuración
        $aiProviders = collect(config('services.ai_providers'))
            ->map(function ($provider, $key) {
                if (is_array($provider) && isset($provider['name'])) {
                    return ['value' => $key, 'name' => $provider['name']];
                }
                return null;
            })
            ->filter()
            ->values()
            ->all();
            
        return view('legal-cases.create', compact('clients', 'aiProviders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'judicial_process_number' => 'nullable|string|max:100',
            'court' => 'nullable|string|max:255',
            'judge' => 'nullable|string|max:255',
            'jurisdiction' => 'nullable|string|max:255',
        ]); 
        
        // Verificar que el cliente pertenezca al abogado autenticado
        $client = Client::where('id', $validated['client_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $case = new LegalCase($validated);
        $case->user_id = Auth::id();
        $case->case_number = 'CASE-' . Str::upper(Str::random(8));
        $case->status = 'abierto';
        $case->start_date = now();
        $case->save();
        
        return redirect()->route('legal-cases.show', $case)
            ->with('success', 'Caso legal creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LegalCase $legalCase)
    {
        $this->authorize('view', $legalCase);

        // Eager load the documents associated with the case
        $legalCase->load('documents');

        return view('legal-cases.show', compact('legalCase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LegalCase $legalCase)
    {
        $this->authorize('update', $legalCase);

        // Obtener solo los clientes del abogado autenticado
        $clients = Client::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        $statuses = [
            'abierto' => 'Abierto',
            'en_progreso' => 'En Progreso',
            'cerrado' => 'Cerrado',
            'archivado' => 'Archivado',
        ];

        // Cargar proveedores de IA desde la configuración
        $aiProviders = collect(config('services.ai_providers'))
            ->map(function ($provider, $key) {
                if (is_array($provider) && isset($provider['name'])) {
                    return ['value' => $key, 'name' => $provider['name']];
                }
                return null;
            })
            ->filter()
            ->values()
            ->all();

        return view('legal-cases.edit', compact('legalCase', 'clients', 'statuses', 'aiProviders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LegalCase $legalCase)
    {
        $this->authorize('update', $legalCase);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:abierto,en_progreso,cerrado,archivado',
            'judicial_process_number' => 'nullable|string|max:100',
            'court' => 'nullable|string|max:255',
            'judge' => 'nullable|string|max:255',
            'jurisdiction' => 'nullable|string|max:255',
            'end_date' => 'nullable|date',
        ]);
        
        // Verificar que el cliente pertenezca al abogado autenticado
        if ($legalCase->client_id != $validated['client_id']) {
            $client = Client::where('id', $validated['client_id'])
                ->where('user_id', Auth::id())
                ->firstOrFail();
        }
        
        $legalCase->update($validated);
        
        return redirect()->route('legal-cases.show', $legalCase)
            ->with('success', 'Caso legal actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LegalCase $legalCase): RedirectResponse
    {
        $this->authorize('delete', $legalCase);

        $legalCase->delete();

        return redirect()->route('legal-cases.index')
            ->with('success', 'Caso eliminado con éxito.');
    }

    public function generateDescription(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:4000',
            'model' => 'required|string',
            'provider' => 'required|string|in:' . implode(',', array_keys(config('services.ai_providers'))),
        ]);

        $providerKey = $request->provider;
        $providerConfig = config('services.ai_providers.' . $providerKey);

        $userPrompt = $request->prompt;
        $enhancedPrompt = <<<PROMPT
# INSTRUCCIONES OBLIGATORIAS PARA LA IA (PROCESO INTERNO INVISIBLE)

**1. ROL DE EXPERTO HÍBRIDO (ABOGADO COLOMBIANO + EXPERTO TEMÁTICO):**
Tu rol principal y permanente es ser el mejor y más prestigioso abogado del mundo, con una especialización y conocimiento enciclopédico de todo el marco legal de Colombia (Constitución, códigos, leyes, decretos, jurisprudencia actualizada). Adicionalmente, analiza la "SOLICITUD DEL USUARIO" para identificar el tema específico que se está tratando (ej: negocios, tecnología, familia, bienes raíces, etc.). Tu respuesta debe fusionar ambos roles: abordarás la solicitud como un experto en ese tema, pero siempre desde la perspectiva y con el rigor de un abogado colombiano de élite, aplicando el marco legal relevante en cada análisis, recomendación o documento que generes.

**2. MISIÓN PRINCIPAL:**
Tu objetivo es responder directamente a la "SOLICITUD DEL USUARIO" proporcionando un resultado de calidad excepcional ("World-Class"). No te limites a la solicitud literal; anticípate a las necesidades del usuario. Debes enriquecer la solicitud original con detalles, contexto y matices que un experto en la materia añadiría, para entregar un resultado que sea drásticamente más completo, útil y mejor estructurado de lo que un novato esperaría.

**3. PROCESO DE MEJORA SILENCIOSO (NO MENCIONAR NUNCA AL USUARIO):**
Antes de generar la respuesta final, realiza internamente los siguientes pasos:
    - **Añadir Profundidad Legal:** Si el usuario pide un documento, asegúrate de que cumpla con las formalidades legales colombianas. Si pide consejo, fundamenta tus respuestas en la legislación o jurisprudencia pertinente.
    - **Estructurar la Respuesta:** Organiza la información de la forma más clara y lógica posible. Para documentos legales, usa una estructura formal y profesional.
    - **Mantener Limpieza:** Genera la respuesta final como texto plano y limpio. No uses formato Markdown como `**`, `#` o `*`, a menos que el formato sea absolutamente esencial para la respuesta (como en un bloque de código para programación o para la estructura de un documento legal si es necesario).

**4. REGLA DE ORO (LA MÁS IMPORTANTE):**
**JAMÁS, bajo ninguna circunstancia, menciones que has mejorado, optimizado o enriquecido el prompt del usuario.** No hables de que eres una IA, un abogado o un motor de prompts. Tu proceso de mejora debe ser 100% invisible. Simplemente, actúa como el abogado experto y entrega el resultado final directamente.

---
**SOLICITUD DEL USUARIO:**
$userPrompt
---
PROMPT;

        try {
            $payload = [];
            $headers = [
                'Content-Type' => 'application/json',
            ];

            if (!empty($providerConfig['api_key'])) {
                $headers['Authorization'] = 'Bearer ' . $providerConfig['api_key'];
            }

            // Adapt payload for different providers
            if ($providerKey === 'ollama') {
                 $payload = [
                    'model' => $request->model,
                    'prompt' => $enhancedPrompt,
                    'stream' => false
                ];
            } else { // OpenAI, Groq, etc.
                $payload = [
                    'model' => $request->model,
                    'messages' => [
                        ['role' => 'user', 'content' => $enhancedPrompt]
                    ],
                    'max_tokens' => 2000,
                    'stream' => false
                ];
            }

            $response = Http::withHeaders($headers)
                ->timeout(180)
                ->post($providerConfig['url'], $payload);

            if ($response->failed()) {
                Log::error("AI API Error for provider {$providerKey}: " . $response->body());
                return response()->json(['message' => 'Error al contactar el servicio de IA: ' . $response->reason()], 500);
            }

            $jsonResponse = $response->json();
            $description = '';

            if ($providerKey === 'ollama') {
                $description = $jsonResponse['response'] ?? '';
            } else { // OpenAI, Groq, etc.
                $description = $jsonResponse['choices'][0]['message']['content'] ?? '';
            }

            return response()->json(['description' => trim($description)]);

        } catch (\Exception $e) {
            Log::error('AI Generation Exception: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error inesperado durante la generación.'], 500);
        }
    }
}
