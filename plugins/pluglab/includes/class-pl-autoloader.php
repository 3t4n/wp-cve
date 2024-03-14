<?php

defined( 'ABSPATH' ) || exit;

/**
 * Autoloader class.
 */
class PL_Autoloader {

	/**
	 * Path to the includes directory.
	 *
	 * @var string
	 */
	private $include_path = '';

	/**
	 * The Constructor.
	 */
	public function __construct() {

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->include_path = untrailingslashit( plugin_dir_path( PL_PLUGIN_FILE ) ) . '/includes/';
	}

	/**
	 * @param  string $class Class name.
	 * @return string
	 */
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}

	/**
	 * Include a class file.
	 *
	 * @param  string $path File path.
	 * @return bool Successful or not.
	 */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once $path;
			return true;
		}
		return false;
	}

	public function autoload( $class ) {
		$class = strtolower( $class );

		if ( 0 !== strpos( $class, 'pl_' ) ) {
			return;
		}

		$file = $this->get_file_name_from_class( $class );
		$path = '';

		if ( 0 === strpos( $class, 'pl_customizer_control_' ) ) {
			$path = $this->include_path . 'customizer/controls/';
		} elseif ( 0 === strpos( $class, 'pl_theme_shapro_' ) ) {
			$path = $this->include_path . 'theme/shapro/';
		} elseif ( 0 === strpos( $class, 'pl_theme_biznol_' ) ) {
			$path = $this->include_path . 'theme/biznol/';
		} elseif ( 0 === strpos( $class, 'pl_theme_corposet_' ) ) {
			$path = $this->include_path . 'theme/corposet/';
		} elseif ( 0 === strpos( $class, 'pl_theme_bizstrait_' ) ) {
			$path = $this->include_path . 'theme/bizstrait/';
		}

		if ( empty( $path ) || ! $this->load_file( $path . $file ) ) {
			$this->load_file( $this->include_path . $file );
		}
	}

}

new PL_Autoloader();
