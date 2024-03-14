<?php
/*
Plugin Name: WP Admin UI
Plugin URI: https://wpadminui.net/
Description: The best plugin to customize WordPress administration in seconds.
Version: 1.9.10
Author: Benjamin Denis
Author URI: https://wpadminui.net/
License: GPLv2
Text Domain: wp-admin-ui
Domain Path: /languages
*/

/*  Copyright 2015 - 2019 - Benjamin DENIS  (email : contact@wpadminui.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
	exit;
}

function wpui_activation() {
	add_option( 'wpui_activated', 'yes' );
    do_action('wpui_activation');
}
register_activation_hook(__FILE__, 'wpui_activation');

function wpui_deactivation() {
	delete_option( 'wpui_activated' );
    do_action('wpui_deactivation');
}
register_deactivation_hook(__FILE__, 'wpui_deactivation');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Define
///////////////////////////////////////////////////////////////////////////////////////////////////

define( 'WPUI_VERSION', '1.9.10' ); 
define( 'WPUI_AUTHOR', 'Benjamin Denis' ); 

///////////////////////////////////////////////////////////////////////////////////////////////////
//WPUI INIT = Admin + Core + Menu Ajax + Translation
///////////////////////////////////////////////////////////////////////////////////////////////////
function wpui_login_page(){
    $siteurl = str_replace(array('\\','/'), DIRECTORY_SEPARATOR, ABSPATH);
    return ((in_array($siteurl.'wp-login.php', get_included_files()) || in_array($siteurl.'wp-register.php', get_included_files()) ) || $GLOBALS['pagenow'] === 'wp-login.php' || $_SERVER['PHP_SELF'] == '/wp-login.php');
}
function wpui_init() {
    load_plugin_textdomain( 'wp-admin-ui', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    if(wpui_login_page()){
        require_once ( dirname( __FILE__ ) . '/inc/functions/options-login.php'); //Login
    }
    
    if ( is_admin() ) {
        require_once dirname( __FILE__ ) . '/inc/admin/admin.php';
        
        if(current_user_can('manage_options')) {
            require_once dirname( __FILE__ ) . '/inc/admin/pointers.php';
            require_once dirname( __FILE__ ) . '/inc/admin/adminbar.php';
        }
        require_once dirname( __FILE__ ) . '/inc/admin/ajax.php'; 
    }
    require_once dirname( __FILE__ ) . '/inc/functions/options.php';
    require_once dirname( __FILE__ ) . '/inc/functions/options-front.php'; //Front-end
}
add_action('plugins_loaded', 'wpui_init', 999);

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS in admin
///////////////////////////////////////////////////////////////////////////////////////////////////
//WPUI Options page
function wpui_add_admin_options_scripts() {
    wp_register_style( 'wpui-admin', plugins_url('assets/css/wpadminui.css', __FILE__));
    wp_enqueue_style( 'wpui-admin' );

    // Pointers
    wp_enqueue_style( 'wp-pointer' );
    wp_enqueue_script( 'wp-pointer' );
    
    //Tabs
    if (isset($_GET['page']) && ($_GET['page'] == 'wpui-global') ) {
        wp_enqueue_script( 'tabs-js', plugins_url( 'assets/js/wpadminui-tabs.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
    }

    if (isset($_GET['page']) && ($_GET['page'] == 'wpui-library') ) {
        wp_enqueue_script( 'tabs2-js', plugins_url( 'assets/js/wpadminui-tabs2.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
    }

    //Accordeon Menu
    if (isset($_GET['page']) && (($_GET['page'] == 'wpui-admin-menu'))) {
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script( 'wpadminui-custom', plugins_url( 'assets/js/wpadminui-ajax-menu.js', __FILE__ ), array( 'jquery' ), '', true );

        $wpui_ajax_reset_menu = array(
            'wpui_nonce' => wp_create_nonce('wpui_reset_menus_nonce'),
            'wpui_post_url' => admin_url( 'admin-ajax.php'),
        );
        wp_localize_script( 'wpadminui-custom', 'wpuiAjaxResetMenu', $wpui_ajax_reset_menu ); 
    }

    //Dashboard metaboxes
    if (isset($_GET['page']) && ($_GET['page'] == 'wpui-dashboard')) {
        wp_enqueue_script( 'wpui-ajax-dashboard', plugins_url('assets/js/wpadminui-ajax-dashboard.js', __FILE__), array('jquery'), '', false );
        $wpui_ajax_data_dashboard = array(
            'wpui_nonce' => wp_create_nonce('wpui_dasboard_metaboxes_nonce'),
            'wpui_post_url' => admin_url('index.php'),
        );
        wp_localize_script( 'wpui-ajax-dashboard', 'wpuiAjaxDashboard', $wpui_ajax_data_dashboard ); 
    }

    //CPT Metaboxes
    if (isset($_GET['page']) && ($_GET['page'] == 'wpui-metaboxes')) {
        wp_enqueue_script( 'wpui-ajax-metaboxes', plugins_url('assets/js/wpadminui-ajax-metaboxes.js', __FILE__), array('jquery'), '', false );
        $wpui_ajax_data_metaboxes = array(
            'wpui_nonce' => wp_create_nonce('wpui_metaboxes_nonce'),
            'wpui_post_types' => array_values(wpui_get_post_types()),
            'wpui_post_url' => admin_url('post-new.php', 'relative'),
        );
        wp_localize_script( 'wpui-ajax-metaboxes', 'wpuiAjaxMetaboxes', $wpui_ajax_data_metaboxes );
    } 

    //CPT Columns
    if (isset($_GET['page']) && ($_GET['page'] == 'wpui-columns')) {
        wp_enqueue_script( 'wpui-ajax-columns', plugins_url('assets/js/wpadminui-ajax-columns.js', __FILE__), array('jquery'), '', false );
        $wpui_ajax_data_columns = array(
            'wpui_nonce' => wp_create_nonce('wpui_columns_nonce'),
            'wpui_post_types' => array_values(wpui_get_post_types()),
            'wpui_post_url' => admin_url('edit.php', 'relative'),
        );
        wp_localize_script( 'wpui-ajax-columns', 'wpuiAjaxColumns', $wpui_ajax_data_columns );

        $wpui_ajax_data_columns_media = array(
            'wpui_nonce' => wp_create_nonce('wpui_columns_media_nonce'),
            'wpui_media_url' => admin_url('upload.php', 'relative'),
        );
        wp_localize_script( 'wpui-ajax-columns', 'wpuiAjaxColumnsMedia', $wpui_ajax_data_columns_media );
    } 

    //License
    if (isset($_GET['page']) && ($_GET['page'] == 'wpui-license')) {
        wp_enqueue_script( 'wpui-ajax-license', plugins_url('assets/js/wpadminui-ajax-license.js', __FILE__), array('jquery'), '', false );
    }
}

add_action('admin_enqueue_scripts', 'wpui_add_admin_options_scripts', 10, 1);

///////////////////////////////////////////////////////////////////////////////////////////////////
//Shortcut settings page
///////////////////////////////////////////////////////////////////////////////////////////////////

add_filter('plugin_action_links', 'wpui_plugin_action_links', 10, 2);

function wpui_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wpui-option">'.__("Settings","wp-admin-ui").'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Get all registered post types
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpui_get_post_types() {
    global $wp_post_types;

    $args = array(
        'show_ui' => true,
    );

    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'

    $post_types = get_post_types( $args, $output, $operator ); 
    unset($post_types['attachment']);    
    return $post_types;
} 

///////////////////////////////////////////////////////////////////////////////////////////////////
//Get all registered metaboxes
///////////////////////////////////////////////////////////////////////////////////////////////////

//Dashboard
function wpui_dashboard_list_all_widgets() {
    if (isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] == admin_url('admin.php?page=wpui-dashboard')) && current_user_can( 'manage_options' )) {
        global $wp_meta_boxes;
        if (get_option('wpui_dashboard_list_all_widgets') =='' || !get_option('wpui_dashboard_list_all_widgets')) {
            update_option( 'wpui_dashboard_list_all_widgets', $wp_meta_boxes );
            exit();
        } else {
            delete_option('wpui_dashboard_list_all_widgets');
            update_option( 'wpui_dashboard_list_all_widgets', $wp_meta_boxes );
            exit();
        }
    }
}
add_action('wp_dashboard_setup', 'wpui_dashboard_list_all_widgets', 999);

//CPT
function wpui_metaboxes_list_all_widgets() {
    if (isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] == admin_url('admin.php?page=wpui-metaboxes')) && current_user_can( 'manage_options' )) {
        global $wp_meta_boxes;
        $wpui_current_screen = get_current_screen();

        $screen = $wpui_current_screen->post_type;
        $wpui_metaboxes_screen = array($screen, array($wp_meta_boxes[$screen]));

        if (post_type_supports($screen, 'revisions')) {
            $wp_meta_boxes[$screen]['normal']['revisions'] = array('revisionsdiv' => array('id' => 'revisionsdiv', 'title' => __('Revisions'), 'callback' => $screen.'revisions_meta_box'));
        }

        if (post_type_supports($screen, 'comments')) {
            $wp_meta_boxes[$screen]['normal']['comments'] = array('commentsdiv' => array('id' => 'commentsdiv', 'title' => __('Comments'), 'callback' => $screen.'comments_meta_box'));
        }

        if (get_option('wpui_metaboxes_list_all_widgets_'.$screen) !='' || !get_option('wpui_metaboxes_list_all_widgets_'.$screen)) {
            update_option( 'wpui_metaboxes_list_all_widgets_'.$screen, $wpui_metaboxes_screen );
            //exit();
        } else {
            delete_option('wpui_metaboxes_list_all_widgets_'.$screen);
            update_option( 'wpui_metaboxes_list_all_widgets_'.$screen, $wpui_metaboxes_screen );
            //exit();
        }
    }
}
add_action('do_meta_boxes', 'wpui_metaboxes_list_all_widgets', 999);

///////////////////////////////////////////////////////////////////////////////////////////////////
//Get all registered columns
///////////////////////////////////////////////////////////////////////////////////////////////////
//CPT
function wpui_columns_list_all_col($columns) {
    if (isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] == admin_url('admin.php?page=wpui-columns')) && current_user_can( 'manage_options' )) {
        $wpui_current_screen = get_current_screen();

        $screen = $wpui_current_screen->post_type;

        $wpui_col_screen = array('screen' => $screen, 'columns' => $columns);

        if ($screen !='') {
            if (get_option('wpui_columns_list_all_col_'.$screen) !='' || !get_option('wpui_columns_list_all_col_'.$screen)) {
                update_option( 'wpui_columns_list_all_col_'.$screen, $wpui_col_screen );
                //exit();
            } else {
                delete_option('wpui_columns_list_all_col_'.$screen);
                update_option( 'wpui_columns_list_all_col_'.$screen, $wpui_col_screen );
                //exit();
            }
        }
    }
}
function wpui_columns_list_col_media($columns) {
    update_option( 'wpui_columns_list_all_col_media', $columns );
}
function wpui_columns_list_all_col_cpt(){
    if (isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] == admin_url('admin.php?page=wpui-columns')) && current_user_can( 'manage_options' )) {
        foreach (wpui_get_post_types() as $value) {
            add_action('manage_'.$value.'_posts_columns', 'wpui_columns_list_all_col', 999, 1);
        }
        add_action('manage_media_columns', 'wpui_columns_list_col_media', 999, 1);
    }
}
add_action('admin_menu', 'wpui_columns_list_all_col_cpt', 9999);
