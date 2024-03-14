<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Controllers class
*/
abstract class MXMTZC_Controller
{

	/**
	* Catch missing methods on the controller
	*/
	public function __call( $name, $arguments ) {

		echo 'Missing method "' . $name . '"!';

	}
	
}