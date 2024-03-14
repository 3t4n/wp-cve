<?php

defined( 'ABSPATH' ) || die();

use Sellkit\Global_Checkout\Checkout;

/**
 * Sellkit global checkout.
 *
 * @since 1.7.4
 */
class Sellkit_Global_Checkout {
	/**
	 * Class construct.
	 *
	 * @since 1.7.4
	 */
	public function __construct() {
		add_action( 'wp_ajax_sellkit_global_checkout_id', [ $this, 'get_global_checkout_funnel_id' ] );
		add_action( 'wp_ajax_sellkit_global_checkout_toggle_status', [ $this, 'change_status' ] );
	}

	/**
	 * Get global checkout id.
	 *
	 * @since 1.7.4
	 */
	public function get_global_checkout_funnel_id() {
		check_ajax_referer( 'sellkit', 'nonce' );

		$global_checkout_id = get_option( Checkout::SELLKIT_GLOBAL_CHECKOUT_OPTION, 0 );

		// Post not exists.
		if ( false === get_post_status( $global_checkout_id ) ) {
			wp_send_json_success( 0 );
		}

		wp_send_json_success( $global_checkout_id );
	}

	/**
	 * Toggle global checkout funnel status.
	 *
	 * @since 1.7.4
	 */
	public function change_status() {
		check_ajax_referer( 'sellkit', 'nonce' );

		$status = filter_input( INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$id     = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		$post   = get_post( $id );

		$post->post_status = 'draft';

		if ( 'publish' !== $status ) {
			$post->post_status = 'publish';
		}

		$id = wp_update_post( $post );

		wp_send_json_success( $post->post_status );
	}
}

new Sellkit_Global_Checkout();
