<?php
namespace OACS\SolidPostLikes\Controllers\App;

if ( ! defined( 'WPINC' ) ) { die; }
/**
 * Fired during plugin deinstallation
 *
 */

 class SolidPostLikesDeinstallator {

	/**
	 * Short Description. (use period)
	 */
	public static function deinstall($prefix) {


		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );

	}

}
