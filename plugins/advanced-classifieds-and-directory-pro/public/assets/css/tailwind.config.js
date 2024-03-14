/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  important: '.acadp',
  prefix: 'acadp-',
  corePlugins: {
    preflight: false,
  },
  content: [
    './public/templates/**/*.php',
    './widgets/templates/**/*.php',
    './premium/public/templates/**/*.php',
    './premium/widgets/templates/**/*.php'
  ],
  safelist: [
    {
      pattern: /acadp-grid-cols-(1|2|3|4|5|6|7|8|9|10|11|12)/,
      variants: ['md'],
    },
    'acadp-animate-spin'
  ],
  theme: { 
    screens: {
      'xs': '475px',
      ...defaultTheme.screens,
    }, 
    extend: {},
  },
  plugins: [],
}
