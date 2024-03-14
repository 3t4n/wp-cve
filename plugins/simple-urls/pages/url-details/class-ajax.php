<?php
/**
 * Lasso Url detail - Ajax.
 *
 * @package Pages
 */

namespace LassoLite\Pages\Url_Details;

use LassoLite\Classes\Affiliate_Link;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Setting;

/**
 * Lasso Url detail - Ajax.
 */
class Ajax {
	/**
	 * Declare "Lasso ajax requests" to WordPress.
	 */
	public function register_hooks() {
		add_action( 'wp_ajax_lasso_lite_save_lasso_url', array( $this, 'lasso_lite_save_lasso_url' ) );
		add_action( 'wp_ajax_lasso_lite_delete_post', array( $this, 'lasso_lite_delete_post' ) );
		add_action( 'wp_ajax_lasso_lite_save_amazon_tracking_id', array( $this, 'lasso_lite_save_amazon_tracking_id' ) );
	}

	/**
	 * Save Lasso data into DB
	 */
	public function lasso_lite_save_lasso_url() {
		Helper::verify_access_and_nonce();

		$post                 = Helper::POST();
		$lasso_affiliate_link = new Affiliate_Link();
		$lasso_affiliate_link->save_lasso_url();
	}

	/**
	 * Send error via ajax request
	 *
	 * @param string $error_message Error message.
	 */
	private function lasso_ajax_error( $error_message ) {
		wp_send_json_success(
			array(
				'status' => 0,
				'error'  => $error_message,
			)
		);
	}

	/**
	 * Delete a lasso post
	 */
	public function lasso_lite_delete_post() {
		Helper::verify_access_and_nonce();

		$post    = Helper::POST();
		$post_id = $post['post_id'];

		wp_update_post(
			array(
				'ID'          => $post_id,
				'post_status' => 'trash',
			)
		);

		wp_send_json_success(
			array(
				'data' => 1,
				'post' => $post,
			)
		);
	}

	/**
	 * Save Amazon tracking id
	 */
	public function lasso_lite_save_amazon_tracking_id() {
		Helper::verify_access_and_nonce();

		// phpcs:ignore
		$post    = Helper::POST();
		$amazon_tracking_id = $post['amazon_tracking_id'] ?? '';

		if ( empty( $amazon_tracking_id ) ) {
			wp_send_json_error( 'No settings to save.' );
		}
		$options['amazon_tracking_id'] = $amazon_tracking_id;

		Setting::set_settings( $options );

		wp_send_json_success( array( 'msg' => 'Tracking ID Saved!' ) );
	}
}
