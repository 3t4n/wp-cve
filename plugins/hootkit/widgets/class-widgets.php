<?php
/**
 * HootKit Widgets Module
 * This file is loaded at 'after_setup_theme' hook @priority 96
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

if ( ! class_exists( '\HootKit\Mods\Widgets' ) ) :

	class Widgets {

		/**
		 * Class Instance
		 */
		private static $instance;

		/**
		 * Active widgets array
		 */
		private $activewidgets = array();

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->activewidgets = hootkit()->get_config( 'activemods', 'widget' );
			if ( !empty( $this->activewidgets ) ) {

				require_once( hootkit()->dir . 'widgets/class-hk-widget.php' );

				$this->load_assets();
				$this->load_widgets();

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

			foreach ( $this->activewidgets as $widget ) {
				if ( !empty( $modules[$widget]['assets'] ) )
					$assets = array_merge( $assets, $modules[$widget]['assets'] );
				if ( !empty( $modules[$widget]['adminassets'] ) )
					$adminassets = array_merge( $adminassets, $modules[$widget]['adminassets'] );
			}

			/* Frontend */
			foreach ( $assets as $asset )
				Helper_Assets::add_asset( $asset );
			if( ! hootkit()->get_config( 'theme_css' ) )
				Helper_Assets::add_asset( hootkit()->slug );
			Helper_Assets::add_asset( 'widgets' );

			/* Admin */
			$hooks = ( defined( 'SITEORIGIN_PANELS_VERSION' ) && version_compare( SITEORIGIN_PANELS_VERSION, '2.0' ) >= 0 ) ?
						array( 'widgets.php', 'post.php', 'post-new.php' ):
						array( 'widgets.php' );
			// SiteOrigin Page Builder compatibility - Load css for Live Preview in backend
			// > Limitation: dynamic css is not loaded // @todo test all widgets (inc sliders)
			// if( $widgetload && hootkit()->get_config( 'theme_css' ) && function_exists( 'hoot_locate_style' ) ) {
			// 	wp_enqueue_style( 'theme-hootkit', hoot_data()->template_uri . 'hootkit/hootkit.css' );
			// 	// wp_enqueue_style( 'theme-style', hoot_data()->template_uri . 'style.css' ); // Loads all styles including headings, grid etc -> Not Needed // Loads grid etc for widget post grid etc -> Needed
			// }
			foreach ( $adminassets as $adminasset )
				Helper_Assets::add_adminasset( $adminasset, $hooks );
			Helper_Assets::add_adminasset( 'wp-color-picker' );
			Helper_Assets::add_adminasset( 'adminwidgets' );

		}

		/**
		 * Load individual widgets
		 *
		 * @since  2.0.0
		 */
		private function load_widgets() {

			foreach ( $this->activewidgets as $widget )
				if ( file_exists( hootkit()->dir . 'widgets/' . sanitize_file_name( $widget ) . '/admin.php' ) )
					require_once( hootkit()->dir . 'widgets/' . sanitize_file_name( $widget ) . '/admin.php' );

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

	Widgets::get_instance();

endif;