<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */

namespace A3Rev\RSlider\FrameWork\Tabs {

use A3Rev\RSlider\FrameWork;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
Slider Template 2 Tab

TABLE OF CONTENTS

- var parent_page
- var position
- var tab_data

- __construct()
- tab_init()
- tab_data()
- add_tab()
- settings_include()
- tab_manager()

-----------------------------------------------------------------------------------*/

class Template_2 extends FrameWork\Admin_UI
{	
	/**
	 * @var string
	 */
	private $parent_page = 'a3-rslider-skins';
	
	/**
	 * @var string
	 * You can change the order show of this tab in list tabs
	 */
	private $position = 2;
	
	/**
	 * @var array
	 */
	private $tab_data;
	
	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		
		$this->settings_include();
		$this->tab_init();
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* tab_init() */
	/* Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function tab_init() {
		
		add_filter( $this->plugin_name . '-' . $this->parent_page . '_settings_tabs_array', array( $this, 'add_tab' ), $this->position );
		
	}
	
	/**
	 * tab_data()
	 * Get Tab Data
	 * =============================================
	 * array ( 
	 *		'name'				=> 'my_tab_name'				: (required) Enter your tab name that you want to set for this tab
	 *		'label'				=> 'My Tab Name' 				: (required) Enter the tab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this tab
	 * )
	 *
	 */
	public function tab_data() {
		
		$tab_data = array( 
			'name'				=> 'a3-rslider-template-2',
			'label'				=> \A3Rev\RSlider\Functions::get_slider_template( 'template-2' ),
			'callback_function'	=> 'a3_responsive_slider_template_2_tab_manager',
		);
		
		if ( $this->tab_data ) return $this->tab_data;
		return $this->tab_data = $tab_data;
		
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* add_tab() */
	/* Add tab to Admin Init and Parent Page
	/*-----------------------------------------------------------------------------------*/
	public function add_tab( $tabs_array ) {
			
		if ( ! is_array( $tabs_array ) ) $tabs_array = array();
		$tabs_array[] = $this->tab_data();
		
		return $tabs_array;
	}
	
	/*-----------------------------------------------------------------------------------*/
	/* panels_include() */
	/* Include form settings panels 
	/*-----------------------------------------------------------------------------------*/
	public function settings_include() {
		
		// Includes Settings file
		global $a3_responsive_sider_template_2_global_settings;
		$a3_responsive_sider_template_2_global_settings = new FrameWork\Settings\Template_2_Global();

		global $a3_responsive_sider_template_2_dimensions_settings;
		$a3_responsive_sider_template_2_dimensions_settings = new FrameWork\Settings\Template_2_Dimensions();

		global $a3_responsive_sider_template_2_slider_styles_settings;
		$a3_responsive_sider_template_2_slider_styles_settings = new FrameWork\Settings\Template_2_Slider_Styles();

		global $a3_responsive_sider_template_2_control_settings;
		$a3_responsive_sider_template_2_control_settings = new FrameWork\Settings\Template_2_Control();
		
		global $a3_responsive_sider_template_2_pager_settings;
		$a3_responsive_sider_template_2_pager_settings = new FrameWork\Settings\Template_2_Pager();

		global $a3_responsive_sider_template_2_title_settings;
		$a3_responsive_sider_template_2_title_settings = new FrameWork\Settings\Template_2_Title();

		global $a3_responsive_sider_template_2_caption_settings;
		$a3_responsive_sider_template_2_caption_settings = new FrameWork\Settings\Template_2_Caption();

		global $a3_responsive_sider_template_2_readmore_settings;
		$a3_responsive_sider_template_2_readmore_settings = new FrameWork\Settings\Template_2_ReadMore();

		global $a3_responsive_sider_template_2_shortcode_settings;
		$a3_responsive_sider_template_2_shortcode_settings = new FrameWork\Settings\Template_2_Shortcode();

	}
	
	/*-----------------------------------------------------------------------------------*/
	/* tab_manager() */
	/* Call tab layout from Admin Init 
	/*-----------------------------------------------------------------------------------*/
	public function tab_manager() {
		$this->plugin_extension_start();
		$GLOBALS[$this->plugin_prefix.'admin_init']->admin_settings_tab( $this->parent_page, $this->tab_data() );
		$this->plugin_extension_end();
	}
}

}

// global code
namespace {

/** 
 * people_contact_grid_view_tab_manager()
 * Define the callback function to show tab content
 */
function a3_responsive_slider_template_2_tab_manager() {
	global $a3_responsive_slider_template_2_tab;
	$a3_responsive_slider_template_2_tab->tab_manager();
}

}
