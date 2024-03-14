<?php

class BWFAN_API_Update_Automation extends BWFAN_API_Base {
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
		$this->route        = '/automation/(?P<automation_id>[\\d]+)';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation ID to retrieve', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			),
		);
	}

	public function process_api_call() {
		$automation_id = $this->get_sanitized_arg( 'automation_id', 'text_field' );
		$arg_data      = $this->args['data'];
		if ( empty( $arg_data ) ) {
			return $this->error_response( [], __( 'Automation Data is missing.', 'wp-marketing-automations' ) );
		}
		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $automation_id );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [], $automation_obj->error );
		}

		$data    = $steps = $links = $meta = [];
		$count   = 0;
		$updated = false;
		/** Main table data */
		if ( isset( $arg_data['data'] ) && ! empty( $arg_data['data'] ) ) {
			$data = $arg_data['data'];
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

		/** Update automation meta */
		if ( isset( $arg_data['meta'] ) && ! empty( $arg_data['meta'] ) ) {
			$meta = $arg_data['meta'];
		}

		/** Check for unique key */
		if ( isset( $arg_data['need_unique_key'] ) && is_bool( $arg_data['need_unique_key'] ) && $arg_data['need_unique_key'] == true ) {
			$meta['event_meta'] = [
				'bwfan_unique_key' => md5( uniqid( time(), true ) )
			];
		}

		/** Check for unique key */
		if ( isset( $arg_data['isWebhook'] ) && is_bool( $arg_data['isWebhook'] ) && $arg_data['isWebhook'] == true && isset( $meta['event_meta'] ) ) {
			$automation_meta = $automation_obj->get_automation_meta_data();
			$ameta           = [];
			if ( isset( $automation_meta['event_meta'] ) ) {
				$ameta = $automation_meta['event_meta'];
			}

			$exclude_key = [ 'bwfan_unique_key', 'received_at', 'referer', 'webhook_data' ];
			if ( ! empty( $meta['event_meta'] ) ) {
				foreach ( $meta['event_meta'] as $key => $value ) {
					if ( ! in_array( $key, $exclude_key ) ) {
						$ameta[ $key ] = $value;
					}
				}
			}
			$meta['event_meta'] = $ameta;
		}
		/** Check for data */
		if ( empty( $data ) && empty( $steps ) && empty( $links ) && $count == 0 && empty( $meta ) ) {
			return $this->error_response( [], __( 'Automation Data is missing.', 'wp-marketing-automations' ) );
		}

		if ( ! empty( $data ) ) {
			if ( isset( $data['start'] ) ) {
				unset( $data['start'] );
			}
			/** Update main table data */
			$updated = $automation_obj->update_automation_main_table( $data );
		}

		if ( ! empty( $steps ) || ! empty( $links ) || $count !== 0 || ! empty( $meta ) ) {
			/** Update automation data with meta data */
			$updated = $automation_obj->update_automation_meta_data( $meta, $steps, $links, $count );
		}

		if ( $updated ) {
			$this->response_code = 200;

			return $this->success_response( [], __( 'Automation Data Updated', 'wp-marketing-automations' ) );
		} else {
			$this->response_code = 404;

			return $this->error_response( [], __( 'Unable to updated data', 'wp-marketing-automations' ) );
		}
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Update_Automation' );