<?php
/*
Widget Name: Render Layout
Description: Render a reusable layout.
Author: Echelon
Author URI: http://echelonso.com
Documentation: https://echelonso.com/documentation/creating-a-reusable-siteorigin-layout/
*/

class EchelonSOEsoRenderLayout extends SiteOrigin_Widget {
	
	function __construct() {
		
		global $esorl;
		
		parent::__construct(
			'echelonso-eso-render-layout',
			__('Render Layout', $esorl->plugin_text_domain()),
			array(
				'description' => __('Display the content from a reusable layout.', $esorl->plugin_text_domain() ),
				'help' => 'https://echelonso.com/documentation/creating-a-reusable-siteorigin-layout/',
			),
			array(
				
			),
			array(
				'option' => array(
					'type' => 'section',
					'label' => __( 'Options' , $esorl->plugin_text_domain() ),
					'hide' => true,
					'fields' => array(
						'layout' => array(
							'type' => 'select',
							'label' => __( 'Layout', $esorl->plugin_text_domain() ),
							'description' => __( 'Select which layout you would like to display.' , $esorl->plugin_text_domain() ),
							'default' => '0',
							'options' => $esorl->get_layout_select_options()
						)
					)
				)
			),
			plugin_dir_path(__FILE__)
		);
	}
	
	function get_template_name($instance) {
		return 'tpl';
	}
	
	function get_style_name($instance) {
		return false;
	}
	
}

siteorigin_widget_register('echelonso-eso-render-layout', __FILE__, 'EchelonSOEsoRenderLayout');
