import axios from 'axios';
window.axios = axios;
window.swal = require('sweetalert2');
window.swal = swal.mixin({
    customClass: {
        confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2',
        cancelButton: 'bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded'
    },
    buttonsStyling: false
});

window.api = require("./services/Api");

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ea4fef43c6af137e3fca',
    cluster: 'us2',
    forceTLS: true
});
