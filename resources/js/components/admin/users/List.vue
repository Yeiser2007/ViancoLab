<template>
        <TitleCard icon="users" title="Gestión de Usuarios">
                <Button @click="createItem" :theme="'primary'" :title="'Agregar'" :icon="'plus'"
                        :eventListener="'open-modal'"
                        class="w-auto flex-shrink-0 text-sm px-3 py-1.5 sm:px-4 sm:py-2 sm:text-base sm:ml-2" />
        </TitleCard>

        <TableList ref="tableRef" @edit-item="updateItem($event)" @delete-item="deleteItem($event)"></TableList>

        <Modal v-model="showModal" :title="titleModal" size="xl" :backdrop-close="true" @close="onModalClose">
                <Form :item="formData" :roles="rolesList" @confirm-action="confirmAction" @close="onModalClose" />
        </Modal>
        
</template>
<script setup>
import { ref, reactive, inject, onMounted } from 'vue';
import TableList from './Table.vue';
import Modal from '../../partials/Modal.vue';
import Button from '../../partials/Button.vue';
import TitleCard from '../../partials/TitleCard.vue';
import Form from './Form.vue';

const api = inject('api');
const Swal = inject('swal');

const showModal = ref(false);
const titleModal = ref('');
const tableRef = ref(null);
const rolesList = ref([]);

const fetchRoles = async () => {
        try {
                const response = await api.get('admin/roles');
                rolesList.value = response.data.map(role => ({
                        label: role.name.charAt(0).toUpperCase() + role.name.slice(1),
                        value: role.id,
                        name: role.name.toLowerCase()
                }));
        } catch (err) {
                console.error('Error al cargar roles:', err);
                Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los roles'
                });
        }
};

const defaultFormData = () => ({
        id: '',
        name: '',
        first_name: '',
        last_name: '',
        email: '',
        roles: [],
        password: '',
        password_confirmation: '',
        option: '1'
});
const formData = reactive(defaultFormData());
const updateItem = (item) => {
        const rolesList = item.roles.map(role => role.id);
        Object.assign(formData, {
                id: item.id,
                name: item.name,
                first_name: item.first_name,
                last_name: item.last_name,
                email: item.email,
                roles: rolesList,
                password: '',
                password_confirmation: '',
                option: '2'
        });
        titleModal.value = 'Actualizar Usuario';
        showModal.value = true;
};

const createItem = () => {
        Object.assign(formData, defaultFormData());
        titleModal.value = 'Crear Usuario';
        showModal.value = true;
};

const deleteItem = async (userId) => {
        try {
                Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                }).then(async (result) => {
                        if (result.isConfirmed) {
                                const response = await api.delete(`admin/users`, userId);
                                if (response?.status === 200) {
                                        Swal.fire({
                                                icon: 'success',
                                                title: 'Éxito',
                                                text: response.data.message || 'Usuario eliminado correctamente',
                                                timer: 1500,
                                                showConfirmButton: true
                                        });
                                        await reloadTable();
                                }
                        }
                });
        } catch (err) {
                console.error('Error al eliminar usuario:', err);
                Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.response?.data?.message || 'Error al eliminar el usuario',
                });
        }
};
const onModalClose = () => {
        showModal.value = false;
};
const confirmAction = () => {
        showModal.value = false;
        reloadTable();
}
const reloadTable = () => {
        tableRef.value.refreshData();
}
onMounted(() => {
        fetchRoles();
});

</script>
