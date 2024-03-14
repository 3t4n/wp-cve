/**
 *   WP Meteor Wordpress Plugin
 *   Copyright (C) 2020  Aleksandr Guidrevitch
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/* eslint import/no-extraneous-dependencies: ["error", {"devDependencies": true}] */
/* eslint global-require: ["off"] */

const mix = require('laravel-mix');
require('laravel-mix-polyfill');

// Variables
const publicPath = 'assets';

// Settings
mix.setPublicPath(publicPath);

mix.options({
  processCssUrls: false,
  autoprefixer: {
    options: {
      grid: true,
    },
  },
});

mix.polyfill({
  enabled: true,
  useBuiltIns: "usage",
  targets: {"ie": 11},
  debug: true,
  corejs: 3, 
});

// Assets build and copying
mix.sass('src/scss/admin/settings.scss', 'css/admin/settings.css');
mix.js('src/js/admin/settings.js', 'js/admin/settings.js')

// Enable sourceMaps for development
mix.sourceMaps(true, 'inline-source-map');

mix.babelConfig({
  presets: [
    "@babel/preset-react"],
  plugins: [
    '@babel/plugin-proposal-class-properties',
    '@babel/plugin-transform-object-assign'],
});

mix.disableNotifications();