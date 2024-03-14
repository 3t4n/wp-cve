<?php

/**
 * Class Review
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 *
 */

namespace AppBuilder\Api;

defined( 'ABSPATH' ) || exit;

class Review extends Base {

	public function __construct() {
		$this->namespace = constant( 'APP_BUILDER_REST_BASE' ) . '/v1';
	}

	/**
	 * Registers a REST API route
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		/**
		 * Write product review
		 *
		 * @author Ngoc Dang
		 * @since 1.0.0
		 */
		if ( class_exists( '\WC_REST_Product_Reviews_Controller' ) ) {
			register_rest_route( $this->namespace, 'reviews', array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => '__return_true',
			) );
		}
	}

	public function create_item( $request ) {

		$validate = apply_filters( 'app_builder_validate_form_data', true, $request, 'ReviewProduct' );

		if ( is_wp_error( $validate ) ) {
			return $validate;
		}

		$product_id = $request->get_param( 'product_id' );
		$user_id    = get_current_user_id();

		if ( $user_id > 0 ) {
			$transient_name = 'wc_customer_bought_product_' . md5( '' . $user_id );
			delete_transient( $transient_name );
		}

		if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', $user_id, $product_id ) ) {
			$review = new \WC_REST_Product_Reviews_Controller();

			return $review->create_item( $request );
		}

		return new \WP_Error(
			'app_builder_review',
			__( 'Only logged in customers who have purchased this product may leave a review.' ),
			array(
				'status' => 403,
			)
		);
	}
}