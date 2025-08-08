<template>
  <button :type="type" v-if="hasPermission" class="btn inline-flex items-center justify-center gap-2" :class="buttonClasses" :disabled="disabled">
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
    validator: (value) => ['primary', 'secondary', 'danger', 'success', 'info', 'warning','neutral'].includes(value)
  },
  title: {
    type: String,
    default: null
  }, 
  icon: {
    type: String,
    default: null,
    validator: (value) => {
      if (!value) return true;
      return /^[a-z-]+$/.test(value);
    }
  },
  permission: {
    type: String,
    default: null
  },
  disabled: {
    type: Boolean,
    default: false
  },
  type: {
    type: String,
    default: 'button'
  }
});

const hasPermission = computed(() => {
  return !props.permission || true;
});

const buttonClasses = computed(() => {
  const base = {
    primary: 'bg-primary-500 hover:bg-white text-white hover:text-primary-500 hover:border-primary-500',
    secondary: 'bg-secondary-500 hover:bg-white text-white hover:text-secondary-500 hover:border-secondary-500',
    warning: 'bg-warning-500 hover:bg-white text-white hover:text-warning-500 hover:border-warning-500',
    info: 'bg-info-500 hover:bg-white text-white hover:text-info-500 hover:border-info-500',
    danger: 'bg-danger-500 hover:bg-white text-white hover:text-danger-500 hover:border-danger-500',
    success: 'bg-success-500 hover:bg-white text-white hover:text-success-500 hover:border-success-500',
    neutral : 'bg-neutral-500 hover:bg-white text-white hover:text-neutral-500 hover:border-neutral-500'  
  };

  return [
    base[props.theme],
    {
      'px-4 py-2': props.title,
      'p-2': !props.title && props.icon,
      'rounded-md': true,
      'border border-transparent': true,
      'transition-colors duration-200': true,
      'opacity-50 cursor-not-allowed': props.disabled
    }
  ];
});

</script>