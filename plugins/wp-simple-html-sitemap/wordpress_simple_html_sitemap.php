<?php
/**
 * Plugin Name: WordPress Simple HTML Sitemap
 * Plugin URI: http://wordpress.org/plugins/wp-simple-html-sitemap/
 * Description: Using WordPress Simple HTML Sitemap plugin, you can add HTML Sitemap anywhere on the website using Shortcode.
 * Author: Ashish Ajani
 * Version: 2.8
 * Author: Ashish Ajani
 * Author URI: http://freelancer-coder.com/
 * License: GPLv2 or later
*/ 
 
 

/* Security: Considered blocking direct access to PHP files by adding the following line. */
defined('ABSPATH') or die("No script kiddies please!");

require_once( ABSPATH . '/wp-includes/shortcodes.php' );

/* Define plugin constants */
define('WSHS_POST_LIST_VERSION', '1.1');
define('WSHS_DATABASE_VERSION', '1.1');
define('WSHS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define("WSHS_PLUGIN_URL", plugins_url('', __FILE__));
define("WSHS_PLUGIN_CSS", WSHS_PLUGIN_URL . '/css/');
define("WSHS_PLUGIN_JS", WSHS_PLUGIN_URL . '/js/');
define('WSHS_SAVED_CODE_TABLE', 'wshs_saved_code');

/* Plugin activation process */
register_activation_hook(__FILE__, 'wshs_plugin_install');
function wshs_plugin_install() {
    error_log('Debug: Activating the plugin.');

}

function wshs_update_db_check() {
    error_log('Debug: Database update check triggered.');
    if (get_site_option( 'wshs_db_version' ) != WSHS_DATABASE_VERSION) {
        wshs_create_saved_code_table();
    }
}
add_action( 'plugins_loaded', 'wshs_update_db_check' );




/* Plugin deactivation process */
register_deactivation_hook(__FILE__, 'wshs_plugin_deactivate');
function wshs_plugin_deactivate() {
    error_log('Debug: Deactivating the plugin.');
    // Silence is golden
}

/* Add menu to WordPress sidebar menu. */

function wshs_admin_menu() {
    error_log('Debug: Adding admin menu.');
    add_menu_page('WordPress Simple HTML Sitemap','WordPress Simple HTML Sitemap' , 'manage_options', 'wshs_page_list', 'wshs_page_list', plugins_url('/wp-simple-html-sitemap/images/sitemap.png'));
    //add_submenu_page( 'options-general.php', 'WordPress Simple HTML Sitemap', 'WordPress Simple HTML Sitemap', 'manage_options', 'wshs_page_list', 'wshs_page_list' );
    add_submenu_page('wshs_page_list', 'WordPress Simple HTML Sitemap - Pages', 'Pages', 'manage_options', 'wshs_page_list', 'wshs_page_list');
    add_submenu_page('wshs_page_list', 'WordPress Simple HTML Sitemap - Posts', 'Posts', 'manage_options', 'wshs_post_list', 'wshs_post_list');
    add_submenu_page('wshs_page_list', 'WordPress Simple HTML Sitemap - Saved Shortcodes', 'Saved Shortcodes', 'manage_options', 'wshs_saved', 'wshs_saved');
    add_submenu_page('wshs_page_list', 'WordPress Simple HTML Sitemap - Documentation', 'Documentation', 'manage_options', 'wshs_documentation', 'wshs_documentation');
}

add_action('admin_menu', 'wshs_admin_menu');

/* Enqueue admin script */
function wshs_admin_assets($hook) {
    error_log('Debug: Enqueueing admin assets.');
    // JS
    wp_enqueue_script('wshs_main_script', WSHS_PLUGIN_JS . 'wshs_script.js');
    wp_localize_script( 'wshs_main_script', 'wshs_ajax_object',
		array( 
            'ajax_nonce' => wp_create_nonce('ajax-nonce'),
			'placeholder_image' => plugin_dir_url('').'/wp-simple-html-sitemap/',
		)
	);

    //CSS
    wp_enqueue_style('wshs_admin_css', WSHS_PLUGIN_CSS . 'wshs_style.css');
}

add_action('admin_enqueue_scripts', 'wshs_admin_assets');

/* Plugins setting hooks */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wshs_add_action_links');

function wshs_add_action_links($links) {
    error_log('Debug: add action link');
    $mylinks = array(
        '<a href="admin.php?page=wshs_page_list">Settings</a>',
    );
    return array_merge($links, $mylinks);
}

// Plugins meta hooks
add_filter('plugin_row_meta', 'wshs_plugin_row_meta', 10, 2);

function wshs_plugin_row_meta($links, $file) {
    error_log('Debug: plugin row meta');
    if (plugin_basename(__FILE__) == $file) {
        $row_meta = array(
            'visitpage' => '<a href="' . esc_url('https://wordpress.org/plugins/wp-simple-html-sitemap/') . '" target="_blank" aria-label="' . esc_attr__('Visit WordPress.org page', 'domain') . '" >' . esc_html__('Visit WordPress.org page', 'domain') . '</a>',
            'rate' => '<a href="' . esc_url('https://wordpress.org/support/plugin/wp-simple-html-sitemap/reviews/?rate=5#new-post') . '" target="_blank" aria-label="' . esc_attr__('Rate this plugin', 'domain') . '" >' . esc_html__('Rate this plugin', 'domain') . '</a>'
        );

        return array_merge($links, $row_meta);
    }
    return (array) $links;
}

add_filter('plugin_action_links', 'wshs_details_link', 10, 3);

function wshs_details_link($links, $plugin_file, $plugin_data) {
    error_log('Debug: details link');
    if (isset($plugin_data['PluginURI']) && false !== strpos($plugin_data['PluginURI'], 'http://wordpress.org/extend/plugins/')) {
        $slug = basename($plugin_data['PluginURI']);
        $links[] = sprintf('<a href="%s" class="thickbox" title="%s">%s</a>', self_admin_url('plugin-install.php?tab=plugin-information&amp;plugin=' . $slug . '&amp;TB_iframe=true&amp;width=600&amp;height=550'), esc_attr(sprintf(__('More information about %s'), $plugin_data['Name'])), __('Details')
        );
    }
    return $links;
}

if(!function_exists('wshs_create_saved_code_table')){
    function wshs_create_saved_code_table(){
        error_log('Debug: create saved code table');
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}".WSHS_SAVED_CODE_TABLE."` (
        id bigint(50) NOT NULL AUTO_INCREMENT,
        title varchar(100),
        code_type varchar(100),
        user_id bigint(20),
        attributes text(2000),
        created_at timestamp,
        updated_at timestamp,
        PRIMARY KEY (id)
        ) $charset_collate;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
        $is_error = empty( $wpdb->last_error );
        if($is_error){
            update_option('wshs_db_version', WSHS_DATABASE_VERSION);
        }
    }
}


/* Include other files */
require_once WSHS_PLUGIN_PATH . '/inc/wshs_admin_view.php';
require_once WSHS_PLUGIN_PATH . '/inc/wshs_front_view.php';
require_once WSHS_PLUGIN_PATH . '/inc/wshs_page_list.php';
require_once WSHS_PLUGIN_PATH . '/inc/wshs_post_list.php';
require_once WSHS_PLUGIN_PATH . '/inc/wshs_saved.php';
require_once WSHS_PLUGIN_PATH . '/inc/wshs_documentation.php';