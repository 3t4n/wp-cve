<?php
namespace ElementorStretchColumn;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Extensions_Manager {

	private $_extensions = null;

	/**
	 * Loops though available extensions and registers them
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 * @return void
	 */
	public function register_extensions() {

		$this->_extensions = [];

			//$class_name = str_replace( '-', '_', $extension_id );
		
			require COLUMN_STRETCH_ELEMENTOR_PATH . 'extensions/stretch-column.php';

			$class_name = 'ElementorStretchColumn\Extensions\Extension_Stretch_Column';

			$this->register_extension( 'stretch_column', new $class_name() );

		do_action( 'column_stretch_elementor/extensions/extensions_registered', $this );
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @param Extension_Base $extension_instance
	 */
	public function register_extension( $extension_id, Base\Extension_Base $extension_instance ) {
		$this->_extensions[ $extension_id ] = $extension_instance;
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @return bool
	 */
	public function unregister_extension( $extension_id ) {
		if ( ! isset( $this->_extensions[ $extension_id ] ) ) {
			return false;
		}

		unset( $this->_extensions[ $extension_id ] );

		return true;
	}

	/**
	 * @since 0.1.0
	 *
	 * @return Extension_Base[]
	 */
	public function get_extensions() {
		if ( null === $this->_extensions ) {
			$this->register_extensions();
		}

		return $this->_extensions;
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @return bool|\ElementorStretchColumn\Extension_Base
	 */
	public function get_extension( $extension_id ) {
		$extensions = $this->get_extensions();

		return isset( $extensions[ $extension_id ] ) ? $extensions[ $extension_id ] : false;
	}

	private function require_files() {
		require COLUMN_STRETCH_ELEMENTOR_PATH . 'base/extension.php';
	}

	public function __construct() {
		$this->require_files();
		$this->register_extensions();
	}
}