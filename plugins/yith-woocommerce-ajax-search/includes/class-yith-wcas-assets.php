<?php
/**
 * Class to load the assets
 *
 * @author  YITH
 * @package YITH/Search
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCAS_Assets' ) ) {
	/**
	 * Class definition
	 */
	class YITH_WCAS_Assets {
		/**
		 * Contains an array of script handles registered by YITH AJAX Search.
		 *
		 * @var array
		 */
		private static $scripts = array();

		/**
		 * Contains an array of style handles registered by YITH AJAX Search.
		 *
		 * @var array
		 */
		private static $styles = array();

		/**
		 * Contains an array of script handles localized by YITH AJAX Search.
		 *
		 * @var array
		 */
		private static $ywcas_localize_scripts = array();

		/**
		 * YITH_WCAS_Assets constructor.
		 */
		public static function init() {
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'load_admin_scripts' ), 11 );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_frontend_scripts' ), 11 );

		}

		/**
		 * Enqueue admin scripts
		 *
		 * @param string $hook The current admin page.
		 */
		public static function load_admin_scripts( $hook ) {
			self::register_backend_styles();
			self::register_backend_script();

			if ( 'yith-plugins_page_yith_wcas_panel' === $hook ) {
				self::enqueue_script( 'ywcas-admin' );
				self::enqueue_style( 'ywcas-admin' );
				if ( isset( $_GET['tab'] ) ) { //phpcs:ignore
					$tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); //phpcs:ignore
					if ( 'search-fields' === $tab ) { //phpcs:ignore
						self::enqueue_script( 'ywcas-search-fields' );
						self::enqueue_script( 'ywcas-indexing' );
					}

					if ( 'shortcodes' === $tab ) {
						self::enqueue_script( 'ywcas-shortcodes' );
					}
					if ( 'statistic' === $tab ) {
						self::enqueue_script( 'ywcas-statistic' );
					}

				} else {
					self::enqueue_script( 'ywcas-statistic' );
				}
			}

			self::localize_printed_scripts();
		}


		/**
		 * Enqueue frontend scripts
		 */
		public static function load_frontend_scripts() {
			self::register_frontend_styles();

			if ( defined( 'YITH_PROTEO_VERSION' ) ) {
				self::enqueue_style( 'ywcas-yith-proteo' );
			}
		}

		/**
		 * Register the frontend styles
		 *
		 * @author YITH
		 * @since  2.0.0
		 */
		private static function register_frontend_styles() {
			$frontend_styles = array(
				'ywcas-frontend'    => array(
					'src'     => YITH_WCAS_ASSETS_URL . '/css/frontend.css',
					'deps'    => array(),
					'has_rtl' => false,
					'version' => YITH_WCAS_VERSION,
				),
				'ywcas-yith-proteo' => array(
					'src'     => YITH_WCAS_ASSETS_URL . '/css/yith-proteo.css',
					'deps'    => array(),
					'has_rtl' => false,
					'version' => YITH_WCAS_VERSION,
				),
			);

			foreach ( $frontend_styles as $handle => $frontend_style ) {
				self::register_style( $handle, $frontend_style['src'], $frontend_style['deps'], $frontend_style['version'], 'all', $frontend_style['has_rtl'] );
			}
		}


		/**
		 * Register the backend styles
		 *
		 * @author YITH
		 * @since  2.0.0
		 */
		private static function register_backend_styles() {
			$backend_styles = array(
				'ywcas-admin' => array(
					'src'     => YITH_WCAS_ASSETS_URL . '/css/admin.css',
					'deps'    => array(),
					'has_rtl' => false,
					'version' => YITH_WCAS_VERSION,
				),
			);
			foreach ( $backend_styles as $handle => $backend_style ) {
				self::register_style( $handle, $backend_style['src'], $backend_style['deps'], $backend_style['version'], 'all', $backend_style['has_rtl'] );
			}
		}


		/**
		 * Enqueue backend scripts
		 */
		public static function register_backend_script() {
			$admin_scripts = array(
				'ywcas-admin'         => array(
					'src'     => yit_load_js_file( YITH_WCAS_ASSETS_URL . '/js/admin/build/panel.js' ),
					'deps'    => array( 'jquery' ),
					'version' => YITH_WCAS_VERSION,
				),
				'ywcas-search-fields' => array(
					'src'     => yit_load_js_file( YITH_WCAS_ASSETS_URL . '/js/admin/build/search-fields.js' ),
					'deps'    => array( 'jquery', 'selectWoo', 'ywcas-admin' ),
					'version' => YITH_WCAS_VERSION,
				),
				'ywcas-indexing'      => array(
					'src'     => yit_load_js_file( YITH_WCAS_ASSETS_URL . '/js/admin/build/indexing.js' ),
					'deps'    => array( 'jquery', 'ywcas-admin' ),
					'version' => YITH_WCAS_VERSION,
				),
				'ywcas-shortcodes'    => array(
					'src'     => yit_load_js_file( YITH_WCAS_ASSETS_URL . '/js/admin/build/shortcodes.js' ),
					'deps'    => array( 'jquery' ),
					'version' => YITH_WCAS_VERSION,
				),
				'ywcas-statistic'     => array(
					'src'     => yit_load_js_file( YITH_WCAS_ASSETS_URL . '/js/admin/build/statistic.js' ),
					'deps'    => array( 'jquery', 'jquery-blockui' ),
					'version' => YITH_WCAS_VERSION,
				)
			);
			foreach ( $admin_scripts as $handle => $admin_script ) {
				self::register_script( $handle, $admin_script['src'], $admin_script['deps'], $admin_script['version'] );
			}
		}

		/**
		 * Register a style for use.
		 *
		 * @param string   $handle Name of the stylesheet. Should be unique.
		 * @param string   $path Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
		 * @param string[] $deps An array of registered stylesheet handles this stylesheet depends on.
		 * @param string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
		 * @param string   $media The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
		 * @param boolean  $has_rtl If has RTL version to load too.
		 *
		 * @author YITH
		 * @since  2.0.0
		 * @uses   wp_register_style()
		 */
		private static function register_style( $handle, $path, $deps = array(), $version = YITH_WCAS_VERSION, $media = 'all', $has_rtl = false ) {
			self::$styles[] = $handle;
			wp_register_style( $handle, $path, $deps, $version, $media );

			if ( $has_rtl ) {
				wp_style_add_data( $handle, 'rtl', 'replace' );
			}
		}

		/**
		 * Register a script for use.
		 *
		 * @param string   $handle Name of the script. Should be unique.
		 * @param string   $path Full URL of the script, or path of the script relative to the WordPress root directory.
		 * @param string[] $deps An array of registered script handles this script depends on.
		 * @param string   $version String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
		 * @param boolean  $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
		 *
		 * @since  2.0.0
		 * @author YITH
		 * @uses   wp_register_script()
		 */
		private static function register_script( $handle, $path, $deps = array( 'jquery' ), $version = YITH_WCAS_VERSION, $in_footer = true ) {
			self::$scripts[] = $handle;
			wp_register_script( $handle, $path, $deps, $version, $in_footer );
		}

		/**
		 * Register and enqueue a styles for use.
		 *
		 * @param string   $handle Name of the stylesheet. Should be unique.
		 * @param string   $path Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
		 * @param string[] $deps An array of registered stylesheet handles this stylesheet depends on.
		 * @param string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
		 * @param string   $media The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
		 * @param boolean  $has_rtl If has RTL version to load too.
		 *
		 * @uses   wp_enqueue_style()
		 */
		private static function enqueue_style( $handle, $path = '', $deps = array(), $version = YITH_WCAS_VERSION, $media = 'all', $has_rtl = false ) {
			if ( ! in_array( $handle, self::$styles, true ) && $path ) {
				self::register_style( $handle, $path, $deps, $version, $media, $has_rtl );
			}
			wp_enqueue_style( $handle );
		}

		/**
		 * Register and enqueue a script for use.
		 *
		 * @param string   $handle Name of the script. Should be unique.
		 * @param string   $path Full URL of the script, or path of the script relative to the WordPress root directory.
		 * @param string[] $deps An array of registered script handles this script depends on.
		 * @param string   $version String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
		 * @param boolean  $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
		 *
		 * @uses   wp_enqueue_script()
		 */
		private static function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = YITH_WCAS_VERSION, $in_footer = true ) {
			if ( ! in_array( $handle, self::$scripts, true ) && $path ) {
				self::register_script( $handle, $path, $deps, $version, $in_footer );
			}
			wp_enqueue_script( $handle );
		}

		/**
		 * Return data for script handles.
		 *
		 * @param string $handle Script handle the data will be attached to.
		 *
		 * @return array|bool
		 * @author YITH
		 * @since  2.0.0
		 */
		private static function get_script_data( $handle ) {
			switch ( $handle ) {
				case 'ywcas-admin':
					$params = array(
						'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
						'emptyField'              => esc_html__( 'This field is required.', 'yith-woocommerce-ajax-search' ),
						'indexNonce'              => wp_create_nonce( 'ywcas-search-index' ),
						'shortcodeNonce'          => wp_create_nonce( 'ywcas-search-shortcode' ),
						'statisticNonce'          => wp_create_nonce( 'ywcas-search-statistic' ),
						'message_alert'           => array(
							'title'         => __( 'Are you sure?', 'yith-woocommerce-ajax-search' ),
							'desc'          => __( 'Do you want to remove this field from your search field list?', 'yith-woocommerce-ajax-search' ),
							'confirmButton' => __( 'Yes, proceed', 'yith-woocommerce-ajax-search' ),
						),
						'shortcode_message_alert' => array(
							'title'         => __( 'Are you sure?', 'yith-woocommerce-ajax-search' ),
							'desc'          => __( 'Do you want to remove this shortcode preset?', 'yith-woocommerce-ajax-search' ),
							'confirmButton' => __( 'Yes, proceed', 'yith-woocommerce-ajax-search' ),
						),

					);
					break;
				default:
					$params = false;
			}

			/**
			 * APPLY_FILTERS: ywcas_get_scripts_data
			 *
			 * This filter allow to add, remove or change the param of a specific script.
			 *
			 * @param array  $params The script params.
			 * @param string $handle The script handle.
			 *
			 * @return array
			 */
			return apply_filters( 'ywcas_get_scripts_data', $params, $handle );
		}


		/**
		 * Localize a WC script once.
		 *
		 * @since 2.3.0 this needs less wp_script_is() calls due to https://core.trac.wordpress.org/ticket/28404 being added in WP 4.0.
		 *
		 * @param string $handle Script handle the data will be attached to.
		 */
		public static function localize_script( $handle ) {
			if ( ! in_array( $handle, self::$ywcas_localize_scripts, true ) ) {
				$data = self::get_script_data( $handle );

				if ( ! $data ) {
					return;
				}

				$name = str_replace( '-', '_', $handle ) . '_params';

				self::$ywcas_localize_scripts[] = $handle;
				/**
				 * APPLY_FILTERS: ywcas_$handle_params
				 *
				 * The filter allow to add ,remove the data in the script.
				 *
				 * @param array $data The script data.
				 *
				 * @return array
				 */
				wp_localize_script( $handle, $name, apply_filters( $name, $data ) );
			}
		}

		/**
		 * Localize scripts only when enqueued.
		 */
		public static function localize_printed_scripts() {
			foreach ( self::$scripts as $handle ) {
				self::localize_script( $handle );
			}
		}

		/** -------------------------------------------------------
		 * Public Static Getters - to get specific settings
		 */

		/**
		 * Get WC data
		 *
		 * @return array
		 */
		public static function get_wc_data() {
			$currency_code = get_woocommerce_currency();

			return array(
				'currency'             => array(
					'code'      => $currency_code,
					'precision' => wc_get_price_decimals(),
					'symbol'    => html_entity_decode( get_woocommerce_currency_symbol( $currency_code ) ),
					'position'  => get_option( 'woocommerce_currency_pos' ),
					'decimal'   => wc_get_price_decimal_separator(),
					'thousand'  => wc_get_price_thousand_separator(),
					'format'    => html_entity_decode( get_woocommerce_price_format() ),
				),
				'placeholderImageSrc'  => wc_placeholder_img_src(),
				'discountRoundingMode' => defined( 'WC_DISCOUNT_ROUNDING_MODE' ) && PHP_ROUND_HALF_UP === WC_DISCOUNT_ROUNDING_MODE ? 'half-up' : 'half-down',

			);
		}
	}

}
