<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Documento') }}
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

                    <form action="{{ route('documents.update', $document) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Metadatos del Documento -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <x-input-label for="title" :value="__('Título del Documento')" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $document->title)" required />
                            </div>
                            <div>
                                <x-input-label for="case_id" :value="__('Caso Legal Asociado')" />
                                <x-select-input id="case_id" name="case_id" class="block mt-1 w-full">
                                    <option value="">{{ __('Selecciona un caso') }}</option>
                                    @foreach($legalCases as $case)
                                        <option value="{{ $case->id }}" {{ old('case_id', $document->case_id) == $case->id ? 'selected' : '' }}>
                                            {{ $case->title }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                            </div>
                            <div>
                                <x-input-label for="document_type" :value="__('Tipo de Documento')" />
                                <x-select-input id="document_type" name="document_type" class="block mt-1 w-full">
                                    <option value="demanda" {{ old('document_type', $document->document_type) == 'demanda' ? 'selected' : '' }}>Demanda</option>
                                    <option value="contestacion" {{ old('document_type', $document->document_type) == 'contestacion' ? 'selected' : '' }}>Contestación</option>
                                    <option value="prueba" {{ old('document_type', $document->document_type) == 'prueba' ? 'selected' : '' }}>Prueba</option>
                                    <option value="sentencia" {{ old('document_type', $document->document_type) == 'sentencia' ? 'selected' : '' }}>Sentencia</option>
                                    <option value="contrato" {{ old('document_type', $document->document_type) == 'contrato' ? 'selected' : '' }}>Contrato</option>
                                    <option value="otro" {{ old('document_type', $document->document_type) == 'otro' ? 'selected' : '' }}>Otro</option>
                                </x-select-input>
                            </div>
                        </div>

                        <!-- Contenido del Documento (Solo lectura en la edición) -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Contenido del Documento')" />
                            <textarea id="description" name="description" rows="15" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $document->description) }}</textarea>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex items-center justify-end mt-4 space-x-4">
                            <a href="{{ route('documents.show', $document) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Actualizar Documento') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
