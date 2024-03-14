<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

//Include the dependencies needed to instantiate the block.
add_action('woocommerce_blocks_loaded', function() {
    require_once __DIR__ . '/vat-number-block-integration.php';
	add_action(
		'woocommerce_blocks_checkout_block_registration',
		function( $integration_registry ) {
			$integration_registry->register( new WC_Szamlazz_VAT_Number_Block_Integration() );
		}
	);
	
	//Extends the cart schema to include the vat number values
	if(function_exists('woocommerce_store_api_register_endpoint_data')) {
		require_once __DIR__ . '/vat-number-block-endpoints.php';
		WC_Szamlazz_VAT_Number_Block_Extend_Store_Endpoint::init();
	}
	
	//Save order meta
	add_action('woocommerce_store_api_checkout_update_order_from_request', function( \WC_Order $order, \WP_REST_Request $request ) {
		$request_data = $request['extensions']['wc-szamlazz-vat-number'];
		if($request_data['customer_type'] == 'company') {

			//If company name is missing, ask for it
			if(!$request['billing_address']['company']) {
				throw new Exception(apply_filters('wc_szamlazz_company_validation_required_message', esc_html__( 'If you enter a VAT number, the company name field is required.', 'wc-szamlazz')));
			}

			//Check if VAT number is EU, in that case we need to validate the billing country too
			$vat_number = $request_data['billing_vat_number'];
			if(preg_match('/^[A-Z]{2}/', $vat_number)) {
				$country_code = substr($vat_number, 0, 2);
				$billing_country = $request['billing_address']['country'];
				if($country_code != $billing_country) {
					throw new Exception(apply_filters('wc_szamlazz_eu_vat_number_validation_country_mismatch_message', esc_html__( 'The VAT number is from another country, please select that country in the billing address.', 'wc-szamlazz')));
				}
			}
			
			//Validate it if we don't have data, this will also return the data to save
			$vat_number_data = WC()->session->get( 'vat-number-data' );
			if(!isset($vat_number_data['valid']) || !isset($vat_number_data['name'])) {
				if(preg_match('/^[A-Z]{2}/', $vat_number)) {
					$vat_number_data = WC_Szamlazz_Vat_Number_Field::get_eu_vat_number_data($vat_number);
				} else {
					$vat_number_data = WC_Szamlazz_Vat_Number_Field::get_vat_number_data($vat_number);
				}
			}

			//Maybe validate it again just in case?
			if(!$vat_number_data['valid']) {
				throw new Exception(apply_filters('wc_szamlazz_vat_number_validation_failed_message', esc_html__( 'The VAT number is invalid.', 'wc-szamlazz')));
			}

			//Save data
			$order->update_meta_data( '_billing_wc_szamlazz_adoszam', $vat_number );
			$order->update_meta_data( '_wc_szamlazz_adoszam_data', $vat_number_data );
			$order->save();

			//Update customer meta too
			if(is_user_logged_in()) {
				$customer_id = get_current_user_id();
				update_user_meta( $customer_id, 'wc_szamlazz_adoszam', $vat_number );
			}

		}

		//If a company name was entered, but not a vat number, throw error
		if($request_data['customer_type'] == 'individual') {
			if($request['billing_address']['company']) {
				throw new Exception(apply_filters('wc_szamlazz_tax_validation_required_message', esc_html__( 'If you enter a company name, the VAT number field is required.', 'wc-szamlazz')));
			}
		}
	}, 10, 2);

});