<?php

defined( 'ABSPATH' ) || exit();

/**
 * Handle user submission from Help dialog
 */
class EPHD_Admin_Ctrl {

	public function __construct() {
		add_action( 'wp_ajax_ephd_save_global_settings', array( $this, 'save_global_settings' ) );
		add_action( 'wp_ajax_nopriv_ephd_save_global_settings', array( 'EPHD_Utilities', 'user_not_logged_in' ) );
	}

	/**
	 * User updated Help Dialog Settings
	 */
	public function save_global_settings() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 31 ) );
		}

		$global_config_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );

		/**
		 * OPTION: email to receive contact form submissions
		 */
		// if entered email address is not valid, then let user know and exit (do not generate error if user removed email)
		$global_config['contact_submission_email'] = EPHD_Utilities::post( 'contact_submission_email', $global_config['contact_submission_email'], $global_config_specs['contact_submission_email']['type'], intval( $global_config_specs['contact_submission_email']['max'] ) );
		if ( ! empty( $_POST['contact_submission_email'] ) && ! is_email( $global_config['contact_submission_email'] ) ) {
			EPHD_Utilities::ajax_show_error_die( __( 'Please enter a valid email address.', 'help-dialog' ) );
		}

		/**
		 * OPTION: kb article hidden classes
		 */
		$global_config['kb_article_hidden_classes'] = EPHD_Utilities::post( 'kb_article_hidden_classes', $global_config['kb_article_hidden_classes'], $global_config_specs['kb_article_hidden_classes']['type'] );

		/**
		 * OPTION: wpml parameter only for hd pro
		 */
		if ( EPHD_Utilities::is_help_dialog_pro_enabled() ) {
			$wpml_toggle = EPHD_Utilities::post( 'wpml_toggle' );
			if ( is_array( $wpml_toggle ) ) {
				$wpml_toggle = $wpml_toggle[0];
			}

			$global_config['wpml_toggle'] = $wpml_toggle == 'on' ? 'on' : 'off';
		}

		/**
		 * OPTION: included roles for private faqs
		 */
		$global_config['private_faqs_included_roles'] = EPHD_Utilities::post( 'private_faqs_included_roles', $global_config['private_faqs_included_roles'], $global_config_specs['private_faqs_included_roles']['type'] );

		/**
		 * OpenAI options
		 */
		$global_config['openai_api_key'] = EPHD_Utilities::post( 'openai_api_key', $global_config['openai_api_key'], $global_config_specs['openai_api_key']['type'] );
		//$global_config['openai_max_tokens'] = EPHD_Utilities::post( 'openai_max_tokens', $global_config['openai_max_tokens'], $global_config_specs['openai_max_tokens']['type'] );

		// update Global configuration
		$updated_global_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_global_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Global configuration. (35)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 35, $updated_global_config ) );
		}

		// update Access configuration
		$updated_access_global_config = EPHD_Admin_UI_Access::save_access_control( $global_config );
		if ( is_wp_error( $updated_access_global_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Global Access configuration. (36)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 36, $updated_access_global_config ) );
		}

		wp_die( wp_json_encode( array(
			'status'    => 'success',
			'message'   => esc_html__( 'Configuration Saved', 'help-dialog' )
		) ) );
	}


	/**
	 * Handle actions that need reload of the page
	 */
	public static function handle_form_actions() {

		$message = [];

		$action = EPHD_Utilities::post( 'action' );
		if ( ! in_array( $action, ['ephd_export_help_dialog', 'ephd_import_help_dialog'] ) ) {
			return [];
		}

		// verify that request is authentic
		if ( ! isset( $_REQUEST['_wpnonce_manage_hd'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce_manage_hd'], '_wpnonce_manage_hd' ) ) {
			return [ 'error' => EPHD_Utilities::report_generic_error( 1 ) ];
		}

		// only admin user can handle these actions
		if ( ! current_user_can( 'manage_options' ) ) {
			return [ 'error' => __( 'You do not have permission.', 'help-dialog' ) ];
		}

		// retrieve global KB configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Logging::add_log( 'Could not retrieve HD global config' );
			return [ 'error' => EPHD_Utilities::report_generic_error( 2 ) ];
		}

		// EXPORT CONFIG
		if ( $action == 'ephd_export_help_dialog' ) {
			$export = new EPHD_Export_Import();
			$message = $export->download_export_file();

			// stop php because we sent the file
			if ( empty( $message ) ) {
				exit;
			}
			return $message;
		}

		// IMPORT CONFIG
		if ( $action == 'ephd_import_help_dialog' ) {
			$import = new EPHD_Export_Import();
			return $import->import_hd_config();
		}

		return is_array( $message ) ? $message : [];
	}
}
