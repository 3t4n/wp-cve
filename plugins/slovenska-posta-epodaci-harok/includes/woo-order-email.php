<?php

/*
*	Add info about tracking code to the email 
*/
function tsseph_add_tracking_code_to_email($order, $sent_to_admin, $plain_text, $email) {

	$tsseph_options = get_option('tsseph_options'); 
	$SendTrackingNo = isset($tsseph_options['SendTrackingNo']) ? $tsseph_options['SendTrackingNo'] : 1;

    if($SendTrackingNo && $email->id  == 'customer_completed_order'){

		$tsseph_tracking_no = tsseph_get_tracking_code($order->get_id());

		if ($tsseph_tracking_no != '') {
			echo "<p>" . __('Zásielku môžete sledovať tu','spirit-eph') . ": <a href='https://tandt.posta.sk/zasielky/" . $tsseph_tracking_no . "'>" . $tsseph_tracking_no . "</a></p>";	
		}
    }
}
add_action( 'woocommerce_email_before_order_table', 'tsseph_add_tracking_code_to_email', 10, 4 );
