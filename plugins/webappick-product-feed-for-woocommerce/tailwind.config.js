/** @type {import('tailwindcss').Config} */
const plugin = require("tailwindcss/plugin");
module.exports = {
  content: ["./V5/src/**/*.{html,js, jsx,ts}"],
  prefix: "ctx-",
  theme: {
    extend: {
      colors: {
        themeColor: "linear-gradient(125deg, #3cb0fd 0, #6c5ce7 140%)",
        themeHoverColor: "#3cb0fd !important",
      },
      screens: {
        "2.5xl": "1830px",
        "3xl": "2200px",
      },
    },
  },
  plugins: [],
};
