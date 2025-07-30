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

        <Modal v-model="showModal" title="Confirmación" size="lg" :backdrop-close="true" @close="onModalClose">
               <Form @confirm-action="confirmAction"></Form>
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

 
<style lang="">

</style>