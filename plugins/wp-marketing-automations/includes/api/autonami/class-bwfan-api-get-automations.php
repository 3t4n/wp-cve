<?php

class BWFAN_API_Get_Automations extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public $total_count = 0;
	public $count_data = 0;

	public function __construct() {
		parent::__construct();
		$this->method             = WP_REST_Server::READABLE;
		$this->route              = '/automations';
		$this->pagination->offset = 0;
		$this->pagination->limit  = 25;
		$this->request_args       = array(
			'search' => array(
				'description' => __( 'Autonami Search', 'wp-marketing-automations' ),
				'type'        => 'string',
			),
			'status' => array(
				'description' => __( 'Autonami Status', 'wp-marketing-automations' ),
				'type'        => 'string',
			),
			'offset' => array(
				'description' => __( 'Autonami list Offset', 'wp-marketing-automations' ),
				'type'        => 'integer',
			),
			'limit'  => array(
				'description' => __( 'Per page limit', 'wp-marketing-automations' ),
				'type'        => 'integer',
			)
		);
	}

	public function default_args_values() {
		$args = [
			'search' => '',
			'status' => 'all',
			'offset' => 0,
			'limit'  => 25
		];

		return $args;
	}

	public function process_api_call() {
		$status  = $this->get_sanitized_arg( 'status', 'text_field' );
		$search  = $this->get_sanitized_arg( 'search', 'text_field' );
		$offset  = ! empty( $this->get_sanitized_arg( 'offset', 'text_field' ) ) ? $this->get_sanitized_arg( 'offset', 'text_field' ) : 0;
		$limit   = ! empty( $this->get_sanitized_arg( 'limit', 'text_field' ) ) ? $this->get_sanitized_arg( 'limit', 'text_field' ) : 25;
		$version = isset( $this->args['version'] ) ? $this->args['version'] : 1;

		$get_automations = BWFAN_Common::get_all_automations( $search, $status, $offset, $limit, false, $version );
		if ( ! is_array( $get_automations ) || ! isset( $get_automations['automations'] ) || ! is_array( $get_automations['automations'] ) ) {
			return $this->error_response( __( 'Unable to fetch automations', 'wp-marketing-automations' ), null, 500 );
		}

		$this->total_count = isset( $get_automations['total_records'] ) ? absint( $get_automations['total_records'] ) : 0;
		$this->count_data  = BWFAN_Common::get_automation_data_count( $version );

		return $this->success_response( $get_automations['automations'], __( 'Automations found', 'wp-marketing-automations' ) );
	}

	public function get_result_total_count() {
		return $this->total_count;
	}

	public function get_result_count_data() {
		return $this->count_data;
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Automations' );
