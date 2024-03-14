<?php
/**
	Addressfinder plugin for autocompleting addresses in WooCommerce for New Zealand and Australia
	Plugin Name: Addressfinder
	Plugin URI: https://github.com/AddressFinder/woocommerce-addressfinder
	Version: 1.6.1
	Author: Addressfinder
	Description: Woocommerce address finder plugin for autocompleting addresses in New Zealand and Australia

	@package AddressFinder/WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ADDRESSFINDER_WOOCOMMERCE_VERSION' ) ) {
	define( 'ADDRESSFINDER_WOOCOMMERCE_VERSION', '1.6.1' );
}

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action( 'woocommerce_after_checkout_form', 'add_addressfinder_widget' );
	add_action( 'woocommerce_after_edit_address_form_billing', 'add_addressfinder_widget' );
	add_action( 'woocommerce_after_edit_address_form_shipping', 'add_addressfinder_widget' );
	add_action( 'wp_enqueue_scripts', 'addressfinder_add_styles' );
	add_action( 'woocommerce_blocks_enqueue_checkout_block_scripts_after', 'add_addressfinder_widget' );

	/**
	 * Adds necessary js code to load and initializes our autocomplete widget
	 *
	 * @param string[] $_checkout unused.
	 */
	function add_addressfinder_widget( $_checkout ) {
		$path               = plugin_dir_path( __FILE__ );
		$af_key_nz          = esc_attr( get_option( 'af-key' ) );
		$af_key_au          = esc_attr( get_option( 'af-key-au' ) );
		$af_widget_options  = get_option( 'af-widget-options' );
		$af_debug           = esc_attr( get_option( 'af-debug' ) );
		$af_default_country = esc_attr( get_option( 'af-default-country' ) );
		$addressfinder_js   = file_get_contents( $path . 'addressfinder.js' );
		echo "<script>\nvar AddressFinderConfig = {};\n";

		if ( $af_key_nz ) {
			printf( "AddressFinderConfig.key_nz = '%s';\n", esc_js( $af_key_nz ) );
		}

		if ( $af_key_au ) {
			printf( "AddressFinderConfig.key_au = '%s';\n", esc_js( $af_key_au ) );
		}

		if ( null !== $af_widget_options && ! empty( trim( $af_widget_options ) ) ) {
			printf( "AddressFinderConfig.widget_options = '%s';\n", wp_json_encode( json_decode( $af_widget_options ) ) );
		}

		if ( 'yes' == $af_debug ) {
			echo "AddressFinderConfig.debug = true;\n";
		}

		if ( $af_default_country ) {
			printf( "AddressFinderConfig.default_country = '%s';\n", esc_js( $af_default_country ) );
		}

		echo "\n</script>";

		wp_enqueue_script( 'addressfinder_js', plugins_url( 'addressfinder.js', __FILE__ ), array(), ADDRESSFINDER_WOOCOMMERCE_VERSION, true );
	}

	// Add the tab to the tabs array
	function filter_addressfinder_settings_tabs_array( $settings_tabs ) {
    $settings_tabs['addressfinder-settings'] = __( 'Addressfinder', 'woocommerce' );

    return $settings_tabs;
	}
	add_filter( 'woocommerce_settings_tabs_array', 'filter_addressfinder_settings_tabs_array', 99 );
	add_filter( 'woocommerce_settings_addressfinder-settings', 'add_settings', 10, 1 );

	function add_settings( ) {
		return WC_Admin_Settings::output_fields( addressfinder_settings() );
	}
	/**
	 * Injects AF related settings into the AF settings page
	 */
	function addressfinder_settings( ) {

		$settings = array();

		$settings[] = array(
			'name' => __( 'Addressfinder', 'text-domain' ),
			'type' => 'title',
			'desc' => __( 'Addressfinder supports Australian and New Zealand data verification services.', 'text-domain' ),
			'id'   => 'addressfinder-widget',
		);

		$af_key_nz = esc_attr( get_option( 'af-key' ) );
		$af_key_au = esc_attr( get_option( 'af-key-au' ) );
		if ( $af_key_nz && $af_key_au ) {
			$settings[] = array(
				'name'     => __( 'Licence key for New Zealand', 'text-domain' ),
				'desc_tip' => __( 'The key shown in the Addressfinder portal', 'text-domain' ),
				'id'       => 'af-key',
				'type'     => 'text',
				'desc'     => __( 'Find your Addressfinder Key from <a href="https://portal.addressfinder.net" target="_blank">Addressfinder Portal</a>', 'text-domain' ),
			);

			$settings[] = array(
				'name'     => __( 'Licence key for Australia', 'text-domain' ),
				'desc_tip' => __( 'The key shown in the Addressfinder Australian portal', 'text-domain' ),
				'id'       => 'af-key-au',
				'type'     => 'text',
				'desc'     => __( 'Find your Addressfinder Key from <a href="https://portal.addressfinder.net" target="_blank">Addressfinder Portal</a>', 'text-domain' ),
			);
		} elseif ( $af_key_au ) {
			$settings[] = array(
				'name'     => __( 'Licence key', 'text-domain' ),
				'desc_tip' => __( 'The key shown in the Addressfinder Australian portal', 'text-domain' ),
				'id'       => 'af-key-au',
				'type'     => 'text',
				'desc'     => __( 'Find your Addressfinder Key from <a href="https://portal.addressfinder.net" target="_blank">Addressfinder Portal</a>', 'text-domain' ),
			);
		} else {
			$settings[] = array(
				'name'     => __( 'Licence key', 'text-domain' ),
				'desc_tip' => __( 'The key shown in the Addressfinder portal', 'text-domain' ),
				'id'       => 'af-key',
				'type'     => 'text',
				'desc'     => __( 'Find your Addressfinder Key from <a href="https://portal.addressfinder.net" target="_blank">Addressfinder Portal</a>', 'text-domain' ),
			);
		}

		$settings[] = array(
			'name'        => __( 'Widget Options', 'text-domain' ),
			'id'          => 'af-widget-options',
			'type'        => 'textarea',
			'placeholder' => __( 'Eg: {&quot;byline&quot;: true}', 'text-domain' ),
			'desc'        => __( '<p>Additional options that allow you to adjust the default behaviour of the widget. These options should be in the form of a JSON string with proper quoting of keys. </p><p>This section may be left blank for default behaviour.</p><p>For a full list of possible options <a href="https://addressfinder.nz/docs/widget_docs/">see our Widget documentation</a></p>', 'text-domain' ),
		);

		$settings[] = array(
			'name' => __( 'Debug Mode', 'text-domain' ),
			'id'   => 'af-debug',
			'type' => 'checkbox',
			'desc' => __( 'Show error messages when expected fields are missing', 'text-domain' ),
		);

		$settings[] = array(
			'name'    => __( 'Default Country', 'text-domain' ),
			'desc'    => __( 'If your checkout page does not have a country select field, addresses from this country will be displayed', 'text-domain' ),
			'id'      => 'af-default-country',
			'default' => 'AU',
			'type'    => 'select',
			'options' => array(
				'au' => __( 'Australia', 'text-domain' ),
				'nz' => __( 'New Zealand', 'text-domain' ),
			),
		);

		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'addressfinder-widget',
		);

		return $settings;
	}

	// Process save the settings
	function action_woocommerce_settings_save_addressfinder_settings() {
    global $current_section;

    $tab_id = 'addressfinder-settings';

    // Call settings function
    $settings = addressfinder_settings();

    WC_Admin_Settings::save_fields( $settings );

    if ( $current_section ) {
	    do_action( 'woocommerce_update_options_' . $tab_id . '_' . $current_section );
    }
	}
	add_action( 'woocommerce_settings_save_addressfinder-settings', 'action_woocommerce_settings_save_addressfinder_settings', 10 );


	/**
	 * Load Addressfinder styles
	 */
	function addressfinder_add_styles() {
		$plugin_url = plugin_dir_url( __FILE__ );
		wp_enqueue_style( 'addressfinder-woocommerce', $plugin_url . 'addressfinder-styles.css', array(), ADDRESSFINDER_WOOCOMMERCE_VERSION );
	}
}
