<template>
  <div class="flex h-screen bg-gray-100 overflow-hidden">
    <Sidebar :collapsed="isSidebarCollapsed" :is-mobile-open="isMobileSidebarOpen" @toggle-collapse="toggleSidebar"
      class="flex-none" />
    <div v-if="isMobileSidebarOpen" @click="isMobileSidebarOpen = false"
      class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      <Navbar :collapsed="isSidebarCollapsed" :is-mobile-open="isMobileSidebarOpen" :user="user"
        @toggle-collapse="toggleSidebar" @toggle-mobile-sidebar="toggleMobileSidebar" />

      <main class="flex-1 overflow-y-auto p-2 mt-2 h-[calc(100vh-<header-height>)]">
        <div class="mx-auto" :style="contentWidthStyle">
          <slot></slot>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import Sidebar from '../partials/sidebar.vue'
import Navbar from '../partials/navbar.vue'
import { ref, computed } from 'vue'
const props = defineProps({
  user: {
    type: Object,
    default: () => ({})
  }
})
const isSidebarCollapsed = ref(false)
const isMobileSidebarOpen = ref(false)

const contentWidthStyle = computed(() => {
  return {
    'max-width': isSidebarCollapsed.value ? 'calc(100% - 2rem)' : 'calc(100% - 1rem)'
  }
})

const toggleSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
  isMobileSidebarOpen.value = false;
};

const toggleMobileSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
  isMobileSidebarOpen.value = !isMobileSidebarOpen.value;
};

</script>