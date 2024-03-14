<?php

class BWFAN_API_Delete_Automation_Step extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::DELETABLE;
		$this->route        = '/automation/(?P<automation_id>[\\d]+)/step/(?P<step_id>[\\d]+)';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation ID to retrieve', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			),
			'step_id'       => array(
				'description' => __( 'Step ID to delete', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			),
		);
	}

	public function process_api_call() {
		$automation_id = $this->get_sanitized_arg( 'automation_id', 'text_field' );
		$step_id       = $this->get_sanitized_arg( 'step_id', 'text_field' );
		$node_type     = $this->get_sanitized_arg( 'nodeType', 'text_field' );
		$arg_data      = $this->args;

		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $automation_id );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		/** Step data */
		if ( isset( $arg_data['steps'] ) && ! empty( $arg_data['steps'] ) ) {
			$steps = $arg_data['steps'];
		}

		/** Link data */
		if ( isset( $arg_data['links'] ) && ! empty( $arg_data['links'] ) ) {
			$links = $arg_data['links'];
		}

		/** Node count */
		if ( isset( $arg_data['count'] ) && intval( $arg_data['count'] ) > 0 ) {
			$count = intval( $arg_data['count'] );
		}

		/** Update automation data */
		if ( ! empty( $steps ) && ! empty( $links ) ) {
			$automation_obj->update_automation_meta_data( [], $steps, $links, $count );
		}

		/** Delete step and get response */
		$response = $automation_obj->delete_automation_step( $step_id );

		if ( ! $response ) {
			return $this->error_response( [], 'Unable to delete the node' );
		}

		if ( 'benchmark' === $node_type || 'wait' === $node_type ) {
			$status             = 'wait' === $node_type ? 1 : 4;
			$queued_automations = BWFAN_Model_Automation_Contact::get_automation_contact_by_sid( $step_id, '', $status );

			if ( ! empty( $queued_automations ) && is_array( $queued_automations ) ) {
				$key  = 'bwf_queued_automations_' . $step_id;
				$args = [ 'sid' => $step_id ];

				/** Un-schedule action */
				if ( bwf_has_action_scheduled( 'bwfan_automation_step_deleted', $args ) ) {
					bwf_unschedule_actions( 'bwfan_automation_step_deleted', $args );
				}

				$ids                = array_column( $queued_automations, 'ID' );
				$queued_automations = wp_json_encode( $ids );
				update_option( $key, $queued_automations, false );
				bwf_schedule_recurring_action( time(), 120, 'bwfan_automation_step_deleted', $args );
			}
		}

		$this->response_code = 200;

		return $this->success_response( [], __( 'Data updated', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Delete_Automation_Step' );