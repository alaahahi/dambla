const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./node_modules/flowbite/**/*.js"
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    darkSelector: '.dark-mode',
    variants: {
        extend: {
          // ...
          margin: ['responsive', 'direction'],
          padding: ['responsive', 'direction'],
          textAlign: ['responsive', 'direction'],
        },
        direction: ['rtl'],
      },

    plugins: [require('@tailwindcss/forms'),require('flowbite/plugin')],
};
