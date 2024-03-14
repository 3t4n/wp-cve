<?php
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
/**
 * Create the section beneath the products tab
 **/
add_filter( 'woocommerce_get_sections_products', 'outofstock_add_section' );

function outofstock_add_section( $sections ) {
	
	$sections['outofstock'] = __( 'Out of Stock', 'woo-outofstock' );
	return $sections;
	
}

/**
 * Add settings to the specific section we created before
 */
add_filter( 'woocommerce_get_settings_products', 'outofstock_all_settings', 10, 2 );

function outofstock_all_settings( $settings, $current_section ) {
	/**
	 * Check the current section is what we want
	 **/
	if ( $current_section == 'outofstock' ) {
		$settings_outofstock = array();
		// Add Title to the Settings
		$settings_outofstock[] = array( 'name' => __( 'Woo Out of Stock Settings', 'woo-outofstock' ), 'type' => 'title', 'desc' => __( 'The following options are used to configure Woo Out of Stock Products', 'woo-outofstock' ), 'id' => 'outofstock' );
		// Add first checkbox option
		/*$settings_outofstock[] = array(
			'name'     => __( 'Disable Overlay', 'woo-outofstock' ),
			'desc_tip' => __( '', 'woo-outofstock' ),
			'id'       => 'disable_outofstock_overlay',
			'type'     => 'checkbox',
			//'css'      => 'min-width:300px;',
			'desc'     => __( '<small>&nbsp;Check this to <b>DISABLE</b> the out of stock overlay</small>', 'woo-outofstock' )
		);*/
		// Add second text field option
		
		$settings_outofstock[] = array(
			'name'     => __( 'Overlay Image URL', 'woo-outofstock' ),
			'desc_tip' => __( 'This will be the URL of the image you are using for the Out of Stock overlay. Make sure it is a <b>PNG</b>', 'woo-outofstock' ),
			'id'       => 'outofstock_image_url',
			'type'     => 'text',
			'desc'     => __( '&nbsp;Make sure your image is a <b>PNG!</b>', 'woo-outofstock' ),
			'class'    => 'overlay-input'
		);
		
		$settings_outofstock[] = array( 'type' => 'sectionend', 'id' => 'outofstock' );
		return $settings_outofstock;
	
	/**
	 * If not, return the standard settings
	 **/
	} else {
		return $settings;
	}
}

?>