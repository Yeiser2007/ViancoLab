<template>
        <TitleCard icon="list-check" title="Roles">
                <Button @click="createItem" :theme="'primary'" :title="'Agregar'" :icon="'plus'"
                        :eventListener="'open-modal'"
                        class="w-auto flex-shrink-0 text-sm px-3 py-1.5 sm:px-4 sm:py-2 sm:text-base sm:ml-2" />
        </TitleCard>

        <TableList ref="tableRef" @edit-item="updateItem($event)" @delete-item="deleteItem($event)"
                @show-permissions="showPermissions($event)"></TableList>

        <Modal v-model="showModal" :title="titleModal" size="xl" :backdrop-close="true">
                <Form :item="formData" @confirm-action="confirmAction" @close="onModalClose" />
        </Modal>

        <Modal v-model="showModal2" :title="titleModal" size="xl" :backdrop-close="true">
                <FormPermissions :item="roleItem" @confirm-action="confirmAction" @close="onModal2Close"/>
        </Modal>

</template>
<script setup>
import { ref, reactive, inject, onMounted } from 'vue';
import TableList from './Table.vue';
import Modal from '../../partials/Modal.vue';
import Button from '../../partials/Button.vue';
import TitleCard from '../../partials/TitleCard.vue';
import Form from './Form.vue';
import FormPermissions from './FormPermissions.vue';

const api = inject('api');
const Swal = inject('swal');

const showModal = ref(false);
const roleItem = ref({});
const showModal2 = ref(false);
const titleModal = ref('');
const tableRef = ref(null);

const defaultFormData = () => ({
        id: '',
        name: '',
        option: '1'
});
const formData = reactive(defaultFormData());
const updateItem = (item) => {
        Object.assign(formData, {
                id: item.id,
                name: item.name,
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
const showPermissions = (role) => {
        roleItem.value = role;
        titleModal.value = `Permisos de ${role.name}`;
        showModal2.value = true;
};
const deleteItem = async (id) => {
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
                                const response = await api.delete(`admin/roles`, id);
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
const onModal2Close = () => {
        showModal2.value = false;
};
const confirmAction = () => {
        showModal.value = false;
        reloadTable();
}
const reloadTable = () => {
        tableRef.value.refreshData();
}


</script>
