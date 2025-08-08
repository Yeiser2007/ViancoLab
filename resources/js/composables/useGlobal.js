import { inject } from 'vue';
import InputField from '../components/partials/InputField.vue';
import SelectField from '../components/partials/SelectField.vue';
import Button from '../components/partials/Button.vue';


export function useGlobal() {
  const api = inject('api');
  const swal = inject('swal');

  if (!InputField || !SelectField || !Button) {
    throw new Error('InputField or SelectField not provided');
  }

  if (!api || !swal) {
    throw new Error('api or swal not provided');
  }

  return { api, swal, InputField, SelectField, Button };
}
