<?php

class PL_Theme_Biznol_Load extends PL_Theme_Biznol_Layout {

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
		require_once PL_PLUGIN_INC . 'theme/biznol/pl-default-functions.php';

		/**
		 * Initialize Customizer settings
		 */
		new PL_Theme_Biznol_Customizer_Config();
		/**
		 * Initialize Customizer settings
		 */
		new PL_Theme_Biznol_Customizer();
		
		/**
		 * @todo seperate custom css
		 */
		wp_register_style('pluglab-separator', PL_PLUGIN_INC_URL . 'customizer/css/custom.css');
		wp_enqueue_style ( 'pluglab-separator');//customcss
		/**
		 * LayOut
		 */
		add_action( 'biznol_header_layouts', array( $this, 'top_header' ), 1 );
		add_action( 'biznol_hometemplate_layouts', array( $this, 'slider' ), 1 );
		// add_action('biznol_hometemplate_layouts', [$this, 'callout'], 2);
		add_action( 'biznol_hometemplate_layouts', array( $this, 'service' ), 3 );
		// add_action('biznol_hometemplate_layouts', [$this, 'cta'], 4);
		add_action( 'biznol_hometemplate_layouts', array( $this, 'testimonial' ), 5 );
		add_action( 'biznol_hometemplate_layouts', array( $this, 'blog' ), 6 );
	}

}
