<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXMTZC_Enqueue_Scripts_Frontend
{

	/*
	* MXMTZC_Enqueue_Scripts_Frontend
	*/
	public function __construct()
	{

	}

	/*
	* Registration of styles and scripts
	*/
	public static function mxmtzc_register()
	{

		// register scripts and styles
		add_action( 'wp_enqueue_scripts', array( 'MXMTZC_Enqueue_Scripts_Frontend', 'mxmtzc_enqueue' ) );

	}

		public static function mxmtzc_enqueue()
		{

			// wp_enqueue_style( 'mxmtzc_font_awesome', MXMTZC_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css' );
			
			wp_enqueue_style( 'mxmtzc_style', MXMTZC_PLUGIN_URL . 'includes/frontend/assets/css/style.css', array(), MXMTZC_PLUGIN_VERSION, 'all' );

			wp_enqueue_script( 'mxmtzc_script_frontend', MXMTZC_PLUGIN_URL . 'includes/frontend/assets/js/jquery.canvasClock.js', array( 'jquery' ), MXMTZC_PLUGIN_VERSION, false );
			
			wp_enqueue_script( 'mxmtzc_script', MXMTZC_PLUGIN_URL . 'includes/frontend/assets/js/script.js', array( 'jquery', 'mxmtzc_script_frontend' ), MXMTZC_PLUGIN_VERSION, false );
		
		}

}