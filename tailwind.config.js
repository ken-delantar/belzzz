import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            hidden: {
                display: "flex", // This would be wrong and cause the issue
            },
            colors: {
                "stat-orange": "#FF6B6B",
                "stat-green": "#4CAF50",
                "stat-blue": "#2196F3",
                "stat-pink": "#E91E63",
            },
        },
    },

    plugins: [forms],
};
