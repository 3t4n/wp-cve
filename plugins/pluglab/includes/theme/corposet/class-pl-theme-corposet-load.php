<?php

class PL_Theme_Corposet_Load extends PL_Theme_Corposet_Layout {

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
		require_once PL_PLUGIN_INC . 'theme/corposet/pl-default-functions.php';

		/**
		 * Initialize Customizer settings
		 */
		new PL_Theme_Corposet_Customizer_Config();
		/**
		 * Initialize Customizer settings
		 */
		new PL_Theme_Corposet_Customizer();
		
		/**
		 * @todo seperate custom css
		 */
		wp_register_style('pluglab-separator', PL_PLUGIN_INC_URL . 'customizer/css/custom.css');
		wp_enqueue_style ( 'pluglab-separator');//customcss
		wp_register_style('pluglab-corposet-custom', PL_PLUGIN_INC_URL . 'theme/corposet/custom.js');
		wp_enqueue_style ( 'pluglab-corposet-custom');//customcss


		/**
		 * Corposet Custom Actions
		 */
		new PL_Theme_Corposet_Custom_Action();

		/**
		 * LayOut
		 */
		add_action( 'corposet_header_layouts', array( $this, 'top_header' ), 1 , 1 );
		add_action( 'corposet_hometemplate_layouts', array( $this, 'slider' ), 2 );
		add_action('corposet_hometemplate_layouts', [$this, 'callout'], 3);
		//add_action('corposet_hometemplate_layouts', [$this, 'about'], 4);
		add_action( 'corposet_hometemplate_layouts', array( $this, 'service' ), 5 );
		add_action( 'corposet_hometemplate_layouts', array( $this, 'portfolio' ), 6 );
		// add_action('corposet_hometemplate_layouts', [$this, 'cta'], 4);
		add_action( 'corposet_hometemplate_layouts', array( $this, 'testimonial' ), 7 );
		add_action( 'corposet_hometemplate_layouts', array( $this, 'blog' ), 8 );
		add_action( 'corposet_contact_us', array( $this, 'contactus_template' ), 12 );

		/**
         * Corposet Portfolio Template
         */
        add_action( 'corposet_portfolio_template', array( $this, 'portfolio_template' ), 12 );
	}

}
