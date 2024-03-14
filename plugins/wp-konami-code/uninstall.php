<?php
/**
 * WP Konami Code Uninstall
 *
 * Uninstalling WP Konami Code delete options.
 */

// If uninstall not called from Wordpress exit 
if( !defined('WP_UNINSTALL_PLUGIN') ) exit();

delete_option('wp_konami_code_options');
