<?php
/**
 * Base API
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Api;

defined( 'ABSPATH' ) || exit;

use WP_REST_Controller;

class Base extends WP_REST_Controller {

	public function __construct() {
		$this->namespace = APP_BUILDER_REST_BASE . '/v1';
	}

	/**
	 *
	 * Get text name
	 *
	 * @param string $txt
	 *
	 * @return string
	 */
	public function get_txt_name( string $txt = '' ): string {
		return trim( APP_BUILDER_NAME . $txt );
	}

	/**
	 *
	 * Get domain name
	 *
	 * @return string
	 */
	public function get_txt_domain(): string {
		return APP_BUILDER_DOMAIN;
	}

	/**
	 * @param $request
	 *
	 * @return bool
	 */
	public function admin_permissions_check( $request ): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Check user logged
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function logged_permissions_check( $request ): bool {
		return get_current_user_id() > 0;
	}
}
