<?php

class BWFAN_API_Save_Connector extends BWFAN_API_Base {
	public static $ins;

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::CREATABLE;
		$this->route  = '/connector/save';
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function process_api_call() {
		$wfco_connector = $this->get_sanitized_arg( 'wfco_connector', 'text_field', $this->args['wfco_connector'] );
		if ( empty( $wfco_connector ) ) {
			$this->response_code = 400;

			return $this->error_response( __( 'Provided connector is empty.', 'wp-marketing-automations' ) );
		}

		$active_connectors = WFCO_Load_Connectors::get_active_connectors();
		$connector         = $active_connectors[ sanitize_text_field( $wfco_connector ) ];
		if ( ! $connector instanceof BWF_CO ) {
			$message             = __( 'Something is wrong, connector isn\'t available.', 'wp-marketing-automations' );
			$this->response_code = 500;

			return $this->error_response( $message );
		}

		$id     = $this->get_sanitized_arg( 'id', 'text_field' );
		$action = empty( absint( $id ) ) ? 'save' : 'update';

		/** Do Wizard Connector Handling (Mailchimp, GetResponse, Mautic) */
		if ( BWFAN_Core()->connectors->is_wizard_connector( $connector ) ) {
			$next_step = $connector->get_next_step( $this->args );
			/** Error in Data provided for next step */
			if ( is_wp_error( $next_step ) ) {
				return $this->error_response( '', $next_step, 500 );
			}

			/** If step type = 'handle_settings_with_params', then go through handle_settings_form with new params */
			if ( is_array( $next_step ) && isset( $next_step['step_type'] ) && 'handle_settings_with_params' === $next_step['step_type'] ) {
				$this->args = $next_step['params'];
			} elseif ( true !== $next_step ) {
				/** If true, then go through handle_settings_form, else get next step data */
				return $this->success_response( $next_step );
			}
		}

		$response = $connector->handle_settings_form( $this->args, $action );
		$status   = in_array(  $response['status'], ['success', 'rerun_api', 'not_connected'] ) ? true : false;
		$message  = $response['message'];
		/** Error occurred */
		if ( false === $status ) {
			$this->response_code = 500;

			return $this->error_response( $message );
		}

		return $this->success_response( $response, __( 'Connector updated', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Save_Connector' );
