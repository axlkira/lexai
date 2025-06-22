@extends('layouts.app', ['header' => 'Mi Perfil'])

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-dark-800 rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Información Personal</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre completo</label>
                <div class="p-3 bg-gray-50 dark:bg-dark-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    {{ auth()->user()->name }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo electrónico</label>
                <div class="p-3 bg-gray-50 dark:bg-dark-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    {{ auth()->user()->email }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de tarjeta profesional</label>
                <div class="p-3 bg-gray-50 dark:bg-dark-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    {{ auth()->user()->bar_association_number }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                <div class="p-3 bg-gray-50 dark:bg-dark-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    {{ auth()->user()->phone ?? 'No especificado' }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-dark-800 rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Seguridad</h2>
        
        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            
            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña actual</label>
                <input id="current_password" type="password" name="current_password" required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nueva contraseña</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white">
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar nueva contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-dark-700 dark:text-white">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Actualizar contraseña
                </button>
            </div>
        </form>
    </div>
    
    <div class="bg-white dark:bg-dark-800 rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Preferencias</h2>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800 dark:text-white">Modo oscuro</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Activa el modo oscuro para reducir la fatiga visual</p>
                </div>
                <button id="theme-toggle" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 dark:bg-gray-600 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" role="switch">
                    <span class="translate-x-0 pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-200 shadow ring-0 transition duration-200 ease-in-out">
                        <span class="opacity-100 duration-200 ease-in absolute inset-0 flex h-full w-full items-center justify-center transition-opacity" aria-hidden="true">
                            <svg class="h-3 w-3 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 12 12">
                                <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="opacity-0 duration-100 ease-out absolute inset-0 flex h-full w-full items-center justify-center transition-opacity" aria-hidden="true">
                            <svg class="h-3 w-3 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 12 12">
                                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                            </svg>
                        </span>
                    </span>
                </button>
            </div>
            
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800 dark:text-white">Notificaciones por correo</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Recibe alertas importantes en tu correo</p>
                </div>
                <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-primary-600 dark:bg-primary-500 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" role="switch">
                    <span class="translate-x-5 pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                        <span class="opacity-0 duration-100 ease-out absolute inset-0 flex h-full w-full items-center justify-center transition-opacity" aria-hidden="true">
                            <svg class="h-3 w-3 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 12 12">
                                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                            </svg>
                        </span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection