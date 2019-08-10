import _ENV from './env';
import AuthHelper from './helpers/AuthHelper';
import AppHelper from './helpers/AppHelper';

window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window._ENV = _ENV;
window.AuthHelper = AuthHelper;
window.AppHelper = AppHelper;

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

axios.interceptors.request.use((config) => {
    const authToken = AuthHelper.getAccessToken();

    if (authToken) {
        config.headers.common['Authorization'] = `Bearer ${authToken}`;
    }

    return config;
}, (error) => {
    return Promise.reject(error);
});

axios.interceptors.response.use((response) => {

    if (response.data.status === 0 && response.data.code == '401') {
        AuthHelper.clearSession();
        window.location = '/login?error=You have been logged out due to inactivity. Please log in.';
    }

    if (response.data.status === 0 && response.data.code == '403') {
        toastr.error(response.data.errors[0], 'Error!');
        response.data.data = [];
    }

    return response.data;
}, (error) => {

    if (error.response.status === 401 && error.response.config.url != '/oauth/token') {
        AuthHelper.clearSession();
        window.location = '/login?error=You have been logged out due to inactivity. Please log in.';
    }

    return Promise.reject(error);
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
