<?php

class BWFAN_API_Change_Automation_Status extends BWFAN_API_Base {
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
		$this->route        = '/automation/(?P<contact_automation_id>[\\d]+)/contact/status';
		$this->request_args = array(
			'contact_automation_id' => array(
				'description' => __( 'Automation contact ID to retrieve', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			)
		);
	}

	public function process_api_call() {
		$a_cid = $this->get_sanitized_arg( 'contact_automation_id', 'text_field' );

		if ( empty( $a_cid ) ) {
			return $this->error_response( [], __( 'Automation contact ID is missing', 'wp-marketing-automations' ) );
		}
		/** To be changed status*/
		$to = $this->get_sanitized_arg( 'to', 'text_field' );
		if ( empty( $to ) ) {
			return $this->error_response( [], __( 'Status is missing', 'wp-marketing-automations' ) );
		}

		$data = BWFAN_Model_Automation_Contact::get_data( $a_cid );

		$aid = isset( $data['aid'] ) ? $data['aid'] : 0;

		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $aid );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		if ( 'end' === $to ) {
			$trail = isset( $data['trail'] ) ? $data['trail'] : '';

			/** Update the step trail status as complete if active */
			BWFAN_Model_Automation_Contact_Trail::update_all_step_trail_status_complete( $trail );

			$result = BWFAN_Common::end_v2_automation( $a_cid, $data );
		} else {
			// 1 - active | 2 - complete | 3 - paused | 4 - waiting | 5 - terminate | 6 - Retry
			$result = $automation_obj->change_automation_status( $to, $a_cid );
		}
		if ( $result ) {
			$this->response_code = 200;

			return $this->success_response( [], __( 'Automation updated', 'wp-marketing-automations' ) );
		} else {
			$this->response_code = 404;

			return $this->error_response( [], __( 'Unable to update automation', 'wp-marketing-automations' ) );
		}
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Change_Automation_Status' );