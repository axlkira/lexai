<?php

namespace App\Http\Controllers\Api;

use App\Models\LegalCase;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LegalCaseController extends Controller
{
    public function index()
    {
        // Obtener solo los casos del abogado autenticado
        $cases = LegalCase::with(['client', 'lawyer'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return response()->json($cases);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'judicial_process_number' => 'nullable|string',
        ]);

        // Verificar que el cliente pertenezca al abogado autenticado
        $client = Client::where('id', $request->client_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $case = LegalCase::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'client_id' => $request->client_id,
            'case_number' => 'CASE-' . uniqid(),
            'start_date' => now(),
            'status' => 'abierto',
            'judicial_process_number' => $request->judicial_process_number,
        ]);

        return response()->json($case->load(['client', 'lawyer']), 201);
    }

    public function show(LegalCase $case)
    {
        $this->authorize('view', $case);
        return response()->json($case->load(['client', 'lawyer', 'documents']));
    }

    public function update(Request $request, LegalCase $case)
    {
        $this->authorize('update', $case);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:abierto,en_progreso,cerrado,archivado',
            'client_id' => 'required|exists:clients,id',
            'judicial_process_number' => 'nullable|string',
        ]);

        // Verificar que el cliente pertenezca al abogado autenticado
        if ($case->client_id != $request->client_id) {
            $client = Client::where('id', $request->client_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        }

        $case->update($request->all());
        return response()->json($case->load(['client', 'lawyer']));
    }

    public function destroy(LegalCase $case)
    {
        $this->authorize('delete', $case);
        $case->delete();
        return response()->json(null, 204);
    }
}