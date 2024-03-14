<?php
/**
 * Responsible for managing plugin ajax endpoints.
 *
 * @since   1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages plugin ajax endpoints.
 *
 * @since 1.0.1
 */
class Ajax {


	/**
	 * Class constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		 // Store.
		add_action( 'wp_ajax_update_store', [ $this, 'update_store' ] );

		// Settings.
		add_action( 'wp_ajax_save_settings', [ $this, 'save_settings' ] );

		// Turnstile credentials verification.
		add_action( 'wp_ajax_verify_connection', [ $this, 'verify_connection' ] );

		// Plugin install & active.
		add_action( 'wp_ajax_wp_ajax_install_plugin', 'wp_ajax_install_plugin' );
		add_action( 'wp_ajax_active_plugin', [ $this, 'active_plugin' ] );

		add_action( 'wp_ajax_ect_placement', [ $this, 'ect_selected_placement' ] );
		add_action( 'wp_ajax_ect_disabled_ids', [ $this, 'ect_disabled_form_ids' ] );
	}

	/**
	 * Activates plugin.
	 *
	 * @since 1.0.4
	 */
	public function active_plugin()
	{
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ect_app_global_nonce' )) {
			wp_send_json_error([
				'message' => __( 'Invalid nonce.', 'wppool-turnstile' ),
			]);
		}

		if ( ! isset( $_POST['slug'] )) {
			wp_send_json_error([
				'message' => __( 'Invalid plugin to active.', 'wppool-turnstile' ),
			]);
		}

