<?php
/**
 * Nativerent Settings
 *
 * @package nativerent
 */

namespace NativeRent;

use WP_Filesystem_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Nativerent class
 *
 * @note This class is no longer used except to remove leftover configuration by previous versions.
 */
class Settings {
	const DATA_FOLDER = 'nativerent';

	/**
	 * The single instance of the class.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * WP Filesystem
	 *
	 * @var WP_Filesystem_Base
	 */
	private $filesystem = '';

	/**
	 * Main Instance.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * A dummy magic method to prevent class from being cloned
	 */
	public function __clone() {
	}

	/**
	 * A dummy magic method to prevent class from being unserialized
	 */
	public function __wakeup() {
	}

	/**
	 * Constructor is private to privent creating new instances
	 */
	private function __construct() {
	}

	/**
	 * Uninstall
	 */
	public function uninstall() {
		return $this->delete_data_folder();
	}

	/**
	 * Get data folder path
	 */
	private function get_data_folder_path() {
		return ABSPATH . 'wp-content/' . self::DATA_FOLDER; // No need to use DIRECTORY_SEPARATOR. Cannot use WP_CONTENT_DIR.
	}

	/**
	 * Create folder inside wp-content
	 * Uploads folder cannot be used because it can be moved and its relative path isn't a constant.
	 */
	private function delete_data_folder() {
		$folder_path = $this->get_data_folder_path();
		if ( ! file_exists( $folder_path ) ) {
			return true;
		}

		return $this->get_filesystem()->delete( $folder_path, true, 'd' );
	}

	/**
	 * Get WP filesystem
	 */
	private function get_filesystem() {
		if ( empty( $this->filesystem ) ) {
			$this->set_filesystem();
		}

		return $this->filesystem;
	}

	/**
	 * Set up WP filesystem
	 */
	private function set_filesystem() {
		if ( ! empty( $this->filesystem ) ) {
			return;
		}

		global $wp_filesystem;

		// Create object to work with files if it is not exists already.
		if ( ! $wp_filesystem ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$this->filesystem = $wp_filesystem;
	}
}
