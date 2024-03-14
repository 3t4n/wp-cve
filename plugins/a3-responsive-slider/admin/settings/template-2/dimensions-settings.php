<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\RSlider\FrameWork\Settings {

use A3Rev\RSlider\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Slider Template 2 Dimensions Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class Template_2_Dimensions extends FrameWork\Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'a3-rslider-template-2';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'a3_rslider_template2_dimensions_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'a3_rslider_template2_dimensions_settings';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 2;
	
	/**
	 * @var array
	 */
	public $form_fields = array();
	
	/**
	 * @var array
	 */
	public $form_messages = array();
	
	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init_form_fields' ), 1 );
		$this->subtab_init();
		
		$this->form_messages = array(
				'success_message'	=> __( 'Dimensions Settings successfully saved.', 'a3-responsive-slider' ),
				'error_message'		=> __( 'Error: Dimensions Settings can not save.', 'a3-responsive-slider' ),
				'reset_message'		=> __( 'Dimensions Settings successfully reseted.', 'a3-responsive-slider' ),
			);
		
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
			
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
				
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_init' , array( $this, 'reset_default_settings' ) );
				
		//add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
		
		add_action( $this->plugin_name . '-'. $this->form_key.'_settings_start', array( $this, 'pro_fields_before' ) );
		add_action( $this->plugin_name . '-'. $this->form_key.'_settings_end', array( $this, 'pro_fields_after' ) );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {
		
		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
				
		$GLOBALS[$this->plugin_prefix.'admin_interface']->reset_settings( $this->form_fields, $this->option_name, false );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* reset_default_settings()
	/* Reset default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function reset_default_settings() {
				
		$GLOBALS[$this->plugin_prefix.'admin_interface']->reset_settings( $this->form_fields, $this->option_name, true, true );
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
				
		$GLOBALS[$this->plugin_prefix.'admin_interface']->get_settings( $this->form_fields, $this->option_name );
	}
	
	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array ( 
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {
		
		$subtab_data = array( 
			'name'				=> 'dimensions',
			'label'				=> __( 'Dimensions', 'a3-responsive-slider' ),
			'callback_function'	=> 'a3_responsive_sider_template_2_dimensions_settings_form',
		);
		
		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {
	
		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();
		
		return $subtabs_array;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
				
		$output = '';
		$output .= $GLOBALS[$this->plugin_prefix.'admin_interface']->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );
		
		return $output;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {
		
  		// Define settings			
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
		
			array(
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Slider Type', 'a3-responsive-slider' ),
				'id' 		=> 'is_slider_responsive',
				'class'		=> 'is_slider_responsive',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 1,
				'checked_value'		=> 1,
				'unchecked_value'	=> 0,
				'checked_label'		=> __( 'Responsive', 'a3-responsive-slider' ),
				'unchecked_label' 	=> __( 'Fixed Wide', 'a3-responsive-slider' ),	
			),
			
			array(
                'type' 		=> 'heading',
				'class'		=> 'is_slider_responsive_off',
           	),
			array(  
				'name' 		=> __( 'Slider Size', 'a3-responsive-slider' ),
				'id' 		=> 'slider_size',
				'type' 		=> 'array_textfields',
				'ids'		=> array( 
	 								array( 
											'id' 		=> 'slider_width',
	 										'name' 		=> __( 'Width', 'a3-responsive-slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 300 ),
	 
	 								array(  'id' 		=> 'slider_height',
	 										'name' 		=> __( 'Height', 'a3-responsive-slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 250 ),
	 							)
			),
			
			array(
                'type' 		=> 'heading',
				'class'		=> 'is_slider_responsive_on',
           	),
			array(  
				'name' 		=> __( 'Wide', 'a3-responsive-slider' ),
				'desc'		=> '%',
				'id' 		=> 'slider_wide_responsive',
				'type' 		=> 'slider',
				'default'	=> 100,
				'min'		=> 25,
				'max'		=> 100,
				'increment'	=> 1,
			),
			array(  
				'name' 		=> __( 'Tall Type', 'a3-responsive-slider' ),
				'id' 		=> 'is_slider_tall_dynamic',
				'class'		=> 'is_slider_tall_dynamic',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 0,
				'checked_value'		=> 0,
				'unchecked_value'	=> 1,
				'checked_label'		=> __( 'Fixed', 'a3-responsive-slider' ),
				'unchecked_label' 	=> __( 'Dynamic', 'a3-responsive-slider' ),	
			),
			
			array(
                'type' 		=> 'heading',
				'class'		=> 'is_slider_tall_dynamic_off',
           	),
			array(  
				'name' 		=> __( 'Height', 'a3-responsive-slider' ),
				'desc'		=> 'px',
				'id' 		=> 'slider_height_fixed',
				'type' 		=> 'text',
				'default'	=> 250,
				'css'		=> 'width:40px;',
			),
			
        ));
	}
	
	public function include_script() {
	?>
<script>
(function($) {
$(document).ready(function() {
	if ( $("input.is_slider_tall_dynamic:checked").val() == '0') {
		$(".is_slider_tall_dynamic_off").show();
	} else {
		$(".is_slider_tall_dynamic_off").hide();
	}
	
	if ( $("input.is_slider_responsive:checked").val() == '1') {
		$(".is_slider_responsive_on").css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		$(".is_slider_responsive_off").hide();
	} else {
		$(".is_slider_responsive_on").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
		$(".is_slider_responsive_off").show();
		$(".is_slider_tall_dynamic_off").hide();
	}
	
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.is_slider_responsive', function( event, value, status ) {
		$(".is_slider_responsive_on").hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		if ( status == 'true' ) {
			$(".is_slider_responsive_on").slideDown();
			$(".is_slider_responsive_off").slideUp();
			if ( $("input.is_slider_tall_dynamic:checked").val() == '0') {
				$(".is_slider_tall_dynamic_off").slideDown();
			}
		} else {
			$(".is_slider_responsive_on").slideUp();
			$(".is_slider_responsive_off").slideDown();
			$(".is_slider_tall_dynamic_off").slideUp();
		}
	});
	
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.is_slider_tall_dynamic', function( event, value, status ) {
		if ( status == 'true' ) {
			$(".is_slider_tall_dynamic_off").slideDown();
		} else {
			$(".is_slider_tall_dynamic_off").slideUp();
		}
	});
	
});
})(jQuery);
</script>
    <?php	
	}
}

}

// global code
namespace {

/** 
 * a3_responsive_sider_template_2_dimensions_settings_form()
 * Define the callback function to show subtab content
 */
function a3_responsive_sider_template_2_dimensions_settings_form() {
	global $a3_responsive_sider_template_2_dimensions_settings;
	$a3_responsive_sider_template_2_dimensions_settings->settings_form();
}

}
