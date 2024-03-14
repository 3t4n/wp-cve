<?php
/**
 * Save Settings
 */
function rafflepress_lite_save_settings() {
	if ( check_ajax_referer( 'rafflepress_lite_save_settings' ) ) {
		$_POST = stripslashes_deep( $_POST );

		if ( ! empty( $_POST['settings'] ) ) {
			$settings = $_POST['settings'];
			array_walk( $settings, 'sanitize_text_field' );

			if ( ! empty( $settings['slug'] ) && $settings['slug'] != 'rafflepress' ) {
				rafflepress_lite_add_custom_slug( $settings['slug'] );
			}

			update_option( 'rafflepress_settings', wp_json_encode( $settings ) );

			$response = array(
				'status' => 'true',
				'msg'    => __( 'Settings Updated', 'rafflepress' ),
			);
		} else {
			$response = array(
				'status' => 'false',
				'msg'    => __( 'Error Updating Settings', 'rafflepress' ),
			);
		}

		// Send Response
		wp_send_json( $response );
		exit;
	}
}

/**
 * Dismiss Settings Lite CTA
 */
function rafflepress_lite_dismiss_settings_lite_cta() {
	if ( check_ajax_referer( 'rafflepress_lite_dismiss_settings_lite_cta' ) ) {
		$_POST = stripslashes_deep( $_POST );

		if ( ! empty( $_POST['dismiss'] ) ) {
			update_option( 'rafflepress_dismiss_settings_lite_cta', true );

			$response = array(
				'status' => 'true',

			);
		}

		// Send Response
		wp_send_json( $response );
		exit;
	}
}
