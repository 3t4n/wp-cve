<?php

/**
 * @link       https://khadim.nz
 * @since      1.0.0
 *
 * @package    Simple_Address_Autocomplete
 * @subpackage Simple_Address_Autocomplete/public
 */

class Simple_Address_Autocomplete_Public
{

	private $plugin_name;
	private $version;

	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/simple-address-autocomplete-public.css', array(), $this->version, 'all');
	}

	public function enqueue_scripts()
	{

		//enqueue main script and localise get_option
		wp_enqueue_script('simple_address_autocomplete_js_scripts', plugin_dir_url(__FILE__) . 'js/simple-address-autocomplete-public.js');

		wp_localize_script('simple_address_autocomplete_js_scripts', 'simple_address_autocomplete_settings_vars', array(
			'simple_address_autocomplete_google_maps_api' => get_option('simple_aa_options_google_maps_api_key'),
			'simple_address_autocomplete_country_selected' => get_option('simple_aa_options_country', 'option'),
			'simple_address_autocomplete_form_field_ids' => get_option('simple_aa_options_field_ids'),
			'simple_address_autocomplete_bias_coordinates' => get_option('simple_aa_options_bias_coordinates'),
			'simple_address_autocomplete_restriction_type' => get_option('simple_aa_options_restriction_type'),
		));
	}
}
