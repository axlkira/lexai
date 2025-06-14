import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2'; // Importar SweetAlert2

window.Alpine = Alpine;
window.Swal = Swal; // Hacer Swal disponible globalmente (opcional, pero útil)

Alpine.start();

// Dark Mode Toggle
document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('theme-toggle');
    const themeDarkIcon = document.getElementById('theme-dark-icon');
    const themeLightIcon = document.getElementById('theme-light-icon');

    // Verifica si los elementos existen antes de operar sobre ellos
    if (themeToggle && themeDarkIcon && themeLightIcon) {
        const applyTheme = () => {
            if (localStorage.getItem('color-theme') === 'dark' ||
                (!('color-theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                themeLightIcon.classList.remove('hidden');
                themeDarkIcon.classList.add('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                themeLightIcon.classList.add('hidden');
                themeDarkIcon.classList.remove('hidden');
            }
        };

        applyTheme(); // Aplica el tema al cargar la página

        themeToggle.addEventListener('click', function() {
            themeLightIcon.classList.toggle('hidden');
            themeDarkIcon.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });
    } else {
        // console.warn('Dark mode toggle elements not found. Ensure they are present in your Blade layout.');
    }
});

