<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles del Caso: ') }} {{ $caso->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Nombre del Caso') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $caso->nombre }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Número de Radicado') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $caso->numero_radicado ?? '--' }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Fecha de Apertura') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($caso->fecha_apertura)->isoFormat('D [de] MMMM [de] YYYY') }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Estado') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
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
                        </p>
                    </div>

                    @if($caso->descripcion)
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Descripción') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $caso->descripcion }}</p>
                    </div>
                    @endif

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('casos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Volver a la Lista') }}
                        </a>
                        <a href="{{ route('casos.edit', $caso) }}" class="ms-3 inline-flex items-center px-4 py-2 bg-blue-500 dark:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-red-700 focus:bg-blue-700 dark:focus:bg-red-700 active:bg-blue-800 dark:active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Editar Caso') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
