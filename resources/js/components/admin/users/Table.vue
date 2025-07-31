<template>
    <div class="w-full sm:flex-1 sm:min-w-0">
        <FilterBar :filters="{
            name: true,
            status: true,
            role: true,
            date: false,
            dateRange: false
        }" :roles="rolesList" @filter="handleFilter" class="h-full" />
    </div>
    <div class="relative">
        <div v-if="loading"
            class="absolute inset-0 z-10 flex items-center justify-center bg-white/60 backdrop-blur-sm rounded">
            <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <DataTable :value="userData" :loading="loading" :lazy="true" :paginator="true" :rows="10"
            :totalRecords="totalRecords" @page="onPageChange" @sort="onSort">
            <Column field="id" header="ID" :sortable="true"></Column>
            <Column field="name" header="Nombre" :sortable="true"></Column>
            <Column field="email" header="Correo" :sortable="true"></Column>
            <Column header="Acciones" class="text-center">
                <template #body="slotProps">
                    <button @click="handleAction(slotProps.data)"
                        class="btn inline-flex items-center justify-center gap-2">
                        Acción para {{ slotProps.data.name }}
                    </button>
                </template>
            </Column>

            <template #empty>Sin resultados</template>
        </DataTable>
    </div>
</template>

<script setup>
import { inject,ref, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import FilterBar from '../../partials/FilterBar.vue';
import Column from 'primevue/column';


const api = inject('api');
const userData = ref([]);
const totalRecords = ref(0);
const loading = ref(false);
const filters = ref({});
const sortField = ref(null);
const sortOrder = ref(null);
const rolesList = ref([]);
const currentPage = ref(1);

const fetchUsers = async () => {
    loading.value = true;
    try {
        const params = {
            page: currentPage.value, 
            ...filters.value,
        };

        if (sortField.value && sortOrder.value) {
            params.sort = `${sortField.value}|${sortOrder.value === 1 ? 'asc' : 'desc'}`;
        }

        const response = await api.get('admin/users', { params });
        
        userData.value = response.data?.data || [];
        totalRecords.value = response.data?.meta?.total || response.data?.total || 0;
    } catch (err) {
        console.error('Error al cargar usuarios:', err);
    } finally {
        loading.value = false;
    }
};



const handleFilter = (appliedFilters) => {
    filters.value = appliedFilters;
    currentPage.value = 1; // Reseteamos a la primera página
    fetchUsers();
};

const onPageChange = (event) => {
    currentPage.value = event.page + 1; // Actualizamos página actual
    fetchUsers();
};

const onSort = (event) => {
    sortField.value = event.sortField;
    sortOrder.value = event.sortOrder;
    currentPage.value = 1; // Reseteamos a la primera página
    fetchUsers();
};

const handleAction = (rowData) => {
    console.log('Datos de la fila:', rowData);
    // Implementa tus acciones aquí
};

onMounted(() => {
    fetchUsers();
});
</script>