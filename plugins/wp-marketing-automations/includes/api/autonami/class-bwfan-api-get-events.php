<?php

class BWFAN_API_Get_Events extends BWFAN_API_Base {
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
		$this->route  = '/events';
	}


	public function process_api_call() {
		$events       = BWFAN_Load_Sources::get_sources_events_arr();
		$all_triggers = BWFAN_Core()->sources->get_source_localize_data();
		uasort( $all_triggers, function ( $a, $b ) {
			return $a['priority'] <= $b['priority'] ? - 1 : 1;
		} );
		$event_data = array_map( function ( $all_trigger ) use ( $events ) {
			if ( isset( $events[ $all_trigger['slug'] ] ) ) {
				$all_trigger = array_replace( $all_trigger, $events[ $all_trigger['slug'] ] );
			}

			return $all_trigger;
		}, $all_triggers );
		if ( ! is_array( $event_data ) || empty( $event_data ) ) {
			return $this->error_response( __( 'Unable to fetch events', 'wp-marketing-automations' ), null, 500 );
		}

		return $this->success_response( $event_data, __( 'Events found', 'wp-marketing-automations' ) );
	}

	public function get_result_total_count() {
		return $this->total_count;
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Events' );
