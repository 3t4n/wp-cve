<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

class PaytrCheckoutCallbackEft {
	public static function callback_eft( $post ) {
		// Get PayTR Options
		$options = get_option( 'woocommerce_paytreft_settings' );

		$hash = base64_encode( hash_hmac( 'sha256', sanitize_text_field( $post['merchant_oid'] ) . $options['paytr_eft_merchant_salt'] . sanitize_text_field( $post['status'] ) . sanitize_text_field( $post['total_amount'] ), $options['paytr_eft_merchant_key'], true ) );

		if ( $hash != sanitize_text_field( $post['hash'] ) ) {
			die( 'PAYTR notification failed: bad hash' );
		}

		$order_id = explode( 'PAYTRWOO', sanitize_text_field( $post['merchant_oid'] ) );
		$order    = new WC_Order( $order_id[1] );

		$post_status = get_post_status( $order_id[1] );

		if ( $post_status == 'wc-pending' or $post_status == 'wc-failed' or $post_status == 'wc_cancelled') {
			if ( sanitize_text_field( $post['status'] ) == 'success' ) {

				// Reduce Stock Levels
				wc_reduce_stock_levels( $order_id[1] );

				$total_amount   = round( sanitize_text_field( $post['total_amount'] ) / 100, 2 );
				$payment_amount = round( sanitize_text_field( $post['payment_amount'] ) / 100, 2 );

				// Note Start
				$note = __( 'PAYTR NOTIFICATION - Payment Accepted', 'paytr-sanal-pos-woocommerce-iframe-api' ) . "\n";
				$note .= __( 'Total Paid', 'paytr-sanal-pos-woocommerce-iframe-api' ) . ': ' . sanitize_text_field( wc_price( $total_amount, array( 'currency' => $order->get_currency() ) ) ) . "\n";
				$note .= __( 'Paid', 'paytr-sanal-pos-woocommerce-iframe-api' ) . ': ' . sanitize_text_field( wc_price( $payment_amount, array( 'currency' => $order->get_currency() ) ) ) . "\n";
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
				$wpdb->update( $table_prefix . 'paytr_eft_transaction', $data, $where );

				$order->add_order_note( nl2br( $note ) );
				$order->update_status( $options['paytr_eft_order_status'] );
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
				$wpdb->update( $table_prefix . 'paytr_eft_transaction', $data, $where );

				$order->add_order_note( nl2br( $note ) );
				$order->update_status( 'failed' );
			}
		}

		echo 'OK';
		exit;
	}

	public static function callback_eft_interim( $post ) {
		// Get PayTR Options
		$options = get_option( 'woocommerce_paytreft_settings' );

		$order_id = explode( 'PAYTRWOO', sanitize_text_field( $post['merchant_oid'] ) );
		$order    = new WC_Order( $order_id[1] );

		$merchant['merchant_key']  = $options['paytr_eft_merchant_key'];
		$merchant['merchant_salt'] = $options['paytr_eft_merchant_salt'];

		$hash = base64_encode( hash_hmac( 'sha256', sanitize_text_field( $post['merchant_oid'] ) . sanitize_text_field( $post['bank'] ) . $merchant['merchant_salt'], $merchant['merchant_key'], true ) );

		if ( $hash != $_POST['hash'] ) {
			die( 'PAYTR notification failed: bad hash' );
		}

		// Note Start
		$note = __( 'PAYTR NOTIFICATION - Interim Notify', 'paytr-sanal-pos-woocommerce-eft-api' ) . "\n";
		$note .= __( 'Bank', 'paytr-sanal-pos-woocommerce-eft-api' ) . ': ' . sanitize_text_field( $post['bank'] ) . "\n";
		$note .= __( 'Name Surname', 'paytr-sanal-pos-woocommerce-eft-api' ) . ': ' . sanitize_text_field( $post['user_name'] ) . "\n";
		$note .= __( 'Phone', 'paytr-sanal-pos-woocommerce-eft-api' ) . ': ' . sanitize_text_field( $post['user_phone'] ) . "\n";
		$note .= __( 'Date', 'paytr-sanal-pos-woocommerce-eft-api' ) . ': ' . sanitize_text_field( $post['payment_sent_date'] ) . "\n";
		$note .= __( 'PayTR Order ID', 'paytr-sanal-pos-woocommerce-eft-api' ) . ': <a href="https://www.paytr.com/magaza/islemler?merchant_oid=' . sanitize_text_field( $post['merchant_oid'] ) . '" target="_blank">' . sanitize_text_field( $post['merchant_oid'] ) . '</a>';
		// Note End

		$order->add_order_note( nl2br( $note ) );

		echo 'OK';
		exit;
	}
}