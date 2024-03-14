<?php

/*************************************************************************

Plugin Name: Enable Virtual Card Upload - Vcard,Vcf
Donate Link: https://paypal.me/prodabo
Plugin URI: https://prodabo.com
Description: Enables upload of virtual card (vcf,vcard) files.
Version: 2.2.0
Author: Amit verma
Author URI: https://www.linkedin.com/in/avcodelord/
Text Domain: enable-virtual-card-upload

**************************************************************************/


if ( !defined( 'ABSPATH' ) ) {
	exit;
}


class PRDBEnableVcardUpload {

	/**
	 * Construct the plugin object
	 * @since    1.0.0
	 */
	public function __construct() {
		add_filter('upload_mimes', array( &$this, 'enable_vcard_upload') );
	} // END public function __construct

	/**
	 * Activate the plugin
	 */
	public static function activate() {
		// Do nothing
	} // END public static function activate

	/**
	 * Deactivate the plugin
	 */
	public static function deactivate() {
		// Do nothing
	} // END public static function deactivate

	/**
	 * Add vcf/vcard supprt
	 * @since 1.0.0
	 */
	public function enable_vcard_upload ( $mime_types=array() ){
		$mime_types['vcf'] = 'text/vcard';
		$mime_types['vcard'] = 'text/vcard';
		return $mime_types;
	}
}


$GLOBALS['PRDBEnableVcardUpload'] = new PRDBEnableVcardUpload();
