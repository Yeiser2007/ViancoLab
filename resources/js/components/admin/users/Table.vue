<template>
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-4">
        <div class="w-full sm:flex-1 sm:min-w-0">
            <FilterBar :filters="{ name: false, status: false }" @filter="handleFilter" class="h-full" />
        </div>
    </div>

    <DataTable :value="userData" :loading="loading" :lazy="true" :paginator="true" :rows="10" :totalRecords="total"
        @page="onPageChange" @sort="onSort">
        <Column field="id" header="ID" :sortable="true"></Column>
        <Column field="name" header="Nombre"></Column>
        <Column field="email" header="Correo"></Column>
        <Column header="Acciones" class="text-center"></Column>

        <template #empty>Sin resultados</template>
    </DataTable>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Button from '../../partials/Button.vue';
import DataTable from 'primevue/datatable';
import FilterBar from '../../partials/FilterBar.vue';
import Column from 'primevue/column';
import axios from 'axios';

const userData = ref([]);
const total = ref(0);
const loading = ref(false);
const showModal = ref(false);
const fetchUsers = async (page = 1) => {
    loading.value = true;
    try {
        const response = await axios.get('/users', {
            params: {
                page: page,
            },
            headers: {
                Accept: 'application/json',
            },
        });
        userData.value = response.data.data;
        total.value = response.data.meta.total;
    } catch (err) {
        console.error('Error al cargar usuarios:', err);
    } finally {
        loading.value = false;
    }
};

const onPageChange = (event) => {
    const page = event.page + 1;
    fetchUsers(page);
};

onMounted(() => {
    fetchUsers();
});
</script>
