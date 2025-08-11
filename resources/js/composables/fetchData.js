// src/composables/useDataTable.js
import { ref } from 'vue';

export function useDataTable(api, endpoint) {
    const data = ref([]);
    const totalRecords = ref(0);
    const loading = ref(false);
    const filters = ref({});
    const sortField = ref(null);
    const sortOrder = ref(null);
    const currentPage = ref(1);
    const rows = ref(5);

    const fetchData = async (customParams = {}) => {
        loading.value = true;
        try {
            const params = {
                page: currentPage.value,
                per_page: rows.value,
                ...filters.value,
                ...customParams
            };

            if (sortField.value && sortOrder.value) {
                params.sort = `${sortField.value}|${sortOrder.value === 1 ? 'asc' : 'desc'}`;
            }

            const response = await api.get(endpoint, params);
            data.value = response.data?.data || [];
            totalRecords.value = response.data?.meta?.total || response.data?.total || 0;
        } catch (err) {
            console.error(`Error cargando datos de ${endpoint}:`, err);
        } finally {
            loading.value = false;
        }
    };

    const handleFilter = (appliedFilters) => {
        filters.value = appliedFilters;
        currentPage.value = 1;
        fetchData();
    };

    const onPageChange = (event) => {
        currentPage.value = event.page + 1;
        rows.value = event.rows;
        fetchData();
    };

    const onSort = (event) => {
        sortField.value = event.sortField;
        sortOrder.value = event.sortOrder;
        currentPage.value = 1;
        fetchData();
    };
    const refreshData = async () => {
        return fetchData();
    };

    return {
        data,
        totalRecords,
        loading,
        filters,
        sortField,
        sortOrder,
        currentPage,
        rows,
        refreshData,
        fetchData,
        handleFilter,
        onPageChange,
        onSort
    };
}
