<?php

class embeddedCheckout extends WC_Peach_Payments{

	function __construct() {
		
	}
	
	public function get_embed_urls($status, $mode) {
		if($mode == 'auth'){
			if($status == 'INTEGRATOR_TEST'){
				return 'https://sandbox-dashboard.peachpayments.com';
			}else{
				return 'https://dashboard.peachpayments.com';
			}
		}
		
		if($mode == 'checkout'){
			if($status == 'INTEGRATOR_TEST'){
				return 'https://testsecure.peachpayments.com';
			}else{
				return 'https://secure.peachpayments.com';
			}
		}
	}
	
	public function get_access_token($status, $embed_clientid, $embed_clientsecret, $embed_merchantid, $mode){
		$url = $this->get_embed_urls($status, $mode);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url.'/api/oauth/token',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
			"clientId": "'.$embed_clientid.'",
			"clientSecret": "'.$embed_clientsecret.'",
			"merchantId": "'.$embed_merchantid.'"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$access_response = curl_exec($curl);

		curl_close($curl);
		
		$access_result = json_decode($access_response);
		
		if($access_result->access_token){
			return $access_result->access_token;
		}else{
			return 'error';
		}
	}
	
	public function embed_checkout_instance($status, $mode, $embed_token, $order_id, $order, $entityId){
		$error = '';
		$url = $this->get_embed_urls($status, $mode);
		$nonce = wp_create_nonce( $order_id.'_'.time() );
		$site_url = site_url();
		
		$billing_address = substr($order->get_billing_address_1(),0,50);
		$billing_address = str_replace('&', ' ',$billing_address);
		$billing_address = str_replace('.', '',$billing_address);
		
		$shipping_address = substr($order->get_shipping_address_1(),0,50);
		$shipping_address = str_replace('&', ' ',$shipping_address);
		$shipping_address = str_replace('.', '',$shipping_address);
		
		$post_fields = '{
		"authentication": {
			"entityId": "'.$entityId.'"
		},
		"amount":'.number_format((float)$order->get_total(), 2, '.', '').',
		"currency":"'.$order->get_currency().'",
		"shopperResultUrl":"'.$order->get_checkout_order_received_url().'",
		"merchantTransactionId": "Checkout_'.$order_id.'",
		"nonce": "'.$nonce.'",
		"cancelUrl": "",
		"merchantInvoiceId": "INV-'.$order_id.'",
		"customer": {
			"merchantCustomerId": "'.$order->get_customer_id().'",
			"givenName": "'.str_replace(' ', '', $order->get_billing_first_name()).'",
			"surname": "'.str_replace(' ', '', $order->get_billing_last_name()).'",
			"mobile": "'.$order->get_billing_phone().'",
			"email": "'.$order->get_billing_email().'"
		},
		"billing": {
			"street1": "'.$billing_address.'",
			"city": "'.$order->get_billing_city().'",
			"country": "'.$order->get_billing_country().'",
			"state": "'.$order->get_billing_state().'",
			"postcode": "'.$order->get_billing_postcode().'"
		},
		"shipping": {
			"street1": "'.$shipping_address.'",
			"city": "'.$order->get_shipping_city().'",
			"country": "'.$order->get_shipping_country().'",
			"state": "'.$order->get_shipping_state().'",
			"postcode": "'.$order->get_shipping_postcode().'"
		},
		"defaultPaymentMethod": "",
		"forceDefaultMethod": false,
		"createRegistration": false
		}';
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url.'/v2/checkout',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $post_fields,
		  CURLOPT_HTTPHEADER => array(
			'Origin:'.$site_url,
    		'Referer:'.$site_url,
			'Content-Type:application/json',
			'Authorization:Bearer '.$embed_token
		  ),
		));
		$response = curl_exec($curl);
		if(curl_errno($curl)) {
			$error = curl_error($ch);
		}
		curl_close($curl);
		
		$result = json_decode($response);
		
		$checkout_token = $result->checkoutId;
		
		if($result->checkoutId){
			return $result->checkoutId;
		}else{
			return 'error';
		}
	}
	
}