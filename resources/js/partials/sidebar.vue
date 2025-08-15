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
      <h2 v-if="!collapsed" class="text-xl font-semibold">Doctorado en Ingeniería</h2>
      <button @click="handleToggle" class="text-white hover:text-gray-300 focus:outline-none">
        <i :class="collapsed ? 'pi pi-chevron-right' : 'pi pi-chevron-left'" class="text-lg"></i>
      </button>
    </div>
    
    <nav class="mt-6">
      <ul>
        <li v-for="item in menuItems" v-can="item.permission" v-role="item.role" :key="item.name"
          class="px-3 py-2 hover:bg-primary-300 mx-1 rounded" :title="collapsed ? item.name : ''">
          <div @click="toggleSubmenu(item.name)" class="flex items-center justify-between cursor-pointer">
            <div class="flex items-center">
              <i :class="item.icon" class="text-lg"></i>
              <span v-if="!collapsed" class="ml-3 whitespace-nowrap overflow-hidden overflow-ellipsis">
                {{ item.name }}
              </span>
            </div>
            <i v-if="item.subItems && !collapsed" 
              class="pi pi-chevron-down text-xs transition-transform duration-200"
              :class="{ 'transform rotate-90': openSubmenu === item.name }"></i>
          </div>

          <transition name="slide">
            <ul v-if="item.subItems && openSubmenu === item.name && !collapsed" class="ml-6 mt-1 space-y-1">
              <li v-for="subItem in item.subItems" :key="subItem.name" class="px-2 py-1 hover:bg-primary-400 rounded">
                <a :href="subItem.route" class="flex items-center">
                  <i :class="subItem.icon" class="text-sm mr-2"></i>
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
  // Dashboard
  {
    name: 'Dashboard',
    route: '/dashboard',
    icon: 'pi pi-home',
    permission: ['dashboard.index']
  },
  {
    name: 'Usuarios',
    route: '/users',
    icon: 'pi pi-users',
    role:['administrador'],
    subItems: [
      {
        name: 'Lista de Usuarios',
        route: '/admin/users',
        permission: ['users.index']
      },
      {
        name: 'Roles',
        route: '/admin/roles',
        permission: ['roles.index']
      },
      {
        name: 'Permisos',
        route: '/admin/permissions',
        permission: ['permissions.index']
      }
    ]
  },
  
  // Módulo Estudiantes
  {
    name: 'Mi Progreso',
    route: '/mi-progreso',
    icon: 'pi pi-chart-line',
    role: 'estudiante',
  },
  {
    name: 'Documentación',
    route: '/documentacion',
    icon: 'pi pi-file',
    permission: ['student.documents'],
    subItems: [
      {
        name: 'Requisitos',
        route: '/documentos/requeridos',
        icon: 'pi pi-list',
        permission: ['student.documents.view']
      },
      {
        name: 'Subir Documentos',
        route: '/documentos/subir',
        icon: 'pi pi-upload',
        permission: ['student.documents.upload']
      }
    ]
  },
  {
    name: 'Mi Protocolo',
    route: '/mi-protocolo',
    icon: 'pi pi-book',
    permission: ['student.protocol']
  },
  {
    name: 'Mi Tesis',
    route: '/mi-tesis',
    icon: 'pi pi-file-edit',
    permission: ['student.thesis']
  },
  {
    name: 'Beca CONACYT',
    route: '/mi-beca',
    icon: 'pi pi-wallet',
    permission: ['student.scholarship']
  },
  
  // Módulo Investigadores
  {
    name: 'Estudiantes',
    route: '/asesorados',
    icon: 'pi pi-users',
    permission: ['advisor.students']
  },
  {
    name: 'Revisiones',
    route: '/revisiones',
    icon: 'pi pi-check-circle',
    permission: ['advisor.reviews']
  },
  {
    name: 'Calendario',
    route: '/calendario',
    icon: 'pi pi-calendar',
    permission: ['advisor.calendar']
  },
  
  {
    name: 'Administración',
    route: '/admin',
    icon: 'pi pi-cog',
    role: 'administrador',
    subItems: [
      {
        name: 'Alumnos',
        route: '/admin/students',
        icon: 'pi pi-users',
        permission: ['admin.students']
      },
      {
        name: 'Generaciones',
        route: '/admin/generaciones',
        icon: 'pi pi-sitemap',
        permission: ['admin.generations']
      },
      {
        name: 'Becas',
        route: '/admin/becas',
        icon: 'pi pi-money-bill',
        permission: ['admin.scholarships']
      },
      {
        name: 'Líneas de Investigación',
        route: '/admin/lineas',
        icon: 'pi pi-sitemap',
        permission: ['admin.research_lines']
      },
      {
        name: 'Reportes',
        route: '/admin/reportes',
        icon: 'pi pi-chart-bar',
        permission: ['admin.reports']
      }
    ]
  },
  
  {
    name: 'Mensajes',
    route: '/mensajes',
    icon: 'pi pi-envelope',
    permission: ['messages.access']
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
/* Tus estilos existentes se mantienen igual */
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