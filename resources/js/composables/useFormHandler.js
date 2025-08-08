// src/composables/useFormHandler.js
import { ref, reactive, computed } from 'vue';
import { useGlobal } from './useGlobal';

export function useFormHandler({ endpoint, initialData = {}, roles = [] }) {
  const { api, swal } = useGlobal();

  const errors = ref({});
  const loading = ref(false);

  const item = reactive({
    ...initialData,
    password: '',
    password_confirmation: ''
  });

  const options = computed(() => roles);

  const submitForm = async (emit) => {
    try {
      loading.value = true;
      errors.value = {};
      let response;

      if (item.option === '2') {
        response = await api.put(endpoint, item.id, item);
      } else if (item.option === '1') {
        response = await api.post(endpoint, item);
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

  return {
    item,
    options,
    errors,
    loading,
    submitForm
  };
}
