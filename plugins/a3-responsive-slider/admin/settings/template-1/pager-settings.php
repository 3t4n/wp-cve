<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\RSlider\FrameWork\Settings {

use A3Rev\RSlider\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Slider Template 1 Pager Settings

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

class Template_1_Pager extends FrameWork\Admin_UI
{
	
	/**
	 * @var string
	 */
	private $parent_tab = 'a3-rslider-template-1';
	
	/**
	 * @var array
	 */
	private $subtab_data;
	
	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'a3_rslider_template1_pager_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'a3_rslider_template1_pager_settings';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 5;
	
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
				'success_message'	=> __( 'Pager Settings successfully saved.', 'a3-responsive-slider' ),
				'error_message'		=> __( 'Error: Pager Settings can not save.', 'a3-responsive-slider' ),
				'reset_message'		=> __( 'Pager Settings successfully reseted.', 'a3-responsive-slider' ),
			);
					
		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );
		
		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );
				
		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );
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
			'name'				=> 'pager',
			'label'				=> __( 'Pager', 'a3-responsive-slider' ),
			'callback_function'	=> 'a3_responsive_sider_template_1_pager_settings_form',
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
				'name'		=> __( 'Pager Settings', 'a3-responsive-slider' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Slider Pager', 'a3-responsive-slider' ),
				'id' 		=> 'enable_slider_pager',
				'class'		=> 'enable_slider_pager',
				'type' 		=> 'onoff_checkbox',
				'default'	=> 1,
				'checked_value'		=> 1,
				'unchecked_value' 	=> 0,
				'checked_label'		=> __( 'ON', 'a3-responsive-slider' ),
				'unchecked_label' 	=> __( 'OFF', 'a3-responsive-slider' ),
			),
			
			array(
                'type' 		=> 'heading',
				'class'		=> 'slider_pager_container'
           	),
			array(  
				'name' 		=> __( 'Pager Transition', 'a3-responsive-slider' ),
				'id' 		=> 'slider_pager_transition',
				'type' 		=> 'onoff_radio',
				'class'		=> 'slider_pager_transition',
				'default' 	=> 'hover',
				'onoff_options' => array(
					array(
						'val' 				=> 'alway',
						'text' 				=> __( 'Alway show when slider loaded', 'a3-responsive-slider' ),
						'checked_label'		=> __( 'ON', 'a3-responsive-slider' ) ,
						'unchecked_label' 	=> __( 'OFF', 'a3-responsive-slider' ) ,
					),
					array(
						'val' 				=> 'hover',
						'text' 				=> __( 'Show when hover on slider container', 'a3-responsive-slider' ),
						'checked_label'		=> __( 'ON', 'a3-responsive-slider' ) ,
						'unchecked_label' 	=> __( 'OFF', 'a3-responsive-slider' ) ,
					),
				),			
			),
			array(  
				'name' 		=> __( 'Pager Direction', 'a3-responsive-slider' ),
				'id' 		=> 'slider_pager_direction',
				'class'		=> 'slider_pager_direction',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'horizontal',
				'checked_value'		=> 'horizontal',
				'unchecked_value'	=> 'vertical',
				'checked_label'		=> __( 'Horizontal', 'a3-responsive-slider' ),
				'unchecked_label' 	=> __( 'Vertical', 'a3-responsive-slider' ),	
			),
			array(  
				'name' 		=> __( 'Pager Position', 'a3-responsive-slider' ),
				'id' 		=> 'slider_pager_position',
				'type' 		=> 'select',
				'default'	=> 'bottom-right',
				'options'	=> array(
					'top-left'		=> __( 'Top Left', 'a3-responsive-slider' ),
					'top-center'	=> __( 'Top Center', 'a3-responsive-slider' ),
					'top-right'		=> __( 'Top Right', 'a3-responsive-slider' ),
					'bottom-left'	=> __( 'Bottom Left', 'a3-responsive-slider' ),
					'bottom-center'	=> __( 'Bottom Center', 'a3-responsive-slider' ),
					'bottom-right'	=> __( 'Bottom Right', 'a3-responsive-slider' ),
				),
				'css' 		=> 'width:160px;',
			),
			array(  
				'name' 		=> __( 'Pager Container Background Colour', 'a3-responsive-slider' ),
				'desc' 		=> __( 'Default', 'a3-responsive-slider' ) . ' [default_value]',
				'id' 		=> 'pager_background_colour',
				'type' 		=> 'color',
				'default'	=> '#000000'
			),
			array(  
				'name' 		=> __( 'Pager Container Background Transparency', 'a3-responsive-slider' ),
				'desc'		=> __( 'Scale - 0 = 100% transparent - 100 = 100% Solid Colour.', 'a3-responsive-slider' ),
				'id' 		=> 'pager_background_transparency',
				'type' 		=> 'slider',
				'default'	=> 60,
				'min'		=> 0,
				'max'		=> 100,
				'increment'	=> 10,
			),
			array(  
				'name' 		=> __( 'Pager Container Border', 'a3-responsive-slider' ),
				'id' 		=> 'pager_border',
				'type' 		=> 'border',
				'default'	=> array( 'width' => '0px', 'style' => 'solid', 'color' => '#000000', 'corner' => 'rounded' , 'rounded_value' =>4 )
			),
			array(  
				'name' 		=> __( 'Pager Container Shadow', 'a3-responsive-slider' ),
				'id' 		=> 'pager_shadow',
				'type' 		=> 'box_shadow',
				'default'	=> array( 'enable' => 0, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#DBDBDB', 'inset' => '' )
			),
			
			array(  
				'name' 		=> __( 'Pager Bullet Background Colour', 'a3-responsive-slider' ),
				'desc' 		=> __( 'Default', 'a3-responsive-slider' ) . ' [default_value]',
				'id' 		=> 'pager_item_background_colour',
				'type' 		=> 'color',
				'default'	=> '#000000'
			),
			array(  
				'name' 		=> __( 'Pager Bullet Border', 'a3-responsive-slider' ),
				'id' 		=> 'pager_item_border',
				'type' 		=> 'border',
				'default'	=> array( 'width' => '1px', 'style' => 'solid', 'color' => '#FFFFFF', 'corner' => 'rounded' , 'rounded_value' => 10 )
			),
			array(  
				'name' 		=> __( 'Pager Bullet Shadow', 'a3-responsive-slider' ),
				'id' 		=> 'pager_item_shadow',
				'type' 		=> 'box_shadow',
				'default'	=> array( 'enable' => 0, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#DBDBDB', 'inset' => '' )
			),
			
			array(  
				'name' 		=> __( 'Pager Viewing Bullet Background Colour', 'a3-responsive-slider' ),
				'desc' 		=> __( 'Default', 'a3-responsive-slider' ) . ' [default_value]',
				'id' 		=> 'pager_activate_item_background_colour',
				'type' 		=> 'color',
				'default'	=> '#FFFFFF'
			),
			array(  
				'name' 		=> __( 'Pager Viewing Bullet Border', 'a3-responsive-slider' ),
				'id' 		=> 'pager_activate_item_border',
				'type' 		=> 'border',
				'default'	=> array( 'width' => '1px', 'style' => 'solid', 'color' => '#FFFFFF', 'corner' => 'rounded' , 'rounded_value' => 10 )
			),
			array(  
				'name' 		=> __( 'Pager Viewing Bullet Shadow', 'a3-responsive-slider' ),
				'id' 		=> 'pager_activate_item_shadow',
				'type' 		=> 'box_shadow',
				'default'	=> array( 'enable' => 0, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#DBDBDB', 'inset' => '' )
			),
			
        ));
	}
	
	public function include_script() {
	?>
<script>
(function($) {
$(document).ready(function() {
	
	if ( $("input.enable_slider_pager:checked").val() == '1') {
		$(".slider_pager_container").css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
	} else {
		$(".slider_pager_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
	}
	
	$(document).on( "a3rev-ui-onoff_checkbox-switch", '.enable_slider_pager', function( event, value, status ) {
		$(".slider_pager_container").hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		if ( status == 'true' ) {
			$(".slider_pager_container").slideDown();
		} else {
			$(".slider_pager_container").slideUp();
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
 * a3_responsive_sider_template_1_pager_settings_form()
 * Define the callback function to show subtab content
 */
function a3_responsive_sider_template_1_pager_settings_form() {
	global $a3_responsive_sider_template_1_pager_settings;
	$a3_responsive_sider_template_1_pager_settings->settings_form();
}

}
