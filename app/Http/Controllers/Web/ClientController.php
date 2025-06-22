<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Construir la consulta base para los clientes del usuario autenticado
        $query = Auth::user()->clients();
        
        // Aplicar búsqueda si hay un término de búsqueda
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%");
            });
        }
        
        // Obtener los resultados paginados
        $clients = $query->latest()
                        ->paginate(5)
                        ->withQueryString();
        
        // Si hay búsqueda y no hay resultados, mostrar mensaje
        if ($search && $clients->isEmpty()) {
            session()->flash('info', 'No se encontraron clientes que coincidan con la búsqueda: ' . $search);
        }
            
        return view('clients.index', compact('clients', 'search'));
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
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('clients')->where('user_id', auth()->id())
            ],
            'document_type' => 'required|in:registro_civil,tarjeta_identidad,cedula_ciudadania,cedula_extranjeria,pasaporte,pep,ppt',
            'document_number' => [
                'required',
                'string',
                Rule::unique('clients')->where('user_id', auth()->id()),
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
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Ya tienes un cliente con este correo electrónico.',
            'document_type.required' => 'El tipo de documento es obligatorio.',
            'document_type.in' => 'El tipo de documento seleccionado no es válido.',
            'document_number.required' => 'El número de documento es obligatorio.',
            'document_number.unique' => 'Ya tienes un cliente con este número de documento.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no debe exceder los 20 caracteres.',
            'address.max' => 'La dirección no debe exceder los 500 caracteres.'
        ]);

        // Asociar el cliente con el usuario autenticado
        $validated['user_id'] = Auth::id();
        
        // Crear el cliente
        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente creado exitosamente.');
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
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('clients')
                    ->where('user_id', auth()->id())
                    ->ignore($client->id)
            ],
            'document_type' => 'required|in:registro_civil,tarjeta_identidad,cedula_ciudadania,cedula_extranjeria,pasaporte,pep,ppt',
            'document_number' => [
                'required',
                'string',
                Rule::unique('clients')
                    ->where('user_id', auth()->id())
                    ->ignore($client->id),
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
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Ya tienes un cliente con este correo electrónico.',
            'document_type.required' => 'El tipo de documento es obligatorio.',
            'document_type.in' => 'El tipo de documento seleccionado no es válido.',
            'document_number.required' => 'El número de documento es obligatorio.',
            'document_number.unique' => 'Ya tienes un cliente con este número de documento.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no debe exceder los 20 caracteres.',
            'address.max' => 'La dirección no debe exceder los 500 caracteres.'
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Client $client)
    {
        // Verificar que el cliente pertenezca al usuario autenticado
        if (auth()->id() !== $client->user_id) {
            abort(403, 'No tienes permiso para eliminar este cliente.');
        }
        
        try {
            $client->delete();
            return redirect()->route('clients.index')
                ->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }
}
