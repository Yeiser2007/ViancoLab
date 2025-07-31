import { createApp } from 'vue';
import FloatingVue from 'floating-vue';
import '../css/app.css'
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import 'primeicons/primeicons.css';
import '../css/table.css';
import ApiService from './services/Api';

const app = createApp({});
app.config.globalProperties.$api = ApiService;
app.config.globalProperties.$user = window.Laravel?.user || null;
app.config.globalProperties.$roles = window.Laravel?.roles || null;
app.provide('api', ApiService)
app.directive('can', {
  beforeMount(el, binding) {
    if (!window.Laravel?.permissions?.[binding.value]) {
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


import Button from './components/partials/Button.vue';
import Card from './components/Card.vue';
import AppLayout from './layout/AppLayout.vue';
import Users from './components/admin/users/List.vue';


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
      root: 'flex justify-end items-center gap-2 mt-4',
      pageButton: ({ context }) =>
        `px-3 py-1 rounded-md text-sm ${context.active
          ? 'bg-blue-600 text-white'
          : 'bg-white text-gray-700 hover:bg-gray-100'
        }`,
      current: 'bg-blue-600 text-white px-3 py-1 rounded-md',
    }
  }

});
app.use(ToastService);

app.component('Boton', Button);
app.component('Card', Card);
app.component('AppLayout', AppLayout);
app.component('users', Users);

app.mount('#app');