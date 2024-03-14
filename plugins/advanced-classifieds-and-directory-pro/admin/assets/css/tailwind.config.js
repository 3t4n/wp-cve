/** @type {import('tailwindcss').Config} */
module.exports = {
  important: '.acadp',
  prefix: 'acadp-',
  corePlugins: {
    preflight: false,
  },
  content: [
    './admin/templates/**/*.php',
    './widgets/forms/**/*.php',
    './premium/admin/templates/**/*.php',
    './premium/widgets/forms/**/*.php'
  ],
  theme: { 
    extend: {},
  },
  plugins: [],
}
