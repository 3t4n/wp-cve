<?php
/**
 * @package woo-paydesign
 * @category View
 * @version	1.1.23
 * @author Artisan Workshop
 */
require( '../../../wp-blog-header.php' );
global $wpdb;
global $woocommerce;

//To avoid HTTP status 404 code
status_header( 200 );

if(isset($_GET['SEQ']) and isset($_GET['DATE']) and isset($_GET['SID'])){
	$pd_order_id = $_GET['SID'];
	$prefix_order = get_option( 'wc_paydesign_prefix_order' );
	$order_id = str_replace($prefix_order, '', $pd_order_id);
	$post_type = get_post_type( $order_id );
	$order = wc_get_order( $order_id );
	if(version_compare( WC_VERSION, '2.7', '<' )){
		$order_payment_method = $order->payment_method;
		$order_status = $order->status;
	}else{
		$order_payment_method = $order->get_payment_method();
		$order_status = $order->get_status();
	}
	// Logger object
	$wc_logger = new WC_Logger();
    // Add to logger
	$get_message = '';
	foreach ($_GET as $key => $value){
		$get_message .= $key.':'.$value.',';
	}
    $message = sprintf( __('I received GET data from metaps. (%s)', 'woo-paydesign'), $get_message);
//    $wc_logger->add('wc-metaps', $message);
	if($post_type != 'shop_order'){
		// Add to logger
		$message = sprintf( __('This order number (%s) does not exist..', 'woo-paydesign'), $pd_order_id);
		$wc_logger->add('error-metaps', $message);
		header("Content-Type: text/plain; charset=Shift_JIS");
		print "9";
		exit();
	}
	if(isset($_GET['TIME']) and isset($order_status) and ($order_status == 'on-hold' or $order_status == 'pending')){
		$payment_title = __( 'Credit Card (Paydesign)', 'woo-paydesign' );
		if($order_payment_method == 'paydesign_cs' or $order_payment_method == 'paydesign_pe'){
			$email = WC()->mailer();
			$emails = $email->get_emails();
			$send_processing_email = $emails['WC_Email_Customer_Processing_Order'];//require php file
			if($order_payment_method == 'paydesign_cs'){
				$payment_title = __( 'Convenience Store Payment (Paydesign)', 'woo-paydesign' );
			}elseif($order_payment_method == 'paydesign_pe'){
				$payment_title = __( 'Payeasey Payment (Paydesign)', 'woo-paydesign' );
			}
		}else{
			$payment_cc_setting = null;
			$payment_cc_token_setting = null;
			if(class_exists('WC_Gateway_PAYDESIGN_CC')){
				$payment_cc_setting = new WC_Gateway_PAYDESIGN_CC();
			}
			if(class_exists('WC_Gateway_PAYDESIGN_CC_TOKEN')){
				$payment_cc_token_setting = new WC_Gateway_PAYDESIGN_CC_TOKEN();
			}
			if((isset($payment_cc_setting->user_id_payment) and $payment_cc_setting->user_id_payment == 'yes') or ( isset($payment_cc_token_setting->user_id_payment) and $payment_cc_token_setting->user_id_payment == 'yes') )update_user_meta($order->get_user_id(), '_paydesign_user_id' , $prefix_order.$order->get_user_id());
		}
        if( $order->get_status() != 'completed'){
            //set transaction id for Approval number.
            $order->payment_complete( wc_clean( $_GET['SHONIN'] ) );
            $order->save();
        }

//		$order->update_status( 'processing', sprintf( __( 'Payment of %s was complete.', 'woo-paydesign' ) , $payment_title ) );
		header("Content-Type: text/plain; charset=Shift_JIS");
		print "0";
	}elseif(isset($_GET['TIME']) and isset($order_status) and $order_status == 'processing'){
		$message = sprintf( __('This order (%s)  has already been paid.', 'woo-paydesign') , $pd_order_id );
		$wc_logger->add('error-metaps', $message);
		header("Content-Type: text/plain; charset=Shift_JIS");
		print "9";
	}elseif(isset($_GET['TIME']) and isset($order_status) and $order_status == 'completed'){
		$message = sprintf( __('This order (%s) has already completed.', 'woo-paydesign') , $pd_order_id );
		$wc_logger->add('error-metaps', $message);
		header("Content-Type: text/plain; charset=Shift_JIS");
		print "9";
	}elseif(isset($_GET['TIME']) and isset($order_status) and $order_status == 'cancelled'){
		$message = sprintf( __('This order (%s) has already Cancelled.', 'woo-paydesign') , $pd_order_id );
		$wc_logger->add('error-metaps', $message);
		header("Content-Type: text/plain; charset=Shift_JIS");
		print "9";
	}elseif(isset($_GET['TIME']) and isset($order_status) and $order_status == 'refunded'){
		$message = sprintf( __('This order (%s) has already Refunded.', 'woo-paydesign') , $pd_order_id );
		$wc_logger->add('error-metaps', $message);
		header("Content-Type: text/plain; charset=Shift_JIS");
		print "9";
	}
}else{
	header("Content-Type: text/plain; charset=Shift_JIS");
	print "9";
}
?>