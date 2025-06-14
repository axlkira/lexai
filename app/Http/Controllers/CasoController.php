<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use Illuminate\Http\Request;

class CasoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $casos = auth()->user()->casos()->latest()->get();
        return view('casos.index', compact('casos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('casos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_radicado' => 'nullable|string|max:255|unique:casos,numero_radicado',
            'fecha_apertura' => 'required|date',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string|in:Activo,Pendiente,En Proceso,Archivado,Cerrado,Suspendido',
        ]);

        $request->user()->casos()->create([
            'nombre' => $validatedData['nombre'],
            'numero_radicado' => $validatedData['numero_radicado'],
            'fecha_apertura' => $validatedData['fecha_apertura'],
            'descripcion' => $validatedData['descripcion'],
            'estado' => $validatedData['estado'],
        ]);

        return redirect()->route('casos.index')->with('success', 'Caso creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Caso $caso)
    {
        return view('casos.show', compact('caso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Caso $caso)
    {
        return view('casos.edit', compact('caso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caso $caso)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_radicado' => 'nullable|string|max:255|unique:casos,numero_radicado,' . $caso->id,
            'fecha_apertura' => 'required|date',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string|in:Activo,Pendiente,En Proceso,Archivado,Cerrado,Suspendido',
        ]);

        $caso->update([
            'nombre' => $validatedData['nombre'],
            'numero_radicado' => $validatedData['numero_radicado'],
            'fecha_apertura' => $validatedData['fecha_apertura'],
            'descripcion' => $validatedData['descripcion'],
            'estado' => $validatedData['estado'],
        ]);

        return redirect()->route('casos.show', $caso)->with('success', 'Caso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caso $caso)
    {
        $caso->delete();
        return redirect()->route('casos.index')->with('success', 'Caso eliminado exitosamente.');
    }
}
