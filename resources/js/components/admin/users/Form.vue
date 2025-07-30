<template>
  <div class="min-w-md  ">
    <form @submit.prevent="submitForm">
      <div>
        <label for="name" class="block text-sm font-medium  dark:text-black">Nombre</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
          autofocus
          autocomplete="name"
        />
        <span v-for="error in errors.name" :key="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
          {{ error }}
        </span>
      </div>

      <div class="mt-4">
        <label for="first_name" class="block text-sm font-medium  dark:text-black">Primer Apellido</label>
        <input
          id="first_name"
          v-model="form.first_name"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
          autocomplete="given-name"
        />
        <span v-for="error in errors.first_name" :key="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
          {{ error }}
        </span>
      </div>

      <div class="mt-4">
        <label for="last_name" class="block text-sm font-medium  dark:text-black">Segundo Apellido</label>
        <input
          id="last_name"
          v-model="form.last_name"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
          autocomplete="family-name"
        />
        <span v-for="error in errors.last_name" :key="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
          {{ error }}
        </span>
      </div>

      <div class="mt-4">
        <label for="email" class="block text-sm font-medium  dark:text-black">Correo electrónico</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
          autocomplete="email"
        />
        <span v-for="error in errors.email" :key="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
          {{ error }}
        </span>
      </div>

      <div class="mt-4">
        <label for="password" class="block text-sm font-medium  dark:text-black">Contraseña</label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
          autocomplete="new-password"
        />
        <span v-for="error in errors.password" :key="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
          {{ error }}
        </span>
      </div>

      <div class="mt-4">
        <label for="password_confirmation" class="block text-sm font-medium  dark:text-black">Confirmar contraseña</label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          required
          autocomplete="new-password"
        />
        <span v-for="error in errors.password_confirmation" :key="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
          {{ error }}
        </span>
      </div>
      <div>
      <button type="reset">Cancelar</button>
      <button type="submit">Enviar</button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
const emit = defineEmits(['show-login', 'registered']);

const form = ref({
  name: '',
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const errors = ref({});
const loading = ref(false);

const submitForm = async () => {
  if (loading.value) return;
  
  loading.value = true;
  errors.value = {};

  try {
    const response = await axios.post('/register', form.value);
    emit('registered');
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
    } else {
    }
  } finally {
    loading.value = false;
  }
};
</script>