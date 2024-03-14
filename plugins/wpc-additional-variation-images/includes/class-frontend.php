<?php
defined( 'ABSPATH' ) || exit;

class Wpcvi_Frontend {
	protected static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_action( 'wc_ajax_wpcvi_get_images', [ $this, 'ajax_get_images' ] );
	}

	public function ajax_get_images() {
		if ( ! apply_filters( 'wpcvi_disable_security_check', false ) ) {
			check_ajax_referer( 'wpcvi_nonce', 'security' );
		}

		if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
			// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
			wp_send_json_error();
		}

		$variation_id = isset( $_POST['variation_id'] ) ? absint( sanitize_text_field( $_POST['variation_id'] ) ) : 0;
		$variation    = $variation_id ? wc_get_product( $variation_id ) : false;

		if ( ! $variation ) {
			wp_send_json_error();
		}

		$image_ids            = array_filter( explode( ',', get_post_meta( $variation_id, 'wpcvi_images', true ) ) );
		$variation_main_image = $variation->get_image_id();

		if ( ! empty( $variation_main_image ) ) {
			array_unshift( $image_ids, $variation_main_image );
		}

		if ( empty( $image_ids ) ) {
			wp_send_json_error();
		}

		$image_ids       = array_unique( $image_ids );
		$columns         = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
		$gallery_classes = apply_filters(
			'woocommerce_single_product_image_gallery_classes',
			[
				'woocommerce-product-gallery',
				'woocommerce-product-gallery--wpcvi',
				'woocommerce-product-gallery--variation-' . absint( $variation_id ),
				'woocommerce-product-gallery--with-images',
				'woocommerce-product-gallery--columns-' . absint( $columns ),
				'images',
			]
		);
		$gallery_html    = '<div class="' . esc_attr( implode( ' ', array_map( 'sanitize_html_class', $gallery_classes ) ) ) . '" data-columns="' . esc_attr( $columns ) . '" style="opacity: 0; transition: opacity .25s ease-in-out;">';
		$gallery_html    .= '<div class="woocommerce-product-gallery__wrapper">';

		foreach ( $image_ids as $id ) {
			$gallery_html .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $id ), $id );
		}

		$gallery_html .= '</div></div>';

		wp_send_json( [ 'images' => $gallery_html ] );
	}

	public function scripts() {
		wp_enqueue_script( 'wpcvi-frontend', WPCVI_URI . 'assets/js/frontend.js', [ 'jquery' ], WPCVI_VERSION, true );

		$wpcvi_vars = apply_filters( 'wpcvi_vars', [
				'ajax_url'       => WC_AJAX::get_endpoint( '%%endpoint%%' ),
				'nonce'          => wp_create_nonce( 'wpcvi_nonce' ),
				'images_class'   => apply_filters( 'wpcvi_images_class', '.woocommerce-product-gallery' ),
				'lightbox_class' => apply_filters( 'wpcvi_lightbox_class', '.product .images a.zoom' ),
			]
		);

		wp_localize_script( 'wpcvi-frontend', 'wpcvi_vars', $wpcvi_vars );
	}
}

Wpcvi_Frontend::instance();
