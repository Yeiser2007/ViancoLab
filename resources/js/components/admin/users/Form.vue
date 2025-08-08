<template>
  <div class="min-w-full max-h-[80vh]">
    <form @submit.prevent="submitForm">
      <div :class="['transition-all duration-300 will-change-auto max-w-2xl']">
        <div class="space-y-4">
          <InputField id="name" v-model="item.id" type="hidden" :errors="errors.id" required autofocus />
          <InputField id="name" v-model="item.name" label="Nombre" :errors="errors.name" required autofocus />
          <InputField id="first_name" v-model="item.first_name" label="Primer apellido" :errors="errors.first_name" />
          <InputField id="last_name" v-model="item.last_name" label="Segundo apellido" :errors="errors.last_name" />
          <InputField id="email" v-model="item.email" label="Correo electrónico" type="email" :errors="errors.email" />
          <InputField v-show="item.id" id="password" v-model="item.password" label="Contraseña" type="password"
            :errors="errors.password" />
          <div v-if="!item.id" class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
            <InputField id="password" v-model="item.password" type="password" label="Contraseña"
              :errors="errors.password" required />
            <InputField id="password_confirmation" v-model="item.password_confirmation" type="password"
              label="Confirmar contraseña" :errors="errors.password_confirmation" required />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Selecciona Roles</label>
            <Multiselect v-model="item.roles" :options="options" mode="tags" label="label" trackBy="value"
              placeholder="Selecciona uno o más" />
          </div>
        </div>
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
          <Button theme="neutral" title="Cancelar" icon="times" @click="emit('close');" />
          <Button theme="info" title="Guardar" icon="save" type="submit" />
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue';
import { useGlobal } from '@/composables/useGlobal';
const { api, swal, InputField, SelectField, Button } = useGlobal();
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

const emit = defineEmits(['confirm-action', 'close']);

const props = defineProps({
  item: {
    type: Object,
    required: true,
    default: () => ({})
  },
  roles: {
    type: Array,
    required: true,
    default: () => []
  }
});
const errors = ref({});
const loading = ref(false);

const item = reactive({
  ...props.item,
  password: '',
  password_confirmation: ''
});

const options = computed(() => props.roles);

const submitForm = async () => {
  try {
    loading.value = true;
    errors.value = {};
    let response;
    if (item.option === '2') {
      response = await api.put('admin/users', item.id, item);
    } else if (item.option === '1') {
      response = await api.post('admin/users', item);
    }
    if (response?.status === 200) {
      emit('confirm-action');
      swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: response.data.message || 'Operación realizada correctamente',
        timer: 2500,
        showConfirmButton: true
      });
    }
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors;
    } else {
      swal.fire({
        icon: 'error',
        title: 'Error',
        text: err.response?.data?.message || 'Error en la solicitud',
        timer: 2500,
        showConfirmButton: true
      });
    }

  } finally {
    loading.value = false;
  }
};
</script>