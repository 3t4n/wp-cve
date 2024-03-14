<?php
/**
 * CF7PE_Admin_Action Class
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @subpackage Accept PayPal Payments using Contact Form 7
 * @since 3.5
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'CF7PE_Admin_Action' ) ){

	/**
	 *  The CF7PE_Admin_Action Class
	 */
	class CF7PE_Admin_Action {

		function __construct()  {

			add_action( 'init',  array( $this, 'action__init' ) );

			// Save settings of contact form 7 admin
			add_action( 'wpcf7_save_contact_form', array( $this, 'action__wpcf7_save_contact_form' ), 20, 2 );

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
		function action__init() {
			wp_register_script( CF7PE_PREFIX . '_admin_js', CF7PE_URL . 'assets/js/admin.min.js', array( 'jquery-core' ), CF7PE_VERSION );
			wp_register_style( CF7PE_PREFIX . '_admin_css', CF7PE_URL . 'assets/css/admin.min.css', array(), CF7PE_VERSION );
		}

		/**
		 * Save PayPal field settings
		 */
		public function action__wpcf7_save_contact_form( $WPCF7_form ) {

			$wpcf7 = WPCF7_ContactForm::get_current();

			if ( !empty( $wpcf7 ) ) {
				$post_id = $wpcf7->id;
			}

			$form_fields = array(
				CF7PE_META_PREFIX . 'use_paypal',
				CF7PE_META_PREFIX . 'mode_sandbox',
				CF7PE_META_PREFIX . 'sandbox_client_id',
				CF7PE_META_PREFIX . 'sandbox_client_secret',
				CF7PE_META_PREFIX . 'live_client_id',
				CF7PE_META_PREFIX . 'live_client_secret',
				CF7PE_META_PREFIX . 'amount',
				CF7PE_META_PREFIX . 'quantity',
				CF7PE_META_PREFIX . 'description',
				CF7PE_META_PREFIX . 'currency',
				CF7PE_META_PREFIX . 'success_returnurl',
				CF7PE_META_PREFIX . 'cancel_returnurl',
			);

			if ( !empty( $form_fields ) ) {
				foreach ( $form_fields as $key ) {
					$keyval = sanitize_text_field( $_REQUEST[ $key ] );
					update_post_meta( $post_id, $key, $keyval );
				}
			}

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
		CF7PE()->admin->action = new CF7PE_Admin_Action;
	} );
}
