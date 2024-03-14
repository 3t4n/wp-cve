<?php

use ArtisanWorkshop\WooCommerce\PluginFramework\v2_0_12 as Framework;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Generates requests to send to Paydesign
 */
class WC_Gateway_PAYDESIGN_Request {

	/**
	 * Framework.
	 *
	 * @var object
	 */
	public $jp4wc_framework;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->jp4wc_framework = new Framework\JP4WC_Plugin();
	}

	/**
	 * Get metaps PAYMENT Args for passing to PP
	 *
	 * @param WC_Order $order
	 * @return string URL
	 */
	public function get_post_to_paydesign( $order , $connect_url , $setting, $thanks_url = null, $debug = 'yes') {
		global $woocommerce;
		//Set States Information
		$states = WC()->countries->get_allowed_country_states();

		$post_data['OKURL'] = $thanks_url;
		$post_data['RT'] = $woocommerce->cart->get_cart_url().'?pd=return&sid='.$setting['sid'];
		// Customer parameter
		$post_data = $this->paydesign_address($post_data, $order, $states);
		$post_data = $this->paydesign_setting($post_data, $order, $setting);
		$get_source = http_build_query($post_data);
		$get_url = $connect_url.'?'.$get_source;

		$this->metaps_set_log( $connect_url, $order, $post_data, $debug );

		// GET URL
		return $get_url;
	}

	/**
	 * Set User Information
	 *
	 * @param array post_data
	 * @param object WP_order
	 * @param array State data
	 * @return array post data
	 */
	public function paydesign_address($post_data, $order, $states){
		if(version_compare( WC_VERSION, '2.7', '<' )){
			$post_data['MAIL'] = $order->billing_email;
			$post_data['NAME1'] = mb_convert_encoding($order->billing_last_name, "SJIS");
			$post_data['NAME2'] = mb_convert_encoding($order->billing_first_name, "SJIS");
			$post_data['YUBIN1'] = str_replace('-','',$order->billing_postcode);
			$state = $states['JP'][$order->billing_state];
			$post_data['ADR1'] = mb_convert_encoding($state.$order->billing_city, "SJIS");
			$post_data['TEL'] = substr(str_replace('-','',$order->billing_phone),0,11);
			$billing_address_1 = $order->billing_address_1;
			$billing_address_2 = $order->billing_address_2;
			$billing_yomigana_last_name = $order->billing_yomigana_last_name;
			$billing_yomigana_first_name = $order->billing_yomigana_first_name;
		}else{
			$post_data['MAIL'] = $order->get_billing_email();
			$post_data['NAME1'] = mb_convert_encoding($order->get_billing_last_name(), "SJIS");
			$post_data['NAME2'] = mb_convert_encoding($order->get_billing_first_name(), "SJIS");
			$post_data['YUBIN1'] = str_replace('-','',$order->get_billing_postcode());
			$state = $states['JP'][$order->get_billing_state()];
			$post_data['ADR1'] = mb_convert_encoding($state.$order->get_billing_city(), "SJIS");
			$post_data['TEL'] = substr(str_replace('-','',$order->get_billing_phone()),0,11);
			$billing_address_1 = $order->get_billing_address_1();
			$billing_address_2 = $order->get_billing_address_2();
			$billing_yomigana_last_name = get_post_meta( $order->get_id(), '_billing_yomigana_last_name', true );
			$billing_yomigana_first_name = get_post_meta( $order->get_id(), '_billing_yomigana_first_name', true );
		}
		if(strlen($post_data['NAME1'])>20)$post_data['NAME1'] = substr($post_data['NAME1'],0,20);
		if(strlen($post_data['NAME2'])>20)$post_data['NAME2'] = substr($post_data['NAME2'],0,20);
		if($billing_yomigana_last_name and $billing_yomigana_first_name){
			$post_data['KANA1'] = mb_convert_encoding($billing_yomigana_last_name, "SJIS");
			if(strlen($post_data['KANA1'])>20)$post_data['KANA1'] = substr($post_data['KANA1'],0,20);
			$post_data['KANA2'] = mb_convert_encoding($billing_yomigana_first_name, "SJIS");
			if(strlen($post_data['KANA2'])>20)$post_data['KANA2'] = substr($post_data['KANA2'],0,20);
		}
		if(strlen($post_data['YUBIN1']) > 3){
			$post_data['YUBIN2'] = substr($post_data['YUBIN1'],-4);
			$post_data['YUBIN1'] = substr($post_data['YUBIN1'],0,3);
		}
		if(strlen($post_data['ADR1'])>50)$post_data['ADR1'] = substr($post_data['ADR1'],0,50);
		if(isset($billing_address_2)){
			$post_data['ADR2'] = mb_convert_encoding($billing_address_1.$billing_address_2, "SJIS");
		}else{
			$post_data['ADR2'] = mb_convert_encoding($billing_address_1, "SJIS");
		}
		if(strlen($post_data['ADR2'])>50)$post_data['ADR2'] = mb_convert_encoding(substr($post_data['ADR2'],0,50), "SJIS", "SJIS");

		return $post_data;
	}
	/**
	 * Set Setting Information
	 *
	 * @param array post_data
	 * @param object WP_order
	 * @param array setting data
	 * @return array post_data
	 */
	public function paydesign_setting($post_data, $order, $setting){

		//set post data
		$post_data['IP'] = $setting['ip'];
		$post_data['SID'] = $setting['sid'];
		if(isset($setting['kakutei']))$post_data['KAKUTEI'] = $setting['kakutei'];
		if(isset($setting['pass'])) $post_data['PASS'] = $setting['pass'];
		if(isset($setting['store'])) $post_data['STORE'] = $setting['store'];
		if(isset($setting['lang'])) $post_data['LANG'] = $setting['lang'];
		if(isset($setting['ip_user_id']))$post_data['IP_USER_ID'] = $setting['ip_user_id'];
		//Set Products Name
		if(version_compare( WC_VERSION, '2.7', '<' )){
			foreach($order->get_items() as $product){
				$item_name[] = mb_convert_encoding($product['name'], "SJIS");
			}
		}else{
			foreach($order->get_items() as $item_key => $item_values){
				$item_name[] = mb_convert_encoding($item_values->get_name(), "SJIS");
			}
		}
		$post_data['N1'] = mb_convert_encoding(substr($item_name[0],0,50), "SJIS", "SJIS");
		if(version_compare( WC_VERSION, '2.7', '<' )){
			$post_data['K1'] = $order->order_total;
		}else{
			$post_data['K1'] = $order->get_total();
		}

		// Convenience parameter
		if(isset($setting['kigen']))$post_data['KIGEN'] = $setting['kigen'];
		// Token parameter
		if(isset($setting['token']))$post_data['TOKEN'] = $setting['token'];

		if(isset($setting['paymode'])){
			$post_data['PAYMODE'] = $setting['paymode'];
			if(isset($setting['incount']))$post_data['INCOUNT'] = $setting['incount'];
		}

		return $post_data;
	}
	/**
	 * Send the request to PayDesign's API for URL
	 *
	 * @param array $data, $connect_url, $order
	 * @return string response_url
	 */
	public function paydesign_request( $data, $connect_url, $order, $debug = 'yes' ) {
		$get_source = http_build_query($data);
		$get_url = $connect_url.'?'.$get_source;
		$response = file_get_contents($get_url);

		$this->metaps_set_log( $connect_url, $order, $data, $debug );

		return $response;
	}

	/**
	 * Send the request to PayDesign's API to module
	 *
	 * @param $setting, $connect_url, $order
	 * @return string response
	 */
	public function paydesign_post_request( $order, $connect_url, $setting, $debug = 'yes' ) {
		global $woocommerce;
		//Set States Information
		$states = WC()->countries->get_allowed_country_states();

		$post_data = array();
		$post_data = $this->paydesign_setting($post_data, $order, $setting);
		$post_data = $this->paydesign_address($post_data, $order, $states);

		$get_source = http_build_query($post_data);
		$get_url = $connect_url.'?'.$get_source;
		$response = file($get_url);

		$this->metaps_set_log( $connect_url, $order, $post_data, $debug );

		return $response;
	}

	/**
	 *
	 */
	public function metaps_set_log( $connect_url, $order, $data, $debug ){
		//Save debug send data.
		$send_message = 'connect URL : '.$connect_url."\n";
		if(!is_null($order)){
			$send_message .= __('This request send data for order ID:', 'woo-paydesign' ).$order->get_id()."\n";
		}
		$request_array = array();
		foreach ($data as $key => $value){
			$request_array[$key] = mb_convert_encoding($value, 'UTF-8', 'SJIS');
		}
		$send_message .= __('The request post data is shown below.', 'woo-paydesign' )."\n".$this->jp4wc_framework->jp4wc_array_to_message( $request_array );
		$this->jp4wc_framework->jp4wc_debug_log( $send_message, $debug, 'wc-metaps' );
	}
}
