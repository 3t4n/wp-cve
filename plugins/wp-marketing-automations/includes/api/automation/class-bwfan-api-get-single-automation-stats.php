<?php

class BWFAN_API_Get_Single_Automation_Stats extends BWFAN_API_Base {

	public static $ins;

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::READABLE;
		$this->route        = '/automations-stats/(?P<automation_id>[\\d]+)/';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation id to stats', 'wp-marketing-automations-crm' ),
				'type'        => 'string',
			),
		);

	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/** Customer journey Api call */
	public function process_api_call() {
		$aid = empty( $this->get_sanitized_arg( 'automation_id' ) ) ? 0 : $this->get_sanitized_arg( 'automation_id' );

		if ( empty( $aid ) ) {
			return $this->error_response( __( 'Invalid / Empty automation ID provided', 'wp-marketing-automations' ), null, 400 );
		}

		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $aid );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}
		$data = [
			'start' => [
				'queued'    => 0,
				'active'    => $automation_obj->get_active_count(),
				'completed' => $automation_obj->get_complete_count(),
			]
		];

		$step_ids = BWFAN_Model_Automation_Step::get_automation_step_ids( $aid );
		if ( empty( $step_ids ) ) {
			return $this->success_response( $data, __( 'Automation stats found', 'wp-marketing-automations' ) );
		}
		$step_ids = array_column( $step_ids, 'ID' );

		$completed_steps = BWFAN_Model_Automation_Contact_Trail::get_bulk_step_count( $step_ids );
		$completed_sids  = empty( $completed_steps ) ? [] : array_column( $completed_steps, 'sid' );

		$queued_steps = BWFAN_Model_Automation_Contact_Trail::get_bulk_step_count( $step_ids, false );
		$queued_sids  = empty( $queued_steps ) ? [] : array_column( $queued_steps, 'sid' );

		foreach ( $step_ids as $sid ) {
			$index           = array_search( $sid, $completed_sids );
			$completed_count = ( false !== $index && isset( $completed_steps[ $index ]['count'] ) ) ? $completed_steps[ $index ]['count'] : 0;

			$index        = array_search( $sid, $queued_sids );
			$queued_count = ( false !== $index && isset( $queued_steps[ $index ]['count'] ) ) ? $queued_steps[ $index ]['count'] : 0;
			$data[ $sid ] = [
				'queued'    => $queued_count,
				'active'    => 0,
				'completed' => $completed_count,
			];
		}

		return $this->success_response( $data, __( 'Automation stats found', 'wp-marketing-automations' ) );
	}

}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Single_Automation_Stats' );