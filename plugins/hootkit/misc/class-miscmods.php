<?php
/**
 * HootKit Misc Module
 * This file is loaded at 'after_setup_theme' hook @priority 95
 *
 * @since   2.0.0
 * @package Hootkit
 */

namespace HootKit\Mods;
use \HootKit\Inc\Helper_Assets;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\HootKit\Mods\MiscMods' ) ) :

	class MiscMods {

		/**
		 * Class Instance
		 */
		private static $instance;

		/**
		 * Active miscmods array
		 */
		private $activemiscmods = array();

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->activemiscmods = hootkit()->get_config( 'activemods', 'misc' );
			if ( !empty( $this->activemiscmods ) ) {

				$modules = hootkit()->get_mods( 'modules' );
				foreach ( $this->activemiscmods as $miscmod ) {
					if ( !empty( $modules[$miscmod]['requires'] ) && \is_array( $modules[$miscmod]['requires'] ) && in_array( 'customizer', $modules[$miscmod]['requires'] ) ) {
						require_once( hootkit()->dir . 'misc/customizer.php' );
						break;
					}
				}

				$this->load_assets();
				$this->load_miscmods();

			}

		}

		/**
		 * Load assets
		 *
		 * @since  2.0.0
		 */
		private function load_assets() {

			$modules = hootkit()->get_mods( 'modules' );
			$assets = array();
			$adminassets = array();

			foreach ( $this->activemiscmods as $miscmod ) {
				if ( !empty( $modules[$miscmod]['assets'] ) )
					$assets = array_merge( $assets, $modules[$miscmod]['assets'] );
				if ( !empty( $modules[$miscmod]['adminassets'] ) )
					$adminassets = array_merge( $adminassets, $modules[$miscmod]['adminassets'] );
			}

			/* Frontend */
			foreach ( $assets as $asset )
				Helper_Assets::add_asset( $asset );
			if( ! hootkit()->get_config( 'theme_css' ) )
				Helper_Assets::add_asset( hootkit()->slug );
			Helper_Assets::add_asset( 'miscmods' );
			add_action( 'wp_enqueue_scripts', array( $this, 'localize_script' ), 11 );

			/* Admin */
			// @todo: load font-awesome in customizer (example: Helper_Mods::$mods['fly-cart'] )
			$hooks = array();
			foreach ( $adminassets as $adminasset )
				Helper_Assets::add_adminasset( $adminasset, $hooks );

		}

		/**
		 * Pass script data
		 *
		 * @since  2.0.0
		 */
		public function localize_script() {
			wp_localize_script(
				hootkit()->slug . '-miscmods',
				'hootkitMiscmodsData',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' )
				)
			);
		}

		/**
		 * Load individual miscmods
		 *
		 * @since  2.0.0
		 */
		private function load_miscmods() {

			foreach ( $this->activemiscmods as $miscmod )
				if ( file_exists( hootkit()->dir . 'misc/' . sanitize_file_name( $miscmod ) . '/admin.php' ) )
					require_once( hootkit()->dir . 'misc/' . sanitize_file_name( $miscmod ) . '/admin.php' );

		}

		/**
		 * Returns the instance
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

	}

	MiscMods::get_instance();

endif;