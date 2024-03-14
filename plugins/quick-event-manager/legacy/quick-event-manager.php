<?php

global  $qem_fs ;
// requires
require_once plugin_dir_path( __FILE__ ) . 'quick-event-options.php';
require_once plugin_dir_path( __FILE__ ) . 'quick-event-register.php';
require_once plugin_dir_path( __FILE__ ) . 'quick-event-payments.php';
require_once plugin_dir_path( __FILE__ ) . 'quick-event-widget.php';
require_once plugin_dir_path( __FILE__ ) . 'quick-event-editor.php';
require_once plugin_dir_path( __FILE__ ) . 'shortcodes/qem-qem.php';
require_once plugin_dir_path( __FILE__ ) . 'shortcodes/qem-qemcalendar.php';
require_once plugin_dir_path( __FILE__ ) . 'shortcodes/qem-qemregistration.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-action-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-block-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-calendar-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-filter-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-utility-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-event-build-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-paypal-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-ics-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-csv-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-user-functions.php';
require_once plugin_dir_path( __FILE__ ) . '/qem-event-cpt-functions.php';
if ( is_admin() ) {
    require_once plugin_dir_path( __FILE__ ) . '/quick-event-manager-settings.php';
}
// add admin body class filter
// get the current admin page hook
global  $pagenow ;
add_filter( 'admin_body_class', function ( $classes ) {
    global  $page_hook ;
    if ( !empty($page_hook) && false !== strpos( $page_hook, 'qem' ) ) {
        $classes .= ' qem-admin-page';
    }
    return $classes;
} );
// filters
add_filter(
    'use_block_editor_for_post_type',
    function ( $bool, $post_type ) {
    if ( 'event' === $post_type ) {
        return false;
    }
    return $bool;
},
    10,
    2
);
add_filter( 'pre_get_posts', 'qem_add_custom_types' );
add_filter(
    'qem_short_desc',
    'qem_short_desc_filter',
    10,
    3
);
add_filter(
    'qem_description',
    'qem_description_filter',
    10,
    1
);
add_filter(
    'plugin_action_links',
    'event_plugin_action_links',
    10,
    2
);
add_filter( 'wp_dropdown_users', 'qem_users' );
add_filter( 'the_content', 'get_event_content' );
// actions - calendar
add_action( 'wp_ajax_qem_ajax_calendar', 'qem_ajax_calendar' );
add_action( 'wp_ajax_nopriv_qem_ajax_calendar', 'qem_ajax_calendar' );
// actions - ics
add_action( 'wp_ajax_qem_download_ics', 'qem_download_ics' );
add_action( 'wp_ajax_nopriv_qem_download_ics', 'qem_download_ics' );
// actions - shortcodes
add_shortcode( 'qem', 'qem_event_shortcode_esc' );
add_shortcode( 'qem-calendar', 'qem_show_calendar_esc' );
add_shortcode( 'qemcalendar', 'qem_show_calendar_esc' );
add_shortcode( 'qemregistration', 'qem_loop_esc' );
// actions - scripts and styles
add_action( 'wp_enqueue_scripts', 'qem_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'qem_enqueue_scripts' );
add_action( 'wp_head', 'qem_head_ic' );
// some meta and some that probably should be enqueued
// actions - custom post types
add_action( 'init', 'event_register' );
// actions - widgets ( legacy )
add_action( 'widgets_init', 'add_qem_widget' );
add_action( 'widgets_init', 'add_qem_calendar_widget' );
// actions - blocks
add_action( 'init', 'qem_block_init' );
// actions - admin
add_action( 'admin_menu', 'remove_menus', 100 );
add_action( 'admin_menu', 'event_page_init' );
// actions - paypal / payments
add_action( 'template_redirect', 'qem_ipn' );
// actions - activation
register_activation_hook( __FILE__, 'qem_flush_rules' );
register_activation_hook( __FILE__, 'qem_add_role' );
// theme support
add_theme_support( 'post-thumbnails', array( 'post', 'page', 'event' ) );
$display = event_get_stored_display();
if ( $display['recentposts'] ) {
    add_action( 'pre_get_posts', 'qem_add_custom_post_type_to_query' );
}
add_action( 'pre_get_posts', 'qem_admin_edit_table_order' );