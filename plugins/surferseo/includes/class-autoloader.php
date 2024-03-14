<?php
/**
 * Object to autoload required files.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO;

/**
 * Object to autoload required files.
 */
class Autoloader {

	/**
	 * Object constructor.
	 */
	public function __construct() {
		spl_autoload_register( array( $this, 'register_autoloader' ) );
	}

	/**
	 * Displays tag saved in configuration provided by GSC.
	 *
	 * @param string $class_name - name of the class to import.
	 * @return void
	 */
	public function register_autoloader( $class_name ) {

		// If the specified $class_name does not include our namespace, duck out.
		if ( false === strpos( $class_name, 'SurferSEO' ) ) {
			return;
		}

		// Split the class name into an array to read the namespace and class.
		$file_parts = explode( '\\', $class_name );

		// Do a reverse loop through $file_parts to build the path to the file.
		$namespace = '';
		for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {
			$current = strtolower( $file_parts[ $i ] );
			$current = str_ireplace( '_', '-', $current );

			// If we're at the first entry, then we're at the filename.
			if ( count( $file_parts ) - 1 === $i ) {
				/*
				* If 'interface' is contained in the parts of the file name, then
				* define the $file_name differently so that it's properly loaded.
				* Otherwise, just set the $file_name equal to that of the class
				* filename structure.
				*/

				if ( strpos( strtolower( $file_parts[ count( $file_parts ) - 1 ] ), 'interface' ) ) {

					// Grab the name of the interface from its qualified name.
					$interface_name_parts = explode( '_', $file_parts[ count( $file_parts ) - 1 ] );
					$interface_name       = '';
					foreach ( $interface_name_parts as $part ) {
						if ( 'interface' !== strtolower( $part ) ) {
							$interface_name .= $part . '-';
						}
					}
					$interface_name = rtrim( $interface_name, '-' );

					$file_name = "interface-$interface_name.php";
				} else {
					$file_name = "class-$current.php";
				}
			} else {
				$namespace = '/' . $current . $namespace;
			}
		}

		$namespace = '/includes' . $namespace;

		// Now build a path to the file using mapping to the file location.
		$filepath  = dirname( dirname( __FILE__ ) );
		$filepath  = trailingslashit( $filepath . strtolower( $namespace ) );
		$filepath .= strtolower( $file_name );

		// If the file exists in the specified path, then include it.
		if ( file_exists( $filepath ) ) {
			require_once $filepath;
		} else {
			wp_die(
				esc_html( "The file attempting to be loaded at $filepath does not exist." )
			);
		}
	}

}
