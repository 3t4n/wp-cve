<?php

//email template for auction winners
function ultimate_auction_email_template($auction_name, $auction_id, $auction_desc, 
	$winner_bid, $winner_email, $return_url){

	global $wpdb;

	/*$name_qry = "SELECT name FROM ".$wpdb->prefix."wdm_bidders WHERE bid =".$winner_bid." AND auction_id =".$auction_id." AND email = '".$winner_email."' ORDER BY id DESC";*/

	$table = $wpdb->prefix . "wdm_bidders";
	$name_qry = $wpdb->prepare("SELECT name FROM {$table} WHERE bid = %d  AND 
		auction_id = %d AND email = %s ORDER BY id DESC", $winner_bid, $auction_id, 
		$winner_email);

	$winner_name = $wpdb->get_var($name_qry);

	//$winner_user = get_user_by('email', $winner_email);
	//$winner_name = $winner_user->user_login;
	
	$rec_email    	= get_option('wdm_paypal_address');
	$cur_code     	= substr(get_option('wdm_currency'), -3);
	$site_name 	= get_bloginfo('name');
	$subject = $site_name . ': ' . __("Congratulations! You have won an auction", 
		"wdm-ultimate-auction");
	$auction_email 	= get_option('wdm_auction_email');
	$site_url 	= get_bloginfo('url');
	
	$message = "";
	$message = __("Hi", "wdm-ultimate-auction")." ".$winner_name.", <br /><br />";
	$message .= sprintf(__("This is to inform you that you have won the auction at WEBSITE URL %s. Here are the auction details", "wdm-ultimate-auction"), $site_url).": <br /><br />";
	
	$mode = get_option('wdm_account_mode');
	
	$paypal_link  = "";
	
	if($mode == 'Sandbox')
		$paypal_link  = "https://sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick";
	else
		$paypal_link  = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick";
	
	$paypal_link .= "&amp;business=".urlencode($rec_email);
	//$paypal_link .= "&lc=US";
	$paypal_link .= "&amp;item_name=".urlencode($auction_name);
	$paypal_link .= "&amp;amount=".urlencode($winner_bid);
	//shipping field hooks
	$shipping_link = '';
	//$paypal_link .= apply_filters('ua_product_shipping_cost_link', $shipping_link, $auction_id, $winner_email); //SHP-ADD hook shipping cost link
	//end shipping
	$paypal_link .= "&amp;currency_code=".urlencode($cur_code);
	$paypal_link .= "&amp;return=".urlencode($return_url);
	$paypal_link .= "&amp;button_subtype=services";
	$paypal_link .= "&amp;no_note=0";
	$paypal_link .= "&amp;bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHostedGuest";
	
	$paypal_link = "<a href='".$paypal_link."' target='_blank'>".$paypal_link."</a>";
	
	$message .= __("Product URL", "wdm-ultimate-auction").": <a href='".$return_url."'>".$return_url."</a> <br />";
	$message .= "<br />".__("Product Name", "wdm-ultimate-auction").": ".$auction_name." <br />";
	$message .= "<br />".__("Description", "wdm-ultimate-auction").": <br />".$auction_desc."<br /><br />";
	
	$check_method = get_post_meta($auction_id, 'wdm_payment_method', true);
	
	$pay_amt = "<strong>".$cur_code." ".$winner_bid."</strong>";
	
	$auction_data = array();
	
	if($check_method === 'method_paypal'){
	    //$auction_data = array();
		
		$auction_data = array( 'auc_id' => $auction_id,
			'auc_name' => $auction_name,
			'auc_desc' => $auction_desc,
			'auc_bid' => $winner_bid,
			'auc_merchant' => $rec_email,
			'auc_payer' => $winner_email,
			'auc_currency' => $cur_code
			);
		
		$message .= sprintf(__("You can contact ADMIN at %1\$s for delivery of the item and pay %2\$s through PayPal", "wdm-ultimate-auction"), $auction_email, $pay_amt)." - <br /><br />";
		
		$paypal_link = apply_filters( 'ua_paypal_email_content', $paypal_link, $auction_data );
		
		$message .= $paypal_link;
		
		$message .= "<br/><br /> ".__("Kindly, click on above URL to make payment", "wdm-ultimate-auction")."<br />";
		
	}
	elseif($check_method === 'method_wire_transfer')
	{
		$msg = apply_filters('ua_product_shipping_cost_wire_cheque',$shipping_link, $auction_id,$winner_bid,$winner_email);
		
		if(!empty($msg))
		{
			$message .= sprintf(__("%s by wire transfer","wdm-ultimate-auction"),$msg).'<br /><br />';
		}
		else
		{
			$message .= sprintf(__("You can pay %s by Wire Transfer.", "wdm-ultimate-auction"), $pay_amt)."<br /><br />";
		}
		
		$message .= __("Wire Transfer Details", "wdm-ultimate-auction").": <br />";
		$message .= get_option('wdm_wire_transfer');
	}
	elseif($check_method === 'method_mailing')
	{
		$msg = apply_filters('ua_product_shipping_cost_wire_cheque',$shipping_link, $auction_id,$winner_bid,$winner_email);
		
		if(!empty($msg))
		{
			$message .= sprintf(__("%s by cheque","wdm-ultimate-auction"),$msg).'<br /><br />';
		}
		else
		{
			$message .= sprintf(__("You can pay %s by Cheque.", "wdm-ultimate-auction"), $pay_amt)."<br /><br />";
		}
		
		$message .= __("Mailing Address & Cheque Details", "wdm-ultimate-auction").": <br />";
		$message .= get_option('wdm_mailing_address');
	}
	elseif($check_method === 'method_cash')
	{
		$msg = apply_filters('ua_product_shipping_cost_wire_cheque',$shipping_link, $auction_id,$winner_bid,$winner_email);
		
		if(!empty($msg))
		{
			$message .= sprintf(__("%s by Cash","wdm-ultimate-auction"),$msg).'<br /><br />';
		}
		else
		{
			$message .= sprintf(__("You can pay %s by Cash.", "wdm-ultimate-auction"), $pay_amt)."<br /><br />";
		}
		
		$cash_msg = get_option('wdm_mailing_address');
		
		if(!empty($cash_msg)){
			$message .= __("Cash Details", "wdm-ultimate-auction").": <br />";
			$message .= $cash_msg;
		}
	}
	
	$hdr = "";
	//$headers  = "From: ". $site_name ." <". $auction_email ."> \r\n";
	$hdr .= "MIME-Version: 1.0\r\n";
	$hdr .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
	$email_sent = false;
	
	if(!empty($paypal_link)){
		$headers = "";
		//$headers  = "From: ". get_bloginfo('name') ." <". $seller_email ."> \r\n";
		$headers .= "Reply-To: <". $auction_email ."> \r\n";
		$headers .= $hdr;
		$email_sent = wp_mail( $winner_email, $subject, $message, $headers, '' );
	}
	
	if($email_sent)
	{   
		update_post_meta( $auction_id, 'auction_email_sent', 'sent' );
	}
	
	$headers = "";
	//$headers  = "From: ". get_bloginfo('name') ." <". $seller_email ."> \r\n";
	$headers .= "Reply-To: <". $winner_email ."> \r\n";
	$headers .= $hdr;
	
	$data_to_seller = array();
	$data_to_seller = array('auc_id' => $auction_id,
		'auc_name' => $auction_name,
		'auc_desc' => $auction_desc,
		'auc_price' => $winner_bid,
		'auc_currency' => $cur_code,
		'seller_paypal_email' => $rec_email,
		'winner_email' => $winner_email,
		'seller_email' => $auction_email,
		'winner_name' => $winner_name,
		'pay_method' => $check_method,
		'site_name' => $site_name,
		'site_url' => $site_url,
		'product_url' => $return_url,
		'header' => $headers
		);
	
	if(!empty($paypal_link))
		do_action('ua_shipping_data_email', $data_to_seller);
	
	return $email_sent;
}

