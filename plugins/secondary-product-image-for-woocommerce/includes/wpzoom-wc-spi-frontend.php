<?php

//Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) 
	exit;

/**
 * WPZOOM_WC_Secondary_Image_Frontend Class
 *
 * Frontend output class
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'WPZOOM_WC_Secondary_Image_Frontend' ) ) {

	class WPZOOM_WC_Secondary_Image_Frontend {

		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;

		public function __construct() {
			
			if ( ! is_admin() ) {
				
				add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_scripts' ) );
				add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'output_secondary_product_thumbnail' ), 15 );
				add_filter( 'post_class', array( $this, 'set_product_post_class' ), 21, 3 );

				add_filter( 'wpzoom_wc_spi_secondary_product_thumbnail', array( $this, 'add_image_wrapper') );
			}

		}

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Enqueue WCSPT front-end styles and scripts.
		 */
		public function load_frontend_scripts() {

			if ( wp_is_mobile() ) {
				return;
			}

			wp_enqueue_style(
				'wpzoom-wc-spi-style', 
				WPZOOM_WC_SPI_URL . 'assets/css/secondary-product-image-for-woocommerce.css',
				array(), 
				WPZOOM_WC_SPI_VER 
			);

			wp_enqueue_script(
				'wpzoom-wc-spi-script', 
				WPZOOM_WC_SPI_URL . 'assets/js/secondary-product-image-for-woocommerce.js',
				array(),
				WPZOOM_WC_SPI_VER,
				true
			);
		}

		public function output_secondary_product_thumbnail() {
			echo $this->add_secondary_product_thumbnail();
		}

		public function add_image_wrapper( $image_html ) {

			global $product;

			//Check if the theme is a block theme
			$is_theme_block = wp_is_block_theme();

			if( $is_theme_block ) {
				$image_html = '<a href="' . esc_url( $product->get_permalink() ) . '">' . $image_html . '</a>';
			}

			return '<div class="wpzoom-secondary-image-container">' . $image_html . '</div>';
		}
		
		/*
		* Output the secondary product thumbnail.
		*
		* @param string $size (default: 'woocommerce_thumbnail').
		* @param int    $deprecated1 Deprecated since WooCommerce 2.0 (default: 0).
		* @param int    $deprecated2 Deprecated since WooCommerce 2.0 (default: 0).
		* @return string
		*/
		public function add_secondary_product_thumbnail( $size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0 ) {

			if ( wp_is_mobile() ) {
				return '';
			}

			//Check if the theme is a block theme
			$is_theme_block = wp_is_block_theme();
			if( $is_theme_block ) {
				$size = 'woocommerce_single';
			}

			global $product;

			$image_ids = $this->get_gallery_img_ids( $product );

			$image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

			$classes          = 'attachment-' . $image_size . ' wpzoom-wc-spi-secondary-img wpzoom-wc-spi-transition';
			$secondary_img_id = get_post_meta( $product->get_id(), 'product_wpzoom-product-secondary-image_thumbnail_id', true );

			$image_html = '';

			if( ! empty( $secondary_img_id ) ) {
				$image_html = wp_get_attachment_image( $secondary_img_id, $image_size, false, array( 'class' => $classes ) );
			}
			elseif ( $image_ids ) {
				$secondary_img_id = apply_filters( 'wpzoom_wc_spi_reveal_last_img', false ) ? end( $image_ids ) : reset( $image_ids );				
				$image_html = wp_get_attachment_image( $secondary_img_id, $image_size, false, array( 'class' => $classes ) );
			}
			else {
				return $image_html;
			}

			return apply_filters( 'wpzoom_wc_spi_secondary_product_thumbnail', $image_html, $secondary_img_id, $image_size, $product );
		}


		
		/**
		 * Returns the gallery image ids.
		 *
		 * @param WC_Product $product
		 * @return array
		 */
		public function get_gallery_img_ids( $product ) {
			if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
				$image_ids = $product->get_gallery_image_ids();
			} else {
				// Deprecated in WC 3.0.0
				$image_ids = $product->get_gallery_attachment_ids();
			}
			
			return $image_ids;
		}

		/**
		 * Add wcspt-has-gallery class to products that have at least one gallery image.
		 *
		 * @param array $classes
		 * @param array $class
		 * @param int $post_id
		 * @return array
		 */
		public function set_product_post_class( $classes, $class, $post_id ) {

			if ( ! $post_id || get_post_type( $post_id ) !== 'product' ) {
				return $classes;
			}
			
			global $product;
			
			if ( is_object( $product ) ) {
				
				$secondary_img_id = get_post_meta( $product->get_id(), 'product_wpzoom-product-secondary-image_thumbnail_id', true );
				$image_ids = $this->get_gallery_img_ids( $product );
				
				if ( $image_ids || $secondary_img_id ) {
					$classes[] = 'wpzoom-wc-spi-has-enabled';
				}
			}
			
			return $classes;
		}

	}
	new WPZOOM_WC_Secondary_Image_Frontend;
}