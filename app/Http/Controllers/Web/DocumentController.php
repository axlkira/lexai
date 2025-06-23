<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\LegalCase;
use App\Services\AiGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Document::class); // Descomentar cuando la Policy exista

        $query = Document::where('user_id', Auth::id())->with('legalCase');

        if ($request->filled('case_id')) {
            $query->where('case_id', $request->case_id);
        }

        $documents = $query->latest()->paginate(15);
        $legalCases = LegalCase::where('user_id', Auth::id())->orderBy('title')->get();

        return view('documents.index', compact('documents', 'legalCases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // $this->authorize('create', Document::class); // Descomentar cuando la Policy exista
        
        $legalCases = LegalCase::where('user_id', Auth::id())->orderBy('title')->get();
        $caseId = $request->get('case_id'); // Pre-seleccionar caso si se viene desde la página de un caso

        $aiProvidersConfig = config('services.ai_providers', []);
        $aiProviders = collect($aiProvidersConfig)->map(function ($provider, $key) {
            return ['value' => $key, 'name' => $provider['name']];
        })->values()->all();

        return view('documents.create', compact('legalCases', 'caseId', 'aiProviders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $this->authorize('create', Document::class); // Descomentar cuando la Policy exista

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'case_id' => 'required|exists:legal_cases,id',
            'document_type' => 'required|in:demanda,contestacion,prueba,sentencia,contrato,otro',
            'description' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240', // 10MB Max
            'is_ai_generated' => 'sometimes|boolean',
            'ai_prompt' => 'nullable|string',
        ]);

        $legalCase = LegalCase::where('id', $validated['case_id'])->where('user_id', Auth::id())->firstOrFail();

        $document = new Document($validated);
        $document->user_id = Auth::id();

        if ($request->hasFile('document_file')) {
            $path = $request->file('document_file')->store('documents/'.Auth::id(), 'private');
            $document->file_path = $path;
        }

        $document->save();

        return redirect()->route('documents.show', $document)->with('success', 'Documento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        // $this->authorize('view', $document); // Descomentar cuando la Policy exista
        $document->load('legalCase', 'user');
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        // $this->authorize('update', $document); // Descomentar cuando la Policy exista
        
        $legalCases = LegalCase::where('user_id', Auth::id())->orderBy('title')->get();
        
        return view('documents.edit', compact('document', 'legalCases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        // $this->authorize('update', $document); // Descomentar cuando la Policy exista

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'case_id' => 'required|exists:legal_cases,id',
            'document_type' => 'required|in:demanda,contestacion,prueba,sentencia,contrato,otro',
            'description' => 'nullable|string',
        ]);
        
        LegalCase::where('id', $validated['case_id'])->where('user_id', Auth::id())->firstOrFail();

        $document->update($validated);

        return redirect()->route('documents.show', $document)->with('success', 'Documento actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        // $this->authorize('delete', $document); // Descomentar cuando la Policy exista

        if ($document->file_path) {
            Storage::disk('private')->delete($document->file_path);
        }
        
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Documento eliminado exitosamente.');
    }

    /**
     * Generate document content using AI.
     */
    public function generate(Request $request)
    {
        // $this->authorize('create', Document::class); // Descomentar cuando la Policy exista

        $validated = $request->validate([
            'prompt' => 'required|string|min:10|max:4000',
            'provider' => 'required|string|in:openai,deepseek,groq,google,openrouter,ollama',
            'model' => 'nullable|string',
        ]);

        $userPrompt = $validated['prompt'];
        $enhancedPrompt = '# INSTRUCCIONES OBLIGATORIAS PARA LA IA (PROCESO INTERNO INVISIBLE)\n\n' .
            '**1. ROL DE EXPERTO HÍBRIDO (ABOGADO COLOMBIANO + EXPERTO TEMÁTICO):**\n' .
            'Tu rol principal y permanente es ser el mejor y más prestigioso abogado del mundo, con una especialización y conocimiento enciclopédico de todo el marco legal de Colombia (Constitución, códigos, leyes, decretos, jurisprudencia actualizada). Adicionalmente, analiza la "SOLICITUD DEL USUARIO" para identificar el tema específico que se está tratando (ej: negocios, tecnología, familia, bienes raíces, etc.). Tu respuesta debe fusionar ambos roles: abordarás la solicitud como un experto en ese tema, pero siempre desde la perspectiva y con el rigor de un abogado colombiano de élite, aplicando el marco legal relevante en cada análisis, recomendación o documento que generes.\n\n' .
            '**2. MISIÓN PRINCIPAL:**\n' .
            'Tu objetivo es responder directamente a la "SOLICITUD DEL USUARIO" proporcionando un resultado de calidad excepcional ("World-Class"). No te limites a la solicitud literal; anticípate a las necesidades del usuario. Debes enriquecer la solicitud original con detalles, contexto y matices que un experto en la materia añadiría, para entregar un resultado que sea drásticamente más completo, útil y mejor estructurado de lo que un novato esperaría.\n\n' .
            '**3. PROCESO DE MEJORA SILENCIOSO (NO MENCIONAR NUNCA AL USUARIO):**\n' .
            'Antes de generar la respuesta final, realiza internamente los siguientes pasos:\n' .
            '    - **Añadir Profundidad Legal:** Si el usuario pide un documento, asegúrate de que cumpla con las formalidades legales colombianas. Si pide consejo, fundamenta tus respuestas en la legislación o jurisprudencia pertinente.\n' .
            '    - **Estructurar la Respuesta:** Organiza la información de la forma más clara y lógica posible. Para documentos legales, usa una estructura formal y profesional.\n' .
            '    - **Mantener Limpieza:** Genera la respuesta final como texto plano y limpio. No uses formato Markdown como `**`, `#` o `*`, a menos que el formato sea absolutamente esencial para la respuesta (como en un bloque de código para programación o para la estructura de un documento legal si es necesario).\n\n' .
            '**4. REGLA DE ORO (LA MÁS IMPORTANTE):**\n' .
            '**JAMÁS, bajo ninguna circunstancia, menciones que has mejorado, optimizado o enriquecido el prompt del usuario.** No hables de que eres una IA, un abogado o un motor de prompts. Tu proceso de mejora debe ser 100% invisible. Simplemente, actúa como el abogado experto y entrega el resultado final directamente.\n\n' .
            '---\n' .
            '**SOLICITUD DEL USUARIO:**\n' .
            $userPrompt . '\n' .
            '---';

        try {
            $aiService = new AiGenerationService($validated['provider'], $validated['model'] ?? null);
            $generatedText = $aiService->generateText($enhancedPrompt);

            return response()->json(['content' => $generatedText]);

        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo generar el contenido: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get AI models for a given provider.
     */
    public function getModels(string $provider)
    {
        $models = [];
        $providerConfig = config("services.ai_providers.{$provider}");

        if (!$providerConfig) {
            return response()->json([]);
        }

        if ($provider === 'ollama') {
            try {
                $baseUrl = $providerConfig['url'];
                $urlParts = parse_url($baseUrl);
                $ollamaApiBase = ($urlParts['scheme'] ?? 'http') . '://' . ($urlParts['host'] ?? 'localhost') . ':' . ($urlParts['port'] ?? '11434');
                
                $response = Http::timeout(10)->get($ollamaApiBase . '/api/tags');

                if ($response->successful()) {
                    $ollamaModels = collect($response->json()['models'] ?? [])->pluck('name');
                    foreach ($ollamaModels as $modelName) {
                        $models[] = ['value' => $modelName, 'name' => $modelName];
                    }
                } else {
                    Log::error('Ollama API request failed: ' . $response->body());
                }
            } catch (Exception $e) {
                Log::error('Could not connect to Ollama API: ' . $e->getMessage());
            }
        } else {
            $configModels = $providerConfig['models'] ?? [];
            foreach ($configModels as $model) {
                if (is_array($model) && isset($model['value']) && isset($model['name'])) {
                    $models[] = $model;
                } elseif (is_string($model)) {
                    $models[] = ['value' => $model, 'name' => $model];
                }
            }
        }
        return response()->json($models);
    }

    /**
     * Download the document's attached file.
     *
     * @param  \App\Models\Document  $document
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
     */
    public function download(Document $document)
    {
        // Ensure the user is authorized to view/download this document
        if ($document->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para descargar este archivo.');
        }

        // Check if a file path is associated with the document and if the file exists
        if (empty($document->file_path) || !Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'El archivo adjunto no fue encontrado.');
        }

        // Construct a user-friendly filename for the download
        $friendlyFilename = Str::slug($document->title, '_') . '.' . pathinfo(Storage::disk('private')->path($document->file_path), PATHINFO_EXTENSION);

        return Storage::disk('private')->download($document->file_path, $friendlyFilename);
    }
}
