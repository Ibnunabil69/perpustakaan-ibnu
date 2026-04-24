import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                warm: {
                    50: '#fdf8f0',
                    100: '#f9edda',
                    200: '#f3d9b5',
                    300: '#e8be85',
                    400: '#d9974e',
                    500: '#c97d2e',
                    600: '#b56622',
                    700: '#974e1e',
                    800: '#7c4020',
                    900: '#67361e',
                },
            },
            keyframes: {
                'fade-in-up': {
                    '0%': {
                        opacity: '0',
                    },
                    '100%': {
                        opacity: '1',
                    },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'shimmer': {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.4s ease-out forwards',
                'fade-in': 'fade-in 0.3s ease-out forwards',
                'shimmer': 'shimmer 2s linear infinite',
            }
        },
    },

    plugins: [forms],
};
