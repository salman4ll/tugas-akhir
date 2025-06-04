import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                montserrat: ["Montserrat", "sans-serif"],
            },
            animation: {
                slideDown: "slideDown 1.5s linear infinite",
            },
            keyframes: {
                slideDown: {
                    "0%": { backgroundPosition: "0% 0%" },
                    "100%": { backgroundPosition: "0% 100%" },
                },
            },
        },
    },
    plugins: [],
};