		$slug = sanitize_key( wp_unslash( $_POST['slug'] ) );
		switch ($slug) {
			case 'woocommerce':
				wp_turnstile()->helpers->activate_plugin( 'woocommerce/woocommerce.php' );
				break;
			case 'contact-form-7':
				wp_turnstile()->helpers->activate_plugin( 'contact-form-7/wp-contact-form-7.php' );
				break;
			case 'wpforms-lite':
				wp_turnstile()->helpers->activate_plugin( 'wpforms-lite/wpforms.php' );
				break;
			case 'buddypress':
				wp_turnstile()->helpers->activate_plugin( 'buddypress/bp-loader.php' );
				break;
			case 'elementor':
				wp_turnstile()->helpers->activate_plugin( 'elementor/elementor.php' );
				break;
			case 'gravityforms':
				wp_turnstile()->helpers->activate_plugin( 'gravityforms/gravityforms.php' );
				break;
			case 'formidable':
				wp_turnstile()->helpers->activate_plugin( 'formidable/formidable.php' );
				break;
			case 'mailchimp-for-wp':
				wp_turnstile()->helpers->activate_plugin( 'mailchimp-for-wp/mailchimp-for-wp.php' );
				break;
			case 'forminator':
				wp_turnstile()->helpers->activate_plugin( 'forminator/forminator.php' );
				break;
			case 'wpdiscuz':
				wp_turnstile()->helpers->activate_plugin( 'wpdiscuz/class.WpdiscuzCore.php' );
				break;
			case 'bbpress':
				wp_turnstile()->helpers->activate_plugin( 'bbpress/bbpress.php' );
				break;
			case 'happyforms':
				wp_turnstile()->helpers->activate_plugin( 'happyforms/happyforms.php' );
				break;
			case 'wp-user-frontend':
				wp_turnstile()->helpers->activate_plugin( 'wp-user-frontend/wpuf.php' );
				break;
			case 'zero-bs-crm':
				wp_turnstile()->helpers->activate_plugin( 'zero-bs-crm/ZeroBSCRM.php' );
				break;
		}
	}

	/**
	 * Updates react context store.
	 *
	 * @return void
	 */
	public function update_store() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ect_app_global_nonce' )) {
			wp_send_json_error( 'Invalid nonce.' );
		}

		if ( ! isset( $_POST['store'] )) {
			wp_send_json_error( 'Invalid data to save.' );
		}

		$store = wp_unslash( sanitize_text_field( $_POST['store'] ) );

		// Get the previous value of integrations from the ect_store option.
		$prev_store = get_option( 'ect_store' );

		// Get the previous value of the `integrations` field.
		$previous_store = get_option( 'ect_store' );
		$previous_store = json_decode( $prev_store );
		$previous_integrations = isset( $previous_store->integrations ) ? (array) $previous_store->integrations : [];
		$ect_store = json_decode( $store );
		$integrations = isset( $ect_store->integrations ) ? (array) $ect_store->integrations : [];
		// Save the updated $store object.
		update_option( 'ect_store', $store );

		// Check if any of the integrations values have changed.
		$updated_data = [];
		foreach ($integrations as $key => $value) {
			if (isset( $previous_integrations[ $key ] ) && $previous_integrations[ $key ] !== $value) {
				$updated_data[ $key ] = $value ? 'Turned ON for' : 'Turned OFF for';
			}
		}
		// Save the updated $ect_store object.
		update_option( 'ect_store', $store );

		if ( ! empty( $updated_data )) {
			wp_send_json_success([
				'message' => ucfirst( implode( ', ', array_values( $updated_data ) ) ) . ' ' . ucfirst( implode( ', ', array_keys( $updated_data ) ) ),
				'data' => $updated_data,
			]);
		} else {
			$data = [ 'message' => 'Settings Saved' ];
			wp_send_json_success( [ 'data' => $data ] );
		}
	}

	/**
	 * Save dashboard configuration settings.
	 *
	 * @return void
	 */
	public function save_settings()
	{
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ect_app_global_nonce' )) {
			wp_send_json_error( 'Invalid nonce.' );
		}

		$settings = ! empty( $_POST['settings'] ) && is_string( $_POST['settings'] ) ? json_decode( wp_unslash( sanitize_text_field( $_POST['settings'] ) ), true ) : [];

		if (empty( $settings )) {
			wp_send_json_error([
				'message' => __( 'Invalid settings to save', 'wp-turnstile' ),
			]);
		}

		if (empty( $_POST['context'] ) || 'additional-settings' !== sanitize_text_field( $_POST['context'] )) {
			update_option( 'ect_validated', false );
		}

		wp_turnstile()->settings->save( $settings );

		wp_send_json_success([
			'message'   => __( 'Settings saved.', 'wppool-turnstile' ),
			'validated' => wp_validate_boolean( get_option( 'ect_validated' ) ),
		]);
	}

	/**
	 * Verify turnstile credentials.
	 *
	 * @return void
	 */
	public function verify_connection() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ect_app_global_nonce' )) {
			wp_send_json_error( 'Invalid nonce.' );
		}

		$secret_key = isset( $_POST['secret_key'] ) ? sanitize_text_field( $_POST['secret_key'] ) : '';
		$token      = isset( $_POST['token'] ) ? sanitize_text_field( $_POST['token'] ) : '';

		if (empty( $secret_key )) {
			wp_send_json_error([
				'message'   => __( 'Secret key is missing.', 'wppool-turnstile' ),
				'secretKey' => 'false',
				'token'     => false,
			]);
		}

		if (empty( $token )) {
			wp_send_json_error([
				'message'   => __( 'Invalid token to connect', 'wppool-turnstile' ),
				'secretKey' => 'true',
				'token'     => false,
			]);
		}

		$response = wp_turnstile()->helpers->validate_turnstile( $token );

		update_option( 'ect_validated', wp_validate_boolean( $response['success'] ) );

		if ($response['success']) {
			wp_send_json_success([
				'message'   => __( 'Connection verified & saved.', 'wppool-turnstile' ),
				'validated' => get_option( 'ect_validated' ),
			]);
		} else {
			wp_send_json_error([
				'message'   => __( 'Validation error', 'wppool-turnstile' ),
				'validated' => get_option( 'ect_validated' ),
			]);
		}
	}

	/**
	 * ECT placement ajax field
	 *
	 * @return void
	 */
	public function ect_selected_placement() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ect_app_global_nonce' )) {
			wp_send_json_error([
				'message' => __( 'Invalid nonce.', 'wppool-turnstile' ),
			]);
		}
		$form_name = ( isset( $_POST['form'] ) && ! empty( $_POST['form'] ) ) ? sanitize_text_field( $_POST['form'] ) : '';
		$placements = ( isset( $_POST['selected_option'] ) && ! empty( $_POST['selected_option'] ) ) ? sanitize_text_field( $_POST['selected_option'] ) : '';
		if (isset( $form_name ) && ! empty( $form_name )) {
			$placement = str_replace( '"', '', $placements );
			$this->form_placement( $form_name, $placement );
		}
		wp_die();
	}

	/**
	 * ECT placement Forms
	 *
	 * @param string $form_name  The post form name.
	 *
	 * @param string $placement  The form placement.
	 *
	 * @return string
	 */
	public function form_placement( $form_name, $placement ) {
		$existing_form = get_option( 'ect_placement', [] );
		switch ($form_name) {
			case 'gravityforms':
			case 'formidable':
			case 'woocommerce':
				$existing_form[ $form_name ] = $placement;
				break;
			default:
				return false;
		}
		update_option( 'ect_placement', $existing_form + get_option( 'ect_placement', [] ) );
		wp_send_json_success( $existing_form );
	}


	/**
	 * ECT disabled form Ids
	 *
	 * @return string
	 */
	public function ect_disabled_form_ids() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ect_app_global_nonce' )) {
			wp_send_json_error([
				'message' => __( 'Invalid nonce.', 'wppool-turnstile' ),
			]);
		}

		$form_name = ( isset( $_POST['form'] ) && ! empty( $_POST['form'] ) ) ? sanitize_text_field( $_POST['form'] ) : '';
		$group_ids = ( isset( $_POST['disabled_ids'] ) && ! empty( $_POST['disabled_ids'] ) ) ? sanitize_text_field( $_POST['disabled_ids'] ) : '';
		if (isset( $form_name ) && ! empty( $form_name )) {
			$disabled_ids = preg_replace( '/\s+/', '', $group_ids );
			$this->disabled_ids( $form_name, $disabled_ids );
		}

		return wp_send_json_success( [ 'ids' => $disabled_ids ] );
		wp_die();
	}

	/**
	 * ECT disabled form Ids
	 *
	 * @param string $form_name  The post form name.
	 *
	 * @param string $disabled_ids  The disabled id of forms.
	 *
	 * @return string
	 */
	public function disabled_ids( $form_name, $disabled_ids ) {
		$existing_ids = get_option( 'ect_disabled_ids', [] );
		switch ($form_name) {
			case 'gravityforms':
			case 'formidable':
				$existing_ids[ $form_name ] = $disabled_ids;
				break;
			default:
				return false;
		}
		update_option( 'ect_disabled_ids', $existing_ids + get_option( 'ect_disabled_ids', [] ) );
		wp_send_json_success( $existing_ids );
	}
}