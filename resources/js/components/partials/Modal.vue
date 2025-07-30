<template>
  <Teleport to="body">
    <transition enter-active-class="transition-opacity duration-300 ease-out"
      leave-active-class="transition-opacity duration-200 ease-in" enter-from-class="opacity-0"
      leave-to-class="opacity-0">
      <div v-if="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center w-screen h-screen">
        <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity w-full h-full"
          :class="{ 'cursor-pointer': backdropClose }" @click="closeBackdrop"></div>
        <transition enter-active-class="transition-all duration-300 ease-out"
          leave-active-class="transition-all duration-200 ease-in"
          enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
          <div :class="[
            'relative z-10 transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all mr-6 ml-6',
            modalSizeClasses,
            modalClasses
          ]" role="dialog" aria-modal="true" aria-labelledby="modal-title" @click.stop>
            <div v-if="hasHeader" class="flex items-center justify-between border-b p-4">
              <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                {{ title }}
              </h3>
              <button type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none"
                @click="closeModal">
                <span class="sr-only">Cerrar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="p-4">
              <slot></slot>
            </div>

            <div v-if="$slots.footer" class="border-t bg-gray-50 px-4 py-3">
              <slot name="footer"></slot>
            </div>
          </div>
        </transition>
      </div>
    </transition>
  </Teleport>
</template>


<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
  title: {
    type: String,
    default: ''
  },
  modalClasses: {
    type: String,
    default: ''
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg', 'xl', 'full'].includes(value)
  },
  hasHeader: {
    type: Boolean,
    default: true
  },
  backdropClose: {
    type: Boolean,
    default: true
  },
  backdropCloseConfirm: {
    type: Boolean,
    default: false
  },
  backdropCloseConfirmText: {
    type: String,
    default: '¿Estás seguro?'
  },
  modelValue: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'close'])

const isOpen = ref(props.modelValue)

watch(() => props.modelValue, (newVal) => {
  isOpen.value = newVal
})

const modalSizeClasses = computed(() => {
  const sizes = {
    sm: 'sm:max-w-sm w-full',
    md: 'sm:max-w-md w-full',
    lg: 'sm:max-w-lg w-full',
    xl: 'sm:max-w-xl w-full',
    full: 'sm:max-w-[90vw] w-full'
  }
  return sizes[props.size]
})

const closeModal = () => {
  isOpen.value = false
  emit('update:modelValue', false)
  emit('close')
}

const closeBackdrop = () => {
  if (!props.backdropClose) return

  if (props.backdropCloseConfirm && !confirm(props.backdropCloseConfirmText)) {
    return
  }

  closeModal()
}
</script>