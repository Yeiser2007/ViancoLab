<template>
    <div class="filter-bar bg-white rounded-lg !px-0">
        <div class="flex flex-col sm:flex-row flex-wrap items-stretch gap-3 sm:gap-4">
            <div class="flex-1 min-w-[180px] sm:min-w-[250px]">
                <div class="relative">
                    <input v-model="filterText" type="text"
                        class="w-full pl-9 pr-8 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Buscar..." @keyup.enter="emitFilter">
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <button v-if="filterText" @click="clearSearch"
                        class="absolute inset-y-0 right-0 pr-2 flex items-center">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3">
                <button @click="showFilters = !showFilters"
                    class="sm:hidden flex items-center justify-center gap-1 px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtros
                </button>
                <div
                    :class="['w-full', showFilters ? 'flex' : 'hidden', 'sm:flex sm:flex-row flex-wrap gap-2 sm:gap-3']">
                    <!-- Filtro por nombre -->
                    <div v-if="filters.name" class="flex-1 min-w-[120px] sm:min-w-[180px]">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input v-model="nameFilter" type="text"
                            class="w-full px-2 py-1 sm:px-3 sm:py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Nombre..." @keyup.enter="emitFilter">
                    </div>

                    <div v-if="filters.status" class="flex-1 min-w-[120px] sm:min-w-[180px]">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select v-model="statusFilter"
                            class="w-full px-2 py-1 sm:px-3 sm:py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            @change="emitFilter">
                            <option value="">Todos</option>
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>
                    <template v-if="filters.date || filters.dateRange">
                        <div v-if="filters.date" class="flex-1 min-w-[120px] sm:min-w-[150px]">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Fecha</label>
                            <input v-model="dateFilter" type="date"
                                class="w-full px-2 py-1 sm:px-3 sm:py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                @change="emitFilter">
                        </div>

                        <template v-if="filters.dateRange">
                            <div class="flex-1 min-w-[120px] sm:min-w-[150px]">
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Desde</label>
                                <input v-model="startDate" type="date"
                                    class="w-full px-2 py-1 sm:px-3 sm:py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    @change="emitFilter">
                            </div>
                            <div class="flex-1 min-w-[120px] sm:min-w-[150px]">
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Hasta</label>
                                <input v-model="endDate" type="date"
                                    class="w-full px-2 py-1 sm:px-3 sm:py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    @change="emitFilter">
                            </div>
                        </template>
                    </template>
                    <div class="flex items-end gap-2 mt-auto">
                        <button @click="emitFilter"
                            class="px-3 py-1 sm:px-4 sm:py-2 bg-blue-600 text-white text-sm sm:text-base rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 inline mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Filtrar
                        </button>
                        <button @click="resetFilters"
                            class="px-3 py-1 sm:px-4 sm:py-2 border border-gray-300 text-gray-700 text-sm sm:text-base rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 inline mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

interface FilterProps {
    filters: {
        name?: boolean;
        date?: boolean;
        dateRange?: boolean;
        status?: boolean;
    };
}

const props = defineProps<FilterProps>();
const emit = defineEmits(['filter']);
const showFilters = ref(false);

const filterText = ref('');
const nameFilter = ref('');
const dateFilter = ref('');
const startDate = ref('');
const endDate = ref('');
const statusFilter = ref('');
// Emitir evento de filtrado
const emitFilter = () => {
    const filters: Record<string, string> = {};

    if (filterText.value) filters.query = filterText.value;
    if (props.filters.name && nameFilter.value) filters.name = nameFilter.value;
    if (props.filters.date && dateFilter.value) filters.date = dateFilter.value;
    if (props.filters.dateRange && startDate.value && endDate.value) {
        filters.start_date = startDate.value;
        filters.end_date = endDate.value;
    }
    if (props.filters.status && statusFilter.value) filters.status = statusFilter.value;

    emit('filter', filters);
};

// Limpiar todos los filtros
const resetFilters = () => {
    filterText.value = '';
    nameFilter.value = '';
    dateFilter.value = '';
    startDate.value = '';
    endDate.value = '';
    statusFilter.value = '';
    emit('filter', {});
};

// Limpiar solo la búsqueda general
const clearSearch = () => {
    filterText.value = '';
    emitFilter();
};

// Opcional: Emitir filtro automáticamente después de un retraso
watch([filterText, nameFilter, dateFilter, startDate, endDate, statusFilter], () => {
    // Puedes habilitar esto si quieres filtrado automático
    // emitFilter();
}, { deep: true });

</script>

<style scoped>
.filter-bar {
    transition: all 0.3s ease;
}

@media (max-width: 640px) {
    .filter-bar {
        padding: 0.75rem;
    }
}
</style>