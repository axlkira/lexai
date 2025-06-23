<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Caso Legal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">¡Ups! Hubo algunos problemas con tu entrada.</strong>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('legal-cases.update', $legalCase) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Columna Izquierda -->
                            <div>
                                <!-- Título -->
                                <div class="mb-4">
                                    <x-input-label for="title" :value="__('Título del Caso')" />
                                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $legalCase->title)" required autofocus />
                                </div>

                                <!-- Cliente -->
                                <div class="mb-4">
                                    <x-input-label for="client_id" :value="__('Cliente')" />
                                    <x-select-input id="client_id" name="client_id" class="block mt-1 w-full">
                                        <option value="">{{ __('Selecciona un cliente') }}</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', $legalCase->client_id) == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }} ({{ $client->email }})
                                            </option>
                                        @endforeach
                                    </x-select-input>
                                </div>

                                <!-- Estado -->
                                <div class="mb-4">
                                    <x-input-label for="status" :value="__('Estado')" />
                                    <x-select-input id="status" name="status" class="block mt-1 w-full">
                                        @foreach($statuses as $statusValue => $statusLabel)
                                            <option value="{{ $statusValue }}" {{ old('status', $legalCase->status) == $statusValue ? 'selected' : '' }}>
                                                {{ $statusLabel }}
                                            </option>
                                        @endforeach
                                    </x-select-input>
                                </div>

                                <!-- Fecha de Inicio -->
                                <div class="mb-4">
                                    <x-input-label for="start_date" :value="__('Fecha de Inicio')" />
                                    <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $legalCase->start_date->format('Y-m-d'))" />
                                </div>

                                <!-- Fecha de Cierre -->
                                <div class="mb-4">
                                    <x-input-label for="end_date" :value="__('Fecha de Cierre (Opcional)')" />
                                    <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $legalCase->end_date ? $legalCase->end_date->format('Y-m-d') : '')" />
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div>
                                <!-- Número de Proceso Judicial -->
                                <div class="mb-4">
                                    <x-input-label for="judicial_process_number" :value="__('Número de Proceso Judicial')" />
                                    <x-text-input id="judicial_process_number" class="block mt-1 w-full" type="text" name="judicial_process_number" :value="old('judicial_process_number', $legalCase->judicial_process_number)" />
                                </div>

                                <!-- Corte/Tribunal -->
                                <div class="mb-4">
                                    <x-input-label for="court" :value="__('Corte/Tribunal')" />
                                    <x-text-input id="court" class="block mt-1 w-full" type="text" name="court" :value="old('court', $legalCase->court)" />
                                </div>

                                <!-- Juez -->
                                <div class="mb-4">
                                    <x-input-label for="judge" :value="__('Juez')" />
                                    <x-text-input id="judge" class="block mt-1 w-full" type="text" name="judge" :value="old('judge', $legalCase->judge)" />
                                </div>

                                <!-- Jurisdicción -->
                                <div class="mb-4">
                                    <x-input-label for="jurisdiction" :value="__('Jurisdicción')" />
                                    <x-text-input id="jurisdiction" class="block mt-1 w-full" type="text" name="jurisdiction" :value="old('jurisdiction', $legalCase->jurisdiction)" />
                                </div>
                            </div>
                        </div>

                        <!-- Descripción y Botón IA -->
                        <div class="mt-4">
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />

                            {{-- AI Generation Section --}}
                            <div class="col-span-2 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 mt-6">
                                <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3">Asistente de IA</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <x-input-label for="ai_prompt" :value="__('Prompt para la IA')" />
                                        <x-text-input id="ai_prompt" type="text" class="mt-1 block w-full" placeholder="Ej: Redacta una descripción detallada..." />
                                    </div>
                                    <div class="self-end">
                                        <x-secondary-button type="button" id="generate-description-btn" class="w-full justify-center">
                                            <span id="ai-button-spinner" class="hidden mr-2">
                                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                            <span id="ai-button-text">Generar con IA</span>
                                        </x-secondary-button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div>
                                        <x-input-label for="ai_provider" :value="__('Proveedor IA')" />
                                        <x-select id="ai_provider" name="ai_provider" class="mt-1 block w-full">
                                            <option value="">Seleccione un proveedor</option>
                                            @if(isset($aiProviders))
                                                @foreach($aiProviders as $provider)
                                                    <option value="{{ $provider['value'] }}">{{ $provider['name'] }}</option>
                                                @endforeach
                                            @endif
                                        </x-select>
                                    </div>
                                    <div>
                                        <x-input-label for="ai_model" :value="__('Modelo IA')" />
                                        <x-select id="ai_model" name="ai_model" class="mt-1 block w-full" disabled>
                                            <option value="">Seleccione un proveedor</option>
                                        </x-select>
                                    </div>
                                </div>
                            </div>
                            <x-input-label for="description" :value="__('Descripción')" />
                            <textarea id="description" name="description" rows="10" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $legalCase->description) }}</textarea>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('legal-cases.show', $legalCase) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Actualizar Caso') }}
                            </x-primary-button>
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
            const promptTextarea = document.getElementById('ai_prompt');
            const descriptionTextarea = document.getElementById('description');
            const generateButtonSpinner = document.getElementById('ai-button-spinner');
            const generateButtonText = document.getElementById('ai-button-text');

            function setButtonLoading(isLoading) {
                generateBtn.disabled = isLoading;
                if(isLoading) {
                    generateButtonSpinner.classList.remove('hidden');
                    generateButtonText.textContent = 'Generando...';
                } else {
                    generateButtonSpinner.classList.add('hidden');
                    generateButtonText.textContent = 'Generar con IA';
                }
            }

            function updateModels(provider) {
                if (!provider) {
                    modelSelect.innerHTML = '<option value="">Seleccione un proveedor</option>';
                    modelSelect.disabled = true;
                    return;
                }

                modelSelect.innerHTML = '<option value="">Cargando modelos...</option>';
                modelSelect.disabled = true;

                let url = `{{ route('ai.models', ['provider' => ':provider']) }}`.replace(':provider', provider);

                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('Error al cargar modelos');
                        return response.json();
                    })
                    .then(models => {
                        modelSelect.innerHTML = '';
                        if (Array.isArray(models) && models.length > 0) {
                            models.forEach(model => {
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
                        console.error('Error fetching AI models:', error);
                        modelSelect.innerHTML = '<option value="">Error al cargar</option>';
                        modelSelect.disabled = false;
                    });
            }

            providerSelect.addEventListener('change', function () {
                updateModels(this.value);
            });

            if (providerSelect.value) {
                updateModels(providerSelect.value);
            }

            generateBtn.addEventListener('click', function() {
                const prompt = promptTextarea.value;
                const provider = providerSelect.value;
                const model = modelSelect.value;

                if (!prompt || !provider || !model) {
                    Swal.fire('Campos incompletos', 'Por favor, ingrese un prompt y seleccione un proveedor y modelo de IA.', 'warning');
                    return;
                }

                setButtonLoading(true);

                fetch('{{ route('legal-cases.generate-description') }}', {
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
                        return response.json().then(err => { throw new Error(err.error || 'Error del servidor') });
                    }
                    return response.json();
                })
                .then(data => {
                    if(data.description) {
                        descriptionTextarea.value = data.description;
                        Swal.fire('Éxito', 'La descripción ha sido generada y actualizada.', 'success');
                    } else {
                        throw new Error(data.error || 'Respuesta inesperada del servidor');
                    }
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
