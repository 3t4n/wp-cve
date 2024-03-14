<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This class manage the integration with YITH WooCommerce Zoom Mangifier plugin
 *
 * @package YITH WooCommerce Featured Audio Video Content\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_Featured_Audio_Video_Zoom_Magnifier' ) ) {

	/**
	 * The integration class
	 */
	class YITH_Featured_Audio_Video_Zoom_Magnifier {
		/**
		 * The unique instance of the class
		 *
		 * @var YITH_Featured_Audio_Video_Zoom_Magnifier
		 */
		protected static $instance;

		/**
		 * The construct
		 */
		public function __construct() {

			add_filter( 'ywcfav_get_gallery_item_class', array( $this, 'change_gallery_item_class' ), 10, 1 );
			add_filter( 'ywcfav_get_thumbnail_gallery_item', array( $this, 'change_thumbnail_gallery_item' ), 10, 1 );

			add_filter( 'woocommerce_single_product_image_html', array( $this, 'show_featured_content' ), 10, 2 );
			add_filter( 'yith_wcmg_get_post_thumbnail_id', array( $this, 'get_featured_thumbnail_id' ), 10, 2 );

			add_action( 'wp_enqueue_scripts', array( $this, 'include_scripts' ) );
		}

		/**
		 * Return single instance of class
		 *
		 * @author YITH <plugins@yithemes.com>
		 * @since 2.0.0
		 * @return YITH_Featured_Audio_Video_Zoom_Magnifier
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Change the default gallery item class
		 *
		 * @since 2.0.0
		 * @param string $class the default class.
		 * @return string
		 */
		public function change_gallery_item_class( $class ) {
			return $class;
		}

		/**
		 * Change the default thumbnail gallery item class
		 *
		 * @since 2.0.0
		 * @param string $class the default class.
		 * @return string
		 */
		public function change_thumbnail_gallery_item( $class ) {

			return 'yith_magnifier_gallery li';
		}

		/**
		 * Show the video in frontend
		 *
		 * @since 2.0.0
		 * @param string $html The html.
		 * @param int    $product_id the current product id.
		 * @return string
		 */
		public function show_featured_content( $html, $product_id ) {

			$product    = wc_get_product( $product_id );
			$video_args = YITH_Featured_Video_Manager()->get_featured_video_args( $product );

			if ( ! empty( $video_args ) ) {

				ob_start();
				wc_get_template( 'template_video.php', $video_args, YWCFAV_TEMPLATE_PATH, YWCFAV_TEMPLATE_PATH );
				$new_html = ob_get_contents();
				ob_end_clean();

				$html = $new_html . '<div class="ywcfav_hide">' . $html . '</div>';
			}

			return $html;
		}

		/**
		 * Get the thumbnail id of the video
		 *
		 * @since 2.0.0
		 * @param int $thumbnail_id  the old thumbnail id.
		 * @param int $product_id the product id.
		 * @return int
		 */
		public function get_featured_thumbnail_id( $thumbnail_id, $product_id ) {
			$product    = wc_get_product( $product_id );
			$video_args = YITH_Featured_Video_Manager()->get_featured_video_args( $product );

			if ( ! empty( $video_args ) ) {

				$thumbnail_id = $video_args['thumbnail_id'];
			}

			return $thumbnail_id;
		}

		/**
		 * Include style and script
		 *
		 * @since 2.0.0
		 */
		public function include_scripts() {

			wp_register_script( 'ywcfav_zoom_magnifier', YWCFAV_ASSETS_URL . 'js/' . yit_load_js_file( 'ywfav_zoom_magnifier.js' ), array( 'jquery', 'ywcfav_video' ), YWCFAV_VERSION, true );
			$script_args = array(

				'img_class_container'             => '.' . ywcfav_get_gallery_item_class(),
				'thumbnail_gallery_class_element' => '.' . ywcfav_get_thumbnail_gallery_item(),
			);
			if ( is_product() ) {
				wp_localize_script( 'ywcfav_zoom_magnifier', 'ywcfav_zoom_params', $script_args );
				wp_enqueue_script( 'ywcfav_zoom_magnifier' );
			}
		}

	}
}


if ( ! function_exists( 'YITH_Featured_Audio_Video_Zoom_Magnifier' ) ) {
	/**
	 * Return unique instance of the class
	 *
	 * @since 2.0.0
	 * @return YITH_Featured_Audio_Video_Zoom_Magnifier
	 */
	function YITH_Featured_Audio_Video_Zoom_Magnifier() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName

		if ( class_exists( 'YITH_Featured_Audio_Video_Zoom_Magnifier_Premium' ) ) {
			return YITH_Featured_Audio_Video_Zoom_Magnifier_Premium::get_instance();
		} else {
			YITH_Featured_Audio_Video_Zoom_Magnifier::get_instance();
		}
	}
}
