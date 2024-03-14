<?php

/*
Name:    d4pLib - Class - Admin Core - Options
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

if (!class_exists('d4p_admin_core_options')) {
    abstract class d4p_admin_core_options extends d4p_admin_core_basic {
        public function __construct() {
            parent::__construct();
        }

        public function var_screen_id() {
            return 'settings_page_'.$this->plugin;
        }

        public function current_screen($screen) {
            if ($screen->id == $this->var_screen_id()) {
                $this->page = true;
            }

            if ($this->page) {
                if (isset($_GET['panel']) && $_GET['panel'] != '') {
                    $this->panel = d4p_sanitize_slug($_GET['panel']);
                } else {
                    $this->panel = 'settings';
                }

                if (isset($_GET['task']) && $_GET['task'] != '') {
                    $this->task = d4p_sanitize_slug($_GET['task']);
                }
            }

            $this->load_postget_back();
        }

        public function current_url($with_panel = true, $with_task = true) {
            $page = 'options-general.php?page='.$this->plugin;

            if ($with_panel && $this->panel !== false && $this->panel != '') {
                $page.= '&panel='.$this->panel;
            }

            if ($with_task && isset($this->task) && $this->task !== false && $this->task != '') {
                $page.= '&task='.$this->task;
            }

            return self_admin_url($page);
        }

        public function plugin_init() {
            parent::plugin_init();

            if ($this->is_install()) {
                add_action('admin_notices', array($this, 'install_notice'));
            }

            if ($this->is_update()) {
                add_action('admin_notices', array($this, 'update_notice'));
            }
        }

        public function admin_menu() {
            $this->page_ids[] = add_options_page($this->title(), $this->menu_title(), $this->menu_cap, $this->plugin, array($this, 'plugin_interface'));

            $this->admin_load_hooks();
        }

        public function install_or_update() {
            $install = $this->is_install();
            $update = $this->is_update();

            if ($install) {
                include($this->path.'forms/install.php');
            } else if ($update) {
                include($this->path.'forms/update.php');
            }

            return $install || $update;
        }

        public function plugin_interface() {
            if (!$this->install_or_update()) {
                require_once($this->path.'forms/front.php');
            }
        }
    }
}
