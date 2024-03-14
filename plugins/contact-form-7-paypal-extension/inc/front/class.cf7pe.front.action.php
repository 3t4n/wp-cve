<?php
/**
 * CF7PE_Front_Action Class
 *
 * Handles the Frontend Actions.
 *
 * @package WordPress
 * @subpackage Accept PayPal Payments using Contact Form 7
 * @since 3.5
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'CF7PE_Front_Action' ) ){

	/**
	 *  The CF7PE_Front_Action Class
	 */
	class CF7PE_Front_Action {

		function __construct()  {

			add_action( 'wp_enqueue_scripts', array( $this, 'action__wp_enqueue_scripts' ) );

		}

		/*
		   ###     ######  ######## ####  #######  ##    ##  ######
		  ## ##   ##    ##    ##     ##  ##     ## ###   ## ##    ##
		 ##   ##  ##          ##     ##  ##     ## ####  ## ##
		##     ## ##          ##     ##  ##     ## ## ## ##  ######
		######### ##          ##     ##  ##     ## ##  ####       ##
		##     ## ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##     ##  ######     ##    ####  #######  ##    ##  ######
		*/

		function action__wp_enqueue_scripts() {
			wp_enqueue_script( CF7PE_PREFIX . '_front_js', CF7PE_URL . 'assets/js/front.min.js', array( 'jquery-core' ), CF7PE_VERSION );
		}



		/*
		######## ##     ## ##    ##  ######  ######## ####  #######  ##    ##  ######
		##       ##     ## ###   ## ##    ##    ##     ##  ##     ## ###   ## ##    ##
		##       ##     ## ####  ## ##          ##     ##  ##     ## ####  ## ##
		######   ##     ## ## ## ## ##          ##     ##  ##     ## ## ## ##  ######
		##       ##     ## ##  #### ##          ##     ##  ##     ## ##  ####       ##
		##       ##     ## ##   ### ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##        #######  ##    ##  ######     ##    ####  #######  ##    ##  ######
		*/

	}

	add_action( 'plugins_loaded' , function() {
		CF7PE()->front->action = new CF7PE_Front_Action;
	} );
}
