<template lang="">
    <div>
        <h1>{{ title }}</h1>
        <Button :theme="'info'" :title="'Agregar +'" :eventListener="'opern-modal'"></Button>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue'
import Button from './partials/Button.vue';
const title = ref('Esto es un Card')
</script>
<style lang="">
    
</style>