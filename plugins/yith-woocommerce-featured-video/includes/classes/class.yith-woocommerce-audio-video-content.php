<?php // phpcs:ignore WordPress.Files.FileName
/**
 * The main class that manage all features
 *
 * @package YITH WooCommerce Featured Video Audio Content\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_WC_Audio_Video' ) ) {
	/**
	 * The main class
	 *
	 * @author YITH <plugins@yithemes.com>
	 */
	class YITH_WC_Audio_Video {

		/**
		 * The unique instance of the class
		 *
		 * @var YITH_WC_Audio_Video
		 */
		protected static $_instance;

		/**
		 * The construct
		 *
		 */
		public function __construct() {

			// Load Plugin Framework.
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			add_action( 'wp_enqueue_scripts', array( $this, 'include_video_scripts' ), 20 );

			if ( is_admin() ) {
				YITH_Featured_Audio_Video_Admin();
			} else {

				// Load zoom magnifier module.
				if ( ywcfav_check_is_zoom_magnifier_is_active() && ! ywcfav_check_is_product_is_exclude_from_zoom() ) {
					YITH_Featured_Audio_Video_Zoom_Magnifier();
				} else {
					YITH_Featured_Audio_Video_Frontend();
				}
			}

			add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );
		}

		/**
		 * Return single instance of class
		 *
		 * @since 2.0.0
		 * @return YITH_WC_Audio_Video
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Load the plugin framework
		 *
		 * @since 1.0.0
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}
		/**
		 * Include style and script
		 *
		 * @since 1.0.0
		 */
		public function include_video_scripts() {

			if ( is_product() ) {

				wp_enqueue_style( 'ywcfav_style', YWCFAV_ASSETS_URL . 'css/ywcfav_frontend.css', array(), YWCFAV_VERSION );
				wp_enqueue_script( 'vimeo-api', YWCFAV_ASSETS_URL . 'js/lib/vimeo_player.js', array(), YWCFAV_VERSION, true );
				wp_enqueue_script( 'youtube-api', YWCFAV_ASSETS_URL . 'js/lib/youtube_api.js', array( 'jquery' ), YWCFAV_VERSION, true );

				wp_register_script(
					'ywcfav_video',
					YWCFAV_ASSETS_URL . 'js/' . yit_load_js_file( 'ywcfav_video.js' ),
					array(
						'jquery',
						'youtube-api',
						'vimeo-api',
						'ywcfav_frontend',
					),
					YWCFAV_VERSION,
					true
				);

				$script_args = array(
					'product_gallery_trigger_class' => '.' . ywcfav_get_product_gallery_trigger(),
				);

				wp_localize_script( 'ywcfav_video', 'ywcfav_args', $script_args );
			}
		}

		/***
		 * Declare support for WooCommerce features.
		 */
		public function declare_wc_features_support() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YWCFAV_FREE_INIT, true );
			}
		}

	}

}


if ( ! function_exists( 'YITH_Featured_Video' ) ) {
	/**
	 * Return the instance of the class
	 */
	function YITH_Featured_Video() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		return YITH_WC_Audio_Video::get_instance();
	}
}
