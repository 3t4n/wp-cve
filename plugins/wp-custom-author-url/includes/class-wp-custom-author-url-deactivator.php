<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://chrisjallen.com
 * @since      1.0.0
 *
 * @package    Wp_Custom_Author_Url
 * @subpackage Wp_Custom_Author_Url/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Custom_Author_Url
 * @subpackage Wp_Custom_Author_Url/includes
 * @author     Chris Allen <me@chrisjallen.com>
 */
class Wp_Custom_Author_Url_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( 'wp_custom_author_url_global_options' );

		$meta_type  = 'user';
		$user_id    = 0; // This will be ignored, since we are deleting for all users.
		$meta_keys  = [ 'use_custom_author_url', 'custom_author_url' ];
		$meta_value = ''; // Also ignored. The meta will be deleted regardless of value.
		$delete_all = true;

		foreach ( $meta_keys as $meta_key ) {
			delete_metadata( $meta_type, $user_id, $meta_key, $meta_value, $delete_all );
		}

	}

}
