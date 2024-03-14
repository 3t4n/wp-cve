<?php

namespace WC_BPost_Shipping\Core;

class WC_BPost_Shipping_Core_Autoload {

	private $classes_directory;

	/**
	 * @param string $class
	 */
	public static function load( $class ) {
		$self = new self( dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'classes' );
		$self->include_class( $class );
	}

	public function __construct( $classes_directory ) {
		$this->classes_directory = $classes_directory;
	}

	private function include_class( $class ) {
		$class_path = $this->get_class_path( $class );

		if ( $class_path !== '' && file_exists( $class_path ) ) {
			require_once $class_path;
		}
	}

	public function get_class_path( $class ) {
		if ( strpos( $class, 'WC_BPost_Shipping' ) !== 0 ) {
			return '';
		}

		$spaces = explode( '\\', $class );

		$final_space = array_pop( $spaces );

		// Build the file path
		$class_path = 'class-' . $this->wp_filter( $final_space ) . '.php';

		if ( count( $spaces ) > 0 ) {
			// Remove the root space (WC_BPost_Shipping)
			array_shift( $spaces );

			// Apply filter to
			$folders = array_map( array( $this, 'wp_filter' ), $spaces );

			$class_path = implode( DIRECTORY_SEPARATOR, $folders ) . DIRECTORY_SEPARATOR . $class_path;
		}

		return $this->classes_directory . DIRECTORY_SEPARATOR . $class_path;
	}

	private function wp_filter( $text ) {
		return str_replace( '_', '-', strtolower( $text ) );
	}
}
