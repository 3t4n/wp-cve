<?php
/*
Plugin Name: Addy's NZ Address Autocomplete for WooCommerce
Version: 2.1.7
Author: Addy Limited
Author URI: https://www.addy.co.nz
Description: Address autocomplete will validate and suggest addresses as a user types to make online checkouts fast, easy and accurate. 
*/
 
if (!defined('ABSPATH')) {
	exit;
}

/* Verify that woocommerce is installed */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	add_action('woocommerce_after_checkout_form', 'register_addy_complete');
	add_action('woocommerce_after_edit_address_form_billing', 'register_addy_complete');
	add_action('woocommerce_after_edit_address_form_shipping', 'register_addy_complete');
 	add_action('wp_enqueue_scripts', 'add_addy_frontend_scripts');
	
 	function add_addy_frontend_scripts() {		
		wp_enqueue_script('jquery');
    
		$addy_script_path = plugins_url('addycomplete.min.js', __FILE__ );
		wp_enqueue_script('addy-script', $addy_script_path);
								
		$addy_style_path = plugins_url('addycomplete.min.css', __FILE__ );
		wp_enqueue_style('addy-css', $addy_style_path);
	}
		
	function register_addy_complete($checkout) {
 		$addy_api_key = esc_attr(get_option('addy-api-key'));
 		$addy_hide_postcode = get_option('addy-hide-postcode');
		$addy_hide_rural = get_option('addy-hide-rural');
		$addy_hide_undeliver = get_option('addy-hide-undeliver');
		$addy_enable_location = get_option('addy-enable-location');
		$addy_filter_postcodes = esc_attr(get_option('addy-filter-postcodes'));
		$addy_filter_regions = esc_attr(get_option('addy-filter-regions'));
		$addy_not_found = esc_attr(get_option('addy-not-found'));
		
		echo "<script>\n var addyConfig = { 'key': '{$addy_api_key}', hidePostCode: '$addy_hide_postcode', hideRural: '$addy_hide_rural', hideUndeliver: '$addy_hide_undeliver', enableLocation: '$addy_enable_location', filterPostcodes: '{$addy_filter_postcodes}', filterRegions: '{$addy_filter_regions}', notFound: '{$addy_not_found}' };\n</script>";
	}

	add_filter('woocommerce_get_settings_checkout', 'addy_settings', 10, 1);

	function addy_settings($settings) {
		$settings[] = array('name' => __( 'Addy\'s NZ Address Autocomplete Settings', 'text-domain' ),
			'type' => 'title',
			'desc' => __( 'Configure your address autocomplete and validation settings below:', 'text-domain' ),
			'id' => 'addy-api-settings' );

		$settings[] = array(
			'name'     => __( 'Addy API Key', 'text-domain' ),
			'desc_tip' => __( 'Get your Addy API Key from www.addy.co.nz', 'text-domain' ),
			'id'       => 'addy-api-key',
			'type'     => 'text',
      'default'  => __( 'demo-api-key', 'text-domain' ),
			'desc'     => __( '<br />Get your Addy API key from <a href="https://www.addy.co.nz/signup" target="_blank">www.addy.co.nz</a> or use the demo key \'demo-api-key\'.', 'text-domain' ),
		);

		$settings[] = array(
			'name'     => __( 'Exclude PO Box addresses', 'text-domain' ),
			'id'       => 'addy-hide-postcode',
			'type'     => 'checkbox',
			'desc'     => __( 'Check this box if you do not deliver to PO Box addresses', 'text-domain' ),
		);		

		$settings[] = array(
			'name'     => __( 'Exclude Rural addresses', 'text-domain' ),
			'id'       => 'addy-hide-rural',
			'type'     => 'checkbox',
			'desc'     => __( 'Check this box if you do not deliver to rural addresses', 'text-domain' ),
		);		

		$settings[] = array(
			'name'     => __( 'Exclude Non-Mail addresses', 'text-domain' ),
			'id'       => 'addy-hide-undeliver',
			'type'     => 'checkbox',
			'desc'     => __( 'Check this box if you only ship to mail delivery addresses', 'text-domain' ),
		);		
		
		$settings[] = array(
			'name'     => __( 'Enable Location Services', 'text-domain' ),
			'id'       => 'addy-enable-location',
			'type'     => 'checkbox',
			'desc'     => __( 'Enable <a href="https://www.addy.co.nz/reverse-geocoding-geospatial-addresses" target="_blank">reverse geocoding</a> to return "Addresses near me"', 'text-domain' ),
		);				
		
		$settings[] = array(
			'name'     => __( 'Postal Codes Filter', 'text-domain' ),
			'id'       => 'addy-filter-postcodes',
			'type'     => 'text',
			'desc'     => __( '<br />A dash separated list of supported postcodes (e.g. 0622-1010-7550).<br />Leave blank to support NZ wide coverage. See <a href="https://www.addy.co.nz/faq-nz-postcode-list" target="_blank">Postcode List</a>', 'text-domain' ),
		);		

		$settings[] = array(
			'name'     => __( 'Region Filter', 'text-domain' ),
			'id'       => 'addy-filter-regions',
			'type'     => 'text',
			'desc'     => __( '<br />A dash separated list of supported regions (e.g. 1-2-3-4).<br />Leave blank to support NZ wide coverage. See <a href="https://www.addy.co.nz/faq-nz-regioncode-list" target="_blank">Region List</a>', 'text-domain' ),
		);		
		
		$settings[] = array(
			'name'     => __( 'Not Found Message', 'text-domain' ),
			'id'       => 'addy-not-found',
			'type'     => 'textarea',
			'placeholder' => __('e.g. Address not found. We only deliver to postcodes: 0622 and 1010.'),
			'desc'     => __( 'Add a message to inform the user what to do when an address could not be located.<br />Leave blank to use the default message.', 'text-domain' ),
		);			
		
		$settings[] = array( 'type' => 'sectionend', 'id' => 'addy-api-settings' );
		return $settings;
	}  	
}
?>
