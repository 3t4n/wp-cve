<?php
/**
 * NetiveRent Autoloader.
 *
 * @package    nativerent
 * @version    1.0.0
 */

namespace NativeRent;

defined( 'ABSPATH' ) || exit;

/**
 * Class Autoloader
 */
class Autoloader {

	/**
	 * Path to the includes directory.
	 *
	 * @var string
	 */
	private $paths_map = '';

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$base = rtrim( NATIVERENT_PLUGIN_DIR, '/\\' );

		$this->paths_map = array(
			'NativeRent\\Admin\\' => $base . '/admin/',
			'NativeRent\\'      => $base . '/includes/',
		);

		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Take a class name and turn it into a file name.
	 *
	 * @param  string $class Class name.
	 * @return string
	 */
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}

	/**
	 * Include a class file.
	 *
	 * @param  string $file File.
	 * @return bool Successful or not.
	 */
	private function load_file( $file ) {
		$path       = $this->path . $file;

		if ( $this->path && is_readable( $path ) ) {
			include_once $path;
			return true;
		}

		return false;
	}

	/**
	 * Loads the given class or interface.
	 *
	 * @param string $class The name of the class.
	 * @return bool|null True if loaded, null otherwise
	 */
	public function autoload( $class ) {
		foreach ( $this->paths_map as $space_name => $path ) {
			if ( 0 === mb_strpos( $class, $space_name ) ) {
				$this->path = $path;

				$class = substr( $class, strlen( $space_name ) );
				$class = strtolower( $class );
				$file  = $this->get_file_name_from_class( $class );

				$this->load_file( $file );

				return true;
			}
		}

		return false;
	}
}

new Autoloader();
