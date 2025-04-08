import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // 👈 add this line

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // 👇 Customize your dark theme colors here
                darkBg: '#0f172a', // e.g. a navy dark background
                darkText: '#f1f5f9', // e.g. light gray text
            },
        },
    },

    plugins: [forms],
};
