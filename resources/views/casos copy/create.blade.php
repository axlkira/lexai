<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Caso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('casos.store') }}">
                        @csrf

                        <!-- Nombre del Caso -->
                        <div>
                            <x-input-label for="nombre" :value="__('Nombre del Caso')" />
                            <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        <!-- Número de Radicado -->
                        <div class="mt-4">
                            <x-input-label for="numero_radicado" :value="__('Número de Radicado (Opcional)')" />
                            <x-text-input id="numero_radicado" class="block mt-1 w-full" type="text" name="numero_radicado" :value="old('numero_radicado')" />
                            <x-input-error :messages="$errors->get('numero_radicado')" class="mt-2" />
                        </div>

                        <!-- Fecha de Apertura -->
                        <div class="mt-4">
                            <x-input-label for="fecha_apertura" :value="__('Fecha de Apertura')" />
                            <x-text-input id="fecha_apertura" class="block mt-1 w-full" type="date" name="fecha_apertura" :value="old('fecha_apertura')" required />
                            <x-input-error :messages="$errors->get('fecha_apertura')" class="mt-2" />
                        </div>

                        <!-- Descripción -->
                        <div class="mt-4">
                            <x-input-label for="descripcion" :value="__('Descripción (Opcional)')" />
                            <textarea id="descripcion" name="descripcion" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 rounded-md shadow-sm">{{ old('descripcion') }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>

                        <!-- Estado del Caso -->
                        <div class="mt-4">
                            <x-input-label for="estado" :value="__('Estado del Caso')" />
                            <select id="estado" name="estado" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 rounded-md shadow-sm">
                                <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>{{ __('Activo') }}</option>
                                <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>{{ __('Pendiente') }}</option>
                                <option value="En Proceso" {{ old('estado') == 'En Proceso' ? 'selected' : '' }}>{{ __('En Proceso') }}</option>
                                <option value="Archivado" {{ old('estado') == 'Archivado' ? 'selected' : '' }}>{{ __('Archivado') }}</option>
                                <option value="Cerrado" {{ old('estado') == 'Cerrado' ? 'selected' : '' }}>{{ __('Cerrado') }}</option>
                                <option value="Suspendido" {{ old('estado') == 'Suspendido' ? 'selected' : '' }}>{{ __('Suspendido') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Guardar Caso') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
