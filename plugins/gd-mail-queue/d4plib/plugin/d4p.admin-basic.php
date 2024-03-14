<?php

/*
Name:    d4pLib - Class - Admin Core - Basic
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

if (!class_exists('d4p_admin_core_basic')) {
    abstract class d4p_admin_core_basic {
        public $url = '';
        public $path = '';
        public $plugin = '';
        public $plugin_prefix = '';
        public $menu_cap = 'activate_plugins';

        public $is_debug;

        public $page = false;
        public $panel = false;
        public $task = false;
        public $action = false;

        public $menu_items;
        public $page_ids = array();

        function __construct() {
            add_filter('plugin_action_links', array($this, 'plugin_actions'), 10, 2);
            add_filter('plugin_row_meta', array($this, 'plugin_links'), 10, 2);
        }

        public function var_handler() {
            return $this->plugin_prefix.'_handler';
        }

        public function plugin_name() {
            return $this->plugin.'/'.$this->plugin.'.php';
        }

        public function file($type, $name, $d4p = false, $min = true, $base_url = null) {
            $get = is_null($base_url) ? $this->url : $base_url;

            if ($d4p) {
                $get.= 'd4plib/resources/';
            }

            if ($name == 'font') {
                $get.= 'font/styles.css';
            } else if ($name == 'flags') {
                $get.= 'flags/flags.css';
            } else {
                $get.= $type.'/'.$name;

                if (!$this->is_debug && $type != 'font' && $min) {
                    $get.= '.min';
                }

                $get.= '.'.$type;
            }

            return $get;
        }

        public function plugin_init() {
            $this->is_debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;

            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));

            add_action('current_screen', array($this, 'current_screen'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        }

        public function admin_init() { }

        public function admin_load_hooks() {
            foreach ($this->page_ids as $id) {
                add_action('load-'.$id, array($this, 'load_admin_page'));
            }
        }

        public function load_admin_page() {
            $this->help_tab_sidebar();

            do_action($this->plugin_prefix.'_load_admin_page_'.$this->page);

            if ($this->panel !== false && $this->panel != '') {
                do_action($this->plugin_prefix.'_load_admin_page_'.$this->page.'_'.$this->panel);
            }

            $this->help_tab_getting_help();
        }

        public function help_tab_sidebar() {
            $links = apply_filters($this->plugin_prefix.'admin_help_sidebar_links', array(
                'home' => '<a target="_blank" href="https://plugins.dev4press.com/'.$this->plugin.'/">'.__("Home Page", "d4plib").'</a>',
                'kb' => '<a target="_blank" href="https://support.dev4press.com/kb/product/'.$this->plugin.'/">'.__("Knowledge Base", "d4plib").'</a>',
                'forum' => '<a target="_blank" href="https://support.dev4press.com/forums/forum/plugins/'.$this->plugin.'/">'.__("Support Forum", "d4plib").'</a>'
            ), $this);

            $this->screen()->set_help_sidebar('<p><strong>'.$this->title().'</strong></p><p>'.join('<br/>', $links).'</p>');
        }

        public function help_tab_getting_help() {
            $this->screen()->add_help_tab(
                array(
                    'id' => 'd4p-help-info',
                    'title' => __("Getting Help", "d4plib"),
                    'content' => '<p>'.__("To get help with this plugin, you can start with Knowledge Base list of frequently asked questions and articles. If you have any questions, or you want to report a bug, or you have a suggestion, you can use support forum. All important links for this are on the right side of this help dialog.", "d4plib").'</p>'
                )
            );

            do_action($this->plugin_prefix.'_admin_help_tabs', $this);
        }

        public function plugin_actions($links, $file) {
            if ($file == $this->plugin_name()) {
                $settings_link = '<a href="'.$this->current_url(false, false).'">'.__("Settings", "d4plib").'</a>';
                array_unshift($links, $settings_link);
            }

            return $links;
        }

        public function plugin_links($links, $file) {
            if ($file == $this->plugin_name()) {
                $links[] = '<a target="_blank" href="https://support.dev4press.com/kb/product/'.$this->plugin.'/">'.__("Knowledge Base", "d4plib").'</a>';
                $links[] = '<a target="_blank" href="https://support.dev4press.com/forums/forum/plugins/'.$this->plugin.'/">'.__("Support Forum", "d4plib").'</a>';
            }

            return $links;
        }

        public function install_notice() {
            if (current_user_can('install_plugins') && $this->page === false) {
                echo '<div class="updated"><p>';
                echo sprintf(__("%s is activated and it needs to finish installation.", "d4plib"), $this->title());
                echo ' <a href="options-general.php?page='.$this->plugin.'">'.__("Click Here", "d4plib").'</a>.';
                echo '</p></div>';
            }
        }

        public function update_notice() {
            if (current_user_can('install_plugins') && $this->page === false) {
                echo '<div class="updated"><p>';
                echo sprintf(__("%s is updated, and you need to review the update process.", "d4plib"), $this->title());
                echo ' <a href="options-general.php?page='.$this->plugin.'">'.__("Click Here", "d4plib").'</a>.';
                echo '</p></div>';
            }
        }

        protected function screen() {
            return get_current_screen();
        }

        protected function load_postget_back() {
            if (isset($_POST[$this->var_handler()]) && $_POST[$this->var_handler()] == 'postback') {
                require_once($this->path.'core/admin/postback.php');
            } else if (isset($_GET[$this->var_handler()]) && $_GET[$this->var_handler()] == 'getback') {
                require_once($this->path.'core/admin/getback.php');
            }
        }

        protected function enqueue_dev4press_library() {
            d4p_admin_enqueue_defaults();

            wp_enqueue_style('d4plib-fontawesome', $this->url.'d4plib/resources/fontawesome/css/font-awesome.min.css', array(), D4P_FONTAWESOME);

            wp_enqueue_style('d4plib-font', $this->file('css', 'font', true), array(), d4p_library_enqueue_ver());
            wp_enqueue_style('d4plib-shared', $this->file('css', 'shared', true), array(), d4p_library_enqueue_ver());
            wp_enqueue_style('d4plib-admin', $this->file('css', 'admin', true), array('d4plib-shared'), d4p_library_enqueue_ver());

            wp_enqueue_script('d4plib-shared', $this->file('js', 'shared', true), array('jquery', 'wp-color-picker'), d4p_library_enqueue_ver(), true);
            wp_enqueue_script('d4plib-admin', $this->file('js', 'admin', true), array('d4plib-shared'), d4p_library_enqueue_ver(), true);

            wp_localize_script('d4plib-shared', 'd4plib_admin_data', array(
                'string_media_image_title' => __("Select Image", "d4plib"),
                'string_media_image_button' => __("Use Selected Image", "d4plib"),
                'string_are_you_sure' => __("Are you sure you want to do this?", "d4plib"),
                'string_image_not_selected' => __("Image not selected.", "d4plib")
            ));

            if ($this->panel == 'about') {
                wp_enqueue_style('d4plib-grid', $this->file('css', 'grid', true), array(), d4p_library_enqueue_ver());
            }
        }

        protected function enqueue_dev4press_library_widgets() {
            wp_enqueue_style('wp-color-picker');

            wp_enqueue_script('suggest');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('jquery-ui-sortable');

            wp_enqueue_style('d4plib-widgets', $this->file('css', 'widgets', true), array('wp-color-picker'),  d4p_library_enqueue_ver());
            wp_enqueue_script('d4plib-widgets', $this->file('js', 'widgets', true), array('jquery', 'suggest', 'wp-color-picker', 'jquery-ui-sortable'),  d4p_library_enqueue_ver(), true);
        }

        abstract public function title();
        abstract public function menu_title();
        abstract public function is_install();
        abstract public function is_update();

        abstract public function admin_menu();
        abstract public function current_screen($screen);
        abstract public function enqueue_scripts($hook);
    }
}
