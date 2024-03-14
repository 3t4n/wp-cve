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
const webpack = require('webpack/');

// Variables
const proxy = process.env.LOCAL_URL;
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

const buildOptions = process.env.DEBUG
  ? {}
  : { "loose": true };

mix.webpackConfig({
  plugins: [
    new webpack.DefinePlugin({
      'process.env': {
        DEBUG: process.env.DEBUG ? true : false
      }
    })
  ]
})

mix.babelConfig({
  presets: [
    ["@babel/preset-env", Object.assign({targets: { esmodules: true }}, buildOptions)]],
    // ["@babel/preset-env", Object.assign({}, buildOptions)]],
    plugins: [
    // ['@babel/plugin-proposal-class-properties', buildOptions],
    // ['@babel/plugin-transform-object-assign', buildOptions],
  ]
})

mix.disableNotifications();

module.exports = mix;
