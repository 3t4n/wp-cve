<?php
defined( 'ABSPATH' ) || exit();

/**
 * @Class Wpopal_Core_Admin_Menu
 * 
 * Entry point class to setup load all files and init working on frontend and process something logic in admin
 */

class FE_Core_Admin {

	public function __construct() {

		// add_action('admin_init', array( $this, 'setup' ) , 1);
		$this->load();
	}	
	
	/**
	 * Load 
	 */
	public function load(){
		global $portfolio_options;
		$this->includes( [
			'admin/class-menu.php',
		] );
		$portfolio_options = portfolio_get_settings();
		// CMB2
        if (!class_exists('CMB2')) {
            require_once PE_PLUGIN_INC_DIR . '/vendors/cmb2/libraries/init.php';
        }
	}
	/**
	 * Include list of collection files
	 *
	 * @var array $files
	 */
	public function includes ( $files ) {
		foreach ( $files as $file ) {
			$this->_include( $file );
		}
	}
	/**
	 * include single file if found 
	 * 
	 * @var string $file
	 */
	private function _include( $file = '' ) {
		$file = PE_PLUGIN_INC_DIR  . $file;  
		if ( file_exists( $file ) ) {
			include_once $file;
		}
	}
	 
}

new FE_Core_Admin();
