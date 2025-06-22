<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'LexColombia') }} - @yield('title', 'Gestión Legal Inteligente')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CDN y Config (Temporal para fidelidad con HTML provisto) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: {
              '50': '#f0fdfa',
              '100': '#ccfbf1',
              '200': '#99f6e4',
              '300': '#5eead4',
              '400': '#2dd4bf',
              '500': '#14b8a6', // Base primary color (teal-500)
              '600': '#0d9488', // Usado en el HTML provisto (primary)
              '700': '#0f766e',
              '800': '#115e59',
              '900': '#134e4a',
              '950': '#042f2e',
            },
            secondary: {
              '50': '#fffbeb',
              '100': '#fef3c7',
              '200': '#fde68a',
              '300': '#fcd34d',
              '400': '#fbbf24',
              '500': '#f59e0b', // Usado en el HTML provisto (secondary)
              '600': '#d97706',
              '700': '#b45309',
              '800': '#92400e',
              '900': '#78350f',
              '950': '#451a03',
            }
          },
          fontFamily: {
            sans: ['Inter', 'Figtree', ...tailwind.defaultTheme.fontFamily.sans] // Asegura que Inter y Figtree estén disponibles
          }
        }
      }
    }
  </script>

  <!-- Styles -->
  <style type="text/css">
    [x-cloak] { display: none !important; }
    .document-card:hover .doc-actions {
      opacity: 1;
      transform: translateY(0);
    }
  </style>

  <!-- Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')

</head>
<body class="font-sans bg-stone-50 text-stone-700 dark:bg-stone-900 dark:text-stone-200 transition-colors duration-300 antialiased">
  <!-- Header -->
  <header class="sticky top-0 z-30 bg-white dark:bg-stone-800 shadow-sm">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <!-- Logo -->
        <div class="flex items-center">
          <a href="{{ (Route::has('dashboard') ? route('dashboard') : url('/dashboard')) }}" class="flex items-center">
            <div class="bg-primary-600 w-8 h-8 rounded-lg flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
              </svg>
            </div>
            <span class="ml-2 text-xl font-semibold text-stone-800 dark:text-stone-100">{{ config('app.name', 'LexColombia') }}</span>
          </a>
        </div>

        <!-- Search & Actions -->
        <div class="flex items-center space-x-4">
          <div class="hidden md:block">
            <div class="relative">
              <input type="text" placeholder="Buscar casos, documentos..." class="pl-10 pr-4 py-2 w-64 rounded-lg border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-primary-300 dark:focus:ring-primary-600 text-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <button class="p-2 rounded-full hover:bg-stone-100 dark:hover:bg-stone-700 text-stone-500 dark:text-stone-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
          </button>

          <!-- Dark Mode Toggle -->
          <button id="theme-toggle" class="p-2 rounded-full hover:bg-stone-100 dark:hover:bg-stone-700 text-stone-500 dark:text-stone-400">
            <svg id="theme-dark-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg id="theme-light-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </button>

          <!-- User Menu -->
          @auth
          <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
              <div class="bg-stone-200 dark:bg-stone-700 w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium text-stone-600 dark:text-stone-300">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
              </div>
              <span class="hidden md:block text-sm text-stone-700 dark:text-stone-200">{{ Auth::user()->name }}</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-stone-500 dark:text-stone-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-stone-800 rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-20" 
                 x-cloak>
              <a href="{{ (Route::has('profile.edit') ? route('profile.edit') : '#') }}" class="block px-4 py-2 text-sm text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-700">Mi Perfil</a>
              <form method="POST" action="{{ (Route::has('logout') ? route('logout') : '#') }}">
                @csrf
                <a href="{{ (Route::has('logout') ? route('logout') : '#') }}" 
                   onclick="event.preventDefault(); this.closest('form').submit();" 
                   class="block px-4 py-2 text-sm text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-700">
                  Cerrar Sesión
                </a>
              </form>
            </div>
          </div>
          @else
            <a href="{{ route('login') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">Iniciar Sesión</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="ml-4 text-sm font-medium text-primary-600 hover:text-primary-500">Registrarse</a>
            @endif
          @endauth
        </div>
      </div>
    </div>
  </header>

  <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 flex">
    <!-- Sidebar -->
    <aside class="hidden md:block w-64 min-h-[calc(100vh-4rem)] py-6 pr-6 sticky top-16">
      <nav class="flex flex-col h-full">
        <ul class="space-y-1 flex-grow">
          <li>
            <a href="{{ (Route::has('dashboard') ? route('dashboard') : url('/dashboard')) }}" class="flex items-center p-3 rounded-lg {{ request()->is('dashboard*') ? 'bg-primary-50 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 font-medium' : 'hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-600 dark:text-stone-300' }}">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
              <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ (Route::has('casos.index') ? route('casos.index') : url('/casos')) }}" class="flex items-center p-3 rounded-lg {{ request()->is('casos*') ? 'bg-primary-50 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 font-medium' : 'hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-600 dark:text-stone-300' }}">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
              <span>Casos</span>
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-600 dark:text-stone-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
              <span>Documentos</span>
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-600 dark:text-stone-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
              <span>Calendario</span>
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-600 dark:text-stone-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
              <span>Asistente IA</span>
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-600 dark:text-stone-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              <span>Facturación</span>
            </a>
          </li>
        </ul>

        <div class="mt-auto pt-8 border-t border-stone-200 dark:border-stone-700">
          <h3 class="px-3 text-xs font-semibold uppercase tracking-wider text-stone-500 dark:text-stone-400 mb-3">Integraciones</h3>
          <ul class="space-y-1">
            <li>
              <a href="#" class="flex items-center p-3 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-600 dark:text-stone-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-secondary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                <span>SIUGJ</span>
              </a>
            </li>
            <li>
              <a href="#" class="flex items-center p-3 rounded-lg hover:bg-stone-100 dark:hover:bg-stone-800 text-stone-600 dark:text-stone-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-secondary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                <span>SGDE</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 py-6 pl-0 md:pl-6">
      <!-- Session Messages and Errors -->
      <div class="px-4 sm:px-0">
        @if (session('success'))
          <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md dark:bg-green-900/80 dark:border-green-700 dark:text-green-200" role="alert">
              {{ session('success') }}
          </div>
        @endif
        @if (session('error'))
          <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md dark:bg-red-900/80 dark:border-red-700 dark:text-red-200" role="alert">
              {{ session('error') }}
          </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md dark:bg-red-900/80 dark:border-red-700 dark:text-red-200" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
      </div>

      @hasSection('header')
        <div class="px-4 sm:px-0 mb-6">
            @yield('header')
        </div>
      @endif

      <div class="px-4 sm:px-0">
        @yield('content')
      </div>
    </main>
  </div>

  @stack('scripts')
</body>
</html>
