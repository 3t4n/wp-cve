<?php

class BWFAN_API_Update_User_Preference extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public $contact;

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::EDITABLE;
		$this->route  = '/user-preference/(?P<user_id>[\\d]+)';
	}

	public function default_args_values() {
		return array(
			'user_id' => 0,
			'data'    => []
		);
	}

	public function process_api_call() {
		/** checking if id present in params **/
		$user_id = $this->get_sanitized_arg( 'user_id', 'key' );
		if ( empty( $user_id ) ) {
			$this->response_code = 404;

			return $this->error_response( __( "Please provide the user for the data.", 'wp-marketing-automations' ) );
		}

		$user_exists = (bool) get_users( array(
			'include' => $user_id,
			'fields'  => 'ID',
		) );
		if ( ! $user_exists ) {
			$this->response_code = 404;

			return $this->error_response( __( "Contact doesn't exists with the id : ", 'wp-marketing-automations' ) . $user_id );
		}

		if ( ! empty( $this->args['data'] ) && is_array( $this->args['data'] ) ) {
			$data = $this->args['data'];
		}

		if ( isset( $data['contact_column'] ) ) {
			update_user_meta( $user_id, '_bwfan_contact_columns', $data['contact_column'] );
		}
		if ( isset( $data['contact_columnv2'] ) ) {
			update_user_meta( $user_id, '_bwfan_contact_columns_v2', $data['contact_columnv2'] );
		}
		if ( isset( $data['campaign_column'] ) ) {
			update_user_meta( $user_id, '_bwfan_broadcast_columns', $data['campaign_column'] );
		}
		if ( isset( $data['welcome_note_dismiss'] ) ) {
			update_user_meta( $user_id, '_bwfan_welcome_note_dismissed', $data['welcome_note_dismiss'] );
		}
		if ( isset( $data['bwfan_header_notification'] ) ) {
			$userdata   = get_user_meta( $user_id, '_bwfan_header_notification', true );
			$userdata   = empty( $userdata ) && ! is_array( $userdata ) ? [] : $userdata;
			$userdata[] = $data['bwfan_header_notification'];
			update_user_meta( $user_id, '_bwfan_header_notification', $userdata );
		}
		if ( isset( $data['table_sort_data'] ) ) {
			update_user_meta( $user_id, '_bwfan_table_sort_data', $data['table_sort_data'] );
		}

		return $this->success_response( [], __( "Preferences Updated ", 'wp-marketing-automations' ) );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Update_User_Preference' );
