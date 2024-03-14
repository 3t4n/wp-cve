<?php
/**
 * Save settings.
 *
 * @package Custom_Login_Dashboard
 */

namespace CustomLoginDashboard\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to handle ajax request of settings saving.
 */
class Save_Settings {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_cldashboard_save_settings', [ $this, 'save' ] );

	}

	/**
	 * Save menu.
	 */
	public function save() {
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! wp_verify_nonce( $nonce, 'cldashboard_nonce_save_settings' ) ) {
			wp_send_json_error( __( 'Invalid token', 'erident-custom-login-and-dashboard' ), 401 );
		}

		$available_fields = cldashboard_get_field_data_types();

		$raw_post_data = $_POST;
		$post_data     = [];

		foreach ( $available_fields as $field => $value_type ) {
			if ( isset( $raw_post_data[ $field ] ) ) {
				$value = $raw_post_data[ $field ];

				switch ( $value_type ) {
					case 'int':
						$post_data[ $field ] = absint( $value );
						break;

					case 'float':
						$post_data[ $field ] = floatval( $value );
						break;

					case 'bool':
						$value = (bool) $value;

						if ( $value ) {
							$post_data[ $field ] = 1;
						}

						break;

					default:
						$post_data[ $field ] = sanitize_text_field( $value );
						break;
				}
			}
		}

		/**
		 * Update settings.
		 * If the value of a field is not set, then it will be deleted from the database.
		 * So that the database will not contain empty fields.
		 */
		update_option( 'plugin_erident_settings', $post_data );

		wp_send_json_success( __( 'Settings have been saved', 'erident-custom-login-and-dashboard' ) );
	}

}
