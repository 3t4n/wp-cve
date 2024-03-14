<?php
/**
 * Plugin Name: ID Arrays
 * Plugin URI:
 * Description: Get comma-delimited lists of post/page IDs by taxonomy/tag, post type, template or user selection from WP list-tables.
 * Version: 2.1.2
 * Author: Harry Mandilas
 * Author URI:
 * License: GPL2
 *
 * Copyright 2016 Harry Mandilas (email : harman79 at gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA
 */

if (!defined('ABSPATH')) {
    die('Get outta here!');
}

if (is_admin()) {

// Add the settings link
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ida79_action_links');
    function ida79_action_links($links)
    {
        $mylinks = array(
            '<a href="' . admin_url('options-general.php?page=id-arrays') . '">Settings</a>',
        );
        return array_merge($links, $mylinks);
    }

// Load the options
    if (file_exists(dirname(__FILE__) . '/id-arrays-options.php')) {
        require_once(dirname(__FILE__) . '/id-arrays-options.php');
    }

// Get the options
    $options = get_option('ida79_options_val');

// ID Column definition
    if (!isset($options['ida79_hide_col']) || $options['ida79_hide_col'] === 'default') {
        function ida79_define_column($columns)
        {
            $options = get_option('ida79_options_val');
            if (!isset($options['ida79_first_col']) || $options['ida79_first_col'] === 'default') {
                $columns = array_slice($columns, 0, count($columns), true) + array('ida_79_col' => __('ID', 'id-arrays')) + array_slice($columns, count($columns), NULL, true);
                return $columns;
            } else {
                $columns = array_slice($columns, 0, 1, true) + array('ida_79_col' => __('ID', 'id-arrays')) + array_slice($columns, 1, NULL, true);
                return $columns;
            }
        }
    }

// Define CSS for the column, buttons and text
    if (!function_exists('ida79_col_css')) {
        function ida79_col_css()
        { ?>
            <style type="text/css">
                #ida_79_col {
                    width: 80px;
                }

                #ida79_btn {
                    margin: 1px 2px 0 20px;
                    position: relative;
                    float: right;
                }

                #ida79Textbox {
                    margin: 2px 6px 0 2px;
                    position: relative;
                    float: right;
                }
            </style>
            <?php
        }
    }
    add_action('admin_head', 'ida79_col_css');

// Create values for IDs column in pages, posts, media
    if (!isset($options['ida79_hide_col']) || $options['ida79_hide_col'] === 'default') {
        if (!function_exists('ida79_values_post_type')) {
            function ida79_values_post_type($column_name, $id)
            {
                if ($column_name == 'ida_79_col') {
                    $options = get_option('ida79_options_val');
                    echo $id;
                }
            }
        }
    }

// Create values for IDs column in taxonomies
    if (!isset($options['ida79_hide_col']) || $options['ida79_hide_col'] === 'default') {
        if (!function_exists('ida79_values_tax_type')) {
            function ida79_values_tax_type($empty = '', $column_name, $id)
            {
                if ($column_name == 'ida_79_col') {
                    $options = get_option('ida79_options_val');
                    echo $id;
                }
            }
        }
    }

// Create values for IDs column in users
    if (!isset($options['ida79_hide_col']) || $options['ida79_hide_col'] === 'default') {
        if (!function_exists('ida79_values_user_type')) {
            function ida79_values_user_type($empty = '', $column_name, $id)
            {
                if ($column_name == 'ida_79_col') {
                    return $id;
                }
            }
        }
    }

// Add ID column and values to standard and custom posts
    if (!isset($options['ida79_hide_col']) || $options['ida79_hide_col'] === 'default') {
        add_action('init', 'ida79_posts_col', 100);
        function ida79_posts_col()
        {
            $post_types = get_post_types();
            foreach ($post_types as $post_type) {
                add_action("manage_edit-${post_type}_columns", 'ida79_define_column', 10, 1);
                add_filter("manage_edit-${post_type}_sortable_columns", 'ida79_define_column', 10, 1);
                add_filter("manage_${post_type}_posts_custom_column", 'ida79_values_post_type', 10, 2);
            }
        }
    }

// Add ID column and values to media
    if (!isset($options['ida79_hide_col']) || $options['ida79_hide_col'] === 'default') {
        add_action("manage_media_columns", 'ida79_define_column', 10, 1);
        add_filter("manage_upload_sortable_columns", 'ida79_define_column', 10, 1);
        add_filter("manage_media_custom_column", 'ida79_values_post_type', 10, 2);
    }


// Add ID column and values to users
    if (!isset($options['ida79_hide_col']) || $options['ida79_hide_col'] === 'default') {
        add_action("manage_users_columns", 'ida79_define_column', 10, 1);
        add_filter("manage_users_sortable_columns", 'ida79_define_column', 10, 1);
        add_filter("manage_users_custom_column", 'ida79_values_user_type', 10, 3);
    }


