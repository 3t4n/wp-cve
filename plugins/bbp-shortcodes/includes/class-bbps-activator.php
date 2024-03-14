<?php
/**
 * Fires during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0
 * @package    BBPS
 * @subpackage BBPS/includes
 * @author     Free WPTP <mozillavvd@gmail.com>
 */

if ( ! class_exists( 'BBPS_Activator' ) ) {

	class BBPS_Activator {

		/**
		 * The code that runs during plugin activation.
		 *
		 * @since    1.0
		 */
		public static function activate() {
			$opt = get_option( 'bbpress_shortcodes' );

			if ( ! isset( $opt['bbpress_shortcodes_posts'] ) ) {

				$args = array( 'public' => true );
				$posts = get_post_types( $args );
				$post_keys = array_keys( $posts );

				foreach ( $post_keys as $post_key ) {
					$opt['bbpress_shortcodes_posts'][ $post_key ] = $post_key;
				}

				update_option( 'bbpress_shortcodes', $opt );
			}
		}
	}
}
