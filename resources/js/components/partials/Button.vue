<template>
  <button class="btn inline-flex items-center justify-center gap-2" :class="buttonClasses"
    @click="$emit(eventListener)">
    <i v-if="icon" :class="`pi pi-${icon}`" class="text-current"></i>
    <span v-if="title">{{ title }}</span>
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  theme: {
    type: String,
    required: true,
    validator: (value) => ['primary', 'secondary', 'danger', 'success', 'info', 'warning'].includes(value)
  },
  title: {
    type: String,
    default: null
  },
  eventListener: {
    type: String,
    default: 'click'
  },
  icon: {
    type: String,
    default: null,
    validator: (value) => {
      if (!value) return true;
      return /^[a-z-]+$/.test(value);
    }
  }
});

const buttonClasses = computed(() => {
  const base = {
   primary: 'bg-primary-500 hover:bg-white text-white hover:text-primary-500 hover:border-primary-500',
    secondary: 'bg-secondary-500 hover:bg-white text-white hover:text-secondary-500 hover:border-secondary-500',
    warning: 'bg-warning-500 hover:bg-white text-white hover:text-warning-500 hover:border-warning-500',
    info: 'bg-info-500 hover:bg-white text-white hover:text-info-500 hover:border-info-500',
    danger: 'bg-danger-500 hover:bg-white text-white hover:text-danger-500 hover:border-danger-500',
    success: 'bg-success-500 hover:bg-white text-white hover:text-success-500 hover:border-success-500' 
  };
  return [
    base[props.theme],
    {
      'px-4 py-2': props.title, // Tamaño normal si hay texto
      'p-2': !props.title && props.icon, // Tamaño compacto para solo icono
      'rounded-md': true,
      'border border-transparent': true,
      'transition-colors duration-200': true
    }
  ];
});
</script>