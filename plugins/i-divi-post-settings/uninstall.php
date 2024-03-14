<?php

/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// DECLARE PLUGIN OPTIONS
// Post Settings
$option_sidebar = 'idivi_post_settings_sidebar';
$option_dot = 'idivi_post_settings_dot';
$option_scroll = 'idivi_post_settings_before_scroll';
$option_title = 'idivi_post_settings_post_title';
$post_options_remember = 'idivi_post_settings_last_used';

//Page Settings
$option_page_sidebar = 'idivi_page_settings_sidebar';
$option_page_dot = 'idivi_page_settings_dot';
$option_page_scroll = 'idivi_page_settings_before_scroll';
$page_options_remember = 'idivi_page_settings_last_used';

//Project Settings
$option_project_sidebar = 'idivi_project_settings_sidebar';
$option_project_dot = 'idivi_project_settings_dot';
$option_project_scroll = 'idivi_project_settings_before_scroll';
$option_project_nav = 'idivi_project_settings_nav';
$project_options_remember = 'idivi_project_settings_last_used';

//Product Settings
$option_product_sidebar = 'idivi_product_settings_sidebar';
$option_product_dot = 'idivi_product_settings_dot';
$option_product_scroll = 'idivi_product_settings_before_scroll';
$product_options_remember = 'idivi_product_settings_last_used';

//DELETE PLUGIN OPTIONS
//Post Settings
remove_theme_mod($option_sidebar);
remove_theme_mod($option_dot);
remove_theme_mod($option_scroll);
remove_theme_mod($option_title);
remove_theme_mod($post_options_remember);
//Page Settings
remove_theme_mod($option_page_sidebar);
remove_theme_mod($option_page_dot);
remove_theme_mod($option_page_scroll);
remove_theme_mod($page_options_remember);
//Project Settings
remove_theme_mod($option_project_sidebar);
remove_theme_mod($option_project_dot);
remove_theme_mod($option_project_scroll);
remove_theme_mod($option_project_nav);
remove_theme_mod($project_options_remember);
//Product Settings
remove_theme_mod($option_product_sidebar);
remove_theme_mod($option_product_dot);
remove_theme_mod($option_product_scroll);
remove_theme_mod($product_options_remember);

// drop a custom database row.
global $wpdb;
$user_id = get_current_user_id();
$wpdb->delete( $wpdb->usermeta, array( 'meta_key' => 'wp_idivi-dismiss',
                                       'user_id' => $user_id )
                                     );

?>
