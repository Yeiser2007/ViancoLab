<template>
    <div class="filter-bar bg-white rounded-xl shadow-md border border-gray-200 p-4 sm:p-6 mb-4">
        <div class="flex flex-wrap items-end gap-4">

            <!-- Registros por página -->
            <div class="w-full sm:w-auto">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Registros por página</label>
                <select v-model="perPage" class="form-select w-full sm:w-36" @change="emitFilter">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <!-- Rol -->
            <div v-if="filters.role" class="w-full sm:w-auto">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Rol</label>
                <select v-model="roleFilter" @change="emitFilter" class="form-select w-full sm:w-44">
                    <option value="">Todos los roles</option>
                    <option v-for="role in rolesList" :key="role.value" :value="role.value">
                        {{ role.label }}
                    </option>
                </select>
            </div>

            <!-- Búsqueda + Botón Limpiar -->
            <div class="w-full sm:flex-1 min-w-[200px]">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Búsqueda</label>
                <div class="flex items-center gap-2">
                    <!-- Input con ícono -->
                    <div class="relative flex-1">
                        <input v-model="filterText" type="text" class="form-input w-full pl-10 pr-8"
                            placeholder="Buscar..." @keyup.enter="emitFilter">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        </div>
                        <button v-if="filterText" @click="clearSearch"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            ✕
                        </button>
                    </div>

                    <!-- Botón Limpiar -->
                    <button @click="resetFilters"
                        class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-300 text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition flex items-center gap-1 shrink-0">
                        <i class="pi pi-eraser"></i>
                    </button>
                </div>
            </div>


            <!-- Botón Filtros (solo móvil) -->
            <div class="sm:hidden w-full">
                <button @click="showFilters = !showFilters"
                    class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    <span>Filtros</span>
                </button>
            </div>

            <!-- Filtros adicionales -->
            <div :class="[showFilters ? 'flex' : 'hidden', 'sm:flex flex-wrap gap-4 w-full']">
                <template v-if="filters.date || filters.dateRange">
                    <div v-if="filters.date" class="w-full sm:w-auto">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha</label>
                        <input v-model="dateFilter" type="date" class="form-input w-full sm:w-44" @change="emitFilter">
                    </div>

                    <template v-if="filters.dateRange">
                        <div class="w-full sm:w-auto">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Desde</label>
                            <input v-model="startDate" type="date" class="form-input w-full sm:w-44"
                                @change="emitFilter">
                        </div>
                        <div class="w-full sm:w-auto">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Hasta</label>
                            <input v-model="endDate" type="date" class="form-input w-full sm:w-44" @change="emitFilter">
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
import { ref, onMounted, inject } from 'vue';

const api = inject('api');
interface FilterProps {
    filters: {
        name?: boolean;
        date?: boolean;
        dateRange?: boolean;
        status?: boolean;
        role?: boolean;
    };
    roles?: Array<{ id: string, name: string }>;
}

const props = defineProps<FilterProps>();
const emit = defineEmits(['filter']);
const showFilters = ref(false);

const perPage = ref('5');
const filterText = ref('');
const nameFilter = ref('');
const dateFilter = ref('');
const startDate = ref('');
const endDate = ref('');
const statusFilter = ref('');
const roleFilter = ref('');
const rolesList = ref([]);

const emitFilter = () => {
    const filters: Record<string, string> = {
        per_page: perPage.value
    };

    if (filterText.value) filters.name = filterText.value;
    if (props.filters.date && dateFilter.value) filters.date = dateFilter.value;
    if (props.filters.dateRange && startDate.value && endDate.value) {
        filters.start_date = startDate.value;
        filters.end_date = endDate.value;
    }
    if (props.filters.role && roleFilter.value) {
        filters.role = roleFilter.value;
    }

    emit('filter', filters);
};
const fetchRoles = async () => {
    try {
        const response = await api.get('admin/roles');
        rolesList.value = response.data?.data.map(role => ({
            label: role.name.charAt(0).toUpperCase() + role.name.slice(1),
            value: role.id
        }));
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
.form-input,
.form-select {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}
</style>