<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// require Route-Registrar.php
require_once MXMTZC_PLUGIN_ABS_PATH . 'includes/core/Route-Registrar.php';

/*
* Routes class
*/
class MXMTZC_Route
{

	public function __construct()
	{
		// ...
	}
	
	public static function mxmtzc_get( ...$args )
	{

		return new MXMTZC_Route_Registrar( ...$args );

	}
	
}