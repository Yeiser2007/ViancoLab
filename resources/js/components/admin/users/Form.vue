<template>
  <div class="min-w-full max-h-[70vh] overflow-y-auto p-4">
    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Contenedor flexible que se adapta al contenido -->
      <div :class="['transition-all duration-300', showAdditionalFields ? 'grid grid-cols-1 md:grid-cols-2 gap-6' : 'max-w-2xl mx-auto']">
        <!-- Columna principal (siempre visible) -->
        <div class="space-y-4" :class="{'md:pr-6': showAdditionalFields}">
          <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2">
            Información Básica
          </h3>
          
          <InputField id="name" v-model="form.name" label="Nombre" :errors="errors.name" required autofocus />
          <InputField id="first_name" v-model="form.first_name" label="Primer apellido" :errors="errors.first_name" required />
          <InputField id="last_name" v-model="form.last_name" label="Segundo apellido" :errors="errors.last_name" required />
          <InputField id="email" v-model="form.email" label="Correo electrónico" type="email" :errors="errors.email" required />
          
          <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-4">
            <InputField id="password" v-model="form.password" type="password" label="Contraseña" :errors="errors.password" required />
            <InputField id="password_confirmation" v-model="form.password_confirmation" type="password" 
              label="Confirmar contraseña" :errors="errors.password_confirmation" required />
          </div>
          
          <SelectField 
            id="user-role" 
            v-model="form.role" 
            label="Rol del usuario" 
            :options="rolesList" 
            placeholder="Seleccione un rol" 
            :errors="errors.role"
            @change="handleRoleChange"
          />
        </div>

        <!-- Columna derecha (condicional) -->
        <div class="space-y-4" v-if="showAdditionalFields">
          <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2">
            Información Adicional
          </h3>
          
          <InputField id="middle_name" v-model="form.middle_name" label="Segundo nombre" :errors="errors.middle_name" />
          <InputField id="date_of_birth" v-model="form.date_of_birth" label="Fecha de nacimiento" type="date" :errors="errors.date_of_birth" />
          
          <SelectField 
            id="gender" 
            v-model="form.gender" 
            label="Género" 
            :options="genderOptions" 
            :errors="errors.gender"
          />
          
          <InputField id="phone" v-model="form.phone" label="Teléfono fijo" :errors="errors.phone" />
          <InputField id="mobile" v-model="form.mobile" label="Teléfono móvil" :errors="errors.mobile" />
          <InputField id="curp" v-model="form.curp" label="CURP" :errors="errors.curp" />
          <InputField id="rfc" v-model="form.rfc" label="RFC" :errors="errors.rfc" />
        </div>
      </div>

      <!-- Acciones del formulario -->
      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200" :class="{'max-w-2xl mx-auto': !showAdditionalFields}">
        <button 
          type="button" 
          @click="resetForm" 
          class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Cancelar
        </button>
        <button 
          type="submit" 
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:opacity-70 disabled:cursor-not-allowed flex items-center gap-2"
          :disabled="loading"
        >
          <svg v-if="loading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
          </svg>
          {{ loading ? 'Registrando...' : 'Registrar' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, inject } from 'vue';
import InputField from '../../partials/InputField.vue';
import SelectField from '../../partials/SelectField.vue';

const emit = defineEmits(['registered']);
const api = inject('api');

// Datos del formulario
const form = ref({
  name: '',
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: null,
  middle_name: '',
  date_of_birth: '',
  gender: '',
  phone: '',
  mobile: '',
  curp: '',
  rfc: '',
});

const rolesList = ref([]);
const errors = ref({});
const loading = ref(false);

const genderOptions = [
  { label: 'Masculino', value: 'male' },
  { label: 'Femenino', value: 'female' },
  { label: 'Otro', value: 'other' },
  { label: 'Prefiero no decir', value: 'prefer_not_to_say' }
];

const showAdditionalFields = computed(() => {
  if (!form.value.role) return false;
  
  const basicRoles = ['administrador', 'sistemas', 'coordinador'];
  const selectedRole = rolesList.value.find(r => r.value === form.value.role);
  
  return !basicRoles.includes(selectedRole?.label.toLowerCase());
});

const handleRoleChange = () => {
  console.log('Rol seleccionado:', form.value.role);
};

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
  }
};

const submitForm = async () => {
  if (loading.value) return;

  loading.value = true;
  errors.value = {};

  try {
    const formData = { ...form.value };
    
    if (!showAdditionalFields.value) {
      delete formData.middle_name;
      delete formData.date_of_birth;
      delete formData.gender;
      delete formData.phone;
      delete formData.mobile;
      delete formData.curp;
      delete formData.rfc;
    }

    const response = await axios.post('/register', formData);
    emit('registered', response.data);
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      console.error('Error en el registro:', error);
    }
  } finally {
    loading.value = false;
  }
};

const resetForm = () => {
  form.value = {
    name: '',
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: null,
    middle_name: '',
    date_of_birth: '',
    gender: '',
    phone: '',
    mobile: '',
    curp: '',
    rfc: '',
  };
  errors.value = {};
};

onMounted(() => {
  fetchRoles();
});
</script>