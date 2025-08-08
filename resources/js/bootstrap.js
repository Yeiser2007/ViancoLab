import axios from 'axios';
import swal from 'sweetalert2';
import ApiService from './services/Api';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

const customSwal = swal.mixin({
  customClass: {
    confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2',
    cancelButton: 'bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded'
  },
  buttonsStyling: false
});
window.swal = customSwal;
window.api = ApiService;

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ea4fef43c6af137e3fca',
    cluster: 'us2',
    forceTLS: true
});
export { customSwal, ApiService };
