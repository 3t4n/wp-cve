<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}


class Gateway_Sibs_Multibanco extends Sibs_Payment_Gateway {
	
	public $id = 'sibs_multibanco';

	public $title = 'SIBS MULTIBANCO';
	
	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/multibanco.png';
	}


	protected function sibs_set_payment_parameters( $order_id ) {
		$payment_parameters                                  = parent::sibs_set_payment_parameters( $order_id );
		$multibanco_setting                                  = get_option( 'woocommerce_' . $this->payment_id . '_settings' );
		$sibs_payment_entity                                 = $multibanco_setting['sibs_payment_entity'];
        // NOTE: Multibanco Config Expire Date
		$multibanco_parameters['SIBSMULTIBANCO_PtmntEntty']  = empty( $sibs_payment_entity ) ? 'null' : $sibs_payment_entity;
		$multibanco_parameters['SIBSMULTIBANCO_RefIntlDtTm'] = date( 'Y-m-d\TH:i:s.ZP' );
		//$multibanco_parameters['SIBSMULTIBANCO_RefLmtDtTm']  = date( 'Y-m-d\TH:i:s.ZP', strtotime( '+1 year' ) );

		$abs_number = abs($multibanco_setting['sibs_payment_date_value']);
		$date_offset = "+" . $abs_number . " " . $multibanco_setting['sibs_payment_date_unit'];

		//from Wordpress date time
		$new_date_time = current_time( 'Y-m-d\TH:i:s.ZP' );

		//create DateTime Object
		$d = new DateTime( $new_date_time );
		$d->modify( $date_offset );
		$d = (array)$d;

		$multibanco_parameters['SIBSMULTIBANCO_RefLmtDtTm'] = date( 'Y-m-d\TH:i:s.ZP', strtotime( $d["date"] ));
		// date( 'Y-m-d\TH:i:s.ZP', strtotime( $date_offset )

		$multibanco_parameters['SIBS_ENV']           		 = get_option( 'sibs_general_environment' );
		$payment_parameters['customParameters']              = $multibanco_parameters;

		//log
		$log = new WC_Logger();
		$log_entry = print_r( $payment_parameters, true );
		$log->add( 'woocommerce-sibs-log', 'SET PAYMENT PARAMETERS : ' . $log_entry );

		return $payment_parameters;
	}


	protected function sibs_save_transactions( $order_id, $payment_result, $reference_id ) {
		parent::sibs_save_transactions( $order_id, $payment_result, $reference_id );

		if ( isset( $payment_result['resultDetails']['pmtRef'] ) ) {
			WC()->session->set( 'pmtRef',$payment_result['resultDetails']['pmtRef'] );
		}
		if ( isset( $payment_result['customParameters']['SIBSMULTIBANCO_PtmntEntty'] ) ) {
			WC()->session->set( 'ptmntEntty', $payment_result['customParameters']['SIBSMULTIBANCO_PtmntEntty'] );
		}
		if ( isset( $payment_result['customParameters']['SIBSMULTIBANCO_RefIntlDtTm'] ) ) {
			WC()->session->set( 'refIntlDtTm', $payment_result['customParameters']['SIBSMULTIBANCO_RefIntlDtTm'] );
		}
		if ( isset( $payment_result['customParameters']['SIBSMULTIBANCO_RefLmtDtTm'] ) ) {
			WC()->session->set( 'RefLmtDtTm', $payment_result['customParameters']['SIBSMULTIBANCO_RefLmtDtTm'] );
		}
		if ( isset( $payment_result['amount'] ) ) {
			WC()->session->set( 'Amount', $payment_result['amount'] );
		}
		if ( isset( $payment_result['currency'] ) ) {
			WC()->session->set( 'Currency', $payment_result['currency'] );
		}
	}

	public function thankyou_page() {

		if ( ! isset( WC()->session->sibs_thankyou_page ) ) {

			if ( isset( WC()->session->pmtRef ) ) {
				$pmt_ref = WC()->session->pmtRef;
			} else {
				$pmt_ref = 'null';
			}

			if ( isset( WC()->session->ptmntEntty ) ) {
				$ptmnt_entty = WC()->session->ptmntEntty;
			} else {
				$ptmnt_entty = 'null';
			}

			if ( isset( WC()->session->refIntlDtTm ) ) {
				$valid_date = date( 'Y-m-d H:i', strtotime( WC()->session->refIntlDtTm ) );
			} else {
				$valid_date = 'null';
			}

			if ( isset( WC()->session->RefLmtDtTm ) ) {
				$expire_date = date( 'Y-m-d H:i', strtotime( WC()->session->RefLmtDtTm ) );
			} else {
				$expire_date = 'null';
			}

			if ( isset( WC()->session->Amount ) ) {
				$amount = WC()->session->Amount;
			} else {
				$amount = 'null';
			}

			if ( isset( WC()->session->Currency ) ) {
				$currency = WC()->session->Currency;
			} else {
				$currency = 'null';
			}

				echo '<img src="' . esc_attr( $this->sibs_get_payment_logo() ) . '" style="height:40px; max-height:40px; margin:5px 10px 5px 0; float: none; vertical-align: middle;" />';
				echo '<ul class="sibs_info">
						<li>' . esc_attr( __( 'Payment Entity:', 'wc-sibs' ) ) . ' ' . esc_attr( $ptmnt_entty ) . '</li>	
						<li>' . esc_attr( __( 'Payment Reference:', 'wc-sibs' ) ) . ' ' . esc_attr( $pmt_ref ) . ' </li>
						<li>' . esc_attr( __( 'Payment Amount:', 'wc-sibs' ) ) . ' ' . esc_attr( $amount ) . ' ' . esc_attr( $currency ) . '</li>
						<li>' . esc_attr( __( 'Payment Reference Expiration:', 'wc-sibs' ) ) . ' ' . esc_attr( $expire_date ) . '</li>
					</ul>';

		}// End if().
		parent::thankyou_page();
	}
}



$obj = new Gateway_Sibs_Multibanco();