//email template for seller
function wdm_ua_seller_notification_mail($email, $bid, $ret_url, $auc_name, $auc_desc, $mod_email, $mod_name, $hdr, $atch){
	$c_code = substr(get_option('wdm_currency'), -3);
	
	$adm_sub = "[".get_bloginfo('name')."]  ".__("A bidder has placed a bid on the product", "wdm-ultimate-auction")." - ".$auc_name;
	$adm_msg = "";
	$adm_msg  = "<strong> ".__("Bidder Details", "wdm-ultimate-auction")." - </strong>";
	$adm_msg .= "<br /><br /> ".__("Bidder Name", "wdm-ultimate-auction").": ".$mod_name;
	$adm_msg .= "<br /><br /> ".__("Bidder Email", "wdm-ultimate-auction").": ".$mod_email;
	$adm_msg .= "<br /><br /> ".__("Bid Value", "wdm-ultimate-auction").": ".$c_code." ".sprintf("%.2f", $bid);
	$adm_msg .= "<br /><br /><strong>".__("Product Details", "wdm-ultimate-auction")." - </strong>";
	$adm_msg .= "<br /><br /> ".__("Product URL", "wdm-ultimate-auction").": <a href='".$ret_url."'>".$ret_url."</a>";
	$adm_msg .= "<br /><br /> ".__("Product Name", "wdm-ultimate-auction").": ".$auc_name;
	$adm_msg .= "<br /><br /> ".__("Description", "wdm-ultimate-auction").": <br />".html_entity_decode(strip_tags($auc_desc))."<br />";
	
	wp_mail($email, $adm_sub, $adm_msg, $hdr, $atch);
}

