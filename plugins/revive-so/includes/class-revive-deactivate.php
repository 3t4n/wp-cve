<?php
/**
 * Deactivation.
 *
 */

/**
 * Deactivation class.
 */
class REVIVESO_Deactivate
{
	/**
	 * Run plugin deactivation process.
	 */
	public static function deactivate() {
		global $wp_rewrite;
		// remove options.
		delete_option( 'reviveso_plugin_dismiss_rating_notice' );
		delete_option( 'reviveso_plugin_no_thanks_rating_notice' );
		delete_option( 'reviveso_plugin_installed_time' );
		delete_option( 'reviveso_hide_permalink_notice' );
		delete_option( 'reviveso_next_scheduled_timestamp' );

		// Restore permalink structure.
		$permalink_structure = get_option( 'permalink_structure' );
		$permalink_structure = str_replace( array( '%reviveso_', '%revs_' ), '%', $permalink_structure );
		$wp_rewrite->set_permalink_structure( $permalink_structure );

		// register action.
		do_action( 'reviveso_plugin_deactivate' );

		// flush permalinks.
		flush_rewrite_rules();

		// flush cache.
		wp_cache_flush();
	}
}
