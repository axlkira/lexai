<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Muestra la página de ajustes.
     *
     * @return \Illuminate\View\View
     */
        public function index()
    {
        $providers = config('ai.providers');
        $dbSettings = Setting::all()->pluck('value', 'key');

        $settings = [];
        $settings['ai_provider'] = $dbSettings->get('ai_provider', config('ai.default'));

        foreach ($providers as $providerKey => $providerConfig) {
            $apiKey = $dbSettings->get("{$providerKey}_api_key");
            $decryptedApiKey = '';
            if ($apiKey) {
                try {
                    $decryptedApiKey = decrypt($apiKey);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    // Clave inválida o no encriptada, tratar como vacía.
                    $decryptedApiKey = '';
                }
            }

            $settings[$providerKey] = [
                'api_key' => $decryptedApiKey,
                'model' => $dbSettings->get("{$providerKey}_model", array_key_first($providerConfig['models'] ?? [])),
            ];
        }

        return view('settings.index', compact('providers', 'settings'));
    }

    /**
     * Guarda los ajustes actualizados.
     *
     */
    public function store(Request $request)
    {
        $providersConfig = config('ai.providers');
        $providerKeys = array_keys($providersConfig);

        $rules = [
            'ai_provider' => ['required', 'string', Rule::in($providerKeys)],
            'providers' => ['required', 'array'],
        ];

        foreach ($request->input('providers', []) as $providerKey => $data) {
            if (isset($providersConfig[$providerKey])) {
                $rules["providers.{$providerKey}.api_key"] = ['nullable', 'string', 'max:1000'];
                $rules["providers.{$providerKey}.model"] = ['required', 'string', Rule::in(array_keys($providersConfig[$providerKey]['models'] ?? []))];
            }
        }

        $validated = $request->validate($rules);

        // Guardar el proveedor principal
        Setting::updateOrCreate(
            ['key' => 'ai_provider'],
            ['value' => $validated['ai_provider']]
        );

        // Guardar la configuración de cada proveedor
        foreach ($validated['providers'] as $providerKey => $providerData) {
            if (!in_array($providerKey, $providerKeys)) {
                continue;
            }

            // Actualizar la API Key solo si se proporciona un nuevo valor
            if (!empty($providerData['api_key'])) {
                Setting::updateOrCreate(
                    ['key' => "{$providerKey}_api_key"],
                    ['value' => encrypt($providerData['api_key'])]
                );
            }

            // Actualizar el modelo
            if (isset($providerData['model'])) {
                Setting::updateOrCreate(
                    ['key' => "{$providerKey}_model"],
                    ['value' => $providerData['model']]
                );
            }
        }

        return back()->with('success', 'La configuración se ha guardado correctamente.');
    }
}
