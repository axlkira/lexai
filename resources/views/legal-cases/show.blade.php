<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Caso Legal') }}: {{ $legalCase->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Columna Izquierda: Información del Caso -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">Información del Caso</h3>
                            <div>
                                <p class="font-semibold">{{ __('Número de Caso') }}</p>
                                <p>{{ $legalCase->case_number }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">{{ __('Título') }}</p>
                                <p>{{ $legalCase->title }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">{{ __('Estado') }}</p>
                                <p><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $legalCase->status === 'abierto' ? 'bg-green-100 text-green-800' : ($legalCase->status === 'cerrado' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($legalCase->status) }}</span></p>
                            </div>
                            <div>
                                <p class="font-semibold">{{ __('Fecha de Inicio') }}</p>
                                <p>{{ $legalCase->start_date->format('d/m/Y') }}</p>
                            </div>
                            @if($legalCase->end_date)
                                <div>
                                    <p class="font-semibold">{{ __('Fecha de Cierre') }}</p>
                                    <p>{{ $legalCase->end_date->format('d/m/Y') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Columna Derecha: Información Judicial -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">Información Judicial</h3>
                            <div>
                                <p class="font-semibold">{{ __('Número de Proceso Judicial') }}</p>
                                <p>{{ $legalCase->judicial_process_number ?? 'No especificado' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">{{ __('Corte/Tribunal') }}</p>
                                <p>{{ $legalCase->court ?? 'No especificado' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">{{ __('Juez') }}</p>
                                <p>{{ $legalCase->judge ?? 'No especificado' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold">{{ __('Jurisdicción') }}</p>
                                <p>{{ $legalCase->jurisdiction ?? 'No especificado' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">Descripción</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $legalCase->description }}</p>
                    </div>

                    <!-- Partes Involucradas -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">Partes Involucradas</h3>
                        <p><strong>{{ __('Cliente') }}:</strong> {{ $legalCase->client->name ?? 'Cliente no asignado' }} ({{ $legalCase->client->email ?? '' }})</p>
                        <p><strong>{{ __('Abogado Asignado') }}:</strong> {{ $legalCase->lawyer->name ?? 'Abogado no asignado' }}</p>
                    </div>

                    <!-- Documentos -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 pb-2">Documentos Asociados</h3>
                        @if($legalCase->documents->isNotEmpty())
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                @foreach($legalCase->documents as $document)
                                    <li>{{ $document->title }} - <a href="{{-- Idealmente, aquí iría una ruta para descargar/ver el documento --}}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Ver/Descargar</a></li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-2">No hay documentos asociados a este caso.</p>
                        @endif
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('legal-cases.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Volver') }}
                        </a>
                        <a href="{{ route('legal-cases.edit', $legalCase) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Editar') }}
                        </a>
                        <form id="delete-form-{{ $legalCase->id }}" action="{{ route('legal-cases.destroy', $legalCase) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete('{{ $legalCase->id }}')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Eliminar') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡eliminar!',
                cancelButtonText: 'Cancelar',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#111827'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    @endpush
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $downloadableDocuments = $legalCase->documents->filter(function ($document) {
                    return !empty($document->file_path);
                });
            @endphp

            {{-- Documents Section --}}
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Documentos Asociados
                </h3>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($downloadableDocuments as $document)
                            <li class="px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-gray-800 dark:text-gray-200">{{ $document->title }}</span>
                                </div>
                                <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Descargar
                                </a>
                            </li>
                        @empty
                            <li class="px-6 py-4">
                                <p class="text-center text-gray-500 dark:text-gray-400">No hay documentos asociados.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
