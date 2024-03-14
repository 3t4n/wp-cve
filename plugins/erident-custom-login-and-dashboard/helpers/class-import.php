<?php
/**
 * Setup import functions.
 *
 * @package Custom_Login_Dashboard
 */

namespace CustomLoginDashboard\Helpers;

/**
 * Setup widget import functions.
 */
class Import {
	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {}

	/**
	 * Run importer.
	 */
	public function import() {

		$explodes  = explode( '.', $_FILES['import_file']['name'] );
		$extension = end( $explodes );

		if ( 'json' !== $extension ) {
			wp_die( __( 'Please upload a valid .json file' ) );
		}

		$import_file = $_FILES['import_file']['tmp_name'];

		if ( empty( $import_file ) ) {
			wp_die( __( 'Please upload a file to import' ) );
		}

		// Retrieve the settings from the file and convert the json object to an array.
		$settings = (array) json_decode( file_get_contents( $import_file ) );

		update_option( 'plugin_erident_settings', $settings );
		echo '<div id="message" class="updated fade"><p><strong>' . __( 'New settings imported successfully!' ) . '</strong></p></div>';

	}

}
