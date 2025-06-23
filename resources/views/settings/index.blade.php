<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajustes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-3xl">
                    <h2 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                        {{ __('Configuración Global de IA') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Configura los proveedores de IA y API Keys para usar en toda la aplicación.') }}
                    </p>

                    @if (session('success'))
                        <div class="mt-4 p-4 bg-green-100 dark:bg-green-800 text-green-900 dark:text-green-100 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <script>
                        document.addEventListener('alpine:init', () => {
                            Alpine.data('settingsData', () => ({
                                providers: @json($providers),
                                settings: @json($settings),
                                activeProvider: "{{ old('ai_provider', $settings['ai_provider'] ?? array_key_first($providers)) }}",
                                getApiKey(providerKey) {
                                    return this.settings[providerKey]?.api_key || "";
                                },
                                isModelSelected(providerKey, modelKey) {
                                    const currentModel = this.settings[providerKey]?.model;
                                    const defaultModel = Object.keys(this.providers[providerKey]?.models || {})[0];
                                    return (currentModel || defaultModel) === modelKey;
                                }
                            }))
                        });
                    </script>
                    
                    <div x-data="settingsData">
                        <form method="POST" action="{{ route('settings.store') }}">
                            @csrf

                            <!-- Selector de Proveedor Principal -->
                            <div class="mb-8">
                                <x-input-label for="ai_provider" :value="__('Proveedor de IA Activo')" />
                                <select id="ai_provider" name="ai_provider" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" x-model="activeProvider">
                                    @foreach($providers as $provKey => $provValue)
                                        <option value="{{ $provKey }}">{{ $provValue['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Configuración de cada Proveedor -->
                            @foreach($providers as $provKey => $provValue)
                                <div x-show="activeProvider === '{{ $provKey }}'" class="p-6 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $provValue['label'] }}</h3>
                                    
                                    <div class="mt-4 space-y-4">
                                        <!-- Campo para API Key (excepto Ollama) -->
                                        @if($provKey !== 'ollama')
                                            <div>
                                                <x-input-label for="api_key_{{ $provKey }}" :value="__('API Key')" />
                                                <input id="api_key_{{ $provKey }}" 
                                                       name="providers[{{ $provKey }}][api_key]" 
                                                       type="password" 
                                                       class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                                       :value="getApiKey('{{ $provKey }}')" 
                                                       autocomplete="new-password">
                                            </div>
                                        @endif

                                        <!-- Selector de Modelo -->
                                        <div>
                                            <x-input-label for="model_{{ $provKey }}" :value="__('Modelo')" />
                                            <select id="model_{{ $provKey }}" 
                                                    name="providers[{{ $provKey }}][model]" 
                                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                @foreach($provValue['models'] as $modelKey => $modelLabel)
                                                    <option value="{{ $modelKey }}" 
                                                            :selected="isModelSelected('{{ $provKey }}', '{{ $modelKey }}')">{{ $modelLabel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-8 flex items-center gap-4">
                                <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
