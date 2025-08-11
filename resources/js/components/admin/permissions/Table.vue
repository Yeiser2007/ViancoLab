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

        <DataTable :value="data" :loading="loading" :lazy="true" :paginator="true" :rows="rows"
            :totalRecords="totalRecords" @page="onPageChange" @sort="onSort">
            <Column field="id" header="ID" :sortable="true"></Column>
            <Column field="name" header="Nombre" :sortable="true"></Column>
            <Column header="Acciones" class="text-center">
                <template #body="slotProps">
                    <div class="text-center flex gap-2">
                        <Button theme="info" icon="pencil" title="" permission="permissions.update"
                            :data="slotProps.data" @click="$emit('edit-item', slotProps.data)" />
                        <Button theme="danger" icon="trash" title="" permission="permissions.destroy"
                            :data="slotProps.data" @click="$emit('delete-item', slotProps.data.id)" />
                    </div>
                </template>

            </Column>

            <template #empty>Sin resultados</template>
        </DataTable>
    </div>
</template>


<script setup>
import { inject, onMounted, ref } from 'vue';
import { useDataTable } from '@/composables/fetchData';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import FilterBar from '../../partials/FilterBar.vue';
import Button from '../../partials/Button.vue';

const api = inject('api');
const {
    data,
    totalRecords,
    loading,
    rows,
    fetchData,
    handleFilter,
    onPageChange,
    onSort,
    refreshData
} = useDataTable(api, 'admin/permissions');
defineExpose({ refreshData });
onMounted(() => {
    fetchData();
});
</script>