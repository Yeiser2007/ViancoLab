<template>
    <div class="mt-4">
        <label :for="id" class="block text-sm font-medium dark:text-black">{{ label }}</label>
        <input 
            :id="id" 
            :value="modelValue" 
            :type="type" 
            :required="required" 
            :autocomplete="autocomplete"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
            @input="$emit('update:modelValue', $event.target.value)" 
        />
        <span v-for="error in errors" :key="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
            {{ error }}
        </span>
    </div>
</template>

<script setup>
defineProps({
    id: String,
    label: String,
    modelValue: [String, Number],
    type: {
        type: String,
        default: 'text'
    },
    required: {
        type: Boolean,
        default: false
    },
    autocomplete: String,
    errors: {
        type: Array,
        default: () => []
    }
});

defineEmits(['update:modelValue']);
</script>