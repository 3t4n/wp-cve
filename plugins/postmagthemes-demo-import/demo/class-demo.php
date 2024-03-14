<?php
/**
 * Demo class
 *
 * @package pmdi
 */

if ( ! class_exists( 'Theme_Demo_Import' ) ) {

	/**
	 * Main class.
	 *
	 * @since 1.0.0
	 */
	class Theme_Demo_Import {

		/**
		 * Singleton instance of Theme_Demo_Import.
		 *
		 * @var Theme_Demo_Import $instance Theme_Demo_Import instance.
		 */
		private static $instance;

		/**
		 * Configuration.
		 *
		 * @var array $config Configuration.
		 */
		private $config;

		/**
		 * Main Theme_Demo_Import instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $config Configuration array.
		 */
		public static function init( $config ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Theme_Demo_Import ) ) {
				self::$instance = new Theme_Demo_Import();
				if ( ! empty( $config ) && is_array( $config ) ) {

					self::$instance->config = $config;
					self::$instance->setup_actions();
				}
			}
		}

		/**
		 * Setup actions.
		 *
		 * @since 1.0.0
		 */
		public function setup_actions() {

			// Disable branding.
			add_filter( 'pt-pmdi/disable_pt_branding', '__return_true' );

			// PMDI import files.
			add_filter( 'pt-pmdi/import_files', array( $this, 'pmdi_files' ), 99 );

			// PMDI after import.
			add_action( 'pt-pmdi/after_import', array( $this, 'pmdi_after_import' ) );
		}

		/**
		 * PMDI files.
		 *
		 * @since 1.0.0
		 */
		public function pmdi_files() {

			$pmdi = isset( $this->config['pmdi'] ) ? $this->config['pmdi'] : array();
			// echo "<pre>";print_r($pmdi);exit;
			return $pmdi;
		}

		/**
		 * PMDI after import.
		 *
		 * @since 1.0.0
		 */
		public function pmdi_after_import() {

			$front_page_id = get_page_by_title( 'Home' );
			update_option( 'show_on_front', 'page' );

			update_option( 'page_on_front', $front_page_id->ID );

			foreach ( $pages as $option_key => $slug ) {
				$result = get_page_by_path( $slug );
				if ( $result ) {
					if ( is_array( $result ) ) {
						$object = array_shift( $result );
					} else {
						$object = $result;
					}

					update_option( $option_key, $object->ID );
				}
			}

			// Set menu locations.
			$menu_details = isset( $this->config['menu_locations'] ) ? $this->config['menu_locations'] : array();
			if ( ! empty( $menu_details ) ) {
				$nav_settings  = array();
				$current_menus = wp_get_nav_menus();

				if ( ! empty( $current_menus ) && ! is_wp_error( $current_menus ) ) {
					foreach ( $current_menus as $menu ) {
						foreach ( $menu_details as $location => $menu_slug ) {
							if ( $menu->slug === $menu_slug ) {
								$nav_settings[ $location ] = $menu->term_id;
							}
						}
					}
				}

				set_theme_mod( 'nav_menu_locations', $nav_settings );
			}
		}
	}

} // End if().
