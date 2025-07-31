<template>
    <div class="filter-bar bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
        <div class="flex flex-col sm:flex-row flex-wrap items-end gap-4">
            <!-- Registros por página -->
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Registros por página</label>
                <select v-model="perPage"
                    class="w-full sm:w-32 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    @change="emitFilter">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <!-- Búsqueda general -->
            <div class="w-full sm:flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Búsqueda</label>
                <div class="relative">
                    <input v-model="filterText" type="text"
                        class="w-full pl-10 pr-8 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Buscar..." @keyup.enter="emitFilter">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <button v-if="filterText" @click="clearSearch"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            <div v-if="filters.role" class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <select class="w-full sm:w-32 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" v-if="filters.role" v-model="roleFilter" @change="emitFilter">
                    <option value="">Todos los roles</option>
                    <option v-for="role in rolesList" :key="role.value" :value="role.value">
                        {{ role.label }}
                    </option>
                </select>
            </div>

            <!-- Botón de filtros para móvil -->
            <button @click="showFilters = !showFilters"
                class="sm:hidden flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filtros
            </button>


            <!-- Contenedor de filtros avanzados -->
            <div :class="['w-full', showFilters ? 'flex' : 'hidden', 'sm:flex sm:flex-row flex-wrap gap-4']">
                <!-- Filtros de fecha -->
                <template v-if="filters.date || filters.dateRange">
                    <div v-if="filters.date" class="w-full sm:w-auto">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                        <input v-model="dateFilter" type="date"
                            class="w-full sm:w-40 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            @change="emitFilter">
                    </div>

                    <template v-if="filters.dateRange">
                        <div class="w-full sm:w-auto">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                            <input v-model="startDate" type="date"
                                class="w-full sm:w-40 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                @change="emitFilter">
                        </div>
                        <div class="w-full sm:w-auto">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                            <input v-model="endDate" type="date"
                                class="w-full sm:w-40 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                @change="emitFilter">
                        </div>
                    </template>
                </template>

                <!-- Botones de acción -->
                <div class="w-full sm:w-auto flex items-end gap-2">
                    <button @click="emitFilter"
                        class="px-4 py-2 bg-primary-600 hover:bg-secondary-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2 transition flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filtrar
                    </button>
                    <button @click="resetFilters"
                        class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Limpiar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, inject } from 'vue';

const api = inject('api');
interface FilterProps {
    filters: {
        name?: boolean;
        date?: boolean;
        dateRange?: boolean;
        status?: boolean;
        role?: boolean;
    };
    roles?: Array<{ id: string, name: string }>; // Prop opcional para lista de roles
}

const props = defineProps<FilterProps>();
const emit = defineEmits(['filter']);
const showFilters = ref(false);

// Valores por defecto
const perPage = ref('10');
const filterText = ref('');
const nameFilter = ref('');
const dateFilter = ref('');
const startDate = ref('');
const endDate = ref('');
const statusFilter = ref('');
const roleFilter = ref('');
const rolesList = ref([]);

// Emitir evento de filtrado
const emitFilter = () => {
    const filters: Record<string, string> = {
        per_page: perPage.value // Cambiado a per_page para coincidir con Laravel
    };

    if (filterText.value) filters.name = filterText.value;;
    if (props.filters.name && nameFilter.value) filters.name = nameFilter.value;
    if (props.filters.date && dateFilter.value) filters.date = dateFilter.value;
    if (props.filters.dateRange && startDate.value && endDate.value) {
        filters.start_date = startDate.value;
        filters.end_date = endDate.value;
    }
    if (props.filters.status && statusFilter.value) {
        filters.status = statusFilter.value === 'active' ? '1' : '0';
    }
    if (props.filters.role && roleFilter.value) filters.role = roleFilter.value;

    emit('filter', filters);
};
const fetchRoles = async () => {
    try {
        const response = await api.get('admin/roles');
        rolesList.value = response.data.map(role => ({
            label: role.name.charAt(0).toUpperCase() + role.name.slice(1),
            value: role.id
        }));
        console.log("Roles formateados:", JSON.parse(JSON.stringify(rolesList.value)));
    } catch (err) {
        console.error('Error al cargar roles:', err);
    }
};
const resetFilters = () => {
    perPage.value = '10';
    filterText.value = '';
    nameFilter.value = '';
    dateFilter.value = '';
    startDate.value = '';
    endDate.value = '';
    statusFilter.value = '';
    roleFilter.value = '';
    emitFilter();
}
onMounted(() => {
    fetchRoles();
});
</script>

<style scoped>
.filter-bar {
    transition: all 0.2s ease;
}

/* Mejora la transición de los inputs */
input,
select {
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
</style>