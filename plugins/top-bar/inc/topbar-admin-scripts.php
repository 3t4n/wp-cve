<?php

/* Enqueuing admin scripts. */
add_action('admin_enqueue_scripts', 'add_admin_topbar_style');

function add_admin_topbar_style() {
        wp_enqueue_style('topbar_icon', plugins_url('../css/admin_topbar_icon.css', __FILE__));

        $screen = get_current_screen();

        if ('toplevel_page_topbar-options-menu' == $screen->base){
                wp_enqueue_style('wp-color-picker');
                wp_enqueue_style('topbar_admin_styles', plugins_url('../css/admin_topbar_style.css', __FILE__));
                wp_enqueue_script('topbar_admin_js', plugins_url('../js/tpbr.min.js', __FILE__), ['jquery', 'wp-color-picker']);
        }
    }
