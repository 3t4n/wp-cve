<?php
/**
 * Activation.
 *
 */

/**
 * Activation class.
 */
class REVIVESO_Activate
{
	/**
	 * Run plugin activation process.
	 */
	public static function activate() {
		// register action.
		do_action( 'reviveso_plugin_activate' );

		// flush permalinks
		flush_rewrite_rules();
	}
}
