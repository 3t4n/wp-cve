<?php
/**
 * Misc batch import tasks.
 *
 * @package Demo Importer Plus
 */

if ( ! class_exists( 'Demo_Importer_Plus_Batch_Processing_Misc' ) ) :

	/**
	 * Demo_Importer_Plus_Batch_Processing_Misc
	 */
	class Demo_Importer_Plus_Batch_Processing_Misc {

		/**
		 * Instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {}

		/**
		 * Import
		 *
		 * @return void
		 */
		public function import() {

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'Processing "MISC" Batch Import' );
			}

			Demo_Importer_Plus_Sites_Importer_Log::add( '--- Processing MISC ---' );
			self::fix_nav_menus();
		}

		/**
		 * Import Module Images.
		 *
		 * @return object
		 */
		public static function fix_nav_menus() {

			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'Setting Nav Menus' );
			}

			$demo_data = get_option( 'demo_importer_plus_import_data', array() );
			if ( ! isset( $demo_data['post-data-mapping'] ) ) {
				return;
			}

			$xml_url = ( isset( $demo_data['wxr-path'] ) ) ? esc_url( $demo_data['wxr-path'] ) : '';
			if ( empty( $xml_url ) ) {
				return;
			}

			$site_url = strpos( $xml_url, '/wp-content' );
			if ( false === $site_url ) {
				return;
			}

			// Get remote site URL.
			$site_url = substr( $xml_url, 0, $site_url );

			$post_ids = self::get_menu_post_ids();
			if ( is_array( $post_ids ) ) {
				foreach ( $post_ids as $post_id ) {
					if ( defined( 'WP_CLI' ) ) {
						WP_CLI::line( 'Post ID: ' . $post_id );
					}
					Demo_Importer_Plus_Sites_Importer_Log::add( 'Post ID: ' . $post_id );
					$menu_url = get_post_meta( $post_id, '_menu_item_url', true );

					if ( $menu_url ) {
						$menu_url = str_replace( $site_url, site_url(), $menu_url );
						update_post_meta( $post_id, '_menu_item_url', $menu_url );
					}
				}
			}
		}

		/**
		 * Get all post id's
		 *
		 * @return array
		 */
		public static function get_menu_post_ids() {

			$args = array(
				'post_type'     => 'nav_menu_item',

				'fields'        => 'ids',
				'no_found_rows' => true,
				'post_status'   => 'any',
			);

			$query = new WP_Query( $args );

			// Have posts?
			if ( $query->have_posts() ) :

				return $query->posts;

			endif;
			return null;
		}

	}

	/**
	 * Starting this by calling 'get_instance()' method
	 */
	Demo_Importer_Plus_Batch_Processing_Misc::get_instance();

endif;
