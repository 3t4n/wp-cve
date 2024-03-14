<?php

class BWFAN_API_Retry_Abandoned_Carts extends BWFAN_API_Base {
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
		$this->method = WP_REST_Server::EDITABLE;
		$this->route  = '/carts/abandoned/retry/';
	}

	public function default_args_values() {
		return [
			'abandoned_ids' => []
		];
	}

	public function process_api_call() {
		$abandoned_ids = $this->args['abandoned_ids'];
		if ( empty( $abandoned_ids ) || ! is_array( $abandoned_ids ) ) {
			return $this->error_response( __( 'Abandoned carts missing', 'wp-marketing-automations' ) );
		}

		BWFAN_Recoverable_Carts::retry_abandoned_cart( $abandoned_ids );

		return $this->success_response( [], __( 'Cart retry done', 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Retry_Abandoned_Carts' );
