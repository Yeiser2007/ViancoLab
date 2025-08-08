<template>
    <div class="py-3 backdrop-blur-sm rounded-t-2xl flex items-center justify-between border-b-2 mb-3.5">
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="flex items-center justify-center h-8 w-8 sm:h-9 sm:w-9">
                <i :class="`pi pi-${icon} text-secondary-500 text-lg sm:text-xl`"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-secondary-500 sm:text-lg md:text-xl">
                    {{ title }}
                </h2>
            </div>
        </div>
        <slot></slot>
    </div>
</template>

<script setup>
defineProps({
    icon: {
        type: String,
        required: true,
        validator: (value) => /^[a-z-]+$/.test(value) // Valida que sea un icono de PrimeIcons
    },
    title: {
        type: String,
        required: true
    }
});
</script>