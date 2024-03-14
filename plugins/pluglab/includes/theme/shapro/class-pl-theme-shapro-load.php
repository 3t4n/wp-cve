<?php

class PL_Theme_Shapro_Load extends PL_Theme_Shapro_Layout {

	public function __construct() {
		/**
		 * HomeTemplate scripts
		 */
		add_action( 'init', array( $this, 'load' ) );
	}

	public function load() {
		/**
		 * Include helper functions
		 */

		require_once PL_PLUGIN_INC . 'customizer/pl-help-functions.php';
		/**
		 * default contents
		 */
		require_once PL_PLUGIN_INC . 'theme/shapro/pl-default-functions.php';

		/**
		 * Initialize Customizer settings
		 */
		new PL_Theme_Shapro_Customizer_Config();
		/**
		 * Initialize Customizer settings
		 */
		new PL_Theme_Shapro_Customizer();
		/**
		 * LayOut
		 */
		add_action( 'shapro_header_layouts', array( $this, 'top_header' ), 1 );
		add_action( 'shapro_hometemplate_layouts', array( $this, 'slider' ), 1 );
		add_action( 'shapro_hometemplate_layouts', array( $this, 'callout' ), 2 );
		add_action( 'shapro_hometemplate_layouts', array( $this, 'service' ), 3 );
		add_action( 'shapro_hometemplate_layouts', array( $this, 'cta' ), 4 );
		add_action( 'shapro_hometemplate_layouts', array( $this, 'testimonial' ), 5 );
		add_action( 'shapro_hometemplate_layouts', array( $this, 'blog' ), 6 );
		add_action( 'shapro_contact_us', array( $this, 'contactus_template' ), 12 );
	}

}
