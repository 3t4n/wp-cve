<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

class PaytrCheckoutCallbackIframe {
	public static function callback_iframe( $post ) {
		// Get PayTR Options
		$options = get_option( 'woocommerce_paytrcheckout_settings' );

		$hash = base64_encode( hash_hmac( 'sha256', sanitize_text_field( $post['merchant_oid'] ) . $options['paytr_merchant_salt'] . sanitize_text_field( $post['status'] ) . sanitize_text_field( $post['total_amount'] ), $options['paytr_merchant_key'], true ) );

		if ( $hash != sanitize_text_field( $post['hash'] ) ) {
			die( 'PAYTR notification failed: bad hash' );
		}

		$order_id = explode( 'PAYTRWOO', sanitize_text_field( $post['merchant_oid'] ) );
		$order    = new WC_Order( $order_id[1] );

		$post_status = get_post_status( $order_id[1] );

		if ( $post_status == 'wc-pending' or $post_status == 'wc-failed' ) {
			if ( sanitize_text_field( $post['status'] ) == 'success' ) {

				// Reduce Stock Levels
				wc_reduce_stock_levels( $order_id[1] );

				$total_amount    = round( sanitize_text_field( $post['total_amount'] ) / 100, 2 );
				$payment_amount  = round( sanitize_text_field( $post['payment_amount'] ) / 100, 2 );
				$installment_dif = $total_amount - $payment_amount;

				// Note Start
				$note = __( 'PAYTR NOTIFICATION - Payment Accepted', 'paytr-sanal-pos-woocommerce-iframe-api' ) . "\n";
				$note .= __( 'Total Paid', 'paytr-sanal-pos-woocommerce-iframe-api' ) . ': ' . sanitize_text_field( wc_price( $total_amount, array( 'currency' => $order->get_currency() ) ) ) . "\n";
				$note .= __( 'Paid', 'paytr-sanal-pos-woocommerce-iframe-api' ) . ': ' . sanitize_text_field( wc_price( $payment_amount, array( 'currency' => $order->get_currency() ) ) ) . "\n";

				if ( $installment_dif > 0 ) {
					if ( $options['paytr_ins_difference'] == 'yes' ) {
						$installment_fee = new WC_Order_Item_Fee();
						$installment_fee->set_name( __( 'Installment Difference', 'paytr-sanal-pos-woocommerce-iframe-api' ) );
						$installment_fee->set_tax_status( 'none' );
						$installment_fee->set_total( $installment_dif );
						$order->add_item( $installment_fee );

						$order->calculate_totals();
					}

					$note .= __( 'Installment Difference', 'paytr-sanal-pos-woocommerce-iframe-api' ) . ': ' . wc_price( $installment_dif, array( 'currency' => $order->get_currency() ) ) . "\n";
				}

				if ( array_key_exists( 'installment_count', $post ) ) {
					$note .= __( 'Installment Count', 'paytr-sanal-pos-woocommerce-iframe-api' ) . ': ' . ( sanitize_text_field( $post['installment_count'] ) == 1 ? __( 'One Shot', 'paytr-sanal-pos-woocommerce-iframe-api' ) : sanitize_text_field( $post['installment_count'] ) ) . "\n";
				}

				$note .= __( 'PayTR Order ID', 'paytr-sanal-pos-woocommerce-iframe-api' ) . ': <a href="https://www.paytr.com/magaza/islemler?merchant_oid=' . sanitize_text_field( $post['merchant_oid'] ) . '" target="_blank">' . sanitize_text_field( $post['merchant_oid'] ) . '</a>';
				// Note End

				global $wpdb, $table_prefix;

				$data  = [
					'total_paid'     => $total_amount,
					'status'         => 'success',
					'status_message' => 'completed',
					'is_completed'   => 1,
					'is_failed'      => 0,
					'date_updated'   => current_time('mysql')
				];
				$where = [ 'merchant_oid' => sanitize_text_field( $post['merchant_oid'] ) ];
				$wpdb->update( $table_prefix . 'paytr_iframe_transaction', $data, $where );

				$order->add_order_note( nl2br( $note ) );
				$order->update_status( $options['paytr_order_status'] );
			} else {
				// Note Start
				$note = __( 'PAYTR NOTIFICATION - Payment Failed', 'paytr-sanal-pos-woocommerce-iframe-api' ) . "\n";
				$note .= __( 'Error', 'paytr-sanal-pos-woocommerce-iframe-api' ) . ': ' . sanitize_text_field( $post['failed_reason_code'] ) . ' - ' . sanitize_text_field( $post['failed_reason_msg'] ) . "\n";
				$note .= __( 'PayTR Order ID', 'paytr-sanal-pos-woocommerce-iframe-api' ) . ': <a href="https://www.paytr.com/magaza/islemler?merchant_oid=' . sanitize_text_field( $post['merchant_oid'] ) . '" target="_blank">' . sanitize_text_field( $post['merchant_oid'] ) . '</a>';

				global $wpdb, $table_prefix;

				$data  = [
					'total_paid'     => 0,
					'status'         => 'failed',
					'status_message' => sanitize_text_field( $post['failed_reason_code'] ) . ' - ' . sanitize_text_field( $post['failed_reason_msg'] ),
					'is_completed'   => 1,
					'is_failed'      => 1,
					'date_updated'   => current_time('mysql')
				];
				$where = [ 'merchant_oid' => sanitize_text_field( $post['merchant_oid'] ) ];
				$wpdb->update( $table_prefix . 'paytr_iframe_transaction', $data, $where );

				$order->add_order_note( nl2br( $note ) );
				$order->update_status( 'failed' );
			}
		}

		echo 'OK';
		exit;
	}
}