<template>
    <div class="mt-4">
        <label :for="id" class="block text-sm font-medium dark:text-black">{{ label }}</label>
        <select :id="id" :value="modelValue"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
            @change="$emit('update:modelValue', $event.target.value)">
            <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
            <option v-for="option in options" :key="option.value" :value="option.value"
                :selected="option.value === modelValue">
                {{ option.label }}
            </option>
        </select>
        <span v-for="error in errors" :key="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
            {{ error }}
        </span>
    </div>
</template>

<script setup>
defineProps({
    id: String,
    label: String,
    modelValue: [String, Number, Boolean],
    options: {
        type: Array,
        required: true,
        validator: (value) => {
            return value.every(option => 'value' in option && 'label' in option)
        }
    },
    placeholder: String,
    required: {
        type: Boolean,
        default: false
    },
    errors: {
        type: Array,
        default: () => []
    }
})

defineEmits(['update:modelValue'])
</script>