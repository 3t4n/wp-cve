<?php
/**
 * HootKit Assets Loader
 *
 * @package Hootkit
 */

namespace HootKit\Inc;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\HootKit\Inc\Helper_Assets' ) ) :

	class Helper_Assets {

		/**
		 * Class Instance
		 */
		private static $instance;

		/**
		 * Registered Assets
		 * [ style => [ slug => [src,deps,ver,media] ] script => [ slug => [src,deps,ver,in_footer] ] ]
		 */
		private static $assets = array();

		/**
		 * Assets to Load
		 * [ style => [ slug => [] ] script => [ slug => [] ] ]
		 */
		private static $load = array();

		/**
		 * Admin Assets to Load
		 * [ style => [ slug => [ hooks=>[] ] ] script => [ slug => [ hooks=>[] ] ] ]
		 */
		private static $load_admin = array();

		/**
		 * Screen hooks to Enqueue Media (admin screens)
		 */
		private static $load_media_hooks = array();

		/**
		 * Constructor
		 */
		public function __construct() {
			if ( empty( self::$assets ) ) {
				add_action( 'plugins_loaded', array( $this, 'assets_list' ) );
			}
			add_action( 'wp_enqueue_scripts',    array( $this, 'wp_enqueue' )    , 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) , 10 );
		}

		public function wp_enqueue()           { $this->enqueue();              }
		public function admin_enqueue( $hook ) { $this->enqueue( $hook, true ); }

		/**
		 * Enqueue Scripts and Styles
		 *
		 * @since  2.0.0
		 * @access private
		 * @return void
		 */
		private function enqueue( $hook = false, $admin = false ) {

			if ( $admin && !empty( self::$load_media_hooks ) && \in_array( $hook, self::$load_media_hooks ) )
				wp_enqueue_media();

			$loadassets = ( $admin ) ? self::$load_admin : self::$load;

			foreach ( $loadassets as $slug => $props ) {

				$hookmatches = ( ( empty( $props['hooks'] ) || !\is_array( $props['hooks'] ) ) ?
								 true :
								 \in_array( $hook, $props['hooks'] )
								);

				if ( $hookmatches && array_key_exists( $slug, self::$assets ) ) {
					foreach ( self::$assets[ $slug ] as $type => $args ) {

						if ( $type == 'style' ) {
							$handle = !empty( $args['handle'] )    ? $args['handle']    : $slug;
							$src    = !empty( $args['src'] )       ? $args['src']       : '';
							$deps   = !empty( $args['deps'] )      ? $args['deps']      : '';
							$ver    = !empty( $args['ver'] )       ? $args['ver']       : hootkit()->version;
							$media  = !empty( $args['media'] )     ? $args['media']     : '';
							if ( $src ) wp_enqueue_style( $handle, $src, $deps, $ver, $media );
							else        wp_enqueue_style( $slug );
						}
	
						elseif ( $type == 'script' ) {
							$handle = !empty( $args['handle'] )    ? $args['handle']    : $slug;
							$src    = !empty( $args['src'] )       ? $args['src']       : '';
							$deps   = !empty( $args['deps'] )      ? $args['deps']      : '';
							$ver    = !empty( $args['ver'] )       ? $args['ver']       : hootkit()->version;
							$footer = !empty( $args['in_footer'] ) ? $args['in_footer'] : '';
							if ( $src ) wp_enqueue_script( $handle, $src, $deps, $ver, $footer );
							else        wp_enqueue_script( $slug );
						}

					}
				}

			}

		}

		/**
		 * Add assets
		 *
		 * @since  2.0.3
		 * @access public
		 * @return void
		 */
		public static function add_asset( $slug ) {
			self::$load[ $slug ] = array();
		}
		public static function add_adminasset( $slug, $hooks = array() ) {
			if ( $slug == 'wp-media' )
				self::$load_media_hooks = array_merge( self::$load_media_hooks, $hooks );
			else
				self::$load_admin[ $slug ] = array( 'hooks' => $hooks );
		}

		/**
		 * Get asset file uri
		 *
		 * @since  1.0.0
		 * @access public
		 * @param string $location
		 * @param string $type
		 * @return string
		 */
		public static function get_uri( $location, $type ) {
			$dir = hootkit()->dir;
			$uri = hootkit()->uri;

			$location = str_replace( array( $dir, $uri ), '', $location );

			// Return minified uri if not in debug mode and minified file is available
			if (
				( ! defined( 'HKIT_DEBUG' ) || ! HKIT_DEBUG ) &&
				( file_exists( $dir . "{$location}.min.{$type}" ) )
			) {
				return $uri . "{$location}.min.{$type}";
			}

			// Return uri if file is available
			if ( file_exists( $dir . "{$location}.{$type}" ) )
				return $uri . "{$location}.{$type}";
			elseif ( file_exists( $dir . "{$location}.min.{$type}" ) ) // debug true, but unminified doesnt exist
				return $uri . "{$location}.min.{$type}";

			return '';

		}

		/**
		 * Array of available Scripts and Styles
		 * >> after hootkit() is available (constructor executed)
		 *
		 * @since  2.0.0
		 * @access public
		 * @return void
		 */
		public function assets_list() {
			self::$assets = array(

				/*** frotnend ***/

				'lightslider' => array(
					'style' => array(
						'handle' => 'lightSlider',
						'src'    => self::get_uri( 'assets/lightSlider', 'css' ),
						'deps'   => '',
						'ver'    => '1.1.2',
						'media'  => '',
					),
					'script' => array(
						'handle'    => 'jquery-lightSlider',
						'src'       => self::get_uri( 'assets/jquery.lightSlider', 'js' ),
						'deps'      => array( 'jquery' ),
						'ver'       => '1.1.2',
						'in_footer' => true,
					),
				),

				'font-awesome' => array(
					'style' => array(
						'src'    => self::get_uri( 'assets/font-awesome', 'css' ),
						'ver'    => '5.0.10',
					),
				),

				hootkit()->slug => array(
					'style' => array(
						'src'    => self::get_uri( 'assets/hootkit', 'css' ),
					),
				),

				'circliful' => array(
					'script' => array(
						'handle'    => 'jquery-circliful',
						'src'       => self::get_uri( 'assets/jquery.circliful', 'js' ),
						'deps'      => array( 'jquery' ), // ::=> Hootkit does not load Waypoints. It is upto the theme to deploy waypoints.
						'ver'       => '20160309',
						'in_footer' => true,
					),
				),

				'widgets' => array(
					'script' => array(
						'handle'    => hootkit()->slug . '-widgets',
						'src'       => self::get_uri( 'assets/widgets', 'js' ),
						'deps'      => array( 'jquery' ),
						'in_footer' => true,
					),
				),

				'miscmods' => array(
					'script' => array(
						'handle'    => hootkit()->slug . '-miscmods',
						'src'       => self::get_uri( 'assets/miscmods', 'js' ),
						'deps'      => array( 'jquery' ),
						'in_footer' => true,
					),
				),

				/*** admin ***/

				'select2' => array(
					'style' => array(
						'src'    => self::get_uri( 'admin/assets/select2', 'css' ),
						'ver'    => '4.0.7',
					),
					'script' => array(
						'src'       => self::get_uri( 'admin/assets/select2', 'js' ),
						'deps'      => array( 'jquery' ),
						'ver'       => '4.0.7',
						'in_footer' => true,
					),
				),

				'adminsettings' => array(
					'style' => array(
						'handle' => hootkit()->slug . '-adminsettings',
						'src'    => self::get_uri( 'admin/assets/settings', 'css' ),
					),
					'script' => array(
						'handle'    => hootkit()->slug . '-adminsettings',
						'src'       => self::get_uri( 'admin/assets/settings', 'js' ),
						'deps'      => array( 'jquery' ),
					),
				),

				'adminwidgets' => array(
					'style' => array(
						'handle' => hootkit()->slug . '-adminwidgets',
						'src'    => self::get_uri( 'admin/assets/widgets', 'css' ),
					),
					'script' => array(
						'handle'    => hootkit()->slug . '-adminwidgets',
						'src'       => self::get_uri( 'admin/assets/widgets', 'js' ),
						'deps'      => array( 'jquery', 'select2', 'wp-color-picker' ),
						'in_footer' => true,
					),
				),

				'wp-color-picker' => array(
					'style' => array(),
					'script' => array(),
				),

			);
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

	Helper_Assets::get_instance();

endif;