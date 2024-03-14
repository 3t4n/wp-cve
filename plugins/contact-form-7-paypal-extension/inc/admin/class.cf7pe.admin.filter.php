<?php
/**
 * CF7PE_Admin_Filter Class
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @subpackage Accept PayPal Payments using Contact Form 7
 * @since 3.5
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'CF7PE_Admin_Filter' ) ) {

	/**
	 *  The CF7PE_Admin_Filter Class
	 */
	class CF7PE_Admin_Filter {

		function __construct() {

			// Adding Paypal tab
			add_filter( 'wpcf7_editor_panels', array( $this, 'filter__wpcf7_editor_panels' ), 10, 3 );

		}

		/*
		######## #### ##       ######## ######## ########   ######
		##        ##  ##          ##    ##       ##     ## ##    ##
		##        ##  ##          ##    ##       ##     ## ##
		######    ##  ##          ##    ######   ########   ######
		##        ##  ##          ##    ##       ##   ##         ##
		##        ##  ##          ##    ##       ##    ##  ##    ##
		##       #### ########    ##    ######## ##     ##  ######
		*/
		/**
		 * PayPal tab
		 * Adding tab in contact form 7
		 *
		 * @param $panels
		 *
		 * @return array
		 */
		public function filter__wpcf7_editor_panels( $panels ) {

			$panels[ 'paypal-extension' ] = array(
				'title'    => __( 'PayPal', 'accept-paypal-payments-using-contact-form-7' ),
				'callback' => array( $this, 'wpcf7_admin_after_additional_settings' )
			);

			return $panels;
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
		/**
		 * Adding PayPal fields in PayPal tab
		 *
		 * @param $cf7
		 */
		public function wpcf7_admin_after_additional_settings( $cf7 ) {

			wp_enqueue_script( CF7PE_PREFIX . '_admin_js' );

			require_once( CF7PE_DIR .  '/inc/admin/template/' . CF7PE_PREFIX . '.template.php' );

		}

	}

	add_action( 'plugins_loaded' , function() {
		CF7PE()->admin->filter = new CF7PE_Admin_Filter;
	} );
}
