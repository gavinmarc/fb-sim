const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  purge: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],

  theme: {
    extend: {
      colors: {
        gray: {
          darkest: '#121314',
          dark: '#212325'
        }
      },
      fontFamily: {
        sans: ['Nunito', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  variants: {
    extend: {
      borderWidth: ['last'],
      opacity: ['disabled'],
    },
  },

  plugins: [require('@tailwindcss/forms')],
};
