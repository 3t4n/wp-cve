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

if (!class_exists('d4p_plugin_customizer_core')) {
    abstract class d4p_plugin_customizer_core {
        protected $_is_debug = false;

        protected $_lib_path = '';
        protected $_lib_url = '';

        protected $_prefix = 'd4p-';
        protected $_panel = 'dev4press-panel';
        protected $_defaults = array();
        protected $_controls = array();

        public function __construct() {
            $this->_init();

            add_action('customize_controls_enqueue_scripts', array($this, 'enqueue'));
            add_action('customize_register', array($this, 'register'));

            $this->_is_debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;
        }

        /**
         * @return WP_Customize_Manager
         */
        public function c() {
            global $wp_customize;

            return $wp_customize;
        }

        public function prefix() {
            return $this->_prefix;
        }

        public function name($name) {
            return $this->prefix().$name;
        }

        public function section($name) {
            return $this->_panel.'-'.$name;
        }

        public function get_default($name) {
            if (isset($this->_defaults[$name])) {
                return $this->_defaults[$name];
            }

            return '';
        }

        public function get($name) {
            if (isset($this->_defaults[$name])) {
                $value = get_theme_mod($this->name($name), $this->_defaults[$name]);

                return apply_filters($this->prefix().'_customizer_get_option_value_'.$name, $value);
            }

            return null;
        }

        public function enqueue() {
            wp_enqueue_style('d4p-customizer', $this->_file('css', 'customizer'), array(), D4P_VERSION);
            wp_enqueue_script('d4p-customizer', $this->_file('js', 'customizer'), array('jquery','customize-preview'), D4P_VERSION, true);
        }

        public function register() {
            foreach ($this->_controls as $ctrl) {
                d4p_include($ctrl, 'customizer', $this->_lib_path);
            }

            $this->_panels();
            $this->_sections();
            $this->_settings();
            $this->_controls();
        }

        public function sanitize_checkbox($input) {
            return $input ? true : false;
        }

        public function sanitize_slider($input, $setting) {
            $atts = $setting->manager->get_control($setting->id)->input_attrs;

            $min = $atts['min'] ?? $input;
            $max = $atts['max'] ?? $input;
            $step = $atts['step'] ?? 1;

            $number = $step != 1 ? floor($input / $step) * $step : $input;

            return $this->_in_range($number, $min, $max);
        }

        protected function _in_range($input, $min, $max) {
            if ($input < $min) {
                $input = $min;
            }

            if ($input > $max) {
                $input = $max;
            }

            return $input;
        }

        protected function _file($type, $name, $min = true) {
            $get = $this->_lib_url.'resources/'.$type.'/'.$name;

            if (!$this->_is_debug && $min) {
                $get.= '.min';
            }

            $get.= '.'.($type == 'libraries' ? 'js' : $type);

            return $get;
        }

        abstract protected function _init();

        abstract protected function _panels();
        abstract protected function _sections();
        abstract protected function _settings();
        abstract protected function _controls();
    }
}
