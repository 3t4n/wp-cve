<?php

class BWFAN_API_Bulk_Action extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::DELETABLE;
		$this->route  = '/bulk-action/(?P<type>[a-zA-Z0-9_-]+)';

	}

	public function default_args_values() {
		$args = [
			'ids' => []
		];

		return $args;
	}

	public function process_api_call() {

		$type = $this->get_sanitized_arg( 'type' );
		$ids  = ! empty( $this->args['ids'] ) ? array_filter( $this->args['ids'] ) : [];
		if ( empty( $ids ) ) {
			return $this->error_response( __( 'Invalid or empty ids', 'wp-marketing-automations-crm' ), null, 400 );
		}

		$dynamic_string = BWFAN_Common::get_dynamic_string();
		$args           = [ 'key' => $dynamic_string, 'type' => $type ];
		sort( $ids );
		update_option( "bwfan_bulk_action_{$dynamic_string}", $ids );
		bwf_schedule_recurring_action( time(), 60, "bwfan_bulk_action", $args );

		$response = BWFAN_Common::bwfan_bulk_action( $dynamic_string, $type );

		if ( empty( $response ) ) {
			delete_option( "bwfan_bulk_action_{$dynamic_string}" );
			if ( bwf_has_action_scheduled( 'bwfan_bulk_action', $args ) ) {
				bwf_unschedule_actions( "bwfan_bulk_action", $args );
			}

			return $this->success_response( [], __( 'Bulk action run successfully', 'wp-marketing-automations' ) );
		}

		return $this->success_response( [], __( 'Bulk action has been scheduled', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Bulk_Action' );
