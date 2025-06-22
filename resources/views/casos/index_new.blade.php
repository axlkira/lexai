<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Gestión de Casos</h1>
    
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <p class="text-lg mb-4">Sistema de gestión de casos</p>
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
            <p>Esta es una versión básica de la vista de casos para verificar el funcionamiento.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                <h3 class="font-semibold mb-2">Casos Abiertos</h3>
                <p class="text-2xl font-bold">5</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                <h3 class="font-semibold mb-2">En Progreso</h3>
                <p class="text-2xl font-bold">3</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                <h3 class="font-semibold mb-2">Cerrados</h3>
                <p class="text-2xl font-bold">12</p>
            </div>
        </div>
        
        <div class="mb-6">
            <button id="nuevoBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Nuevo Caso</button>
        </div>

        <table class="min-w-full bg-white dark:bg-gray-800">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="py-2 px-4 text-left">ID</th>
                    <th class="py-2 px-4 text-left">Título</th>
                    <th class="py-2 px-4 text-left">Cliente</th>
                    <th class="py-2 px-4 text-left">Estado</th>
                    <th class="py-2 px-4 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t">
                    <td class="py-2 px-4">C001</td>
                    <td class="py-2 px-4">Demanda por incumplimiento</td>
                    <td class="py-2 px-4">Juan Pérez</td>
                    <td class="py-2 px-4"><span class="bg-green-100 text-green-800 py-1 px-2 rounded">Abierto</span></td>
                    <td class="py-2 px-4">
                        <button class="text-blue-500">Ver</button>
                        <button class="text-yellow-500 ml-2">Editar</button>
                        <button class="text-red-500 ml-2">Eliminar</button>
                    </td>
                </tr>
                <tr class="border-t">
                    <td class="py-2 px-4">C002</td>
                    <td class="py-2 px-4">Recurso de apelación</td>
                    <td class="py-2 px-4">María García</td>
                    <td class="py-2 px-4"><span class="bg-yellow-100 text-yellow-800 py-1 px-2 rounded">En Progreso</span></td>
                    <td class="py-2 px-4">
                        <button class="text-blue-500">Ver</button>
                        <button class="text-yellow-500 ml-2">Editar</button>
                        <button class="text-red-500 ml-2">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('La página de casos se cargó correctamente');
        
        const nuevoBtn = document.getElementById('nuevoBtn');
        if (nuevoBtn) {
            nuevoBtn.addEventListener('click', function() {
                alert('Funcionalidad de nuevo caso será implementada pronto');
            });
        }
        
        // Agrega event listeners a todos los botones de acciones
        document.querySelectorAll('button.text-blue-500').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Ver detalles: Funcionalidad en desarrollo');
            });
        });
        
        document.querySelectorAll('button.text-yellow-500').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Editar caso: Funcionalidad en desarrollo');
            });
        });
        
        document.querySelectorAll('button.text-red-500').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Eliminar caso: Funcionalidad en desarrollo');
            });
        });
    });
</script>
</x-app-layout>
