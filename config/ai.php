<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Proveedor de IA por Defecto
    |--------------------------------------------------------------------------
    */
    'default' => env('AI_PROVIDER', 'deepseek'),

    /*
    |--------------------------------------------------------------------------
    | Proveedores de IA Disponibles
    |--------------------------------------------------------------------------
    */
    'providers' => [

        'openai' => [
            'label'   => 'OpenAI',
            'api_url' => 'https://api.openai.com/v1/chat/completions',
            'models'  => [
                'gpt-4o' => 'GPT-4o (Recomendado)',
                'gpt-4-turbo' => 'GPT-4 Turbo',
                'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
            ],
        ],

        'deepseek' => [
            'label'   => 'DeepSeek',
            'api_url' => 'https://api.deepseek.com/chat/completions',
            'models'  => [
                'deepseek-chat' => 'DeepSeek Chat (Recomendado)',
                'deepseek-reasoner' => 'DeepSeek Reasoner',
            ],
        ],

        'groq' => [
            'label'   => 'Groq',
            'api_url' => 'https://api.groq.com/openai/v1/chat/completions',
            'models'  => [
                'llama3-8b-8192' => 'LLaMA3 8b (Rápido)',
                'llama3-70b-8192' => 'LLaMA3 70b (Potente)',
                'mixtral-8x7b-32768' => 'Mixtral 8x7b',
            ],
        ],

        'openrouter' => [
            'label'   => 'OpenRouter',
            'api_url' => 'https://openrouter.ai/api/v1/chat/completions',
            'models' => [
                'google/gemini-flash-1.5' => 'Google Gemini Flash 1.5',
                'anthropic/claude-3-haiku' => 'Claude 3 Haiku',
                'mistralai/mistral-7b-instruct' => 'Mistral 7B Instruct',
            ]
        ],

        'ollama' => [
            'label'    => 'Ollama',
            'api_url'  => rtrim(env('OLLAMA_BASE_URL', 'http://localhost:11434'), '/') . '/api/chat',
            'models'   => [
                'gemma3:4b' => 'Gemma3 4B (Local)',
                'qwen3:8b' => 'Qwen 3 8B (Local)',
                
            ],
        ],

    ],

];
