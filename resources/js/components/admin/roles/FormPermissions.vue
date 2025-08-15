<template>
    <div class="min-w-full max-h-[80vh]">
        <form @submit.prevent="submit">
            <div :class="['transition-all duration-300 will-change-auto max-w-2xl']">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div v-for="permission in permissions" :key="permission.id" class="flex items-center">
                            <input type="checkbox" :id="'permission_' + permission.id" v-model="selectedPermissions"
                                :value="permission.id" class="mr-2" />
                            <label :for="'permission_' + permission.id" class="text-sm">{{ permission.name }}</label>
                        </div>
                    </div>

                    <InputField id="id" v-model="item.id" type="hidden" :errors="errors.id" required autofocus />
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <Button theme="neutral" title="Cancelar" icon="times" @click="emit('close');" />
                    <Button theme="info" :title="loading ? 'Procesando...' : 'Guardar'" icon="save" type="submit"
                        :disabled="loading" />
                </div>
            </div>
        </form>
    </div>
</template>


<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import { useFormSubmit } from '@/composables/useFormHandler';
import { useGlobal } from '@/composables/useGlobal';
import '@vueform/multiselect/themes/default.css';
const { api, swal, InputField, Button } = useGlobal();
const {loading, errors } = useFormSubmit();

const emit = defineEmits(['confirm-action', 'close']);
const props = defineProps({
    item: { type: Object, required: true, default: () => ({}) },
});
const item = reactive({
    ...props.item,
});

const permissions = ref([]);
const selectedPermissions = ref([]);
const fetchPermissions = async () => {
    loading.value = true;
    try {
        const { data: allPermissionsResp } = await api.get('admin/all-permissions');
        permissions.value = allPermissionsResp.data || [];
        if (item.id) {
            const { data: rolePermissionsResp } = await api.show('admin/role-permissions', item.id);
            selectedPermissions.value = rolePermissionsResp.data
                .filter(p => p.has_permission)
                .map(p => p.id);
        }
    } catch (err) {
        console.error('Error al cargar permisos:', err);
        swal.error('Error al cargar permisos');
    } finally {
        loading.value = false;
    }
};

const submit = async () => {
    try {
        loading.value = true;
        const data = {
            permissions: selectedPermissions.value,
        };
        const response = await api.put('admin/role-permissions',item.id, data);
        swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: 'Permisos actualizados correctamente',
            timer: 2000
        });
        emit('confirm-action');
        emit('close');
    } catch (error) {
        swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response?.data?.message || 'Error al actualizar permisos',
            timer: 2000
        });
    } finally {
        loading.value = false;
    }
};


onMounted(() => {
    fetchPermissions();
});
</script>