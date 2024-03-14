<?php

class BWFAN_API_Add_Automation_Step extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public $total_count = 0;

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::EDITABLE;
		$this->route        = '/automation/(?P<automation_id>[\\d]+)/add-step';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation ID to retrieve', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			),
		);
	}

	public function process_api_call() {
		$automation_id = $this->get_sanitized_arg( 'automation_id', 'text_field' );
		$type          = isset( $this->args['type'] ) ? $this->get_sanitized_arg( 'type', 'text_field' ) : 'action';

		$data = isset( $this->args['data'] ) ? $this->args['data'] : [];

		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $automation_id );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		/** Add new step and get id */
		$step_id = $automation_obj->add_new_automation_step( $type, $data );

		$this->response_code = 200;

		return $this->success_response( [ 'step_id' => $step_id ], __( 'Data updated', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Add_Automation_Step' );