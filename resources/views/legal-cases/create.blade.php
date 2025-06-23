<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nuevo Caso Legal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Botón de volver -->
                    <div class="mb-6">
                        <a href="{{ route('legal-cases.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver a la lista
                        </a>
                    </div>

                    <!-- Formulario -->
                    <form action="{{ route('legal-cases.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Cliente -->
                            <div class="md:col-span-2">
                                <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente <span class="text-red-500">*</span></label>
                                <select id="client_id" name="client_id" required
                                    class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }} {{ $client->last_name }} ({{ $client->document_type }} {{ $client->document_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Título -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título del caso <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                    class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Número de proceso judicial -->
                            <div class="md:col-span-2">
                                <label for="judicial_process_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de Proceso Judicial</label>
                                <input type="text" id="judicial_process_number" name="judicial_process_number" value="{{ old('judicial_process_number') }}"
                                    class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('judicial_process_number')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Juzgado -->
                            <div>
                                <label for="court" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Juzgado</label>
                                <input type="text" id="court" name="court" value="{{ old('court') }}"
                                    class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('court')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Juez -->
                            <div>
                                <label for="judge" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Juez</label>
                                <input type="text" id="judge" name="judge" value="{{ old('judge') }}"
                                    class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('judge')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jurisdicción -->
                            <div class="md:col-span-2">
                                <label for="jurisdiction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jurisdicción</label>
                                <input type="text" id="jurisdiction" name="jurisdiction" value="{{ old('jurisdiction') }}"
                                    class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                @error('jurisdiction')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- AI Generation Section -->
                            <div class="md:col-span-2 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4">Generar con IA</h3>

                                <!-- Prompt for AI -->
                                <div>
                                    <label for="ai_prompt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prompt para la IA</label>
                                    <textarea id="ai_prompt" name="ai_prompt" rows="3" placeholder="Describe el caso o la descripción que necesitas..." class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"></textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <!-- AI Provider -->
                                    <div>
                                        <label for="ai_provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Proveedor de IA</label>
                                        <select id="ai_provider" name="ai_provider" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                            <option value="">Seleccionar Proveedor</option>
                                            @foreach($aiProviders as $provider)
                                                <option value="{{ $provider['value'] }}">{{ $provider['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- AI Model -->
                                    <div>
                                        <label for="ai_model" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Modelo</label>
                                        <select id="ai_model" name="ai_model" disabled class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                            <option value="">Seleccione un proveedor</option>
                                        </select>
                                    </div>

                                    <!-- Generate Button -->
                                    <div class="self-end">
                                        <button type="button" id="generate-description-btn" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <span id="btn-text">Generar Descripción</span>
                                            <div id="ai-button-spinner" class="hidden w-4 h-4 ml-2 border-t-2 border-b-2 border-white rounded-full animate-spin"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                                <textarea id="description" name="description" rows="8" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('legal-cases.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Guardar Caso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const providerSelect = document.getElementById('ai_provider');
    const modelSelect = document.getElementById('ai_model');
    const generateBtn = document.getElementById('generate-description-btn');
    const btnText = document.getElementById('btn-text');
    const loadingSpinner = document.getElementById('ai-button-spinner');
    const promptTextarea = document.getElementById('ai_prompt');
    const descriptionTextarea = document.getElementById('description');

    const modelsUrl = '{{ route("ai.models", ["provider" => "PROVIDER_PLACEHOLDER"]) }}';
    const generateUrl = '{{ route("legal-cases.generate-description") }}';

    function setButtonLoading(isLoading) {
        generateBtn.disabled = isLoading;
        if (isLoading) {
            btnText.textContent = 'Generando...';
            loadingSpinner.classList.remove('hidden');
        } else {
            btnText.textContent = 'Generar Descripción';
            loadingSpinner.classList.add('hidden');
        }
    }

    function updateModels() {
        const provider = providerSelect.value;
        modelSelect.innerHTML = '<option value="">Cargando...</option>';
        modelSelect.disabled = true;

        if (!provider) {
            modelSelect.innerHTML = '<option value="">Seleccione un proveedor</option>';
            modelSelect.disabled = true;
            return;
        }

        fetch(modelsUrl.replace('PROVIDER_PLACEHOLDER', provider))
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error del servidor: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                modelSelect.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(model => {
                        const option = document.createElement('option');
                        option.value = model.value;
                        option.textContent = model.name;
                        modelSelect.appendChild(option);
                    });
                } else {
                    modelSelect.innerHTML = '<option value="">No hay modelos disponibles</option>';
                }
                modelSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching models:', error);
                Swal.fire('Error', 'No se pudieron cargar los modelos de IA. ' + error.message, 'error');
                modelSelect.innerHTML = '<option value="">Error al cargar</option>';
                modelSelect.disabled = false;
            });
    }

    providerSelect.addEventListener('change', updateModels);

    if (providerSelect.value) {
        updateModels();
    } else {
        modelSelect.innerHTML = '<option value="">Seleccione un proveedor</option>';
        modelSelect.disabled = true;
    }

    generateBtn.addEventListener('click', function () {
        const prompt = promptTextarea.value;
        const provider = providerSelect.value;
        const model = modelSelect.value;

        if (!prompt || !provider || !model) {
            Swal.fire('Campos incompletos', 'Por favor, complete el prompt, seleccione proveedor y modelo.', 'warning');
            return;
        }

        setButtonLoading(true);

        fetch(generateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ prompt, provider, model })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.message || 'Error del servidor'); });
            }
            return response.json();
        })
        .then(data => {
            descriptionTextarea.value = data.description;
            Swal.fire('Éxito', 'La descripción ha sido generada.', 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudo generar la descripción: ' + error.message, 'error');
        })
        .finally(() => {
            setButtonLoading(false);
        });
    });
});
</script>
@endpush
</x-app-layout>
