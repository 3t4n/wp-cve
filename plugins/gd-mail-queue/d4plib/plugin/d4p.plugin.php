<?php

/*
Name:    d4pLib - Class - Plugin Core
Version: v2.8.13
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) { exit; }

if (!class_exists('d4p_plugin_core')) {
    abstract class d4p_plugin_core {
        public $widgets = array();
        public $enqueue = false;
        public $cap = 'activate_plugins';
        public $plugin = '';
        public $url = '';

        public $is_debug;
        public $wp_version;
        public $wp_version_real;

        public $js_locale = array();

        public function __construct() {
            add_action('plugins_loaded', array($this, 'plugins_loaded'));
            add_action('after_setup_theme', array($this, 'after_setup_theme'));
        }

        public function file($type, $name, $d4p = false, $min = true, $base_url = null) {
            $get = is_null($base_url) ? $this->url : $base_url;

            if ($d4p) {
                $get.= 'd4plib/resources/';
            }

            $get.= $type.'/'.$name;

            if (!$this->is_debug && $type != 'font' && $min) {
                $get.= '.min';
            }

            $get.= '.'.$type;

            return $get;
        }

        public function plugins_loaded() {
            global $wp_version;

            $this->wp_version = substr(str_replace('.', '', $wp_version), 0, 2);
            $this->wp_version_real = $wp_version;

            if (!empty($this->widgets)) {
                add_action('widgets_init', array($this, 'widgets_init'));
            }

            if ($this->enqueue) {
                add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            }

            $this->is_debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;

            $this->load_textdomain();
            $this->init_capabilities();
        }

        public function load_textdomain() {
            load_plugin_textdomain($this->plugin, false, $this->plugin.'/languages');
            load_plugin_textdomain('d4plib', false, $this->plugin.'/d4plib/languages');
        }

        public function locale() {
            return apply_filters('plugin_locale', get_user_locale(), $this->plugin);
        }

        public function locale_js_code($script) {
            $locale = $this->locale();

            if (!empty($locale) && isset($this->js_locale[$script])) {
                $code = strtolower(substr($locale, 0, 2));

                if (in_array($code, $this->js_locale[$script])) {
                    return $code;
                }
            }

            return false;
        }
   
        public function init_capabilities() {
            $role = get_role('administrator');

            if (!is_null($role)) {
                $role->add_cap($this->cap);
            } else {
                $this->cap = 'activate_plugins';
            }
        }

        public function after_setup_theme() {}

        public function widgets_init() {}

        public function enqueue_scripts() {}
    }
}
