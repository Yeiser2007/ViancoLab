import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
const palettes = require('./utils/tailwind/paletteConfig.js');
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './storage/framework/views/*.php',
    ],
    darkMode: false,
    theme: {
        extend: {
            colors: {
                ...palettes,
            },
            spacing: {
                'header': '4rem',
                'sidebar': '16rem'
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
