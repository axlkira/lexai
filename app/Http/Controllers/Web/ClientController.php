<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        // Solo mostrar los clientes del usuario autenticado
        $query = Auth::user()->clients();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('document_number', 'like', "%{$searchTerm}%");
            });
        }

        $clients = $query->latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email',
            'document_type' => 'nullable|in:registro_civil,tarjeta_identidad,cedula_ciudadania,cedula_extranjeria,pasaporte,pep,ppt',
            'document_number' => [
                'nullable',
                'string',
                'unique:clients,document_number',
                function ($attribute, $value, $fail) use ($request) {
                    // Validar formato según el tipo de documento
                    $documentType = $request->input('document_type');
                    
                    if ($documentType === 'cedula_ciudadania' && !preg_match('/^[1-9][0-9]{6,10}$/', $value)) {
                        $fail('El número de cédula de ciudadanía debe contener entre 7 y 11 dígitos numéricos.');
                    } elseif ($documentType === 'tarjeta_identidad' && !preg_match('/^[1-9][0-9]{5,10}$/', $value)) {
                        $fail('El número de tarjeta de identidad debe contener entre 6 y 11 dígitos numéricos.');
                    } elseif ($documentType === 'registro_civil' && !preg_match('/^[A-Za-z0-9-]+$/', $value)) {
                        $fail('El formato del registro civil no es válido.');
                    } elseif ($documentType === 'cedula_extranjeria' && !preg_match('/^[a-zA-Z0-9]{5,20}$/', $value)) {
                        $fail('El formato de la cédula de extranjería no es válido.');
                    } elseif ($documentType === 'pasaporte' && !preg_match('/^[a-zA-Z0-9]{6,12}$/', $value)) {
                        $fail('El formato del pasaporte no es válido.');
                    } elseif (in_array($documentType, ['pep', 'ppt']) && !preg_match('/^[a-zA-Z0-9-]+$/', $value)) {
                        $fail('El formato del documento no es válido.');
                    }
                },
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Asociar el cliente con el usuario autenticado
        $validated['user_id'] = Auth::id();
        
        // Crear el cliente
        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function show(Client $client)
    {
        // Asegurarse de que el cliente pertenezca al usuario autenticado
        $this->authorize('view', $client);
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        // Depuración temporal
        \Log::info('Usuario autenticado ID: ' . auth()->id());
        \Log::info('Cliente user_id: ' . $client->user_id);
        \Log::info('Cliente: ' . $client->toJson());
        
        // TEMPORAL: Permitir acceso a todos los usuarios autenticados
        if (!auth()->check()) {
            abort(403, 'Debes iniciar sesión');
        }
        
        // Asegurarse de que el cliente pertenezca al usuario autenticado
        if (auth()->id() !== $client->user_id) {
            \Log::error('Acceso denegado para el usuario ' . auth()->id() . ' al cliente ' . $client->id . ' (dueño: ' . $client->user_id . ')');
            abort(403, 'No tienes permiso para editar este cliente. Usuario actual: ' . auth()->id() . ', Dueño: ' . $client->user_id);
        }
        
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        // Verificar autenticación
        if (!auth()->check()) {
            abort(403, 'Debes iniciar sesión');
        }
        
        // Verificar que el usuario sea el dueño del cliente
        if (auth()->id() !== $client->user_id) {
            \Log::error('Intento de actualización no autorizado. Usuario: ' . auth()->id() . ', Dueño: ' . $client->user_id);
            abort(403, 'No tienes permiso para actualizar este cliente.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $client->id,
            'document_type' => 'nullable|in:registro_civil,tarjeta_identidad,cedula_ciudadania,cedula_extranjeria,pasaporte,pep,ppt',
            'document_number' => [
                'nullable',
                'string',
                'unique:clients,document_number,' . $client->id,
                function ($attribute, $value, $fail) use ($request) {
                    // Validar formato según el tipo de documento
                    $documentType = $request->input('document_type');
                    
                    if (empty($value)) return;
                    
                    if ($documentType === 'cedula_ciudadania' && !preg_match('/^[1-9][0-9]{6,10}$/', $value)) {
                        $fail('El número de cédula de ciudadanía debe contener entre 7 y 11 dígitos numéricos.');
                    } elseif ($documentType === 'tarjeta_identidad' && !preg_match('/^[1-9][0-9]{5,10}$/', $value)) {
                        $fail('El número de tarjeta de identidad debe contener entre 6 y 11 dígitos numéricos.');
                    } elseif ($documentType === 'registro_civil' && !preg_match('/^[A-Za-z0-9-]+$/', $value)) {
                        $fail('El formato del registro civil no es válido.');
                    } elseif ($documentType === 'cedula_extranjeria' && !preg_match('/^[a-zA-Z0-9]{5,20}$/', $value)) {
                        $fail('El formato de la cédula de extranjería no es válido.');
                    } elseif ($documentType === 'pasaporte' && !preg_match('/^[a-zA-Z0-9]{6,12}$/', $value)) {
                        $fail('El formato del pasaporte no es válido.');
                    } elseif (in_array($documentType, ['pep', 'ppt']) && !preg_match('/^[a-zA-Z0-9-]+$/', $value)) {
                        $fail('El formato del documento no es válido.');
                    }
                },
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Client $client)
    {
        // Asegurarse de que el cliente pertenezca al usuario autenticado
        $this->authorize('delete', $client);
        
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
