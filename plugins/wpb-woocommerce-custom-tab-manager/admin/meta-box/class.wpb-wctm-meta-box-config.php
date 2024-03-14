<?php

/**
 * WPB Woocommerce Custom Tab Manager by WpBean
 */

if ( ! defined( 'ABSPATH' ) ) exit;


class wpb_wctm_meta_box_config {

	public function __construct(){
		add_action( 'init', array( $this, 'wpb_wctm_tab_meta' ) );
	}



	/**
	 * Tab Post Type Meta Boxes
	 */
	
	public function wpb_wctm_tab_meta (){
		$prefix = 'wpb_wctm_';

		$fields = array(
			array(
				'label'		=> __( 'Priority', 'wpb-woocommerce-custom-tab-manager' ),
				'desc'		=> __( 'Tab priority, The custom tabs created by this plugin will shown after the WooCommerce default tabs. Lower number of priority will show first.', 'wpb-woocommerce-custom-tab-manager' ),
				'id'		=> $prefix.'priority',
				'type'		=> 'slider',
				'min'		=> 1,
				'max'		=> 99,
				'step'		=> 1,
				'default'	=> 1,
			),
			array(
				'label'		=> __( 'Active Tab', 'wpb-woocommerce-custom-tab-manager' ),
				'desc'		=> __( 'Active this tab to show globally to all products', 'wpb-woocommerce-custom-tab-manager' ),
				'id'		=> $prefix.'active_tab',
				'type'		=> 'checkbox',
				'default'	=> 1,
			),	
		);

		$wpb_wctm_tab_meta = new WPB_Custom_Add_Meta_Box ( 'wpb_wctm_tab_meta', __( 'Tab Options', 'wpb-woocommerce-custom-tab-manager' ), $fields, 'wpb_wtm_tab', true );
	}

}

new wpb_wctm_meta_box_config();