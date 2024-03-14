<?php


/**
 * class StorePermission
 *
 * @link       https://appcheap.io
 * @since      2.5.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Vendor;

defined( 'ABSPATH' ) || exit;

use WP_REST_Controller;

class StorePermission extends WP_REST_Controller {

	/**
	 * @param $request
	 *
	 * @return bool
	 */
	public function vendor_permissions_check( $request ): bool {
		return wcfm_is_vendor() || current_user_can( 'manage_options' );
	}

	/**
	 * Checking if have any permission to view review
	 *
	 * @return boolean
	 * @since 1.0.0
	 *
	 */
	public function get_review_permissions_check(): bool {
		if ( apply_filters( 'wcfm_is_allow_manage_review', true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Checking if have any permission to manager review
	 *
	 * @return boolean
	 * @since 1.0.0
	 *
	 */
	public function review_manage_permissions_check(): bool {
		if ( apply_filters( 'wcfm_is_allow_manage_review', true ) ) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * Mark read message permission
	 *
	 * @return bool
	 */
	public function mark_read_message(): bool {
		if ( ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'wcfm_vendor' ) && ! current_user_can( 'seller' ) && ! current_user_can( 'vendor' ) && ! current_user_can( 'shop_staff' ) && ! current_user_can( 'wcfm_delivery_boy' ) && ! current_user_can( 'wcfm_affiliate' ) ) {
			return false;
		}

		return true;
	}
}
