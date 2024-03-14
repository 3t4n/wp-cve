<?php
/**
 * @package wp_mail return-path
 */
/*/*
Plugin Name: wp_mail return-path
Version: 1.1.1
Plugin URI: 
Description: Simple plugin that sets the PHPMailer->Sender variable so that the return-path is correctly set when using wp_mail.
Author: Barnaby Puttick
Author URI: http://www.umis.net/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'wp_mail_returnpath_phpmailer' ) ) {
	final class wp_mail_returnpath_phpmailer {

		private static $instance;
		
		public static function getInstance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		private function __construct() {
			add_action( 'plugins_loaded', array( $this, 'init' ) );
		}

		public function init() {
			add_action( 'phpmailer_init', array( $this, 'setReturnPath' ) );
		}

		public function setReturnPath( $phpmailer ) {
			// Set the Sender (return-path) if it is not already set
			if(filter_var($phpmailer->Sender, FILTER_VALIDATE_EMAIL) !== true) {
				$phpmailer->Sender=$phpmailer->From;
			}
		}

	}

	$GLOBALS['wp_mail_returnpath_phpmailer'] = wp_mail_returnpath_phpmailer::getInstance();
}