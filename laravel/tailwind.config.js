import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        screens: {
            sm: '480px',
            md: '768px',
            lg: '976px',
            xl: '1440px',
        },
        colors: {
            primary: '#3A621D', // Dark Green
            secondary: '#868282', // Grey
            background: '#F3ECE0', // Beige
            white: '#FFFFFF', // White
            black: '#000000', // Black
            red: '#E3342F', // Red
            green: '#38C172', // Green
            blue: '#3490DC', // Blue
            yellow: '#F6993F', // Yellow
            purple: '#9561E2', // Purple
            gray: '#6B7280', // Gray
            lightgray: '#E5E7EB', // Light Gray
            lightgreen: '#007f4b', // Light Green
          },
        extend: {
            fontFamily: {
                inria: ['"Inria Serif"', 'serif'],
            },
            fontWeight: {
                light: 300,
                normal: 400,
                bold: 700,
              },
              fontStyle: {
                normal: 'normal',
                italic: 'italic',
                bold: 'bold',
              },
        },
    },

    plugins: [forms],
};
