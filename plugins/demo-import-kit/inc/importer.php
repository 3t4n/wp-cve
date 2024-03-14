<?php
/**
 * Class for declaring the importer used in the Demo Import Kit plugin
 *
 * @package demo-import-kit
 */
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Importer {

	private $importer;

	public function __construct( $importer_options = array(), $logger = null ) {

		// Include files that are needed for WordPress Importer v2.
		$this->include_required_files();

		// Set the WordPress Importer v2 as the importer used in this plugin.
		// More: https://github.com/humanmade/WordPress-Importer.
		$this->importer = new DIK_WXR_Importer( $importer_options );

		// Set logger to the importer.
		if ( ! empty( $logger ) ) {
			$this->set_logger( $logger );
		}
	}

	/**
	 * Include required files.
	 */
	private function include_required_files() {
		defined( 'WP_LOAD_IMPORTERS' ) || define( 'WP_LOAD_IMPORTERS', true );
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if( is_plugin_active( 'theme-demo-import/theme-demo-import.php' ) )
		{
		}
		else if ( is_plugin_active( 'one-click-demo-import/one-click-demo-import.php' ) ){
			# code...
		}
		else{
			require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}
		require DIK_PATH . 'inc/WXRImporter.php';
	}

	/**
	 * Imports content from a WordPress export file.
	 *
	 * @param string $data_file path to xml file, file with WordPress export data.
	 */
	public function import( $data_file ) {
		$this->importer->import( $data_file );
	}

	/**
	 * Set the logger used in the import
	 *
	 * @param object $logger logger instance.
	 */
	public function set_logger( $logger ) {
		$this->importer->set_logger( $logger );
	}

}
