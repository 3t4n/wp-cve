<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoRarConfig' ) ) {

	class WpssoRarConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssorar' => array(			// Plugin acronym.
					'version'     => '3.1.0',	// Plugin version.
					'opt_version' => '7',		// Increment when changing default option values.
					'short'       => 'WPSSO RAR',	// Short plugin name.
					'name'        => 'WPSSO Ratings and Reviews',
					'desc'        => 'Ratings and Reviews for WordPress Comments with Schema Aggregate Rating and Schema Review Markup.',
					'slug'        => 'wpsso-ratings-and-reviews',
					'base'        => 'wpsso-ratings-and-reviews/wpsso-ratings-and-reviews.php',
					'update_auth' => '',		// No premium version.
					'text_domain' => 'wpsso-ratings-and-reviews',
					'domain_path' => '/languages',

					/*
					 * Required plugin and its version.
					 */
					'req' => array(
						'wpsso' => array(
							'name'          => 'WPSSO Core',
							'home'          => 'https://wordpress.org/plugins/wpsso/',
							'plugin_class'  => 'Wpsso',
							'version_const' => 'WPSSO_VERSION',
							'min_version'   => '17.14.4',
						),
					),

					/*
					 * URLs or relative paths to plugin banners and icons.
					 */
					'assets' => array(

						/*
						 * Icon image array keys are '1x' and '2x'.
						 */
						'icons' => array(
							'1x' => 'images/icon-128x128.png',
							'2x' => 'images/icon-256x256.png',
						),
					),

					/*
					 * Library files loaded and instantiated by WPSSO.
					 */
					'lib' => array(
						'submenu' => array(
							'ratings-reviews' => 'Ratings / Reviews',
						),
					),

					/*
					 * Declare compatibility with WooCommerce HPOS.
					 *
					 * See https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book.
					 */
					'wc_compat' => array(
						'custom_order_tables',
					),
				),
			),

			/*
			 * Additional add-on setting options.
			 */
			'opt' => array(
				'defaults' => array(
					'rar_add_to_attachment'            => 0,		// Rating Form for Post Types.
					'rar_add_to_download'              => 1,
					'rar_add_to_page'                  => 1,
					'rar_add_to_post'                  => 0,
					'rar_add_to_product'               => 1,
					'rar_add_to_recipe'                => 1,
					'rar_rating_required'              => 1,		// Rating Required to Submit Review.
					'rar_star_color_selected'          => '#81d742',	// Selected Star Rating Color.
					'rar_star_color_default'           => '#dddddd',	// Unselected Star Rating Color.
					'plugin_avg_rating_col_attachment' => 0,
					'plugin_avg_rating_col_download'   => 1,
					'plugin_avg_rating_col_post'       => 0,
					'plugin_avg_rating_col_page'       => 1,
					'plugin_avg_rating_col_product'    => 1,
					'plugin_avg_rating_col_recipe'     => 1,
				),
			),
		);

		public static function get_version( $add_slug = false ) {

			$info =& self::$cf[ 'plugin' ][ 'wpssorar' ];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_file ) {

			if ( defined( 'WPSSORAR_VERSION' ) ) {	// Define constants only once.

				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssorar' ];

			/*
			 * Define fixed constants.
			 */
			define( 'WPSSORAR_FILEPATH', $plugin_file );
			define( 'WPSSORAR_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-ratings-and-reviews/wpsso-ratings-and-reviews.php.
			define( 'WPSSORAR_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_file ) ) ) );
			define( 'WPSSORAR_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-ratings-and-reviews.
			define( 'WPSSORAR_URLPATH', trailingslashit( plugins_url( '', $plugin_file ) ) );
			define( 'WPSSORAR_VERSION', $info[ 'version' ] );

			/*
			 * Define variable constants.
			 */
			self::set_variable_constants();
		}

		public static function set_variable_constants( $var_const = null ) {

			if ( ! is_array( $var_const ) ) {

				$var_const = self::get_variable_constants();
			}

			/*
			 * Define the variable constants, if not already defined.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( ! defined( $name ) ) {

					define( $name, $value );
				}
			}
		}

		public static function get_variable_constants() {

			$var_const = array();

			$var_const[ 'WPSSORAR_META_ALLOW_RATINGS' ]  = '_wpsso_allow_ratings';	// Post meta 0/1.
			$var_const[ 'WPSSORAR_META_AVERAGE_RATING' ] = '_wpsso_average_rating';	// Post meta float.
			$var_const[ 'WPSSORAR_META_RATING_COUNTS' ]  = '_wpsso_rating_counts';	// Post meta array.
			$var_const[ 'WPSSORAR_META_REVIEW_COUNT' ]   = '_wpsso_review_count';	// Post meta int.

			/*
			 * Maybe override the default constant value with a pre-defined constant value.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( defined( $name ) ) {

					$var_const[$name] = constant( $name );
				}
			}

			return $var_const;
		}

		public static function require_libs( $plugin_file ) {

			require_once WPSSORAR_PLUGINDIR . 'lib/actions.php';
			require_once WPSSORAR_PLUGINDIR . 'lib/comment.php';
			require_once WPSSORAR_PLUGINDIR . 'lib/filters.php';
			require_once WPSSORAR_PLUGINDIR . 'lib/register.php';
			require_once WPSSORAR_PLUGINDIR . 'lib/script.php';
			require_once WPSSORAR_PLUGINDIR . 'lib/style.php';

			if ( is_admin() ) {

				require_once WPSSORAR_PLUGINDIR . 'lib/admin.php';
			}

			add_filter( 'wpssorar_load_lib', array( __CLASS__, 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $success = false, $filespec = '', $classname = '' ) {

			if ( false !== $success ) {

				return $success;
			}

			if ( ! empty( $classname ) ) {

				if ( class_exists( $classname ) ) {

					return $classname;
				}
			}

			if ( ! empty( $filespec ) ) {

				$file_path = WPSSORAR_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $file_path ) ) {

					require_once $file_path;

					if ( empty( $classname ) ) {

						return SucomUtil::sanitize_classname( 'wpssorar' . $filespec, $allow_underscore = false );
					}

					return $classname;
				}
			}

			return $success;
		}
	}
}
