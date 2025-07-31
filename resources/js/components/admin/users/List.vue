<template>
        <div class="py-3 backdrop-blur-sm rounded-t-2xl flex items-center justify-between border-b-2 mb-3.5">
                <div class="flex items-center gap-2 sm:gap-3">
                        <div class="flex items-center justify-center h-8 w-8 sm:h-9 sm:w-9">
                                <i class="pi pi-users text-secondary-500 text-lg sm:text-xl"></i>
                        </div>
                        <div>
                                <h2 class="text-base font-bold text-secondary-500 sm:text-lg md:text-xl">
                                        Gestión de Usuarios
                                </h2>
                        </div>
                </div>
                <Button @open-modal="handleOpenModal" :theme="'primary'" :title="'Agregar'" :icon="'plus'" :eventListener="'open-modal'" class="w-auto flex-shrink-0 text-sm px-3 py-1.5 sm:px-4 sm:py-2 sm:text-base sm:ml-2" />
        </div>
        <TableList></TableList>

    <Modal v-model="showModal" title="Usuario" size="2xl" :backdrop-close="true" @close="onModalClose">
        <div class="modal-content-container"> 
            <Form @confirm-action="confirmAction"></Form>
        </div>
    </Modal>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import TableList from './Table.vue';
import Modal from '../../partials/Modal.vue';
import Button from '../../partials/Button.vue';
import Form from './Form.vue';

const showModal = ref(false);

const confirmAction = () => {
        alert('Acción confirmada!')
        showModal.value = false
}
const handleOpenModal = () => {
        showModal.value = true;
}

const onModalClose = () => {
        console.log('Modal cerrado')
}


</script>

<style scoped>
/* Estilo para el contenedor del contenido del modal */
.modal-content-container {
    max-height: 70vh; /* Ajusta este valor según necesites */
    overflow-y: auto; /* Habilita el scroll vertical cuando sea necesario */
    padding-right: 0.5rem; /* Para evitar que el contenido toque la barra de scroll */
}

/* Estilo opcional para personalizar la barra de scroll */
.modal-content-container::-webkit-scrollbar {
    width: 6px;
}

.modal-content-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-content-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.modal-content-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>