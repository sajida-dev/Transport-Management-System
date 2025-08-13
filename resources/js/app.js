/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import './bootstrap';


import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

if (!window.Alpine) {
    Alpine.plugin(focus);
    window.Alpine = Alpine;
    Alpine.start();
}
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';

window.showToast = function (message, type = 'success') {
    Toastify({
        text: message,
        duration: 4000,
        gravity: 'top',
        position: 'right',
        backgroundColor: type === 'success' ? '#22c55e' : '#ef4444',
        close: true,
        stopOnFocus: true,
    }).showToast();
};

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import './components/Example';

document.addEventListener('livewire:load', () => {
    Livewire.hook('component.initialized', (component) => {
        // Replace this ID if your component has a different one
        const TARGET_COMPONENT_ID = '5fzYDP5w85HirNydw4nw';

        if (component.id === TARGET_COMPONENT_ID) {
            const comp = window.Livewire.find(component.id);
            if (comp) {
                comp.on('loggedOut', () => {
                    clearTimeout(window.logoutTimeout);
                    window.logoutShown = true;
                    window.logoutTimeout = setTimeout(() => {
                        window.logoutShown = false;
                    }, 2000);
                });
            }
        }
    });
});
