<?php 
namespace Adminz\Helper;

class ADMINZ_Helper_Woocommerce_Checkout{
	function __construct() {
		add_filter( 'woocommerce_checkout_fields' , [$this,'custom_remove_woo_checkout_fields'],99);
	}
	function custom_remove_woo_checkout_fields( $fields ) {

		$fields['billing']['billing_email']['required'] = 0;

		
		unset($fields['billing']['billing_last_name']);
		unset($fields['billing']['billing_company']);
		unset($fields['billing']['billing_address_2']);
		unset($fields['billing']['billing_postcode']);
		unset($fields['billing']['billing_state']);
		unset($fields['billing']['billing_country']);

		
		unset($fields['shipping']['shipping_last_name']);
		unset($fields['shipping']['shipping_company']);
		unset($fields['shipping']['shipping_address_2']);
		unset($fields['shipping']['shipping_postcode']);
		unset($fields['shipping']['shipping_state']);
		unset($fields['shipping']['shipping_country']);
	   
	    
	    $fields['billing']['billing_address_1']['class'] = ['form-row-first'];
	    $fields['billing']['billing_city']['placeholder'] = __('City name.','administrator-z');
	    $fields['billing']['billing_city']['required'] = 0;
	    $fields['billing']['billing_city']['class'] = ['form-row-last'];
	    $fields['billing']['billing_first_name']['label'] = __('Name (Last, First)','administrator-z');
	    $fields['billing']['billing_first_name']['placeholder'] = __('Name (Last, First)','administrator-z');

	    
	    // clone billing phone to shipping phone
	    $fields['billing']['billing_phone']['class']= ['form-row-last'];
	    $fields['billing']['billing_phone']['priority']= 11;
	    $fields['shipping']['shipping_phone'] = $fields['billing']['billing_phone'];
	    $fields['shipping']['shipping_phone']['required'] = 0;


	    $fields['shipping']['shipping_address_1']['class'] = ['form-row-first'];
	    $fields['shipping']['shipping_city']['placeholder'] = __('City name.','administrator-z');
	    $fields['shipping']['shipping_city']['required'] = 0;
	    $fields['shipping']['shipping_city']['class'] = ['form-row-last'];
	    $fields['shipping']['shipping_first_name']['label'] = __('Name (Last, First)','administrator-z');
	    $fields['shipping']['shipping_first_name']['placeholder'] = __('Name (Last, First)','administrator-z');

	    return $fields;
	}
}