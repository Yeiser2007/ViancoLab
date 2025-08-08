<template>
    <span :class="[
        'inline-block px-2 py-1 text-xs font-semibold rounded-full text-white',
        colors[type] || colors.default
    ]">
        {{ text }}
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    text: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'default',
        validator: (value) => [
            'default', 'success', 'warning',
            'danger', 'info', 'primary'
        ].includes(value)
    }
});

const colors = {
    default: 'bg-gray-500',
    success: 'bg-success-500',
    warning: 'bg-warning-700',
    danger: 'bg-danger-500',
    info: 'bg-info-500',
    primary: 'bg-primary-500',
    secondary: 'bg-secondary-500 '
};
</script>