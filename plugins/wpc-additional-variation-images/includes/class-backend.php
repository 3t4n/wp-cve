<?php
defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Utilities\FeaturesUtil;

class Wpcvi_Backend {
	protected static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'add_images_form' ], 10, 3 );
		add_action( 'save_post', [ $this, 'save_images' ], 1, 2 );
		add_action( 'woocommerce_save_product_variation', [ $this, 'save_variation' ] );

		// WPC Variation Duplicator
		add_action( 'wpcvd_duplicated', [ $this, 'duplicate_variation' ], 99, 2 );

		// WPC Variation Bulk Editor
		add_action( 'wpcvb_bulk_update_variation', [ $this, 'bulk_update_variation' ], 99, 2 );

		// HPOS compatibility
		add_action( 'before_woocommerce_init', function () {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				FeaturesUtil::declare_compatibility( 'custom_order_tables', WPCVI_FILE );
			}
		} );
	}

	function add_images_form( $loop, $variation_data, $variation ) {
		$id  = $variation->ID;
		$ids = $this->get_images( $id );

		echo '<div class="form-row form-row-full wpcvi-images-form" data-id="' . esc_attr( $id ) . '">';
		echo '<div class="wpcvi-images-form-heading">' . esc_html__( 'WPC Additional Variation Images', 'wpc-additional-variation-images' ) . '</div>';
		echo '<div class="wpcvi-images-form-content">';
		echo '<input type="hidden" class="wpcvi-images-ids" name="wpcvi_images[' . esc_attr( $id ) . ']" value="' . esc_attr( $ids ) . '">';
		echo '<ul class="wpcvi-images">';

		foreach ( explode( ',', $ids ) as $attach_id ) {
			$attachment = wp_get_attachment_image_src( $attach_id, [ 40, 40 ] );

			if ( $attachment ) {
				echo '<li class="wpcvi-image" data-id="' . esc_attr( $attach_id ) . '"><span class="wpcvi-image-thumb"><a class="wpcvi-image-remove" href="#"></a><img src="' . esc_url( $attachment[0] ) . '" width="40" height="40" /></span></li>';
			}
		}

		echo '</ul>';
		echo '<a href="#" class="wpcvi-add-images button" rel="' . esc_attr( $id ) . '">' . esc_html__( '+ Add Additional Images', 'wpc-additional-variation-images' ) . '</a>';
		echo '</div>';
		echo '</div>';
	}

	public function save_images( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || ! isset( $_POST['wpcvi_images'] ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		if ( empty( $_POST['woocommerce_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) {
			return;
		}

		if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( $post->post_type != 'product' ) {
			return;
		}

		$ids = self::sanitize_array( (array) $_POST['wpcvi_images'] );

		if ( count( $ids ) ) {
			foreach ( $ids as $parent_id => $attachment_ids ) {
				if ( isset( $attachment_ids ) ) {
					update_post_meta( $parent_id, 'wpcvi_images', $attachment_ids );
				} else {
					update_post_meta( $parent_id, 'wpcvi_images', '' );
				}
			}
		}

		return true;
	}

	public function save_variation( $variation_id ) {
		if ( ! isset( $_POST['wpcvi_images'] ) ) {
			return;
		}

		$ids = sanitize_text_field( $_POST['wpcvi_images'][ $variation_id ] );

		update_post_meta( $variation_id, 'wpcvi_images', $ids );

		return true;
	}

	function duplicate_variation( $old_variation_id, $new_variation_id ) {
		if ( $images = get_post_meta( $old_variation_id, 'wpcvi_images', true ) ) {
			update_post_meta( $new_variation_id, 'wpcvi_images', $images );
		}
	}

	function bulk_update_variation( $variation_id, $fields ) {
		if ( ! empty( $fields['wpcvi_images'] ) ) {
			update_post_meta( $variation_id, 'wpcvi_images', sanitize_text_field( $fields['wpcvi_images'] ) );
		}
	}

	public function get_images( $id = 0 ) {
		return apply_filters( 'wpcvi_get_images', get_post_meta( $id, 'wpcvi_images', true ), $id );
	}

	public function admin_scripts() {
		if ( 'product' === get_post_type() ) {
			wp_enqueue_script( 'wpcvi-backend', WPCVI_URI . 'assets/js/backend.js', [
				'jquery',
			], WPCVI_VERSION, true );

			$wpcvi_vars = [
				'media_add_text' => esc_html__( 'Add to Variation', 'wpc-additional-variation-images' ),
				'media_title'    => esc_html__( 'Variation Images', 'wpc-additional-variation-images' )
			];

			wp_localize_script( 'wpcvi-backend', 'wpcvi_vars', $wpcvi_vars );
			wp_enqueue_style( 'wpcvi-backend', WPCVI_URI . 'assets/css/backend.css', [], WPCVI_VERSION );
		}
	}

	function sanitize_array( $arr ) {
		foreach ( (array) $arr as $k => $v ) {
			if ( is_array( $v ) ) {
				$arr[ $k ] = self::sanitize_array( $v );
			} else {
				$arr[ $k ] = sanitize_text_field( $v );
			}
		}

		return $arr;
	}
}

Wpcvi_Backend::instance();
