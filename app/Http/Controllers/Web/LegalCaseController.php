<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use App\Models\Client;
use Illuminate\Http\Request;
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
            
        return view('legal-cases.create', compact('clients'));
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
        
        // Cargar relaciones necesarias
        $legalCase->load(['client', 'documents', 'lawyer']);
        
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
            
        return view('legal-cases.edit', compact('legalCase', 'clients'));
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
    public function destroy(LegalCase $legalCase)
    {
        $this->authorize('delete', $legalCase);
        
        $legalCase->delete();
        
        return redirect()->route('legal-cases.index')
            ->with('success', 'Caso legal eliminado exitosamente.');
    }
}
