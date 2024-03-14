<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CLP_Custom_Login_Page_Autoloader
 */
class CLP_Custom_Login_Page_Autoloader {

	public function __construct() {
		spl_autoload_register( array( $this, 'load' ) );
	}

	public function load( $class ) {

		$parts = explode( '_', $class );
		$bind  = implode( '-', $parts );

		if ( 'CLP' == $parts[0] ) {

			$directories = array(
				CLP_PLUGIN_DIR . '/includes',
				CLP_PLUGIN_DIR . '/includes/controls',
			);

			foreach ( $directories as $directory ) {
				if ( file_exists( $directory . '/class-' . strtolower( $bind ) . '.php' ) ) {
					require_once $directory . '/class-' . strtolower( $bind ) . '.php';

					return;
				}
			}
		}
	}
}

$autoloader = new CLP_Custom_Login_Page_Autoloader();
