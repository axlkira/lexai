@extends('layouts.app', ['header' => 'Dashboard'])

@section('content')
@auth
<div class="space-y-6">
    <!-- Bienvenida -->
    <div class="bg-white dark:bg-dark-800 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Bienvenido, {{ auth()->user()->name ?? 'Usuario' }}</h2>
                <p class="text-gray-600 dark:text-gray-300 mt-2">Aquí tienes un resumen de tu actividad reciente</p>
            </div>
            <button class="flex items-center text-primary-600 dark:text-primary-500 hover:text-primary-800 dark:hover:text-primary-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Descargar reporte
            </button>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-dark-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Casos activos</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">24</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-dark-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Documentos este mes</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">42</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-dark-800 rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Horas facturables</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">68.5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Casos recientes -->
    <div class="bg-white dark:bg-dark-800 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Casos recientes</h3>
            <a href="{{ route('casos.index') }}" class="text-primary-600 dark:text-primary-500 hover:underline">Ver todos</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Caso</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Próximo evento</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Ejemplo de fila -->
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">#C-12345</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Demanda laboral</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">María Rodríguez</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">CC 52.345.678</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">En progreso</span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div>15 Sep, 2023</div>
                            <div>Audiencia preliminar</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            <a href="#" class="text-primary-600 dark:text-primary-500 hover:text-primary-900 dark:hover:text-primary-400 mr-3">Ver</a>
                            <a href="#" class="text-primary-600 dark:text-primary-500 hover:text-primary-900 dark:hover:text-primary-400">Editar</a>
                        </td>
                    </tr>
                    <!-- Más filas... -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Documentos recientes -->
    <div class="bg-white dark:bg-dark-800 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Documentos recientes</h3>
            <a href="#" class="text-primary-600 dark:text-primary-500 hover:underline">Ver todos</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Documento 1 -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Demanda laboral</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Caso #C-12345</p>
                        <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <span>Generado por IA</span>
                            <span class="mx-2">•</span>
                            <span>Hace 2 días</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex">
                    <button class="flex-1 mr-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        Descargar
                    </button>
                    <button class="flex-1 bg-primary-100 dark:bg-primary-900 hover:bg-primary-200 dark:hover:bg-primary-800 text-primary-700 dark:text-primary-300 py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        Editar
                    </button>
                </div>
            </div>
            
            <!-- Documento 2 -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Contrato de arrendamiento</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Caso #C-12346</p>
                        <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <span>Subido por cliente</span>
                            <span class="mx-2">•</span>
                            <span>Ayer</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex">
                    <button class="flex-1 mr-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        Descargar
                    </button>
                    <button class="flex-1 bg-primary-100 dark:bg-primary-900 hover:bg-primary-200 dark:hover:bg-primary-800 text-primary-700 dark:text-primary-300 py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        Firmar
                    </button>
                </div>
            </div>
            
            <!-- Documento 3 -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-medium text-gray-900 dark:text-white">Solicitud de pruebas</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Caso #C-12347</p>
                        <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <span>Pendiente de revisión</span>
                            <span class="mx-2">•</span>
                            <span>Hoy</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex">
                    <button class="flex-1 mr-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        Revisar
                    </button>
                    <button class="flex-1 bg-primary-100 dark:bg-primary-900 hover:bg-primary-200 dark:hover:bg-primary-800 text-primary-700 dark:text-primary-300 py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        Comentar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@else
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Acceso no autorizado</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Por favor inicia sesión para acceder al dashboard.</p>
            <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                Iniciar sesión
            </a>
        </div>
    </div>
@endauth
@endsection