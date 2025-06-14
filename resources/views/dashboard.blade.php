@extends('layouts.app')

@section('title', 'Dashboard Principal')

@section('header')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-stone-800 dark:text-stone-100">Bienvenido de nuevo, {{ Auth::user()->name }}</h1>
            <p class="text-sm text-stone-600 dark:text-stone-400">Aquí tienes un resumen de tu actividad reciente.</p>
        </div>
        <button class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-stone-900 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nuevo Caso
        </button>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Stat Card 1: Casos Activos -->
    <div class="bg-white dark:bg-stone-800 p-6 rounded-xl shadow-lg hover:shadow-primary-200/50 dark:hover:shadow-primary-700/30 transition-shadow duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-primary-100 dark:bg-primary-900/60 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 dark:text-green-200 dark:bg-green-700/30 rounded-full">+5.2%</span>
        </div>
        <h3 class="text-3xl font-bold text-stone-800 dark:text-stone-100">75</h3>
        <p class="text-sm text-stone-500 dark:text-stone-400">Casos Activos</p>
    </div>

    <!-- Stat Card 2: Tareas Pendientes -->
    <div class="bg-white dark:bg-stone-800 p-6 rounded-xl shadow-lg hover:shadow-secondary-200/50 dark:hover:shadow-secondary-700/30 transition-shadow duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-secondary-100 dark:bg-secondary-900/60 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary-600 dark:text-secondary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <span class="px-2 py-1 text-xs font-semibold text-stone-700 bg-stone-200 dark:text-stone-300 dark:bg-stone-700/50 rounded-full">Normal</span>
        </div>
        <h3 class="text-3xl font-bold text-stone-800 dark:text-stone-100">12</h3>
        <p class="text-sm text-stone-500 dark:text-stone-400">Tareas Pendientes</p>
    </div>

    <!-- Stat Card 3: Documentos Recientes -->
    <div class="bg-white dark:bg-stone-800 p-6 rounded-xl shadow-lg hover:shadow-indigo-200/50 dark:hover:shadow-indigo-700/30 transition-shadow duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/60 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <span class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 dark:text-blue-200 dark:bg-blue-700/30 rounded-full">+10</span>
        </div>
        <h3 class="text-3xl font-bold text-stone-800 dark:text-stone-100">34</h3>
        <p class="text-sm text-stone-500 dark:text-stone-400">Documentos Esta Semana</p>
    </div>

    <!-- Stat Card 4: Plazos Próximos -->
    <div class="bg-white dark:bg-stone-800 p-6 rounded-xl shadow-lg hover:shadow-rose-200/50 dark:hover:shadow-rose-700/30 transition-shadow duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-rose-100 dark:bg-rose-900/60 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-600 dark:text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 dark:text-red-200 dark:bg-red-700/30 rounded-full">Urgente</span>
        </div>
        <h3 class="text-3xl font-bold text-stone-800 dark:text-stone-100">3</h3>
        <p class="text-sm text-stone-500 dark:text-stone-400">Plazos Próximos (7 días)</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Casos Recientes -->
    <div class="lg:col-span-2 bg-white dark:bg-stone-800 p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-stone-800 dark:text-stone-100">Casos Recientes</h4>
            <a href="{{ (Route::has('casos.index') ? route('casos.index') : url('/casos')) }}" class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-stone-500 dark:text-stone-400">
                <thead class="text-xs text-stone-700 uppercase bg-stone-50 dark:bg-stone-700 dark:text-stone-300">
                    <tr>
                        <th scope="col" class="px-6 py-3">Radicado</th>
                        <th scope="col" class="px-6 py-3">Cliente</th>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b dark:bg-stone-800 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-700/50">
                        <td class="px-6 py-4 font-medium text-stone-900 dark:text-white whitespace-nowrap">CAS-2023-0115</td>
                        <td class="px-6 py-4">Inversiones El Roble SAS</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 dark:text-green-200 dark:bg-green-700/30 rounded-full">Activo</span></td>
                        <td class="px-6 py-4"><a href="#" class="text-primary-600 hover:underline">Ver</a></td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-stone-800 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-700/50">
                        <td class="px-6 py-4 font-medium text-stone-900 dark:text-white whitespace-nowrap">CAS-2023-0230</td>
                        <td class="px-6 py-4">Mariana Gómez Pérez</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 dark:text-yellow-200 dark:bg-yellow-700/30 rounded-full">En Revisión</span></td>
                        <td class="px-6 py-4"><a href="#" class="text-primary-600 hover:underline">Ver</a></td>
                    </tr>
                    <tr class="bg-white dark:bg-stone-800 hover:bg-stone-50 dark:hover:bg-stone-700/50">
                        <td class="px-6 py-4 font-medium text-stone-900 dark:text-white whitespace-nowrap">CAS-2023-0089</td>
                        <td class="px-6 py-4">Constructora Horizonte Ltda.</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 dark:text-red-200 dark:bg-red-700/30 rounded-full">Urgente</span></td>
                        <td class="px-6 py-4"><a href="#" class="text-primary-600 hover:underline">Ver</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Asistente IA -->
    <div class="bg-white dark:bg-stone-800 p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-stone-800 dark:text-stone-100">Asistente IA</h4>
            <span class="text-xs text-primary-500 dark:text-primary-400 font-medium">Beta</span>
        </div>
        <p class="text-sm text-stone-600 dark:text-stone-400 mb-3">¿Necesitas ayuda? Pregunta a nuestro asistente legal inteligente.</p>
        <textarea class="w-full p-3 border border-stone-200 dark:border-stone-700 rounded-lg bg-stone-50 dark:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-primary-300 dark:focus:ring-primary-600 text-sm mb-3" rows="3" placeholder="Ej: Redactar un derecho de petición para..."></textarea>
        <button class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-stone-800 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Consultar IA
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Documentos Recientes -->
    <div class="bg-white dark:bg-stone-800 p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-stone-800 dark:text-stone-100">Documentos Recientes</h4>
            <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Ver todos</a>
        </div>
        <ul class="space-y-3">
            <li class="document-card group flex items-center justify-between p-3 bg-stone-50 dark:bg-stone-700/50 rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-500 dark:text-primary-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <div>
                        <p class="text-sm font-medium text-stone-800 dark:text-stone-100">Contrato_Prestacion_Servicios.docx</p>
                        <p class="text-xs text-stone-500 dark:text-stone-400">Modificado: Hace 2 horas</p>
                    </div>
                </div>
                <div class="doc-actions opacity-0 group-hover:opacity-100 transform translate-y-1 group-hover:translate-y-0 transition-all duration-300 flex space-x-2">
                    <button class="p-1 text-stone-500 hover:text-primary-600 dark:text-stone-400 dark:hover:text-primary-400"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></button>
                    <button class="p-1 text-stone-500 hover:text-rose-600 dark:text-stone-400 dark:hover:text-rose-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                </div>
            </li>
            <li class="document-card group flex items-center justify-between p-3 bg-stone-50 dark:bg-stone-700/50 rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-500 dark:text-primary-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <div>
                        <p class="text-sm font-medium text-stone-800 dark:text-stone-100">Memorial_Apelacion_CAS0115.pdf</p>
                        <p class="text-xs text-stone-500 dark:text-stone-400">Modificado: Ayer</p>
                    </div>
                </div>
                <div class="doc-actions opacity-0 group-hover:opacity-100 transform translate-y-1 group-hover:translate-y-0 transition-all duration-300 flex space-x-2">
                    <button class="p-1 text-stone-500 hover:text-primary-600 dark:text-stone-400 dark:hover:text-primary-400"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></button>
                    <button class="p-1 text-stone-500 hover:text-rose-600 dark:text-stone-400 dark:hover:text-rose-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                </div>
            </li>
        </ul>
    </div>

    <!-- Próximos Plazos -->
    <div class="bg-white dark:bg-stone-800 p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-stone-800 dark:text-stone-100">Próximos Plazos</h4>
            <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Ver calendario</a>
        </div>
        <ul class="space-y-3">
            <li class="flex items-start p-3 bg-stone-50 dark:bg-stone-700/50 rounded-lg">
                <div class="p-2 bg-rose-100 dark:bg-rose-900/60 rounded-md mr-3 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 dark:text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-stone-800 dark:text-stone-100">Contestar Demanda - CAS-2023-0089</p>
                    <p class="text-xs text-rose-500 dark:text-rose-400">Vence: 25 de Julio, 2024 (En 2 días)</p>
                </div>
            </li>
            <li class="flex items-start p-3 bg-stone-50 dark:bg-stone-700/50 rounded-lg">
                <div class="p-2 bg-secondary-100 dark:bg-secondary-900/60 rounded-md mr-3 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-secondary-600 dark:text-secondary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-stone-800 dark:text-stone-100">Audiencia Conciliación - CAS-2023-0115</p>
                    <p class="text-xs text-secondary-500 dark:text-secondary-400">Vence: 01 de Agosto, 2024 (En 8 días)</p>
                </div>
            </li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Pequeño script para manejar la interactividad si es necesaria en el futuro para esta vista específica.
    // Por ahora, el toggle de modo oscuro es global y está en app.js
    console.log('Dashboard view scripts loaded.');
</script>
@endpush
