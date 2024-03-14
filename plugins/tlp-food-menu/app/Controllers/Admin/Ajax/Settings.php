<?php
/**
 * Settings Ajax Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin\Ajax;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Settings Ajax Class.
 */
class Settings {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_fmpSettingsUpdate', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$error = true;

		if ( Fns::verifyNonce() ) {
			unset( $_REQUEST['fmp_nonce'] );
			unset( $_REQUEST['_wp_http_referer'] );
			unset( $_REQUEST['action'] );

			$data  = [];
			$matas = Fns::fmpAllSettingsFields();

			foreach ( $matas as $key => $field ) {
				$rValue       = ! empty( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : null;
				$value        = Fns::sanitize( $field, $rValue );
				$data[ $key ] = $value;
			}

			$settings = get_option( TLPFoodMenu()->options['settings'] );

			if ( ! empty( $settings['slug'] ) && $_REQUEST['slug'] && $settings['slug'] !== $_REQUEST['slug'] ) {
				update_option( TLPFoodMenu()->options['flash'], true );
			}

			update_option( TLPFoodMenu()->options['settings'], $data );

			$error = false;
			$msg   = esc_html__( 'Settings successfully updated', 'tlp-food-menu' );
		} else {
			$msg = esc_html__( 'Security Error !!', 'tlp-food-menu' );
		}

		$response = [
			'error' => $error,
			'msg'   => $msg,
		];

		wp_send_json( $response );

		die();
	}
}
