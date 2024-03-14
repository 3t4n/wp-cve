<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW\AJAXs;
use GPLSCore\GPLS_PLUGIN_AVFSTW\AvifSupport;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_AVFSTW\AJAXs\Base\AJAXBase;
use GPLSCore\GPLS_PLUGIN_AVFSTW\Utils\NoticeUtilsTrait;

/**
 * Settings AUTH AJAX Class.
 */
class SettingsAJAX extends AJAXBase {

	use NoticeUtilsTrait;

	/**
	 * Instance.
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Support Libs.
	 *
	 * @var array
	 */
	protected $supported_libs = array( 'imagick', 'gd' );

	/**
	 * AJAX localize Actions.
	 *
	 * @param array $localize_data
	 * @return array
	 */
	public function ajax_localized_data( $localize_data ) {
		foreach ( $this->ajaxs as $ajax_key => $ajax_arr ) {
			$localize_data['actions'][ $ajax_key . '_action' ] = $ajax_arr['action'];
		}
		return $localize_data;
	}

	/**
	 * Setup AJAX functions.
	 *
	 * @return void
	 */
	protected function setup_ajaxs() {
		$this->ajaxs = array(
			'general_settings' => array(
				'action' => self::$plugin_info['name'] . '-general-settings',
				'func'   => 'ajax_handle_general_settings',
				'nopriv' => false,
			),
		);
	}

	/**
	 * AJAX Get Login - Register - Shipping and Billing Forms
	 *
	 * @return void
	 */
	public function ajax_handle_general_settings() {
		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), self::$plugin_info['prefix'] . '-nonce' ) ) {
			$quality = ! empty( $_POST['quality'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['quality'] ) ) ) : 82;
			$speed   = isset( $_POST['speed'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['speed'] ) ) ) : 6;
			AvifSupport::update_settings(
				array(
					'quality' => $quality,
					'speed'   => $speed,
				)
			);
			self::ajax_success_response(
				esc_html__( 'Settings have been saved successfully!', 'avif-support' ),
				'save-settings'
			);
		}

		self::expired_response();
	}
}
