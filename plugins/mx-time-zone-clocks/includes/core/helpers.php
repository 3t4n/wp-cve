<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Require class for admin panel
*/
function mxmtzc_require_class_file_admin( $file ) {

	require_once MXMTZC_PLUGIN_ABS_PATH . 'includes/admin/classes/' . $file;

}


/*
* Require class for frontend panel
*/
function mxmtzc_require_class_file_frontend( $file ) {

	require_once MXMTZC_PLUGIN_ABS_PATH . 'includes/frontend/classes/' . $file;

}

/*
* Require a Model
*/
function mxmtzc_use_model( $model ) {

	require_once MXMTZC_PLUGIN_ABS_PATH . 'includes/admin/models/' . $model . '.php';

}

/*
* Include a view
*/
function mxmtzc_include_view( $view ) {

	include MXMTZC_PLUGIN_ABS_PATH . 'includes/admin/views/' . $view . '.php';

}