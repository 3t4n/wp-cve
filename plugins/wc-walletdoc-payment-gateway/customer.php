<?php
if (!defined('ABSPATH')) {
	exit;
}
 
include_once "lib/Walletdoc.php";
try{
	$pgwalletdoc = new WP_Gateway_Walletdoc();
	$testmode       = $pgwalletdoc->get_option('testmode');
	$client_id      = '';
	$client_secret  = ($pgwalletdoc->get_option('testmode') == "yes")? $pgwalletdoc->get_option('client_secret'): $pgwalletdoc->get_option('production_secret');
	
	$api = new Walletdoc($client_id, $client_secret, $testmode);
		
	$shop = get_option('woocommerce_shop_page_id');
	$genrated_customer_id = "0001234" . $user_id . "" . $shop;
         	$user['first_name'] = isset($_POST['account_first_name'])?sanitize_text_field($_POST['account_first_name']):"";
            $user['last_name'] = isset($_POST['account_last_name'])?sanitize_text_field($_POST['account_last_name']):"";
            $user['email'] = isset($_POST['account_email'])?sanitize_email($_POST['account_email']):"";
            $user['mobile_number'] = '';
			$user['customer_id'] = sanitize_text_field($genrated_customer_id);
			 
			
			if($user['first_name']!=''){
			$response = $api->updateCustomer($genrated_customer_id,$user);
			}
	
       
      
	

	
	
}catch(WalletdocWcValidationException $e){
		WC_Walletdoc_log("Validation Exception Occured with response ".print_r($e->getResponse(),true));
}catch(Exception $e){
	WC_Walletdoc_log($e->getMessage());	
}		
