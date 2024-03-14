<?php
namespace QuickAdsense;

/**
 * FileHandler acts as a wrapper for the WordPress Filesystem API
 */
class FileHandler {
	/**
	 * The full path to the file.
	 *
	 * @var array $file_path
	 */
	private $file_path = '';

	/**
	 * The content to be sent to the file.
	 *
	 * @var array $file_content
	 */
	private $file_content = '';

	/**
	 * Access credentials for the file.
	 *
	 * @var array $file_credentials
	 */
	private $file_credentials = false;

	/**
	 * Holds an instance of the builtin WordPress global $wp_filesystem.
	 *
	 * @var object $wp_filesystem
	 */
	private $wp_filesystem = null;

	/**
	 * Initialize the class
	 *
	 * @param string $file_path Complete path to the file to be loaded relative to the site root.
	 * @param string $file_content Content to write into the file.
	 */
	public function __construct( $file_path = '', $file_content = '' ) {
		global $wp_filesystem;
		$this->file_content     = $file_content;
		$this->file_credentials = false;
		if ( get_filesystem_method() === 'direct' ) {
			$this->file_credentials = request_filesystem_credentials( '', '', false, false, [] );
			if ( WP_Filesystem( $this->file_credentials ) ) {
				$this->file_path     = $wp_filesystem->abspath() . $file_path;
				$this->wp_filesystem = $wp_filesystem;
			}
		}
	}

	/**
	 * Check if a file exist.
	 *
	 * @param string $file_path Complete path to the file to be loaded relative to the site root.
	 *
	 * @return boolean true on success, false on faliure.
	 */
	public function exists( $file_path = '' ) {
		if ( isset( $this->wp_filesystem ) ) {
			if ( '' === $file_path ) {
				$file_path = $this->file_path;
			} else {
				$file_path = $this->wp_filesystem->abspath() . $file_path;
			}
			if ( file_exists( $file_path ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Read a file.
	 *
	 * @param string $file_path Complete path to the file to be loaded relative to the site root.
	 *
	 * @return mixed The content of the file on success, false on faliure.
	 */
	public function read( $file_path = '' ) {
		if ( isset( $this->wp_filesystem ) ) {
			if ( '' === $file_path ) {
				$file_path = $this->file_path;
			} else {
				$file_path = $this->wp_filesystem->abspath() . $file_path;
			}
			return $this->wp_filesystem->get_contents( $file_path );
		}
		return false;
	}

	/**
	 * Create and write content to a file.
	 *
	 * @param string $file_content Content to write into the file.
	 * @param string $file_path Complete path to the file to be loaded relative to the site root.
	 *
	 * @return boolean true on success, false on faliure
	 */
	public function write( $file_content = '', $file_path = '' ) {
		if ( isset( $this->wp_filesystem ) ) {
			if ( '' === $file_path ) {
				$file_path = $this->file_path;
			} else {
				$file_path = $this->wp_filesystem->abspath() . $file_path;
			}
			if ( '' === $file_content ) {
				$file_content = $this->file_content;
			}
			return $this->wp_filesystem->put_contents( $file_path, $file_content, FS_CHMOD_FILE );
		}
		return false;
	}

	/**
	 * Delete a file
	 *
	 * @param string $file_path Complete path to the file to be loaded relative to the site root.
	 *
	 * @return boolean true on success, false on faliure.
	 */
	public function delete( $file_path = '' ) {
		if ( isset( $this->wp_filesystem ) ) {
			if ( '' === $file_path ) {
				$file_path = $this->file_path;
			} else {
				$file_path = $this->wp_filesystem->abspath() . $file_path;
			}
			return $this->wp_filesystem->delete( $file_path, false, 'f' );
		}
		return false;
	}
}

