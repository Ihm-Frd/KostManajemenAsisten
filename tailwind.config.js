// /** @type {import('tailwindcss').Config} */
// export default {
//   content: [
//     "./resources/**/*.blade.php",
//     "./resources/**/*.js",
//     "./resources/**/*.vue",
//   ],
//   theme: {
//     extend: {},
//   },
//   plugins: [],
// }

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: "class",
    theme: {
        container: {
            center: true,
            padding: "16px",
        },

        extend: {
            colors: {
                ijo: "#14b8a6",
                gelap: "#0f172a",
                pudar: "#475569",
            },

            screens: {
                "2xl": "1320px",
            },
        },
    },

    plugins: [],
};
