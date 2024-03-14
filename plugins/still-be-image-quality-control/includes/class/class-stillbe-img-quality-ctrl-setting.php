<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




class StillBE_Image_Quality_Ctrl_Setting {


	const SETTING_GROUP = STILLBE_IQ_PREFIX. 'setting-group';
	const SETTING_NAME  = STILLBE_IQ_PREFIX. 'setting-option';

	public $plugin_name = null;
	public $description = null;

	protected $sizes        = array();
	protected $js_translate = array();

	protected $allowed_tags_for_note = array();

	protected $is_lossless_options = false;
	protected $is_near_lossless    = false;

	private $settings   = null;
	private $current    = null;
	private $_editor    = null;
	private $_default   = null;


	// Use Traits
	use StillBE_IQC_Setting_Main,
	    StillBE_IQC_Setting_Common_Methods,
	    StillBE_IQC_Setting_Section_General,
	    StillBE_IQC_Setting_Section_Test,
	    StillBE_IQC_Setting_Section_Advanced_Toggle,
	    StillBE_IQC_Setting_Section_Advanced_Others,
	    StillBE_IQC_Setting_Section_ReComp;


	// Constructer
	public function __construct() {

		// Initialize Settings
		$this->_init_settings();

		// Initialize Plugin Setting
		$this->_init_plugin();

		// Add General Section Settings
		$this->add_section_general();

		// Add Test Section Settings
		$this->add_section_test();

		// Add Advanced Section Settings (Toggle Options)
		$this->add_section_advanced_toggle();

		// Add Advanced Section Settings (Other Settings)
		$this->add_section_advanced_others();

		// Add Re-Compress Section Settings
		$this->add_section_recomp();

		// Plugin Information
		$this->plugin_name = esc_html__( 'Image Quality Control | Still BE', 'still-be-image-quality-control' );
		$this->description = esc_html__( 'Control the compression quality level of each image size individually to speed your site up display. It also contributes to improving CWV by automatically generating WebP.', 'still-be-image-quality-control' );

	}


}