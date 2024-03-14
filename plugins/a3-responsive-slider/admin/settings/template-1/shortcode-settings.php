<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\RSlider\FrameWork\Settings {

use A3Rev\RSlider\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Slider Template 1 Shortcode Settings

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

class Template_1_Shortcode extends FrameWork\Admin_UI
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
	public $option_name = 'a3_rslider_template1_shortcode_settings';
	
	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'a3_rslider_template1_shortcode_settings';
	
	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 9;
	
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
				'success_message'	=> __( 'Shortcode Settings successfully saved.', 'a3-responsive-slider' ),
				'error_message'		=> __( 'Error: Shortcode Settings can not save.', 'a3-responsive-slider' ),
				'reset_message'		=> __( 'Shortcode Settings successfully reseted.', 'a3-responsive-slider' ),
			);
									
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
			'name'				=> 'shortcode',
			'label'				=> __( 'Shortcode', 'a3-responsive-slider' ),
			'callback_function'	=> 'a3_responsive_sider_template_1_shortcode_settings_form',
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
				'name'		=> __( 'Slider Description Text', 'a3-responsive-slider' ),
				'desc'		=> __( "The a3 Responsive Slider shortcode embed tool on all post types and pages allows you to add a custom slider description that shows below the slider. The settings below enable you to style the description text and it's container to match this skin.", 'a3-responsive-slider' ),
                'type' 		=> 'heading',
           	),
			array(  
				'name' 		=> __( 'Description Text Align', 'a3-responsive-slider' ),
				'id' 		=> 'shortcode_description_position',
				'type' 		=> 'select',
				'default'	=> 'center',
				'options'	=> array(
					'top-left'		=> __( 'Left', 'a3-responsive-slider' ),
					'top-right'		=> __( 'Center', 'a3-responsive-slider' ),
					'bottom-right'	=> __( 'Right', 'a3-responsive-slider' ),
				),
				'css' 		=> 'width:160px;',
			),
			array(  
				'name' 		=> __( 'Description Font', 'a3-responsive-slider' ),
				'id' 		=> 'shortcode_description_font',
				'type' 		=> 'typography',
				'default'	=> array( 'size' => '14px', 'line_height' => '1.4em', 'face' => 'Arial, sans-serif', 'style' => 'normal', 'color' => '#000000' )
			),
			
			array(
				'name'		=> __( 'Description Container', 'a3-responsive-slider' ),
                'type' 		=> 'heading',
           	),
			
			array(  
				'name' 		=> __( 'Description Container Padding', 'a3-responsive-slider' ),
				'id' 		=> 'shortcode_description_padding',
				'type' 		=> 'array_textfields',
				'ids'		=> array( 
	 								array( 
											'id' 		=> 'shortcode_description_padding_top',
	 										'name' 		=> __( 'Top', 'a3-responsive-slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 10 ),
	 
	 								array(  'id' 		=> 'shortcode_description_padding_bottom',
	 										'name' 		=> __( 'Bottom', 'a3-responsive-slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 10 ),
											
									array( 
											'id' 		=> 'shortcode_description_padding_left',
	 										'name' 		=> __( 'Left', 'a3-responsive-slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 0 ),
											
									array( 
											'id' 		=> 'shortcode_description_padding_right',
	 										'name' 		=> __( 'Right', 'a3-responsive-slider' ),
	 										'css'		=> 'width:40px;',
	 										'default'	=> 0 ),
	 							)
			),
			array(  
				'name' 		=> __( 'Description Container Background Colour', 'a3-responsive-slider' ),
				'desc' 		=> __( 'Default', 'a3-responsive-slider' ) . ' [default_value]',
				'id' 		=> 'shortcode_description_background_colour',
				'type' 		=> 'color',
				'default'	=> '#000000'
			),
			array(  
				'name' 		=> __( 'Description Container Background Transparency', 'a3-responsive-slider' ),
				'desc'		=> __( 'Scale - 0 = 100% transparent - 100 = 100% Solid Colour.', 'a3-responsive-slider' ),
				'id' 		=> 'shortcode_description_background_transparency',
				'type' 		=> 'slider',
				'default'	=> 60,
				'min'		=> 0,
				'max'		=> 100,
				'increment'	=> 10,
			),
			array(  
				'name' 		=> __( 'Description Container Border', 'a3-responsive-slider' ),
				'id' 		=> 'shortcode_description_border',
				'type' 		=> 'border',
				'default'	=> array( 'width' => '0px', 'style' => 'solid', 'color' => '#000000', 'corner' => 'rounded' , 'rounded_value' =>4 )
			),
			array(  
				'name' 		=> __( 'Description Container Shadow', 'a3-responsive-slider' ),
				'id' 		=> 'shortcode_description_shadow',
				'type' 		=> 'box_shadow',
				'default'	=> array( 'enable' => 0, 'h_shadow' => '5px' , 'v_shadow' => '5px', 'blur' => '2px' , 'spread' => '2px', 'color' => '#DBDBDB', 'inset' => '' )
			),
			
        ));
	}
}

}

// global code
namespace {

/** 
 * a3_responsive_sider_template_1_shortcode_settings_form()
 * Define the callback function to show subtab content
 */
function a3_responsive_sider_template_1_shortcode_settings_form() {
	global $a3_responsive_sider_template_1_shortcode_settings;
	$a3_responsive_sider_template_1_shortcode_settings->settings_form();
}

}
