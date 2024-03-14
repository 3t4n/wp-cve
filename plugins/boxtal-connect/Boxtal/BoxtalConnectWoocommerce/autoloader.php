<?php
/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin.
 *
 * @package Boxtal\BoxtalConnectWoocommerce\Autoload
 */

spl_autoload_register(
	function ( $class_name ) {

		// If the specified $class_name does not include our namespace, duck out.
		if ( false === strpos( $class_name, 'Boxtal\BoxtalConnectWoocommerce' ) && false === strpos( $class_name, 'Boxtal\BoxtalPhp' ) ) {
			return;
		}

		// Split the class name into an array to read the namespace and class.
		$file_parts = explode( '\\', $class_name );

		// Do a reverse loop through $file_parts to build the path to the file.
		$namespace = '';
		$file_name = '';
		for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {

			// Read the current component of the file part.
			if ( 1 !== $i && false !== strpos( $class_name, 'Boxtal\BoxtalConnectWoocommerce' ) ) {
				$current = strtolower( $file_parts[ $i ] );
				$current = str_ireplace( '_', '-', $current );
			} else {
				$current = $file_parts[ $i ];
			}

			// If we're at the first entry, then we're at the filename.
			if ( count( $file_parts ) - 1 === $i ) {
				if ( false !== strpos( $class_name, 'Boxtal\BoxtalConnectWoocommerce' ) ) {
					/*
					 * If 'abstracts' is contained in the parts of the file name, then
					 * define the $file_name differently so that it's properly loaded.
					 * Otherwise, just set the $file_name equal to that of the class
					 * filename structure.
					 */
					$file_name = "class-$current.php";
				} else {
					$file_name = "$current.php";
				}
			} else {
				$namespace = '/' . $current . $namespace;
			}
		}

		// Now build a path to the file using mapping to the file location.
		$filepath  = trailingslashit( dirname( __DIR__ ) . $namespace );
		$filepath .= $file_name;

		// If the file exists in the specified path, then include it.
		if ( file_exists( $filepath ) ) {
			include_once $filepath;
		} else {
			wp_die(
				esc_html( "The file attempting to be loaded at $filepath does not exist." )
			);
		}
	}
);
