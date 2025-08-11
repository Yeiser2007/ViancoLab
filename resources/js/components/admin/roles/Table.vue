<template>
    <div class="w-full sm:flex-1 sm:min-w-0">
        <FilterBar :filters="{
            name: true,
            status: true,
            role: false,
            date: false,
            dateRange: false
        }" :roles="rolesList" @filter="handleFilter" class="h-full" />
    </div>
    <div class="relative">
        <div v-if="loading"
            class="absolute inset-0 z-10 flex items-center justify-center bg-white/60 backdrop-blur-sm rounded">
            <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <DataTable :value="rolesData" :loading="loading" :lazy="true" :paginator="true" :rows="rows"
            :totalRecords="totalRecords" @page="onPageChange" @sort="onSort" >
            <Column field="id" header="ID" :sortable="true"></Column>
            <Column field="name" header="Nombre" :sortable="true"></Column>
            <Column header="Acciones" class="text-center">
                <template #body="slotProps">
                    <div class="text-center flex gap-2 justify-center">
                        <Button theme="secondary" icon="eye" title="" permission="users.show" :data="slotProps.data"
                            @click="$emit('show-permissions', slotProps.data)" />
                        <Button theme="info" icon="pencil" title="" permission="users.update" :data="slotProps.data"
                            @click="$emit('edit-item', slotProps.data)" />
                        <Button theme="danger" icon="trash" title="" permission="users.destroy" :data="slotProps.data"
                            @click="$emit('delete-item', slotProps.data.id)" />
                    </div>
                </template>

            </Column>

            <template #empty>Sin resultados</template>
        </DataTable>
    </div>
</template>
<script setup>
import { inject, ref, onMounted, defineExpose } from 'vue';
import DataTable from 'primevue/datatable';
import FilterBar from '../../partials/FilterBar.vue';
import Button from '../../partials/Button.vue';
import Column from 'primevue/column';

defineEmits(['edit-item', 'delete-item', 'refresh', 'show-permissions']);


const api = inject('api');
const rolesData = ref([]);
const totalRecords = ref(0);
const loading = ref(false);
const filters = ref({});
const sortField = ref(null);
const sortOrder = ref(null);
const rolesList = ref([]);
const currentPage = ref(1);
const rows = ref();



const fetchRoles = async () => {
    loading.value = true;
    try {
        const params = {
            page: currentPage.value,
            ...filters.value,
        };
        rows.value = params.per_page  || 10;
        if (sortField.value && sortOrder.value) {
            params.sort = `${sortField.value}|${sortOrder.value === 1 ? 'asc' : 'desc'}`;
        }
        console.log("Params que se envían:", params);
        const response = await api.get('admin/roles', params);
        rolesData.value = response.data?.data || [];
        totalRecords.value = response.data?.meta?.total || response.data?.total || 0;
    } catch (err) {
        console.error('Error al cargar usuarios:', err);
    } finally {
        loading.value = false;
    }
};
const handleFilter = (appliedFilters) => {
    filters.value = appliedFilters;
    currentPage.value = 1;
    fetchRoles();
};
const onPageChange = (event) => {
    currentPage.value = event.page + 1;
    fetchUsers(event.rows);
};

const onSort = (event) => {
    sortField.value = event.sortField;
    sortOrder.value = event.sortOrder;
    currentPage.value = 1;
    fetchUsers();
};

const refreshData = async () => {
    await fetchRoles();
};

onMounted(() => {
    fetchRoles();
});

defineExpose({
    refreshData,
    getCurrentData: () => rolesData.value
});

</script>