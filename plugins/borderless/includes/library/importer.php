<?php

// don't load directly
defined( 'ABSPATH' ) || exit;

class Library_Importer {

	public function __construct() {

		require_once BORDERLESS__LIBRARY__DIR . 'vendor/autoload.php';

		$borderless_library_importer = LIBRARY\BorderlessLibraryImporter::get_instance();

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			WP_CLI::add_command( 'library list', array( 'LIBRARY\WPCLICommands', 'list_predefined' ) );
			WP_CLI::add_command( 'library import', array( 'LIBRARY\WPCLICommands', 'import' ) );
		}

	}
}

$library_importer = new Library_Importer();
