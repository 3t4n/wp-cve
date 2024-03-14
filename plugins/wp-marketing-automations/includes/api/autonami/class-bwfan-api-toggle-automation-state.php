<?php

class BWFAN_API_Toggle_Automation_State extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::EDITABLE;
		$this->route  = '/automations/toggle-state';
	}

	public function default_args_values() {
		$args = [
			'state'         => false,
			'automation_id' => null,
		];

		return $args;
	}

	public function process_api_call() {
		$state         = $this->get_sanitized_arg( 'state', 'text_field' );
		$automation_id = $this->get_sanitized_arg( 'automation_id', 'text_field' );

		if ( empty( $automation_id ) ) {
			return $this->error_response( 'Automation ID is missing', 'wp-marketing-automations' );
		}

		$automation['status'] = 2;
		if ( 1 === absint( $state ) ) {
			$automation['status'] = 1;
		}

		if ( 4 === absint( $state ) ) {
			$result = BWFAN_Model_Automationmeta::insert_automation_meta_data( $automation_id, [
				'v1_migrate' => true,
			] );
		} else {
			$result = BWFAN_Core()->automations->toggle_state( $automation_id, $automation );
		}

		if ( false === $result ) {
			return $this->error_response( 'Unable to update automation', 'wp-marketing-automations' );
		}
		$this->response_code = 200;

		return $this->success_response( [], __( 'Automation updated', 'wp-marketing-automations' ) );
	}

}

BWFAN_API_Loader::register( 'BWFAN_API_Toggle_Automation_State' );