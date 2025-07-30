<template>
  <aside
    class="bg-primary-800 text-white h-screen overflow-y-auto transition-all duration-300 ease-in-out z-40 transform"
    :class="{
      'w-0': isMobile && collapsed,
      'w-full fixed inset-0 z-50 translate-x-0': isMobile && !collapsed,
      'w-16': !isMobile && collapsed,
      'w-64 md:relative md:translate-x-0': !isMobile && !collapsed,
      '-translate-x-full': isMobile && !isMobileOpen
    }">
    <div class="p-4 flex items-center justify-between sticky top-0 bg-primary-800 z-99999">
      <h2 v-if="!collapsed" class="text-xl font-semibold">Menú</h2>
      <button @click="handleToggle" class="text-white hover:text-gray-300 focus:outline-none">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            :d="collapsed ? 'M13 5l7 7-7 7M5 5l7 7-7 7' : 'M11 19l-7-7 7-7m8 14l-7-7 7-7'" />
        </svg>
      </button>
    </div>
    <nav class="mt-6">
      <ul>
        <li v-for="item in menuItems" :key="item.name" class="px-3 py-2 hover:bg-primary-300 mx-1 rounded"
          :title="collapsed ? item.name : ''">
          <div @click="toggleSubmenu(item.name)" class="flex items-center justify-between cursor-pointer">
            <div class="flex items-center">
              <span class="flex-shrink-0" v-html="item.icon"></span>
              <span v-if="!collapsed" class="ml-3 whitespace-nowrap overflow-hidden overflow-ellipsis">
                {{ item.name }}
              </span>
            </div>
            <svg v-if="item.subItems && !collapsed" 
                 class="w-4 h-4 transition-transform duration-200"
                 :class="{ 'transform rotate-90': openSubmenu === item.name }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          
          <transition name="slide">
            <ul v-if="item.subItems && openSubmenu === item.name && !collapsed" 
                class="ml-6 mt-1 space-y-1">
              <li v-for="subItem in item.subItems" :key="subItem.name" 
                  class="px-2 py-1 hover:bg-primary-400 rounded">
                <a :href="subItem.route" class="flex items-center">
                  <span class="text-sm">{{ subItem.name }}</span>
                </a>
              </li>
            </ul>
          </transition>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  collapsed: Boolean,
  isMobileOpen: Boolean
});

const emit = defineEmits(['toggle-collapse']);

const isMobile = ref(false);
const openSubmenu = ref(null);

const menuItems = ref([
  {
    name: 'Dashboard',
    route: '/dashboard',
    icon: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
          </svg>`,
    permission: ['dashboard.show']
  },
  {
    name: 'Usuarios',
    route: '/users',
    icon: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
          </svg>`,
    permission: ['users.index'],
    subItems: [
      {
        name: 'Lista de Usuarios',
        route: '/users',
        permission: ['users.index']
      },
      {
        name: 'Roles',
        route: '/users/roles',
        permission: ['roles.index']
      },
      {
        name: 'Permisos',
        route: '/users/permissions',
        permission: ['permissions.index']
      }
    ]
  },
  {
    name: 'Configuración',
    route: '/settings',
    icon: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
          </svg>`,
    permission: ['settings.show']
  }
]);

const toggleSubmenu = (menuName) => {
  if (openSubmenu.value === menuName) {
    openSubmenu.value = null;
  } else {
    openSubmenu.value = menuName;
  }
};

const closeSubmenus = (event) => {
  if (!event.target.closest('aside')) {
    openSubmenu.value = null;
  }
};

const checkIsMobile = () => {
  isMobile.value = window.innerWidth < 768;
};

const handleToggle = () => {
  emit('toggle-collapse');
};

onMounted(() => {
  checkIsMobile();
  window.addEventListener('resize', checkIsMobile);
  document.addEventListener('click', closeSubmenus);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkIsMobile);
  document.removeEventListener('click', closeSubmenus);
});
</script>

<style scoped>
[title] {
  position: relative;
}

[title]:hover::after {
  content: attr(title);
  position: absolute;
  left: 100%;
  top: 50%;
  transform: translateY(-50%);
  background: #0661e0;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  white-space: nowrap;
  margin-left: 0.5rem;
  z-index: 10;
}

aside::-webkit-scrollbar {
  width: 4px;
}

aside::-webkit-scrollbar-thumb {
  background: #4B5563;
  border-radius: 2px;
}

aside::-webkit-scrollbar-track {
  background: #076dfc;
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
  max-height: 200px;
  overflow: hidden;
}

.slide-enter-from,
.slide-leave-to {
  max-height: 0;
  opacity: 0;
  transform: translateY(-10px);
}
</style>