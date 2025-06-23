<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AiGenerationService
{
    protected $provider;
    protected $apiKey;
    protected $model;
    protected $baseUrl;

    public function __construct(string $provider, string $model = null)
    {
        $this->provider = strtolower($provider);
        $this->model = $model;
        $this->configureProvider();
    }

    private function configureProvider()
    {
        $config = config('services.ai_providers.' . $this->provider);

        if (!$config) {
            throw new Exception("Proveedor de IA '{$this->provider}' no configurado.");
        }

        $this->apiKey = $config['api_key'];
        $this->baseUrl = $config['url'];
        $this->model = $this->model ?? $config['default_model'];
    }

    public function generateText(string $prompt): string
    {
        try {
            switch ($this->provider) {
                case 'openai':
                    return $this->generateWithOpenAI($prompt);
                case 'deepseek':
                    return $this->generateWithDeepSeek($prompt);
                case 'groq':
                    return $this->generateWithGroq($prompt);
                case 'google':
                    return $this->generateWithGoogle($prompt);
                case 'openrouter':
                    return $this->generateWithOpenRouter($prompt);
                case 'ollama':
                    return $this->generateWithOllama($prompt);
                default:
                    throw new Exception("Proveedor de IA '{$this->provider}' no soportado.");
            }
        } catch (Exception $e) {
            Log::error("Error de generación de IA ({$this->provider}): " . $e->getMessage());
            throw $e; // Re-lanzar para que el controlador pueda manejarlo
        }
    }

    private function buildRequest(string $prompt)
    {
        $client = Http::withToken($this->apiKey)
            ->accept('application/json')
            ->timeout(120); // 120 segundos de timeout

        if ($this->provider === 'openrouter') {
            $client->withHeaders(['HTTP-Referer' => config('app.url')]);
        }

        return $client;
    }

    private function generateWithOpenAI(string $prompt): string
    {
        $response = $this->buildRequest($prompt)->post($this->baseUrl, [
            'model' => $this->model,
            'messages' => [['role' => 'user', 'content' => $prompt]],
        ]);

        $response->throw();
        return $response->json('choices.0.message.content');
    }

    private function generateWithDeepSeek(string $prompt): string
    {
        return $this->generateWithOpenAI($prompt); // DeepSeek usa la misma API que OpenAI
    }

    private function generateWithGroq(string $prompt): string
    {
        return $this->generateWithOpenAI($prompt); // Groq usa la misma API que OpenAI
    }

    private function generateWithOpenRouter(string $prompt): string
    {
         $response = $this->buildRequest($prompt)->post($this->baseUrl, [
            'model' => $this->model,
            'messages' => [['role' => 'user', 'content' => $prompt]],
        ]);

        $response->throw();
        return $response->json('choices.0.message.content');
    }

    private function generateWithGoogle(string $prompt): string
    {
        $url = "{$this->baseUrl}/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";
        
        $response = Http::accept('application/json')->post($url, [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ]);

        $response->throw();
        return $response->json('candidates.0.content.parts.0.text');
    }

    private function generateWithOllama(string $prompt): string
    {
        $response = Http::accept('application/json')
            ->post($this->baseUrl, [
                'model' => $this->model,
                'prompt' => $prompt,
                'stream' => false, // Asegurarse de obtener la respuesta completa
            ]);

        $response->throw();
        return $response->json('response');
    }
}
