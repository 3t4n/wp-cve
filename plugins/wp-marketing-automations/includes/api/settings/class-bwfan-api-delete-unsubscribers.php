<?php

class BWFAN_API_Delete_Unsubscribers extends BWFAN_API_Base {
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
		$this->method = WP_REST_Server::DELETABLE;
		$this->route  = '/settings/unsubscribers';
	}

	public function default_args_values() {
		$args = [
			'unsubscribers_ids' => '',
		];

		return $args;
	}

	public function process_api_call() {
		$unsubscribers_ids = $this->args['unsubscribers_ids'];

		if ( empty( $unsubscribers_ids ) ) {
			return $this->error_response( __( 'Unsubscribers IDs missing', 'wp-marketing-automations' ) );
		}

		$failed_ids = array();

		foreach ( $unsubscribers_ids as $id ) {
			$data = BWFAN_Model_Message_Unsubscribe::get( $id );
			if ( empty( $data ) ) {
				$failed_ids[] = $id;
				continue;
			}

			$where = array(
				'ID' => $id,
			);
			BWFAN_Model_Message_Unsubscribe::delete_message_unsubscribe_row( $where );

			do_action( 'bwfan_delete_unsubscriber', $data );
		}

		if ( ! empty( $failed_ids ) ) {
			return $this->error_response( __( 'Unable to delete some of unsubscribers with IDs:' . implode( ', ', $failed_ids ), 'wp-marketing-automations' ) );
		}

		do_action( 'bwfan_bulk_delete_unsubscribers' );

		$this->response_code = 200;

		return $this->success_response( [], __( 'Unsubscribers deleted', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Delete_Unsubscribers' );