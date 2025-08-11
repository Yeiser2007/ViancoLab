import { customSwal, ApiService } from './bootstrap';
import { createApp } from 'vue';
import FloatingVue from 'floating-vue';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';

import '../css/app.css';
import 'primeicons/primeicons.css';
import '../css/table.css';

const app = createApp({});
app.config.globalProperties.$swal = customSwal;
app.config.globalProperties.$api = ApiService;
app.provide('swal', customSwal);
app.provide('api', ApiService);

app.config.globalProperties.$user = window.Laravel?.user || null;
app.config.globalProperties.$roles = window.Laravel?.roles || null;

app.directive('can', {
  beforeMount(el, binding) {
    const userPermissions = window.Laravel?.permissions || {};
    const value = binding.value;
    let allowed = false;
    if (Array.isArray(value)) {
      allowed = value.some(perm => userPermissions[perm]);
    } else if (typeof value === 'string') {
      allowed = !!userPermissions[value];
    }

    if (!allowed) {
      el.style.display = 'none';
    }
  }
});

app.directive('can-else', {
  beforeMount(el, binding) {
    if (window.Laravel?.permissions?.[binding.value]) {
      el.style.display = 'none';
    }
  }
});

app.use(FloatingVue)
app.use(PrimeVue, {
  unstyled: true,
  pt: {
    datatable: {
      table: 'table w-full text-sm text-left text-gray-700 border border-gray-300',
      thead: 'bg-secondary-100 text-xs text-gray-700 uppercase',
      tbody: '',
      row: 'hover:bg-gray-500',
    },
    column: {
      headercell: 'px-4 py-3 border-b border-gray-300',
      bodycell: 'px-4 py-2 border-b border-gray-200',
    },
    paginator: {
      root: 'flex flex-wrap items-center justify-center gap-2 border-t border-gray-200 bg-white px-4 py-3 sm:px-6',
      content: 'flex items-center gap-2',
      first: 'px-3 py-1 rounded-md bg-white text-gray-700 hover:bg-gray-100 border disabled:opacity-50 disabled:cursor-not-allowed',
      prev: 'px-3 py-1 rounded-md bg-white text-gray-700 hover:bg-gray-100 border disabled:opacity-50 disabled:cursor-not-allowed',
      next: 'px-3 py-1 rounded-md bg-white text-gray-700 hover:bg-gray-100 border disabled:opacity-50 disabled:cursor-not-allowed',
      last: 'px-3 py-1 rounded-md bg-white text-gray-700 hover:bg-gray-100 border disabled:opacity-50 disabled:cursor-not-allowed',
      page: ({ context }) =>
        `px-3 py-1 rounded-md text-sm border ${context.active
          ? 'bg-primary-500 text-white border-primary-600'
          : 'bg-white text-gray-700 hover:bg-gray-100 border-gray-300'
        }`,
      pages: 'flex gap-1'
    }
  }

});
app.use(ToastService);


import Button from './components/partials/Button.vue';
import Card from './components/Card.vue';
import AppLayout from './layout/AppLayout.vue';
import Users from './components/admin/users/List.vue';
import Roles from './components/admin/roles/List.vue';
import Permissions from './components/admin/permissions/List.vue';

app.component('Boton', Button);
app.component('Card', Card);
app.component('AppLayout', AppLayout);
app.component('users', Users);
app.component('roles', Roles);
app.component('permissions', Permissions);

app.mount('#app');