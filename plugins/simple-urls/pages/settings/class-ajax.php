<?php
/**
 * Setting General - Ajax.
 *
 * @package Pages
 */

namespace LassoLite\Pages\Settings;

use LassoLite\Admin\Constant;

use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Page;
use LassoLite\Classes\Setting;

/**
 * Setting General - Ajax.
 */
class Ajax {
	/**
	 * Declare "SURLs ajax requests" to WordPress.
	 */
	public function register_hooks() {
		add_action( 'wp_ajax_lasso_lite_save_settings_amazon', array( $this, 'lasso_lite_save_settings_amazon' ) );
		add_action( 'wp_ajax_lasso_lite_save_settings_general', array( $this, 'lasso_lite_save_settings_general' ) );
		add_action( 'wp_ajax_lasso_lite_store_settings', array( $this, 'lasso_lite_store_settings' ) );
	}

	/**
	 * Add a Field to a Product
	 */
	public function lasso_lite_save_settings_amazon() {
		Helper::verify_access_and_nonce();

		$post                            = Helper::POST();
		$amazon_tracking_id              = $post['amazon_tracking_id'] ?? '';
		$amazon_access_key_id            = $post['amazon_access_key_id'] ?? '';
		$amazon_secret_key               = $post['amazon_secret_key'] ?? '';
		$amazon_default_tracking_country = $post['amazon_default_tracking_country'] ?? '';
		$amazon_pricing_daily            = $post['amazon_pricing_daily'] ?? '';

		$options['amazon_tracking_id']              = $amazon_tracking_id;
		$options['amazon_access_key_id']            = $amazon_access_key_id;
		$options['amazon_secret_key']               = $amazon_secret_key;
		$options['amazon_default_tracking_country'] = $amazon_default_tracking_country;
		$options['amazon_pricing_daily']            = 'on' === $amazon_pricing_daily;

		Setting::set_settings( $options );

		if ( ! empty( $amazon_tracking_id ) ) {
			update_option( Enum::SETUP_AMZ_TRACKING_ID, true );
		}

		$data['msg'] = 'All settings saved';
		wp_send_json_success( $data );
	}

	/**
	 * Save settings general
	 */
	public function lasso_lite_save_settings_general() {
		Helper::verify_access_and_nonce();

		$post                                 = Helper::POST();
		$general_disable_amazon_notifications = $post['general_disable_amazon_notifications'] ?? Constant::DEFAULT_SETTINGS['general_disable_amazon_notifications'];
		$general_disable_tooltip              = $post['general_disable_tooltip'] ?? Constant::DEFAULT_SETTINGS['general_disable_tooltip'];
		$general_disable_notification         = $post['general_disable_notification'] ?? Constant::DEFAULT_SETTINGS['general_disable_notification'];
		$general_enable_new_ui                = $post['general_enable_new_ui'] ?? Constant::DEFAULT_SETTINGS['general_enable_new_ui'];

		$options['general_disable_amazon_notifications'] = $general_disable_amazon_notifications;
		$options['general_disable_tooltip']              = $general_disable_tooltip;
		$options['general_disable_notification']         = $general_disable_notification;

		Setting::set_settings( $options );
		$data['msg'] = 'All settings saved';

		// ? disable new UI
		if ( ! $general_enable_new_ui ) {
			update_option( Enum::LASSO_LITE_ACTIVE, 0 ); // ? fix conflict with L.235
			update_option( Enum::SWITCH_TO_NEW_UI, 0 );

			$data['redirect_url'] = Page::get_page_url();
		}

		wp_send_json_success( $data );
	}

	/**
	 * Store Lasso Lite settings
	 */
	public function lasso_lite_store_settings() {
		Helper::verify_access_and_nonce();

		$data = Helper::POST();

		if ( empty( $data['settings'] ) ) {
			wp_send_json_error( 'No settings to save.' );
		}

		// ? User can not change the Disclosure text
		unset( $data['settings']['disclosure_text'] );

		$settings = $data['settings'];
		$options  = $settings;

		// ? Loop and check for checkbox values, convert them to boolean.
		foreach ( $settings as $key => $value ) {
			if ( is_array( $value ) ) {
				$options[ $key ] = $value;
			} elseif ( 'true' === (string) $value ) {
				$options[ $key ] = true;
			} elseif ( 'false' === (string) $value ) {
				$options[ $key ] = false;
			} else {
				$options[ $key ] = trim( $value );
			}
		}

		// ? update settings
		Setting::set_settings( $options );

		wp_send_json_success(
			array(
				'options' => $options,
				'status'  => 1,
			)
		);
	}
}
