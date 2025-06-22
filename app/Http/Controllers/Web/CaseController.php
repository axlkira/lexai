<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cases = Auth::user()->legalCases()->latest()->paginate(10);
        return view('cases.index', compact('cases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = User::where('user_type', 'client')->get();
        return view('cases.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_number' => 'required|unique:legal_cases,case_number',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'client_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'judicial_process_number' => 'nullable|string|max:255',
            'court' => 'nullable|string|max:255',
            'judge' => 'nullable|string|max:255',
            'jurisdiction' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        LegalCase::create($validated);

        return redirect()->route('cases.index')
            ->with('success', 'Caso creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LegalCase $case)
    {
        $this->authorize('view', $case);
        return view('cases.show', compact('case'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LegalCase $case)
    {
        $this->authorize('update', $case);
        $clients = User::where('user_type', 'client')->get();
        return view('cases.edit', compact('case', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LegalCase $case)
    {
        $this->authorize('update', $case);

        $validated = $request->validate([
            'case_number' => 'required|unique:legal_cases,case_number,' . $case->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'client_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'judicial_process_number' => 'nullable|string|max:255',
            'court' => 'nullable|string|max:255',
            'judge' => 'nullable|string|max:255',
            'jurisdiction' => 'nullable|string|max:255',
        ]);

        $case->update($validated);

        return redirect()->route('cases.index')
            ->with('success', 'Caso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LegalCase $case)
    {
        $this->authorize('delete', $case);
        
        $case->delete();

        return redirect()->route('cases.index')
            ->with('success', 'Caso eliminado exitosamente.');
    }
}
