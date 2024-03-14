<?php
/**
 * Array polyfills
 *
 * ! This file intentionally left without namespace
 *
 * @package WP Polyfills
 * @subpackage FileSystem functions
 */

if ( ! function_exists( 'wp_load_filesystem' ) ) :
	/**
	 * Loads the WordPress filesystem
	 *
	 * @param  array<string, mixed> $args              Arguments to pass to the filesystem class constructor.
	 * @param  string|false         $context           Context for get_filesystem_method().
	 * @param  bool                 $relaxed_ownership Whether to allow Group/World writable.
	 * @return WP_Filesystem_Direct|WP_Filesystem_Base|WP_Filesystem_SSH2|WP_Filesystem_FTPext|WP_Filesystem_ftpsockets
	 */
	function wp_load_filesystem( array $args = array(), $context = false, bool $relaxed_ownership = false ) {
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem( $args, $context, $relaxed_ownership );

		global $wp_filesystem;

		return $wp_filesystem;
	}
endif;
