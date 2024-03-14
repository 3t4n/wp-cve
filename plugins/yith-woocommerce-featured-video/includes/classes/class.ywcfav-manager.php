<?php // phpcs:ignore WordPress.Files.FileName
/**
 * The class get build the video infomations.
 *
 * @package YITH WooCommerce Featured Audio Video Content\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'YITH_Featured_Video_Manager' ) ) {
	/**
	 * The manager class
	 */
	class YITH_Featured_Video_Manager {

		/**
		 * The uniqe instance of the class
		 *
		 * @var YITH_Featured_Video_Manager
		 */
		protected static $instance;


		/**
		 * Return single instance of class
		 *
		 * @author YITH <plugins@yithemes.com>
		 * @since 2.0.0
		 * @return YITH_Featured_Video_Manager
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get the video information by product
		 *
		 * @since 2.0.0
		 * @param WC_Product $product the product.
		 * @return array
		 */
		public function get_featured_video_args( $product ) {

			$video_url  = $product->get_meta( '_video_url' );
			$video_args = array();

			if ( ! empty( $video_url ) ) {

				list( $host, $video_id ) = explode( ':', ywcfav_video_type_by_url( $video_url ) );

				$video_args = array(
					'video_id'     => $video_id,
					'host'         => $host,
					'thumbnail_id' => $this->get_featured_image_id( $product, $video_id, $host ),
				);
			}

			return $video_args;
		}

		/**
		 * Get the video thumbnail attachment id
		 *
		 * @since 2.0.0
		 *
		 * @param WC_Product $product The product.
		 * @param string     $video_id  video id.
		 * @param string     $host video host.
		 *
		 * @return int
		 */
		public function get_featured_image_id( $product, $video_id, $host ) {

			$thumbnail_id = $product->get_meta( '_video_image_url' );
			return $thumbnail_id;
		}
	}
}
if ( ! function_exists( 'YITH_Featured_Video_Manager' ) ) {
	/**
	 * Return the unique instance of the class
	 *
	 * @return YITH_Featured_Video_Manager
	 */
	function YITH_Featured_Video_Manager() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName

		return YITH_Featured_Video_Manager::get_instance();
	}
}
