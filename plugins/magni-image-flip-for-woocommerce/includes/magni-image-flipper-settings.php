<?php
/**
 * WooCommerce Magni Image Flipper
 *
 * @author 		Magnigenie
 * @category 	Admin
 * @version     1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (  class_exists( 'WC_Settings_Page' ) ) :

/**
 * WC_Settings_Accounts
 */
class WC_Settings_Woo_Magni_Image extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'woomi';
		$this->label = __( 'Image Flipper', 'woomi' );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}
	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		return apply_filters( 'woocommerce_' . $this->id . '_settings', array(

			array(	'title' => __( 'Magni Image Flip Settings for WooCommerce', 'woomi' ), 'type' => 'title','desc' => '', 'id' => 'woomi_title' ),
                 array(
					'title' 	  => __( 'Enable', 'woomi' ),
					'desc' 		  => __( 'Enable Image Flipper', 'woomi' ),
					'type' 		  => 'checkbox',
					'id'		  	=> 'woomi[enabled]',
					'default' 	=> 'no'
				),
				array(
					'title' 	  => __( 'Select Image Effect', 'woomi' ),
					'desc' 		  => __( 'Choose the effect for the products images', 'woomi' ),
					'id' 		  	=> 'woomi[imgeffect]',
					'type' 		  => 'select',
					'options'	  => array( 'flip' => 'Flip', 'fade' => 'Fade', 'slider' => 'Slider' ),
					'default' 	=> 'flip',
					'css' 		  => 'width: 150px;'
				),
				array(
					'title' 	  => __( 'Turn Off Pager', 'woomi' ),
					'desc' 		  => __( 'Check to hide pager on images', 'woomi' ),
					'id' 		  	=> 'woomi[dots]',
					'type' 		  => 'checkbox',
					'default'	  => 'no'
				),
				array(
					'title' 	  => __( 'Active Image Pager Color', 'woomi' ),
					'desc' 		  => __( 'Choose pager color of active image', 'woomi' ),
					'id' 		  	=> 'woomi[activedotcolor]',
					'type' 		  => 'color',
					'default'	  => '#100e0e',
					'css' 		  => 'width: 125px;'
				),
				array(
					'title' 	  => __( 'Inactive Image Pager Color', 'woomi' ),
					'desc' 		  => __( 'Choose pager color of inactive image', 'woomi' ),
					'id' 		  	=> 'woomi[inactivedotcolor]',
					'type' 		  => 'color',
					'default'	  => '#b2b2ad',
					'css' 		  => 'width: 125px;'
				),
				array(
					'title' 	  => __( 'Select Pager Position', 'woomi' ),
					'desc' 		  => __( 'Choose the position of pager on image', 'woomi' ),
					'id' 		  	=> 'woomi[dotposition]',
					'type' 		  => 'select',
					'options'	  => array( 'topleft' => 'Top Left', 'topright' => 'Top Right', 'bottomleft' => 'Bottom Left', 'bottomright' => 'Bottom Right' ),
					'default' 	=> 'topleft',
					'css' 		  => 'width: 150px;'
				),
				array(
					'title' 	  => __( 'Image Transition Speed', 'woomi' ),
					'desc' 		  => __( 'Give timeout speed for transition', 'woomi' ),
					'id' 		  	=> 'woomi[speed]',
					'type' 		  => 'number',
					'default'	  => '200',
					'css' 		  => 'width: 150px;'
				),
				array( 'type' => 'sectionend', 'id' => 'simple_woomi_options'),

		)); // End page settings
	}
}
return new WC_Settings_Woo_Magni_Image();

endif;