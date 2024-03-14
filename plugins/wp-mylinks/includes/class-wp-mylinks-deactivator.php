<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://walterpinem.me/
 * @since      1.0.0
 *
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/includes
 * @author     Walter Pinem <hello@walterpinem.me>
 */
class Wp_Mylinks_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.5
	 */
	public static function deactivate() {
		// Remove the post type upon deactivation
		unregister_post_type( 'mylink' );
		// Flush the permalinks
		// flush_rewrite_rules();
	}

}
