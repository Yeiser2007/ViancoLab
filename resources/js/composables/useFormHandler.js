// composables/useFormSubmit.js
import { ref } from 'vue';
import { useGlobal } from '@/composables/useGlobal';

export function useFormSubmit() {
  const { api, swal } = useGlobal();
  const loading = ref(false);
  const errors = ref({});

  const submit = async ({ method, url, data, onSuccess }) => {
    try {
      loading.value = true;
      errors.value = {};

      let response;
      if (method === 'post') {
        response = await api.post(url, data);
      } else if (method === 'put') {
        response = await api.put(url, data.id, data);
      }
      if ([200, 201].includes(response?.status)) {
        swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: response.data.message || 'Operación realizada correctamente',
          timer: 2500,
          showConfirmButton: true
        });
        if (onSuccess) onSuccess(response.data);
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

  return { submit, loading, errors };
}
