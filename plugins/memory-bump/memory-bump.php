<?php
/*
 * Plugin Name: Memory Bump
 * Plugin URI: http://wordpress.org/development/2010/06/thelonious/
 * Description: If you are trying to update to WordPress 3.0 and you are frozen on "Downloading..." or seeing "Fatal error: Allowed memory size exhausted" errors, don't fear! Simply activate this plugin and try again. Once you've installed 3.0, you can deactivate it again at any time.
 * Version: 0.1
 * Author: the WordPress team
 * Author URI: http://wordpress.org
 */

function kitteh_memory_bump() {
	if ( current_user_can( 'manage_options' ) )
		@ini_set( 'memory_limit', '256M' );
}
add_action( 'admin_init', 'kitteh_memory_bump' );