//email template for bidders
function wdm_ua_bidder_notification_mail($email, $bid, $ret_url, $auc_name, $auc_desc, $hdr, $atch){
	$c_code = substr(get_option('wdm_currency'), -3);
	
	$bid_sub_before = "[".get_bloginfo('name')."] ".__("You recently placed a bid on the product", "wdm-ultimate-auction")." - ".$auc_name;
	$bid_sub =  html_entity_decode($bid_sub_before, ENT_QUOTES);
	$bid_msg = "";
	$bid_msg = __("Here are the details", "wdm-ultimate-auction")." - ";
	$bid_msg .= "<br /><br /> ".__("Product URL", "wdm-ultimate-auction").": <a href='".$ret_url."'>". $ret_url."</a>";
	$bid_msg .= "<br /><br /> ".__("Product Name", "wdm-ultimate-auction").": ".$auc_name;
	$bid_msg .= "<br /><br /> ".__("Bid Value", "wdm-ultimate-auction").": ".$c_code." ".sprintf("%.2f", $bid);
	$bid_msg .= "<br /><br /> ".__("Description", "wdm-ultimate-auction").": <br />".html_entity_decode(strip_tags($auc_desc))."<br />";
	
	wp_mail($email, $bid_sub, $bid_msg, $hdr, $atch);
}

//email template for outbid
function wdm_ua_outbid_notification_mail($email, $bid, $ret_url, $auc_name, $auc_desc, 
	$hdr, $atch){

	global $wpdb;
	$wpdb->hide_errors();
	$c_code = substr(get_option('wdm_currency'), -3);
	
	$outbid_sub = "[".get_bloginfo('name')."] ".__("You have been outbid on the product", 
		"wdm-ultimate-auction")." - ".$auc_name;
	$bid_msg = "";
	$bid_msg = __("Here are the details", "wdm-ultimate-auction")." - ";
	$bid_msg .= "<br /><br /> ".__("Product URL", "wdm-ultimate-auction").": <a href='".$ret_url."'>". $ret_url."</a>";
	$bid_msg .= "<br /><br /> ".__("Product Name", "wdm-ultimate-auction").": ".$auc_name;
	$bid_msg .= "<br /><br /> ".__("Bid Value", "wdm-ultimate-auction").": ".$c_code." ".sprintf("%.2f", $bid);
	$bid_msg .= "<br /><br /> ".__("Description", "wdm-ultimate-auction").": <br />".html_entity_decode(strip_tags($auc_desc))."<br />";
	
	wp_mail($email, $outbid_sub, $bid_msg, $hdr, '');
}
?>