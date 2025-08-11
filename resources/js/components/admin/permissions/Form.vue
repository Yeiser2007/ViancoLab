<template>
  <div class="min-w-full max-h-[80vh]">
    <form @submit.prevent="submitForm">
      <div :class="['transition-all duration-300 will-change-auto max-w-2xl']">
        <div class="space-y-4">
          <InputField id="id" v-model="item.id" type="hidden" :errors="errors.id" required autofocus />
          <InputField id="name" v-model="item.name" label="Nombre" :errors="errors.name" required autofocus />
        </div>
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
          <Button theme="neutral" title="Cancelar" icon="times" @click="emit('close');" />
          <Button theme="info" :title="loading ? 'Procesando...' : 'Guardar'" icon="save" type="submit" :disabled="loading" />
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue';
import { useFormSubmit } from '@/composables/useFormHandler';
import { useGlobal } from '@/composables/useGlobal';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';

const { InputField, Button } = useGlobal();

const emit = defineEmits(['confirm-action', 'close']);

const props = defineProps({
  item: { type: Object, required: true, default: () => ({}) },
});

const { submit, loading, errors } = useFormSubmit();

const item = reactive({
  ...props.item,
});


const submitForm = () => {
  submit({
    method: item.option === '2' ? 'put' : 'post',
    url: 'admin/permissions',
    data: item,
    onSuccess: () => emit('confirm-action')
  });
};
</script>
