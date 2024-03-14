<?php
/**
 * Responsible for managing ajax endpoints.
 *
 * @since 2.12.15
 * @package SWPTLS
 */

namespace SWPTLS\Ajax;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manage notices.
 *
 * @since 2.12.15
 */
class Settings {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_swptls_get_settings', [ $this, 'get' ] );
		add_action( 'wp_ajax_swptls_save_settings', [ $this, 'save' ] );
	}

	/**
	 * Get field.
	 */
	public function get() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		wp_send_json_success([
			'async' => get_option( 'asynchronous_loading', false ),
			'css'   => get_option( 'css_code_value' ),
			'link_support'   => get_option( 'link_support_mode', 'pretty_link' ),
			'script_support'   => get_option( 'script_support_mode', 'global_loading' ),
		]);
	}

	/**
	 * Save field.
	 */
	public function save() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		$settings_raw = isset( $_POST['settings'] ) ? sanitize_text_field( wp_unslash( $_POST['settings'] ) ) : '';
		$settings = ! empty( $settings_raw ) ? json_decode( $settings_raw, true ) : false;

		update_option( 'asynchronous_loading', isset( $settings['async_loading'] ) ? sanitize_text_field( $settings['async_loading'] ) : '' );

		update_option( 'css_code_value', isset( $settings['css_code_value'] ) ? sanitize_text_field( $settings['css_code_value'] ) : '' );

		update_option( 'link_support_mode', isset( $settings['link_support'] ) ? sanitize_text_field( $settings['link_support'] ) : '' );

		update_option( 'script_support_mode', isset( $settings['script_support'] ) ? sanitize_text_field( $settings['script_support'] ) : '' );

		wp_send_json_success([
			'message' => __( 'Settings saved successfully.', 'sheetstowptable' ),
			'async' => get_option( 'asynchronous_loading', false ),
			'css'   => get_option( 'css_code_value' ),
			'link_support'   => get_option( 'link_support_mode', 'pretty_link' ),
			'script_support'   => get_option( 'script_support_mode', 'global_loading' ),
		]);
	}
}
