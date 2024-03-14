<?php

class BWFAN_API_Delete_Automation_Contacts extends BWFAN_API_Base {

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
		$this->route        = '/automation/(?P<automation_id>[\\d]+)/delete-contact';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation ID', 'wp-marketing-automations-crm' ),
				'type'        => 'string',
			)
		);
	}

	public function process_api_call() {
		$trail_id = $this->get_sanitized_arg( 'trail_id', 'text_field' );
		$ac_id    = $this->get_sanitized_arg( 'ac_id', 'text_field' );
		$type     = $this->get_sanitized_arg( 'type', 'text_field' );

		if ( empty( $trail_id ) && empty( $ac_id ) ) {
			return $this->error_response( __( 'Invalid / Empty Contact data provided', 'wp-marketing-automations-crm' ), null, 400 );
		}

		/** Automation Contact table */
		if ( empty( $trail_id ) && 'completed' !== $type ) {
			$row = BWFAN_Model_Automation_Contact::get( $ac_id );
		} else {
			$row = BWFAN_Model_Automation_Contact::get_row_by_trail_id( $trail_id );
		}

		if ( ! empty( $row ) ) {
			$resp = $this->delete_data( $row );
			if ( is_array( $resp ) && isset( $resp['status'] ) && 'error' === $resp['status'] ) {
				return $this->error_response( $resp['msg'], null, 400 );
			}
			$this->response_code = 200;

			return $this->success_response( $trail_id, __( '', 'wp-marketing-automations-crm' ) );
		}

		/** Search for automation contact complete row */
		if ( empty( $trail_id ) && 'completed' === $type ) {
			$row = BWFAN_Model_Automation_Complete_Contact::get( $ac_id );
		} else {
			$row = BWFAN_Model_Automation_Complete_Contact::get_row_by_trail_id( $trail_id );
		}

		if ( ! empty( $row ) ) {
			$resp = $this->delete_data( $row, 2 );
			if ( is_array( $resp ) && isset( $resp['status'] ) && 'error' === $resp['status'] ) {
				return $this->error_response( $resp['msg'], null, 400 );
			}
		}

		$this->response_code = 200;

		return $this->success_response( $trail_id, __( 'Automation contact deleted', 'wp-marketing-automations-crm' ) );
	}

	/**
	 * @param $row
	 * @param $mode 'default 1 - Automation contact 2 - Automation complete contact'
	 *
	 * @return string[]|void
	 */
	public function delete_data( $row, $mode = 1 ) {
		if ( empty( $row ) ) {
			return;
		}
		$id       = $row['ID'];
		$aid      = $row['aid'];
		$cid      = $row['cid'];
		$trail_id = $row['trail'];

		/** Automation complete contact table */
		$start_date = isset( $row['s_date'] ) ? $row['s_date'] : '';
		$end_date   = isset( $row['c_date'] ) ? $row['c_date'] : '';

		if ( 1 === absint( $mode ) ) {
			/** Automation contact table */
			$start_date = $row['c_date'];
			$end_date   = absint( $row['last_time'] );
			$end_date   = ( $end_date > 0 ) ? $end_date : time();
			$end_date   = date( 'Y-m-d H:i:s', $end_date + 120 );
		}

		try {
			/** Delete db row */
			if ( 1 === absint( $mode ) ) {
				BWFAN_Model_Automation_Contact::delete( $id );
			} else {
				BWFAN_Model_Automation_Complete_Contact::delete( $id );
			}

			BWFAN_Common::maybe_remove_aid_from_contact_fields( $cid, $aid );

			/** Delete automation contact trail */
			BWFAN_Model_Automation_Contact_Trail::delete_row_by_trail_by( $trail_id );
		} catch ( Error $e ) {
			$msg = "Error occurred in deleting the automation contact db row {$e->getMessage()}";
			BWFAN_Common::log_test_data( $msg, 'automation_contact_delete_fail', true );

			return [ 'status' => 'error', 'msg' => $msg ];
		}

		if ( empty( $start_date ) || empty( $end_date ) ) {
			return;
		}

		if ( false === bwfan_is_autonami_pro_active() ) {
			return;
		}

		/** Fetch engagements */
		$engagements = BWFAN_Model_Engagement_Tracking::get_contact_engagements( $aid, $cid, $start_date, $end_date );
		if ( empty( $engagements ) ) {
			return;
		}

		try {
			/** Delete engagement meta */
			BWFAN_Model_Engagement_Trackingmeta::delete_engagements_meta( $engagements );

			/** Delete engagements */
			BWFAN_Model_Engagement_Tracking::delete_contact_engagements( $engagements );

			/** Delete conversions */
			BWFAN_Model_Conversions::delete_conversions_by_track_id( $engagements );
		} catch ( Error $e ) {
		}
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Delete_Automation_Contacts' );