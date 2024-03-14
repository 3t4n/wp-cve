<?php
/**
 * UpStream Autoloader.
 *
 * @package Upstream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UpStream Autoloader.
 *
 * @class       UpStream_Autoloader
 * @version     0.0.1
 * @package     UpStream/Classes
 * @category    Class
 * @author      UpStream
 */
class UpStream_Autoloader {
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
		if ( function_exists( '__autoload' ) ) {
			spl_autoload_register( '__autoload' );
		}

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->include_path = untrailingslashit( plugin_dir_path( UPSTREAM_PLUGIN_FILE ) ) . '/includes/';
	}

	/**
	 * Auto-load classes on demand to reduce memory consumption.
	 *
	 * @param string $class Class name.
	 */
	public function autoload( $class ) {
		$orig_class = $class;
		$class      = strtolower( $class );
		$file       = $this->get_file_name_from_class( $class );
		$path       = '';

		if ( strpos( $class, 'upstream_options' ) === 0 ) {
			$path = $this->include_path . 'admin/options/';
		} elseif ( strpos( $class, 'upstream_metaboxes' ) === 0 ) {
			$path = $this->include_path . 'admin/metaboxes/';
		} elseif ( strpos( $class, 'upstream_model' ) === 0 ) {
			$path = $this->include_path . 'model/';
		}

		// WCS class file name conversion.
		if ( strpos( $class, 'upstream_' ) === 0 ) {
			$file = str_replace( 'class-up-', 'class-upstream-', $file );
		}

		if ( empty( $path ) || ( ! $this->load_file( $path . $file ) && strpos( $class, 'upstream_' ) === 0 ) ) {
			$this->load_file( $this->include_path . $file );
		}
	}

	/**
	 * Take a class name and turn it into a file name.
	 *
	 * @param  string $class Class name.
	 *
	 * @return string
	 */
	private function get_file_name_from_class( $class ) {
		$updated_classes = array(
			'upstream_cache',
			'upstream_client',
			'upstream_import',
			'upstream_project',
		);

		if ( ! in_array( $class, $updated_classes, true ) ) {
			$class = str_replace( 'upstream', 'up', $class );
		}

		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}

	/**
	 * Include a class file.
	 *
	 * @param  string $path Class file path.
	 *
	 * @return bool successful or not
	 */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once $path;
			return true;
		}

		return false;
	}
}

new UpStream_Autoloader();
