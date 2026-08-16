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
            colors: {
                brutalist: {
                    bg: '#F5F0E6',
                    primary: '#111111',
                    yellow: '#FFD43B',
                    green: '#9BE564',
                    blue: '#5BC0EB',
                    pink: '#FF7EB6',
                    red: '#FF6B6B',
                }
            },
            fontFamily: {
                sans: ['Hanken Grotesk', ...defaultTheme.fontFamily.sans],
                headline: ['Bricolage Grotesque', 'sans-serif'],
                mono: ['Space Mono', ...defaultTheme.fontFamily.mono],
            },
            boxShadow: {
                'brutal-sm': '2px 2px 0px 0px #111111',
                'brutal': '4px 4px 0px 0px #111111',
                'brutal-md': '6px 6px 0px 0px #111111',
                'brutal-lg': '8px 8px 0px 0px #111111',
                'brutal-xl': '12px 12px 0px 0px #111111',
            },
            borderWidth: {
                '3': '3px',
                '4': '4px',
            }
        },
    },

    plugins: [forms],
};
