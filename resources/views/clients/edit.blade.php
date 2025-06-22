<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nombre -->
                        <div>
                            <x-input-label for="name" :value="__('Nombre')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $client->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Apellido -->
                        <div>
                            <x-input-label for="last_name" :value="__('Apellido')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $client->last_name)" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $client->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Tipo de Documento -->
                        <div>
                            <x-input-label for="document_type" :value="__('Tipo de Documento')" />
                            <select id="document_type" name="document_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Seleccione un tipo de documento...</option>
                                <option value="cedula_ciudadania" {{ old('document_type', $client->document_type) == 'cedula_ciudadania' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                                <option value="tarjeta_identidad" {{ old('document_type', $client->document_type) == 'tarjeta_identidad' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                                <option value="registro_civil" {{ old('document_type', $client->document_type) == 'registro_civil' ? 'selected' : '' }}>Registro Civil</option>
                                <option value="cedula_extranjeria" {{ old('document_type', $client->document_type) == 'cedula_extranjeria' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                <option value="pasaporte" {{ old('document_type', $client->document_type) == 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                <option value="pep" {{ old('document_type', $client->document_type) == 'pep' ? 'selected' : '' }}>Permiso Especial de Permanencia (PEP)</option>
                                <option value="ppt" {{ old('document_type', $client->document_type) == 'ppt' ? 'selected' : '' }}>Permiso por Protección Temporal (PPT)</option>
                            </select>
                            <x-input-error :messages="$errors->get('document_type')" class="mt-2" />
                        </div>

                        <!-- Número de Documento -->
                        <div>
                            <x-input-label for="document_number" :value="__('Número de Documento')" />
                            <x-text-input id="document_number" class="block mt-1 w-full" type="text" name="document_number" :value="old('document_number', $client->document_number)" />
                            <x-input-error :messages="$errors->get('document_number')" class="mt-2" />
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <x-input-label for="phone" :value="__('Teléfono')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $client->phone)" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Dirección -->
                        <div>
                            <x-input-label for="address" :value="__('Dirección')" />
                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address', $client->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button class="ms-4" onclick="window.history.back()">
                                {{ __('Cancelar') }}
                            </x-secondary-button>
                            <x-primary-button class="ms-4">
                                {{ __('Actualizar Cliente') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
