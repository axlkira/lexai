<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'ai_providers' => [
        'openai' => [
            'name' => 'OpenAI',
            'api_key' => env('OPENAI_API_KEY'),
            'url' => 'https://api.openai.com/v1/chat/completions',
            'models' => [
                ['value' => 'gpt-4o', 'name' => 'GPT-4o'],
                ['value' => 'gpt-4-turbo', 'name' => 'GPT-4 Turbo'],
                ['value' => 'gpt-3.5-turbo', 'name' => 'GPT-3.5 Turbo'],
            ],
        ],
        'deepseek' => [
            'name' => 'DeepSeek',
            'api_key' => env('DEEPSEEK_API_KEY'),
            'url' => 'https://api.deepseek.com/chat/completions',
            'models' => [
                ['value' => 'deepseek-chat', 'name' => 'DeepSeek Chat'],
                ['value' => 'deepseek-coder', 'name' => 'DeepSeek Coder'],
            ],
        ],
        'groq' => [
            'name' => 'Groq',
            'api_key' => env('GROQ_API_KEY'),
            'url' => 'https://api.groq.com/openai/v1/chat/completions',
            'models' => [
                ['value' => 'llama3-8b-8192', 'name' => 'LLaMA3 8b'],
                ['value' => 'llama3-70b-8192', 'name' => 'LLaMA3 70b'],
                ['value' => 'mixtral-8x7b-32768', 'name' => 'Mixtral 8x7b'],
                ['value' => 'gemma-7b-it', 'name' => 'Gemma 7b'],
            ],
        ],
        'google' => [
            'name' => 'Google',
            'api_key' => env('GOOGLE_API_KEY'),
            'url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent',
            'models' => [
                ['value' => 'gemini-1.5-flash', 'name' => 'Gemini 1.5 Flash'],
                ['value' => 'gemini-1.5-pro', 'name' => 'Gemini 1.5 Pro'],
                ['value' => 'gemini-1.0-pro', 'name' => 'Gemini 1.0 Pro'],
            ],
        ],
        'openrouter' => [
            'name' => 'OpenRouter',
            'api_key' => env('OPENROUTER_API_KEY'),
            'url' => 'https://openrouter.ai/api/v1/chat/completions',
            'models' => [
                ['value' => 'google/gemini-flash-1.5', 'name' => 'Google Gemini Flash 1.5'],
                ['value' => 'openai/gpt-4o', 'name' => 'OpenAI GPT-4o'],
                ['value' => 'anthropic/claude-3-haiku', 'name' => 'Anthropic Claude 3 Haiku'],
                ['value' => 'meta-llama/llama-3-8b-instruct', 'name' => 'Meta Llama 3 8b Instruct'],
            ],
        ],
        'ollama' => [
            'name' => 'Ollama (Local)',
            'api_key' => 'ollama',
            'url' => env('OLLAMA_BASE_URL', 'http://localhost:11434/api/generate'),
            'models' => [], // Se obtienen dinámicamente desde la API
        ],
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
