<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXMTZC_FrontEnd_Main
{

	/*
	* MXMTZC_FrontEnd_Main constructor
	*/
	public function __construct()
	{

	}

	/*
	* Additional classes
	*/
	public function mxmtzc_additional_classes()
	{

		// enqueue_scripts class
		mxmtzc_require_class_file_frontend( 'enqueue-scripts.php' );

		MXMTZC_Enqueue_Scripts_Frontend::mxmtzc_register();

		// enqueue_scripts class
		mxmtzc_require_class_file_frontend( 'shortcode.php' );

		$shortcode_instance = new MXMTZC_Shortcode();

		$shortcode_instance->mxmtzc_register_shortcode();

	}

}

// Initialize
$initialize_admin_class = new MXMTZC_FrontEnd_Main();

// include classes
$initialize_admin_class->mxmtzc_additional_classes();