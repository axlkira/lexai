<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Documento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">¡Ups! Hubo algunos problemas.</strong>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Metadatos del Documento -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <x-input-label for="title" :value="__('Título del Documento')" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required />
                            </div>
                            <div>
                                <x-input-label for="case_id" :value="__('Caso Legal Asociado')" />
                                <x-select-input id="case_id" name="case_id" class="block mt-1 w-full">
                                    <option value="">{{ __('Selecciona un caso') }}</option>
                                    @foreach($legalCases as $case)
                                        <option value="{{ $case->id }}" {{ old('case_id', $caseId ?? '') == $case->id ? 'selected' : '' }}>
                                            {{ $case->title }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                            </div>
                            <div>
                                <x-input-label for="document_type" :value="__('Tipo de Documento')" />
                                <x-select-input id="document_type" name="document_type" class="block mt-1 w-full">
                                    <option value="demanda">Demanda</option>
                                    <option value="contestacion">Contestación</option>
                                    <option value="prueba">Prueba</option>
                                    <option value="sentencia">Sentencia</option>
                                    <option value="contrato">Contrato</option>
                                    <option value="otro">Otro</option>
                                </x-select-input>
                            </div>
                        </div>

                        <!-- Generación con IA -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Generar con IA</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Describe el documento que necesitas. Sé lo más detallado posible.</p>
                            
                            <div class="mb-4">
                                <x-input-label for="ai_prompt" :value="__('Prompt para la IA')" />
                                <textarea id="ai_prompt" name="ai_prompt" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('ai_prompt') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                                <div>
                                    <x-input-label for="ai_provider" :value="__('Proveedor de IA')" />
                                    <x-select-input id="ai_provider" name="ai_provider" class="block mt-1 w-full">
                                        <option value="">Seleccione un proveedor</option>
                                        @if(isset($aiProviders))
                                            @foreach($aiProviders as $provider)
                                                <option value="{{ $provider['value'] }}">{{ $provider['name'] }}</option>
                                            @endforeach
                                        @endif
                                    </x-select-input>
                                </div>
                                <div>
                                    <x-input-label for="ai_model" :value="__('Modelo')" />
                                    <x-select-input id="ai_model" name="ai_model" class="block mt-1 w-full">
                                        <!-- Opciones se cargarán dinámicamente -->
                                    </x-select-input>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" id="generate-btn" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        <span id="btn-text">Generar Contenido</span>
                                        <div id="loading-spinner" class="hidden animate-spin ml-2 h-4 w-4 text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido del Documento -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Contenido del Documento')" />
                            <textarea id="description" name="description" rows="20" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <input type="hidden" name="is_ai_generated" id="is_ai_generated" value="0">
                        </div>

                        <!-- Subida de Archivo Alternativa -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">O Subir un Archivo Existente</h3>
                             <x-input-label for="document_file" :value="__('Archivo (PDF, Word, TXT)')" />
                             <input id="document_file" name="document_file" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mt-1"/>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end mt-4 space-x-4">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Guardar Documento') }}
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
        const generateBtn = document.getElementById('generate-btn');
        const btnText = document.getElementById('btn-text');
        const loadingSpinner = document.getElementById('loading-spinner');
        const promptTextarea = document.getElementById('ai_prompt');
        const contentTextarea = document.getElementById('description');
        const isAiGeneratedInput = document.getElementById('is_ai_generated');

        function setButtonLoading(isLoading) {
            generateBtn.disabled = isLoading;
            if (isLoading) {
                btnText.textContent = 'Generando...';
                loadingSpinner.classList.remove('hidden');
            } else {
                btnText.textContent = 'Generar Contenido';
                loadingSpinner.classList.add('hidden');
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
                    console.error('Error fetching models:', error);
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

        generateBtn.addEventListener('click', function () {
            const prompt = promptTextarea.value;
            const provider = providerSelect.value;
            const model = modelSelect.value;

            if (!prompt || !provider || !model) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos Requeridos',
                    text: 'Por favor, complete el prompt, seleccione un proveedor y un modelo.',
                });
                return;
            }

            setButtonLoading(true);
            contentTextarea.disabled = true;

            fetch('{{ route('documents.generate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    prompt: prompt,
                    provider: provider,
                    model: model
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || 'Error del servidor') });
                }
                return response.json();
            })
            .then(data => {
                if (data.content) {
                    contentTextarea.value = data.content;
                    isAiGeneratedInput.value = '1';
                    Swal.fire('Éxito', 'El contenido del documento ha sido generado.', 'success');
                } else {
                    throw new Error(data.error || 'Respuesta inesperada del servidor');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo generar el contenido: ' + error.message, 'error');
            })
            .finally(() => {
                setButtonLoading(false);
                contentTextarea.disabled = false;
            });
        });
    });
</script>
@endpush
</x-app-layout>