// Add extra column and values to standard and custom categories
    if (!isset($options['ida79_hide_col']) || $options['ida79_hide_col'] === 'default') {
        add_action('init', 'ida79_taxonomies_col', 100);
        function ida79_taxonomies_col()
        {
            $taxonomies = get_taxonomies();
            foreach ($taxonomies as $taxonomy) {
                add_action("manage_edit-${taxonomy}_columns", 'ida79_define_column', 10, 1);
                add_filter("manage_edit-${taxonomy}_sortable_columns", 'ida79_define_column', 10, 1);
                add_filter("manage_${taxonomy}_custom_column", 'ida79_values_tax_type', 10, 3);
            }
        }
    }


// Load the list tables
    if (!class_exists('WP_List_Table')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    }

    if (!class_exists('ida79_Tax_List_Table', false)) {
        require_once(dirname(__FILE__) . '/includes/id-arrays-tax-list-table.php');
    }

    if (!class_exists('ida79_Post_Type_List_Table', false)) {
        require_once(dirname(__FILE__) . '/includes/id-arrays-post-type-list-table.php');
    }

    if (!class_exists('ida79_Template_List_Table', false)) {
        require_once(dirname(__FILE__) . '/includes/id-arrays-template-list-table.php');
    }


// Add copy selected IDs function for posts and pages
    if (!isset($options['ida79_WPListTable']) || $options['ida79_WPListTable'] === 'default') {
        function ida79_copy_selected_posts()
        {
            $ida79_options = get_option('ida79_options_val');
            $ida79_delimiter = isset($ida79_options['ida79_delimiter']) ? $ida79_options['ida79_delimiter'] : ',&nbsp;';
            $ida79_textbox = isset($ida79_options['ida79_textbox']) ? $ida79_options['ida79_textbox'] : '';
            if (!isset($ida79_options['ida79_textbox']) || $ida79_options['ida79_textbox'] === 'default') {
                $ida79_copy_option = 0; ?>
                <input type="text" id="ida79Textbox"/>
            <?php } else {
                $ida79_copy_option = 1;
            }
            $screen = get_current_screen();
            if ($screen->parent_base == 'edit') { ?>
                <button id="ida79_btn" type="button"
                        onclick="ida79GetSlctedChkbxs('post[]','<?php echo esc_js($ida79_delimiter); ?>', '<?php echo esc_js($ida79_copy_option); ?>')"
                        class="wp-core-ui button">Copy Selected IDs
                </button>
            <?php }
            if ($screen->parent_base == 'upload') { ?>
                <button id="ida79_btn" type="button"
                        onclick="ida79GetSlctedChkbxs('media[]','<?php echo esc_js($ida79_delimiter); ?>', '<?php echo esc_js($ida79_copy_option); ?>')"
                        class="wp-core-ui button">Copy Selected IDs
                </button>
            <?php }
            if ($screen->parent_base == 'users') { ?>
                <button id="ida79_btn" type="button"
                        onclick="ida79GetSlctedChkbxs('users[]','<?php echo esc_js($ida79_delimiter); ?>', '<?php echo esc_js($ida79_copy_option); ?>')"
                        class="wp-core-ui button">Copy Selected IDs
                </button>
            <?php }
        }

        add_filter('restrict_manage_posts', 'ida79_copy_selected_posts');
        add_filter('restrict_manage_users', 'ida79_copy_selected_posts');
    }


// Add copy selected IDs function for taxonomies
    if (!isset($options['ida79_WPListTable']) || $options['ida79_WPListTable'] === 'default') {
        function ida79_copy_selected_tax()
        {
            $ida79_options = get_option('ida79_options_val');
            $ida79_delimiter = isset($ida79_options['ida79_delimiter']) ? $ida79_options['ida79_delimiter'] : ',&nbsp;';
            $ida79_textbox = isset($ida79_options['ida79_textbox']) ? $ida79_options['ida79_textbox'] : '';
            if (!isset($ida79_options['ida79_textbox']) || $ida79_options['ida79_textbox'] === 'default') {
                $ida79_copy_option = 0; ?>
                <input type="text" style="bottom: 4px;" id="ida79Textbox"/>
            <?php } else {
                $ida79_copy_option = 1;
            } ?>
            <button id="ida79_btn" style="bottom: 4px;" type="button"
                    onclick="ida79GetSlctedChkbxs('delete_tags[]','<?php echo esc_js($ida79_delimiter); ?>', '<?php echo esc_js($ida79_copy_option); ?>')"
                    class="wp-core-ui button">Copy Selected IDs
            </button>
        <?php }

        add_action('init', 'ida79_taxonomies', 100);
        function ida79_taxonomies()
        {
            $taxonomies = get_taxonomies();
            foreach ($taxonomies as $taxonomy) {
                add_action("${taxonomy}_pre_add_form", 'ida79_copy_selected_tax', 10, 1);
            }
        }
    }

// Load JS to copy the IDs selected by the user or via the column 
    if (!isset($options['ida79_WPListTable']) || $options['ida79_WPListTable'] === 'default') {
        if (file_exists(dirname(__FILE__) . '/id-arrays-js.js')) {
            function ida79_js($hook)
            {
                if ($hook != 'edit.php' && $hook != 'edit-tags.php' && $hook != 'upload.php' && $hook != 'users.php') {
                    return;
                }
                wp_enqueue_script('id-arrays-js', plugins_url('id-arrays/id-arrays-js.js', dirname(__FILE__)));
            }

            add_action('admin_enqueue_scripts', 'ida79_js');
        }
    }

}