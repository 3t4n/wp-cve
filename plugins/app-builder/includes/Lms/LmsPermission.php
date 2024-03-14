<?php


/**
 * class LmsPermission
 *
 * @link       https://appcheap.io
 * @since      2.5.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Lms;

defined( 'ABSPATH' ) || exit;

use WP_REST_Controller;

class LmsPermission extends WP_REST_Controller {

	/**
	 * @param $request
	 *
	 * @return bool
	 */
	public function read_review_permissions_check( $request ): bool {
		return true;
	}
}
