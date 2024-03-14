<?php

/*
Name:    d4pLib_Class_Grid
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

if (!class_exists('d4p_grid')) {
    abstract class d4p_grid extends WP_List_Table {
        public $total = 0;

        public $_sanitize_orderby_fields = array();
        public $_checkbox_field = '';
        public $_table_class_name = '';

        protected function get_table_classes() {
            $classes = parent::get_table_classes();

            if (!empty($this->_table_class_name)) {
                $classes[] = $this->_table_class_name;
            }

            return $classes;
        }

        public function get_column_info_simple() {
            $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        }

        protected function get_sortable_columns() {
            return array();
        }

        public function get_row_classes($item) {
            return array();
	    }

        public function single_row($item) {
            $classes = $this->get_row_classes($item);

            echo '<tr'.(empty($classes) ? '' : ' class="'.join(' ', $classes).'"').'>';
            $this->single_row_columns($item);
            echo '</tr>';
	    }

        protected function column_default($item, $column_name){
            return $item->$column_name;
        }

        protected function column_cb($item){
            $key = $this->_checkbox_field;

            return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->$key);
        }

        public function sanitize_field($name, $value, $default = '') {
            switch ($name) {
                case 'orderby':
                    if (in_array($value, $this->_sanitize_orderby_fields)) {
                        return $value;
                    } else {
                        return $default;
                    }
	            case 'order':
                    $value = strtoupper($value);

                    if (in_array($value, array('ASC', 'DESC'))) {
                        return $value;
                    } else {
                        return $default;
                    }
            }
        }
    }
}
