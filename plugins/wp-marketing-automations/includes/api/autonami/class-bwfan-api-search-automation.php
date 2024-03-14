<?php

class BWFAN_API_Search_Automations extends BWFAN_API_Base {
	public static $ins;
	public $total_count = 0;
	public $count_data = 0;

	public function __construct() {
		parent::__construct();
		$this->method             = WP_REST_Server::READABLE;
		$this->route              = '/search/automations';
		$this->pagination->offset = 0;
		$this->pagination->limit  = 25;
		$this->request_args       = array(
			'search' => array(
				'description' => __( 'Autonami Search', 'wp-marketing-automations' ),
				'type'        => 'string',
			)
		);
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function default_args_values() {
		$args = [
			'search' => '',
			'status' => 'all',
			'offset' => 0,
			'limit'  => 10,
			'ids'    => [],
		];

		return $args;
	}

	public function process_api_call() {
		$search  = $this->get_sanitized_arg( 'search', 'text_field' );
		$version = isset( $this->args['version'] ) ? intval( $this->args['version'] ) : 1;
		$ids     = empty( $this->args['ids'] ) ? array() : explode( ',', $this->args['ids'] );
		$limit   = isset( $this->args['limit'] ) ? intval( $this->args['limit'] ) : 10;
		$offset  = isset( $this->args['offset'] ) ? intval( $this->args['offset'] ) : 0;

		$get_automations = BWFAN_Common::get_automation_by_title( $search, $version, $ids, $limit, $offset );

		return $this->success_response( $get_automations, __( 'Automations found', 'wp-marketing-automations' ) );
	}

	public function get_result_total_count() {
		return $this->total_count;
	}

	public function get_result_count_data() {
		return $this->count_data;
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Search_Automations' );
