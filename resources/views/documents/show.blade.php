<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Documento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Metadatos del Documento -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $document->title }}</h3>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600 dark:text-gray-400">
                            <div>
                                <span class="font-semibold">Caso Asociado:</span>
                                <a href="{{ route('legal-cases.show', $document->legalCase) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $document->legalCase->title }}</a>
                            </div>
                            <div>
                                <span class="font-semibold">Tipo de Documento:</span> {{ ucfirst($document->document_type) }}
                            </div>
                            <div>
                                <span class="font-semibold">Fecha de Creación:</span> {{ $document->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        @if($document->is_ai_generated)
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                    Generado con IA
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Contenido del Documento -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Contenido:</h4>
                        <div class="prose dark:prose-invert max-w-none p-4 border border-gray-200 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-900/50">
                            @if($document->description)
                                <p class="whitespace-pre-wrap">{{ $document->description }}</p>
                            @else
                                <p class="text-gray-500">No hay contenido o descripción disponible.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Archivo Adjunto -->
                    @if($document->file_path)
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Archivo Adjunto:</h4>
                            {{-- Aquí se necesitaría una ruta para descargar el archivo de forma segura --}}
                            {{-- Por ejemplo: <a href="{{ route('documents.download', $document) }}">Descargar Archivo</a> --}}
                            <p class="text-sm text-gray-500">Hay un archivo adjunto. La funcionalidad de descarga necesita ser implementada.</p>
                        </div>
                    @endif

                    <!-- Botones de Acción -->
                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <a href="{{ route('documents.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            {{ __('Volver') }}
                        </a>
                        @if($document->file_path)
                            <a href="{{ route('documents.download', $document->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <i class="fas fa-download mr-2"></i>Descargar Adjunto
                            </a>
                        @endif
                        <a href="{{ route('documents.edit', $document->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Editar') }}
                        </a>
                        <form id="delete-form-{{ $document->id }}" action="{{ route('documents.destroy', $document) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete('{{ $document->id }}')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
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
</x-app-layout>
