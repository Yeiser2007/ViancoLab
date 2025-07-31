// bootstrap.js
import axios from 'axios';
import swal from 'sweetalert2';
import ApiService from './services/Api';

// Configuración de Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';

// Configuración de SweetAlert2
window.swal = swal.mixin({
  customClass: {
    confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2',
    cancelButton: 'bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded'
  },
  buttonsStyling: false
});

window.api = ApiService;

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ea4fef43c6af137e3fca',
    cluster: 'us2',
    forceTLS: true
});
