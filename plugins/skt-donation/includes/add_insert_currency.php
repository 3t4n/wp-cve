<?php
if ( !isset( $_POST['name_of_nonce_field'] ) || !wp_verify_nonce($_POST['name_of_nonce_field'], 'name_of_my_action' ) ){	
}else{
	$mode_currency = isset($_POST['mode_currency']) ? $_POST['mode_currency'] : '';
	if($mode_currency !=''){
		$mode_currency = sanitize_text_field($_POST['mode_currency']);
	}
	switch ($mode_currency) {
		case 'currency_sign_paypal':
			$skt_choose_currency_paypal = $wpdb->prefix . "skt_choose_currency_paypal";
			$paypal_sktcurrency_id = $_POST['paypal_sktcurrency_id'];
			$count_row = $wpdb->get_results("SELECT * FROM $skt_choose_currency_paypal");
    		$rowcount = $wpdb->num_rows;
    		if ($rowcount <= 0) {
    			$data_choose_currency = array(
					'type_currency_id' => $paypal_sktcurrency_id
			    );
			  	$choose_currency_data = $wpdb->insert( $skt_choose_currency_paypal, $data_choose_currency );
    		}else{
    			$update_query = $wpdb->get_var($wpdb->prepare("UPDATE $skt_choose_currency_paypal SET type_currency_id='$paypal_sktcurrency_id' WHERE id='1'",$skt_choose_currency_paypal));
    		}
		break;
		case 'currency_sign_twocheckout':
			$skt_choose_currency_twocheckout = $wpdb->prefix . "skt_choose_currency_twocheckout";
			$twocheckout_sktcurrency_id = $_POST['twocheckout_sktcurrency_id'];
			$count_row = $wpdb->get_results("SELECT * FROM $skt_choose_currency_twocheckout");
    		$rowcount = $wpdb->num_rows;
    		if ($rowcount <= 0) {
    			$data_choose_currency = array(
					'type_currency_id' => $twocheckout_sktcurrency_id
			    );
			  	$choose_currency_data = $wpdb->insert( $skt_choose_currency_twocheckout, $data_choose_currency );
    		}else{
    			$update_query = $wpdb->get_var($wpdb->prepare("UPDATE $skt_choose_currency_twocheckout SET type_currency_id='$twocheckout_sktcurrency_id' WHERE id='1'",$skt_choose_currency_twocheckout));
    		}
		break;
	}
}
?>