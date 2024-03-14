<?php

function hjqs_autoload( $base_dir, $namespace ): void {
	// Register the autoloader
	spl_autoload_register( function( $class_name ) use ( $base_dir, $namespace ) {
		// Check if the class belongs to the specified namespace
		if ( ! str_starts_with( $class_name, $namespace ) ) {
			return;
		}

		// Build the file path
		$file_path = $base_dir . str_replace( '\\', '/', substr( $class_name, strlen( $namespace ) ) ) . '.php';

		// Load the file if it exists
		if ( file_exists( $file_path ) ) {
			require $file_path;
		}
	} );
}

