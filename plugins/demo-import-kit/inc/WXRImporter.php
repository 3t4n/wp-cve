<?php
/**
 * WXR importer class used in the Demo Import Kit plugin.
 * Needed to extend the DIKI_WXR_Importer class to get/set the importer protected variables,
 * for use in the multiple AJAX calls.
 *
 * @package demo-import-kit
 */
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Include files.
require DIK_PATH . 'inc/class-wxr-importer.php';

class DIK_WXR_Importer extends DIKI_WXR_Importer {

	public function __construct( $options = array() ) {
		parent::__construct( $options );

		// Set current user to $mapping variable.
		// Fixes the [WARNING] Could not find the author for ... log warning messages.
		$current_user_obj = wp_get_current_user();
		$this->mapping['user_slug'][ $current_user_obj->user_login ] = $current_user_obj->ID;
	}

}
