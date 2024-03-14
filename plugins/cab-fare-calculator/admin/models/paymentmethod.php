<?php

class PaymentmethodModel {

	public $dbtable;

	public function __construct() {
		 global $wpdb;

		$this->dbtable = $wpdb->prefix . 'tblight_paymentmethods';
	}

	public function getItems() {
		global $wpdb;

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$this->dbtable}"
			)
		);

		return $rows;
	}

	public function getItemById( $id = 0 ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->dbtable} WHERE id = %d",
				(int) $id
			)
		);

		return $row;
	}

	public function getDefaultData() {
		$row = new stdClass();

		$row->id              = 0;
		$row->title           = '';
		$row->state           = 1;
		$row->payment_element = '';
		$row->payment_params  = array();
		$row->language        = '';
		$row->text            = '';

		return $row;
	}

	public function store( $post_data ) {
		global $wpdb;

		$id              = (int) $post_data['id'];
		$title           = $post_data['title'];
		$alias           = sanitize_title( $post_data['title'] );
		$state           = $post_data['state'];
		$payment_element = $post_data['payment_element'];
		$text            = $post_data['text'];
		if ( $post_data['payment_element'] == 'paypal' ) {
			$state = 0;
		}

		if ( $id == 0 ) { // New Item
			$payment_params = $this->getPaymentParams( $payment_element );

			$row = $wpdb->insert(
				$this->dbtable,
				array(
					'title'           => $title,
					'alias'           => $alias,
					'state'           => $state,
					'payment_element' => $payment_element,
					'payment_params'  => json_encode( $payment_params ),
					'text'            => $text,
					'created_by'      => get_current_user_id(),
					'created'         => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'%s', // title
					'%s', // alias
					'%d', // state
					'%s', // payment_element
					'%s', // payment_params
					'%s', // text
					'%d', // created_by
					'%s',  // created
				)
			);

			$id = (int) $wpdb->insert_id;
		} elseif ( $id > 0 ) {
			$row = $wpdb->update(
				$this->dbtable,
				array(
					'title'           => $title,
					'alias'           => $alias,
					'state'           => $state,
					'payment_element' => $payment_element,
					'payment_params'  => json_encode( $post_data['params'] ),
					'text'            => $text,
					'modified_by'     => get_current_user_id(),
					'modified'        => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $id,
				)
			);
		}

		return $id;
	}

	public function delete( $id = 0 ) {
		global $wpdb;

		return $wpdb->delete(
			$this->dbtable,
			array( 'id' => $id ),
			array( '%d' )
		);
	}

	public function status( $id = 0 ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT state FROM {$this->dbtable} WHERE id = %d",
				(int) $id
			)
		);

		if ( $row->state == 0 ) {
			$wpdb->update( $this->dbtable, array( 'state' => 1 ), array( 'id' => $id ) );
		} else {
			$wpdb->update( $this->dbtable, array( 'state' => 0 ), array( 'id' => $id ) );
		}

		return true;
	}

	private function getPaymentParams( $payment_element = 'cash' ) {
		if ( $payment_element == 'cash' ) {
			$params = array(
				'min_amount'           => '',
				'max_amount'           => '',
				'cost_per_transaction' => '',
				'cost_percent_total'   => '',
				'default_status'       => -2,
			);
		} elseif ( $payment_element == 'paypal' ) {
			$params = array(
				'sandbox'                    => 1,
				'paypal_merchant_email'      => '',
				'sandbox_merchant_email'     => '',
				'min_amount'                 => '',
				'max_amount'                 => '',
				'cost_per_transaction'       => '',
				'cost_percent_total'         => '',
				'prepayment_percent'         => '100',
				'status_pending'             => -2,
				'status_success'             => 1,
				'status_canceled'            => 0,
				'send_email_pending_status'  => 1,
				'send_email_approved_status' => 1,
				'debug'                      => 0,
			);

		}

		return $params;
	}
}
