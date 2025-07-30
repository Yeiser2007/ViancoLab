<template>
  <nav v-if="!isMobileOpen" class="bg-secondary-500 text-white shadow-md top-0 w-full z-50">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center gap-4">
          <button @click="handleToggle" class="md:hidden text-white focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <span class="text-xl font-bold">MiApp</span>
        </div>
        <div class="ml-4 relative">
          <button @click="toggleDropdown" class="flex items-center text-sm rounded-full focus:outline-none">
            <span class="mr-2">{{ user.name }} {{ user.first_name }} {{ user.last_name }}</span>
            <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </button>

          <div v-show="isDropdownOpen"
            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white text-black py-1 z-50">
            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">Perfil</a>
            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">Configuración</a>
            <a href="#" @click.prevent="logout" class="block px-4 py-2 text-sm hover:bg-gray-100">Cerrar sesión</a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>


<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';

const props = defineProps({
  collapsed: Boolean,
  isMobileOpen: Boolean,
  user: Object
});

const emit = defineEmits(['toggle-collapse', 'toggle-mobile-sidebar']);

const isMobile = ref(false);
const isDropdownOpen = ref(false);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const logout = async () => {
  try {
    await axios.post('/logout');
    window.location.href = '/';
  } catch (error) {
    console.error(error);
  }
};

const checkIsMobile = () => {
  isMobile.value = window.innerWidth < 768;
};

const handleToggle = () => {
  if (isMobile.value) {
    emit('toggle-mobile-sidebar');
  } else {
    emit('toggle-collapse');
  }
};

const closeDropdown = (event) => {
  if (!event.target.closest('.relative')) {
    isDropdownOpen.value = false;
  }
};

onMounted(() => {
  checkIsMobile();
  window.addEventListener('resize', checkIsMobile);
  document.addEventListener('click', closeDropdown);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkIsMobile);
  document.removeEventListener('click', closeDropdown);
});
</script>
