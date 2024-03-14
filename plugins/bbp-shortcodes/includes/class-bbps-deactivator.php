<?php
/**
 * Fires during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0
 * @package    BBPS
 * @subpackage BBPS/includes
 * @author     Free WPTP <mozillavvd@gmail.com>
 */

if ( ! class_exists( 'BBPS_Deactivator' ) ) {

	class BBPS_Deactivator {

		/**
		 * The code that runs during plugin deactivation.
		 *
		 * @since    1.0
		 */
		public static function deactivate() {
			$opt = get_option( 'bbpress_shortcodes' );

			if ( isset( $opt['dismiss_admin_notices'] ) ) {
				unset( $opt['dismiss_admin_notices'] );
				update_option( 'bbpress_shortcodes', $opt );
			}
		}
	}
}
