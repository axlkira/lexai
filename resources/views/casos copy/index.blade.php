<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Casos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('casos.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 dark:bg-primary-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 dark:hover:bg-primary-800 focus:bg-primary-700 dark:focus:bg-primary-600 active:bg-primary-900 dark:active:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Crear Nuevo Caso') }}
                        </a>
                    </div>
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 border border-green-400 dark:border-green-600 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($casos->isEmpty())
                        <p>{{ __('Aún no tienes casos registrados.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Nombre del Caso') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Nº Radicado') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Fecha Apertura') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Estado') }}
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">{{ __('Acciones') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($casos as $caso)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $caso->nombre }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $caso->numero_radicado ?? '--' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($caso->fecha_apertura)->isoFormat('D [de] MMMM [de] YYYY') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <span @class([
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $caso->estado === 'Activo',
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' => $caso->estado === 'Pendiente',
                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' => $caso->estado === 'En Proceso',
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $caso->estado === 'Archivado',
                                                    'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' => $caso->estado === 'Cerrado',
                                                    'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300' => $caso->estado === 'Suspendido',
                                                ])>
                                                    {{ $caso->estado }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('casos.show', $caso) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200">{{ __('Ver') }}</a>
                                                <form action="{{ route('casos.destroy', $caso) }}" method="POST" class="inline-block ml-2 delete-caso-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200">{{ __('Eliminar') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-caso-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevenir el envío normal del formulario
                const currentForm = this; // Guardar referencia al formulario actual

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6', // Azul estándar, podemos ajustar a "Legal Calm"
                    cancelButtonColor: '#d33',    // Rojo estándar
                    confirmButtonText: 'Sí, ¡eliminar!',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 mr-2',
                        cancelButton: 'px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700'
                    },
                    buttonsStyling: false // Deshabilitar estilos por defecto para usar los de Tailwind vía customClass
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, enviar el formulario
                        currentForm.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

</x-app-layout>
