<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXMTZC_Admin_Main
{

	// list of model names used in the plugin
	public $models_collection = [
		'MXMTZC_Main_Page_Model'
	];

	/*
	* MXMTZC_Admin_Main constructor
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
		mxmtzc_require_class_file_admin('enqueue-scripts.php');

		MXMTZC_Enqueue_Scripts::mxmtzc_register();

		// options update
		mxmtzc_require_class_file_admin('update-clock-optons.php');

		MXMTZC_Update_Clock_Optons::mx_update_options();

		// Admin Notices
		mxmtzc_require_class_file_admin('admin-notices.php');

		MXMTZCAdminNotices::intNotices();
		MXMTZCAdminNotices::registerAjaxActions();
	}

	/*
	* Models Connection
	*/
	public function mxmtzc_models_collection()
	{

		// require model file
		foreach ($this->models_collection as $model) {

			mxmtzc_use_model($model);
		}
	}

	/**
	 * registration ajax actions
	 */
	public function mxmtzc_registration_ajax_actions()
	{

		// ajax requests

	}

	/*
	* Routes collection
	*/
	public function mxmtzc_routes_collection()
	{

		// main menu item 
		MXMTZC_Route::mxmtzc_get('MXMTZC_Main_Page_Controller', 'index', '', [
			'page_title' 	=> 'MX Time Zone Clocks Settings',
			'menu_title' 	=> 'Time Zone Clocks',
			'dashicons' 	=> 'dashicons-clock'
		]);

		// additional plugins
		// hide menu item
		MXMTZC_Route::mxmtzc_get('MXMTZC_Main_Page_Controller', 'hidemenu', 'NULL', [
			'page_title' => 'Additional plugins',
		], 'mx_clocks_additional_plugins');

		// offer
		// hide menu item
		MXMTZC_Route::mxmtzc_get('MXMTZC_Main_Page_Controller', 'offer', 'NULL', [
			'page_title' => 'Do you need a Web Developer?',
		], 'mx_clocks_offer');

		// sub settings menu item
        MXMTZC_Route::mxmtzc_get( 'MXMTZC_Main_Page_Controller', 'index', '', [
            'menu_title' => 'Generate Short code',
            'page_title' => 'Title of settings page'
        ], MXMTZC_MAIN_MENU_SLUG, true );

	}
}

// Initialize
$initialize_admin_class = new MXMTZC_Admin_Main();

// include classes
$initialize_admin_class->mxmtzc_additional_classes();

// include models
$initialize_admin_class->mxmtzc_models_collection();

// ajax requests
$initialize_admin_class->mxmtzc_registration_ajax_actions();

// include controllers
$initialize_admin_class->mxmtzc_routes_collection();
