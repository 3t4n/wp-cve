<?php

class BWFAN_API_Get_Actions extends BWFAN_API_Base {
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
		$this->method = WP_REST_Server::READABLE;
		$this->route  = '/actions';
	}


	public function process_api_call() {
		$actions = BWFAN_Core()->automations->get_all_actions();
		if ( ! is_array( $actions ) || empty( $actions ) ) {
			return $this->error_response( __( 'Unable to fetch actions', 'wp-marketing-automations' ), null, 500 );
		}

		return $this->success_response( $actions, __( 'Actions found', 'wp-marketing-automations' ) );
	}

	public function get_result_total_count() {
		return $this->total_count;
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Actions' );
