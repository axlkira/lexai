@extends('layouts.app', ['header' => 'Gestión de Casos'])

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Gestión de Casos</h1>
            <div class="flex space-x-2 mt-4 md:mt-0">
                <button id="openNewCaseModalBtn" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-plus mr-2"></i> Nuevo Caso
                </button>
                <div class="relative">
                    <input type="text" id="searchCasesInput" placeholder="Buscar casos..." class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-gray-200 w-full md:w-64">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Casos Totales</div>
                <div class="text-2xl font-bold text-gray-800 dark:text-gray-100" id="statsTotal">0</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Abiertos</div>
                <div class="text-2xl font-bold text-status_abierto-DEFAULT dark:text-status_abierto-DEFAULT" id="statsAbiertos">0</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">En Progreso</div>
                <div class="text-2xl font-bold text-status_en_progreso-DEFAULT dark:text-status_en_progreso-DEFAULT" id="statsEnProgreso">0</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="text-sm text-gray-500 dark:text-gray-400">Cerrados</div>
                <div class="text-2xl font-bold text-status_cerrado-DEFAULT dark:text-status_cerrado-DEFAULT" id="statsCerrados">0</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Filters Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Filtros</h2>
                    
                    <div class="mb-4">
                        <label for="filterEstado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                        <select id="filterEstado" name="filterEstado" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Todos</option>
                            <option value="Abierto">Abierto</option>
                            <option value="En Progreso">En Progreso</option>
                            <option value="Cerrado">Cerrado</option>
                            <option value="Archivado">Archivado</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="filterPrioridad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridad</label>
                        <select id="filterPrioridad" name="filterPrioridad" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Todas</option>
                            <option value="Alta">Alta</option>
                            <option value="Media">Media</option>
                            <option value="Baja">Baja</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="filterTipoCaso" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Caso</label>
                        <select id="filterTipoCaso" name="filterTipoCaso" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-200 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Todos</option>
                            <option value="Civil">Civil</option>
                            <option value="Penal">Penal</option>
                            <option value="Laboral">Laboral</option>
                            <option value="Familiar">Familiar</option>
                            <option value="Comercial">Comercial</option>
                            <option value="Administrativo">Administrativo</option>
                        </select>
                    </div>
                    
                    <button id="applyFiltersBtn" class="w-full bg-primary-500 text-white py-2 rounded-lg hover:bg-primary-600 transition-colors">Aplicar Filtros</button>
                </div>
            </div>

            <!-- Cases Table -->
            <div class="lg:w-3/4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Caso #</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="casesTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Filas de casos se insertarán aquí por JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button id="prevPageMobileBtn" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Anterior
                            </button>
                            <button id="nextPageMobileBtn" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Siguiente
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Mostrando <span id="paginationInfoFrom" class="font-medium">1</span> a <span id="paginationInfoTo" class="font-medium">10</span> de <span id="paginationInfoTotal" class="font-medium">0</span> resultados
                                </p>
                            </div>
                            <div>
                                <nav id="paginationDesktop" class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <!-- Links de paginación se insertarán aquí -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New/Edit Case Modal -->
    <div id="caseModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto hidden z-50 transition-opacity duration-300 ease-in-out" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-700 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-folder-open text-primary-600 dark:text-primary-300"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="caseModalTitle">Nuevo Caso</h3>
                            <div class="mt-4">
                                <form id="caseForm">
                                    <input type="hidden" id="caseIdInput" name="caseId">
                                    <div class="mb-4">
                                        <label for="caseTitleInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título del Caso</label>
                                        <input type="text" name="title" id="caseTitleInput" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="caseClientInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                                        <input type="text" name="client" id="caseClientInput" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200" required>
                                        <!-- Podría ser un select si los clientes están predefinidos -->
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="caseTypeInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Caso</label>
                                            <select id="caseTypeInput" name="type" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200" required>
                                                <option value="">Seleccione tipo</option>
                                                <option value="Civil">Civil</option>
                                                <option value="Penal">Penal</option>
                                                <option value="Laboral">Laboral</option>
                                                <option value="Familiar">Familiar</option>
                                                <option value="Comercial">Comercial</option>
                                                <option value="Administrativo">Administrativo</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="casePriorityInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prioridad</label>
                                            <select id="casePriorityInput" name="priority" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200" required>
                                                <option value="">Seleccione prioridad</option>
                                                <option value="Alta">Alta</option>
                                                <option value="Media">Media</option>
                                                <option value="Baja">Baja</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="caseStatusInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                        <select id="caseStatusInput" name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200" required>
                                            <option value="Abierto">Abierto</option>
                                            <option value="En Progreso">En Progreso</option>
                                            <option value="Cerrado">Cerrado</option>
                                            <option value="Archivado">Archivado</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="caseDescriptionInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                                        <textarea id="caseDescriptionInput" name="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:text-gray-200"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="saveCaseBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar
                    </button>
                    <button type="button" id="closeCaseModalBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Case Detail Modal -->
    <div id="caseDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto hidden z-50 transition-opacity duration-300 ease-in-out">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:max-w-2xl sm:w-full m-4">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100" id="detailModalTitle">Detalles del Caso</h3>
                    <button id="closeDetailModalBtn" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times fa-lg"></i>
                    </button>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">ID del Caso</h4>
                            <p class="mt-1 text-md text-gray-900 dark:text-gray-100" id="detailCaseId">-</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Título</h4>
                            <p class="mt-1 text-md text-gray-900 dark:text-gray-100" id="detailCaseTitle">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</h4>
                            <p class="mt-1 text-md text-gray-900 dark:text-gray-100" id="detailCaseClient">-</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Caso</h4>
                            <p class="mt-1 text-md text-gray-900 dark:text-gray-100" id="detailCaseType">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Prioridad</h4>
                            <p class="mt-1 text-md text-gray-900 dark:text-gray-100" id="detailCasePriority">-</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</h4>
                            <p class="mt-1 text-md text-gray-900 dark:text-gray-100"><span id="detailCaseStatus" class="px-2 py-1 text-xs font-semibold rounded-full">-</span></p>
                        </div>
                    </div>
                     <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</h4>
                        <p class="mt-1 text-md text-gray-900 dark:text-gray-100 whitespace-pre-wrap" id="detailCaseDescription">-</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Creación</h4>
                            <p class="mt-1 text-md text-gray-900 dark:text-gray-100" id="detailCaseCreated">-</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Última Actualización</h4>
                            <p class="mt-1 text-md text-gray-900 dark:text-gray-100" id="detailCaseUpdated">-</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 text-right">
                     <button type="button" id="editFromDetailBtn" class="bg-secondary-500 hover:bg-secondary-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors mr-2">
                        <i class="fas fa-edit mr-2"></i> Editar
                    </button>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mock data (reemplazar con datos reales de backend)
        let casesData = [
            {
                id: 1,
                title: 'Demanda por incumplimiento de contrato',
                client: 'Juan Pérez',
                type: 'Civil',
                status: 'Abierto',
                priority: 'Alta',
                description: 'El cliente Juan Pérez necesita demandar a la empresa XYZ por incumplimiento de un contrato de servicios. Se adjunta documentación relevante.',
                created_at: '2023-01-15T09:30:00Z',
                updated_at: '2023-01-16T14:45:00Z'
            },
            {
                id: 2,
                title: 'Asesoría legal para constitución de empresa',
                client: 'Ana Gómez',
                type: 'Mercantil',
                status: 'En Progreso',
                priority: 'Media',
                description: 'Ana Gómez solicita asesoría para la constitución de una nueva sociedad limitada. Requiere revisión de estatutos y trámites registrales.',
                created_at: '2023-02-10T11:00:00Z',
                updated_at: '2023-02-12T16:20:00Z'
            },
            {
                id: 3,
                title: 'Defensa en caso de despido improcedente',
                client: 'Carlos Rodríguez',
                type: 'Laboral',
                status: 'Cerrado',
                priority: 'Alta',
                description: 'Representación de Carlos Rodríguez en juicio por despido improcedente contra su anterior empleador. Caso ganado.',
                created_at: '2022-11-05T15:00:00Z',
                updated_at: '2023-03-01T10:05:00Z'
            },
            {
                id: 4,
                title: 'Reclamación de cantidad por accidente de tráfico',
                client: 'Sofía López',
                type: 'Civil',
                status: 'Abierto',
                priority: 'Media',
                description: 'Sofía López sufrió un accidente de tráfico y necesita reclamar indemnización por daños y perjuicios. Se está recopilando información de atestados y peritos.',
                created_at: '2023-03-20T08:15:00Z',
                updated_at: '2023-03-22T12:00:00Z'
            },
            {
                id: 5,
                title: 'Divorcio de mutuo acuerdo',
                client: 'Miguel Torres',
                type: 'Familia',
                status: 'En Progreso',
                priority: 'Baja',
                description: 'Trámite de divorcio de mutuo acuerdo para Miguel Torres y su cónyuge. Pendiente de firma de convenio regulador.',
                created_at: '2023-04-01T17:30:00Z',
                updated_at: '2023-04-05T09:00:00Z'
            },
             {
                id: 6,
                title: 'Herencia y Testamento',
                client: 'Luisa Fernández',
                type: 'Sucesiones',
                status: 'Abierto',
                priority: 'Media',
                description: 'Asesoramiento en la gestión de una herencia y redacción de testamento.',
                created_at: '2023-05-10T10:00:00Z',
                updated_at: '2023-05-12T11:30:00Z'
            },
            {
                id: 7,
                title: 'Contrato de Arrendamiento',
                client: 'Pedro Ramírez',
                type: 'Inmobiliario',
                status: 'Cerrado',
                priority: 'Baja',
                description: 'Redacción y revisión de un contrato de arrendamiento de local comercial.',
                created_at: '2023-01-20T14:00:00Z',
                updated_at: '2023-01-25T16:45:00Z'
            },
            {
                id: 8,
                title: 'Defensa Penal por Delito Leve',
                client: 'Laura Vargas',
                type: 'Penal',
                status: 'En Progreso',
                priority: 'Alta',
                description: 'Defensa en juicio rápido por un presunto delito leve de hurto.',
                created_at: '2023-06-01T09:15:00Z',
                updated_at: '2023-06-05T13:00:00Z'
            }
        ];

        // Helper function to format date
        function formatDate(dateString) {
            if (!dateString) return 'No disponible';
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('es-ES', options);
        }

        function getStatusClass(status) {
            switch (status) {
                case 'Abierto': return 'bg-status_abierto-bg_light text-status_abierto-DEFAULT dark:bg-blue-900 dark:text-blue-300';
                case 'En Progreso': return 'bg-status_en_progreso-bg_light text-status_en_progreso-DEFAULT dark:bg-orange-900 dark:text-orange-300';
                case 'Cerrado': return 'bg-status_cerrado-bg_light text-status_cerrado-DEFAULT dark:bg-green-900 dark:text-green-300';
                default: return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
            }
        }

        // DOM Elements
        console.log('[Casos Script] DOMContentLoaded event fired.');
        const casesTableBody = document.getElementById('casesTableBody');
        console.log('[Casos Script] casesTableBody element:', casesTableBody);
        const paginationControls = document.getElementById('paginationControls'); // For desktop
        console.log('[Casos Script] paginationControls element:', paginationControls);
        const mobilePaginationControls = document.getElementById('mobilePaginationControls'); // For mobile
        console.log('[Casos Script] mobilePaginationControls element:', mobilePaginationControls);
        const resultsCountTop = document.getElementById('resultsCountTop');
        console.log('[Casos Script] resultsCountTop element:', resultsCountTop);
        const resultsCountBottom = document.getElementById('resultsCountBottom');
        console.log('[Casos Script] resultsCountBottom element:', resultsCountBottom);

        const caseModal = document.getElementById('caseModal');
        const caseDetailModal = document.getElementById('caseDetailModal');
        const openNewCaseModalBtn = document.getElementById('openNewCaseModalBtn');
        const closeCaseModalBtn = document.getElementById('closeCaseModalBtn');
        const closeCaseDetailModalBtn = document.getElementById('closeCaseDetailModalBtn');
        const saveCaseBtn = document.getElementById('saveCaseBtn');
        const caseForm = document.getElementById('caseForm');
        const modalTitle = document.getElementById('modalTitle'); // Corrected ID

        // Detail Modal Elements
        const detailCaseId = document.getElementById('detailCaseId');
        const detailTitle = document.getElementById('detailTitle');
        const detailClient = document.getElementById('detailClient');
        const detailType = document.getElementById('detailType');
        const detailStatus = document.getElementById('detailStatus');
        const detailPriority = document.getElementById('detailPriority');
        const detailDescription = document.getElementById('detailDescription');
        const detailCreatedAt = document.getElementById('detailCreatedAt');
        const detailUpdatedAt = document.getElementById('detailUpdatedAt');
        const editFromDetailBtn = document.getElementById('editFromDetailBtn');

        // Search and Filters
        const searchInput = document.getElementById('searchInput'); // Corrected ID
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const typeFilter = document.getElementById('typeFilter');

        // Stats cards
        const totalCasesStat = document.getElementById('totalCasesStat');
        const openCasesStat = document.getElementById('openCasesStat');
        const inProgressCasesStat = document.getElementById('inProgressCasesStat');
        const closedCasesStat = document.getElementById('closedCasesStat');

        let currentEditingCaseId = null;
        let currentPage = 1;
        const itemsPerPage = 5; 

        function openModal(modal, caseId = null) {
            if (!modal) {
                console.error('Modal element not found for openModal');
                return;
            }
            currentEditingCaseId = caseId;
            if (caseId) {
                const caseItem = casesData.find(c => c.id === caseId);
                if (caseItem && caseForm && modalTitle) {
                    modalTitle.textContent = 'Editar Caso';
                    // Ensure all form fields exist before trying to set their value
                    const caseIdHiddenField = document.getElementById('case_id_hidden');
                    if (caseIdHiddenField) caseIdHiddenField.value = caseItem.id;
                    const titleField = document.getElementById('title');
                    if (titleField) titleField.value = caseItem.title;
                    const clientField = document.getElementById('client');
                    if (clientField) clientField.value = caseItem.client;
                    const typeField = document.getElementById('type');
                    if (typeField) typeField.value = caseItem.type;
                    const statusField = document.getElementById('status');
                    if (statusField) statusField.value = caseItem.status;
                    const priorityField = document.getElementById('priority');
                    if (priorityField) priorityField.value = caseItem.priority;
                    const descriptionField = document.getElementById('description');
                    if (descriptionField) descriptionField.value = caseItem.description;
                } else {
                    console.error('Caso no encontrado o elementos del formulario faltantes para editar:', caseId);
                    return; 
                }
            } else {
                if (modalTitle) modalTitle.textContent = 'Nuevo Caso';
                if (caseForm) {
                    caseForm.reset();
                    const hiddenIdField = document.getElementById('case_id_hidden');
                    if (hiddenIdField) hiddenIdField.value = '';
                }
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // Ensure it's flex for proper display if using flex for centering
        }

        function closeModal(modal) {
            if (!modal) {
                console.error('Modal element not found for closeModal');
                return;
            }
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (caseForm) caseForm.reset();
            currentEditingCaseId = null;
        }

        function handleSaveCase() {
            const idField = document.getElementById('case_id_hidden');
            const id = idField ? idField.value : null;
            
            const titleElem = document.getElementById('title');
            const clientElem = document.getElementById('client');
            const typeElem = document.getElementById('type');
            const statusElem = document.getElementById('status');
            const priorityElem = document.getElementById('priority');
            const descriptionElem = document.getElementById('description');

            const caseItemData = {
                title: titleElem ? titleElem.value : '',
                client: clientElem ? clientElem.value : '',
                type: typeElem ? typeElem.value : '',
                status: statusElem ? statusElem.value : '',
                priority: priorityElem ? priorityElem.value : '',
                description: descriptionElem ? descriptionElem.value : '',
                updated_at: new Date().toISOString(),
            };

            if (currentEditingCaseId) {
                const existingCase = casesData.find(c => c.id === currentEditingCaseId);
                const updatedCase = { ...existingCase, ...caseItemData, id: currentEditingCaseId }; // Ensure ID is preserved
                casesData = casesData.map(c => c.id === currentEditingCaseId ? updatedCase : c);
            } else {
                const newCase = {
                    id: Date.now(), // Simple ID generation for mock
                    ...caseItemData,
                    created_at: new Date().toISOString(),
                };
                casesData.unshift(newCase);
            }
            if (caseModal) closeModal(caseModal);
            applyFiltersAndSearch();
            updateStats();
        }

        function populateEditForm(caseId) {
            if (caseModal) openModal(caseModal, caseId);
        }

        function viewCaseDetails(caseId) {
            const caseItem = casesData.find(c => c.id === caseId);
            if (caseItem && caseDetailModal && detailCaseId && detailTitle && detailClient && detailType && detailStatus && detailPriority && detailDescription && detailCreatedAt && detailUpdatedAt && editFromDetailBtn) {
                detailCaseId.textContent = caseItem.id;
                detailTitle.textContent = caseItem.title;
                detailClient.textContent = caseItem.client;
                detailType.textContent = caseItem.type;
                detailStatus.innerHTML = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(caseItem.status)}">${caseItem.status}</span>`;
                detailPriority.textContent = caseItem.priority;
                detailDescription.textContent = caseItem.description || 'No disponible';
                detailCreatedAt.textContent = formatDate(caseItem.created_at);
                detailUpdatedAt.textContent = formatDate(caseItem.updated_at);
                editFromDetailBtn.dataset.id = caseItem.id; // Ensure dataset.id is correctly set
                caseDetailModal.classList.remove('hidden');
                caseDetailModal.classList.add('flex');
            } else {
                console.error("Error al mostrar detalles del caso o falta algún elemento del DOM.", caseItem);
            }
        }

        function deleteCase(caseId) {
            // Consider using a more robust confirmation, e.g., a small modal
            if (confirm('¿Estás seguro de que quieres eliminar este caso?')) {
                casesData = casesData.filter(c => c.id !== caseId);
                applyFiltersAndSearch(); // This will re-render table and pagination
                updateStats();
            }
        }

        function renderTable() {
            console.log('[Casos Script] renderTable() called.');
            if (!casesTableBody) {
                console.error('[Casos Script] casesTableBody is null or undefined in renderTable(). Cannot render table.');
                return;
            }
            casesTableBody.innerHTML = ''; 

            const currentFilteredData = getFilteredAndSearchedData();
            const paginatedData = currentFilteredData.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage);
            console.log('[Casos Script] currentFilteredData in renderTable:', currentFilteredData);
            console.log('[Casos Script] paginatedData in renderTable:', paginatedData);

            if (paginatedData.length === 0 && currentFilteredData.length > 0 && currentPage > 1) {
                currentPage = Math.ceil(currentFilteredData.length / itemsPerPage) || 1;
                renderTable(); 
                return;
            }
            
            if (paginatedData.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No hay casos que mostrar con los filtros actuales.</td>`;
                casesTableBody.appendChild(row);
            } else {
                paginatedData.forEach(caseItem => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">${caseItem.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${caseItem.title}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${caseItem.client}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${caseItem.type}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(caseItem.status)}">
                                ${caseItem.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button title="Ver" class="view-case text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 mr-2" data-id="${caseItem.id}"><i class="fas fa-eye"></i></button>
                            <button title="Editar" class="edit-case text-secondary-600 hover:text-secondary-800 dark:text-secondary-400 dark:hover:text-secondary-300 mr-2" data-id="${caseItem.id}"><i class="fas fa-edit"></i></button>
                            <button title="Eliminar" class="delete-case text-danger-DEFAULT hover:text-danger-hover dark:text-red-400 dark:hover:text-red-300" data-id="${caseItem.id}"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    casesTableBody.appendChild(row);
                });
            }
            console.log('[Casos Script] About to call renderPagination with totalItems:', currentFilteredData.length);
            renderPagination(currentFilteredData.length);
            updateResultsCount(currentFilteredData.length);
            addTableActionListeners(); // Add listeners after table is rendered
        }
        
        function addTableActionListeners() {
            document.querySelectorAll('.view-case').forEach(button => {
                button.addEventListener('click', function() {
                    viewCaseDetails(parseInt(this.dataset.id));
                });
            });
            document.querySelectorAll('.edit-case').forEach(button => {
                button.addEventListener('click', function() {
                    populateEditForm(parseInt(this.dataset.id));
                });
            });
            document.querySelectorAll('.delete-case').forEach(button => {
                button.addEventListener('click', function() {
                    deleteCase(parseInt(this.dataset.id));
                });
            });
        }

        function renderPagination(totalItems) {
            console.log('[Casos Script] renderPagination() called with totalItems:', totalItems);
            if (!paginationControls || !mobilePaginationControls) {
                console.warn('[Casos Script] paginationControls or mobilePaginationControls not found in renderPagination().');
                return;
            }

            paginationControls.innerHTML = '';
            mobilePaginationControls.innerHTML = '';
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            if (totalPages <= 1) return;

            // Desktop Pagination
            let paginationHTML = '<nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">';
            // Previous Button
            paginationHTML += `<button data-page="${currentPage - 1}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === 1 ? 'disabled' : ''}><span class="sr-only">Anterior</span><i class="fas fa-chevron-left h-5 w-5"></i></button>`;

            // Page numbers logic (simplified for brevity, can be expanded for '...')
            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    paginationHTML += `<button aria-current="page" class="z-10 bg-primary-50 border-primary-500 text-primary-600 dark:bg-gray-700 dark:border-primary-400 dark:text-primary-300 relative inline-flex items-center px-4 py-2 border text-sm font-medium">${i}</button>`;
                } else {
                    paginationHTML += `<button data-page="${i}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 relative inline-flex items-center px-4 py-2 border text-sm font-medium">${i}</button>`;
                }
            }
            // Next Button
            paginationHTML += `<button data-page="${currentPage + 1}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === totalPages ? 'disabled' : ''}><span class="sr-only">Siguiente</span><i class="fas fa-chevron-right h-5 w-5"></i></button>`;
            paginationHTML += '</nav>';
            if (paginationControls) paginationControls.innerHTML = paginationHTML;

            // Mobile Pagination
            let mobilePaginationHTML = `<div class="flex-1 flex justify-between">`;
            mobilePaginationHTML += `<button data-page="${currentPage - 1}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === 1 ? 'disabled' : ''}>Anterior</button>`;
            mobilePaginationHTML += `<button data-page="${currentPage + 1}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" ${currentPage === totalPages ? 'disabled' : ''}>Siguiente</button>`;
            mobilePaginationHTML += `</div>`;
            if (mobilePaginationControls) mobilePaginationControls.innerHTML = mobilePaginationHTML;

            // Add event listeners to new pagination buttons
            document.querySelectorAll('#paginationControls button[data-page], #mobilePaginationControls button[data-page]').forEach(button => {
                button.addEventListener('click', function() {
                    if (this.disabled) return;
                    currentPage = parseInt(this.dataset.page);
                    renderTable();
                });
            });
        }

        function updateResultsCount(totalItems) {
            if (!resultsCountTop || !resultsCountBottom) return;
            const startItem = totalItems > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
            const endItem = Math.min(currentPage * itemsPerPage, totalItems);
            const text = `Mostrando <span class="font-medium">${startItem}</span> a <span class="font-medium">${endItem}</span> de <span class="font-medium">${totalItems}</span> resultados`;
            resultsCountTop.innerHTML = text;
            resultsCountBottom.innerHTML = text;
        }

        function getFilteredAndSearchedData() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const status = statusFilter ? statusFilter.value : 'all';
            const priority = priorityFilter ? priorityFilter.value : 'all';
            const typeVal = typeFilter ? typeFilter.value : 'all'; // Renamed to avoid conflict with 'type' in caseItem

            return casesData.filter(caseItem => {
                const matchesSearch = searchTerm === '' || 
                                      caseItem.id.toString().toLowerCase().includes(searchTerm) || // Ensure ID is string for includes
                                      caseItem.title.toLowerCase().includes(searchTerm) || 
                                      caseItem.client.toLowerCase().includes(searchTerm);
                const matchesStatus = status === 'all' || caseItem.status === status;
                const matchesPriority = priority === 'all' || caseItem.priority === priority;
                const matchesType = typeVal === 'all' || caseItem.type === typeVal;
                return matchesSearch && matchesStatus && matchesPriority && matchesType;
            });
        }

        function applyFiltersAndSearch() {
            currentPage = 1; 
            renderTable();
            // updateStats(); // Stats are updated based on the full dataset, not filtered, unless specified
        }

        function updateStats() {
            if (!totalCasesStat || !openCasesStat || !inProgressCasesStat || !closedCasesStat) return;
            // Stats based on the complete dataset
            totalCasesStat.textContent = casesData.length;
            openCasesStat.textContent = casesData.filter(c => c.status === 'Abierto').length;
            inProgressCasesStat.textContent = casesData.filter(c => c.status === 'En Progreso').length;
            closedCasesStat.textContent = casesData.filter(c => c.status === 'Cerrado').length;
        }

        // Event Listeners & Initial Setup
        if (openNewCaseModalBtn) openNewCaseModalBtn.addEventListener('click', () => openModal(caseModal));
        if (closeCaseModalBtn) closeCaseModalBtn.addEventListener('click', () => closeModal(caseModal));
        if (closeCaseDetailModalBtn) closeCaseDetailModalBtn.addEventListener('click', () => closeModal(caseDetailModal));
        
        if (editFromDetailBtn) {
            editFromDetailBtn.addEventListener('click', function() {
                const caseId = parseInt(this.dataset.id);
                if (caseDetailModal) closeModal(caseDetailModal);
                if (caseModal) openModal(caseModal, caseId);
            });
        }
        if (saveCaseBtn) saveCaseBtn.addEventListener('click', handleSaveCase);

        // Delegated event listeners for table actions are handled by addTableActionListeners called in renderTable

        if (searchInput) searchInput.addEventListener('input', applyFiltersAndSearch);
        if (statusFilter) statusFilter.addEventListener('change', applyFiltersAndSearch);
        if (priorityFilter) priorityFilter.addEventListener('change', applyFiltersAndSearch);
        if (typeFilter) typeFilter.addEventListener('change', applyFiltersAndSearch);

        // Initial Render
        console.log('[Casos Script] Starting initial render...');
        applyFiltersAndSearch(); // This will render the table with initial data/filters
        updateStats(); // Initial stat calculation
        console.log('[Casos Script] Initial render complete.');

    });
</script>
@endpush
@endsection
