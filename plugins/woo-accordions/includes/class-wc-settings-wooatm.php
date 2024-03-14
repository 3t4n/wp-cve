<?php
/**
 * WooCommerce Accordions
 *
 * @author 		Magnigenie
 * @category 	Admin
 * @version   1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (  class_exists( 'WC_Settings_Page' ) ) :

/**
 * WC_Settings_Accounts
 */
class WC_Settings_Woo_Accordions extends WC_Settings_Page {

	/**
	* Constructor.
	*/
	public function __construct() {
		$this->id    = 'wooatm';
		$this->label = __( 'Accordions', 'wooatm' );
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

		return apply_filters( 'woocommerce_' . $this->id . '_settings', 
			array(
				array(	
					'title' 			=> __( 'WooCommerce Accordions & Tabs Manager Settings', 'wooatm' ), 
					'type' 				=> 'title',
					'desc' 				=> '', 
					'id' 					=> 'wooatm_title' 
				),
        array(
					'title' 			=> __( 'Enable', 'wooatm' ),
					'desc' 				=> __( 'Enable Accordions.', 'wooatm' ),
					'type' 				=> 'checkbox',
					'id'					=> 'wooatm[enabled]',
					'default' 		=> 'no'											
				),
				array(
					'title' 			=> __( 'Keep the first accordion expanded', 'wooatm' ),
					'desc' 				=> __( 'Keep the first accordion expanded on page load', 'wooatm' ),
					'type' 				=> 'checkbox',
					'id'					=> 'wooatm[first_child_expand]',
					'default' 		=> 'no'											
				),
				array(
					'title' 			=> __( 'Enable multiple expand', 'wooatm' ),
					'desc' 				=> __( 'This option allows you to expand multiple accordions when you click on the title', 'wooatm' ),
					'type' 				=> 'checkbox',
					'id'					=> 'wooatm[multiple_expand]',
					'default' 		=> 'no'											
				),
				array(
					'title' 	  	=> __( 'Accordion background color', 'wooatm' ),
					'id' 		  		=> 'wooatm[bg]',
					'type' 		  	=> 'color',
					'default'	  	=> '#666666',
					'css' 		  	=> 'width: 125px;',
					'desc_tip'	  =>  true
				),
				array(
					'title' 	  	=> __( 'Accordion active background', 'wooatm' ),
					'id' 		  		=> 'wooatm[active_bg]',
					'type' 		  	=> 'color',
					'default'	  	=> '#000000',
					'css' 		  	=> 'width: 125px;',
					'desc_tip'	  =>  true
				),
				array(
					'title' 	  	=> __( 'Accordion title color', 'wooatm' ),
					'id' 		  		=> 'wooatm[title_color]',
					'type' 		  	=> 'color',
					'default'	  	=> '#FFFFFF',
					'css' 		  	=> 'width: 125px;',
					'desc_tip'	  =>  true
				),
				array(
					'title' 	  	=> __( 'Accordion active title color', 'wooatm' ),
					'id' 		  		=> 'wooatm[title_active]',
					'type' 		  	=> 'color',
					'default'	  	=> '#FFFFFF',
					'css' 		  	=> 'width: 125px;',
					'desc_tip'	  =>  true
				),
				array(
					'title' 	  	=> __( 'Accordion arrow color', 'wooatm' ),
					'id' 		  		=> 'wooatm[arrow_color]',
					'type' 		  	=> 'color',
					'default'	  	=> '#FFFFFF',
					'css' 		  	=> 'width: 125px;',
					'desc_tip'	  =>  true
				),
				array(
					'title' 	  	=> __( 'Accordion active arrow color', 'wooatm' ),
					'id' 		  		=> 'wooatm[arrow_active]',
					'type' 		  	=> 'color',
					'default'	  	=> '#FFFFFF',
					'css' 		  	=> 'width: 125px;',
					'desc_tip'	  =>  true
				),
				array( 'type' => 'sectionend', 'id' => 'simple_wooatm_options'),

		)); // End pages settings
	}
}
return new WC_Settings_Woo_Accordions();

endif;