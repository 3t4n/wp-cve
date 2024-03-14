<?php

/**
 * The uninstall process of WP Post Nav
 * Removes any of the options created by the plugin and cleans up all after itself
 *
 * @link:       https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since      0.0.1
 * @package    wp_post_nav
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN') || ! defined( 'ABSPATH' )) {				
	exit;
}

//delete the array of options from the database	
delete_option ('wp_post_nav_options');
delete_option ('wp_post_nav_version');  
