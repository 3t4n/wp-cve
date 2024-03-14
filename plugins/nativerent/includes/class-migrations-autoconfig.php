<?php
/**
 * Native Rent Migrations_Autoconfig class
 * it needs to remove old intagration
 *
 * @package nativerent
 */

namespace NativeRent;

defined( 'ABSPATH' ) || exit;

/**
 * Class API
 *
 * @note This class is no longer used except to remove leftover configuration by previous versions.
 */
class Migrations_Autoconfig {
	/**
	 * Found configs.
	 *
	 * @var array
	 */
	protected static $configs = array( '.htaccess', '.user.ini' );

	/**
	 * Begin string
	 *
	 * @var string
	 */
	protected static $begin_label = 'NativeRent Configuration BEGIN';

	/**
	 * End string
	 *
	 * @var string
	 */
	protected static $end_label = 'NativeRent Configuration END';

	/**
	 * Path to wp-content dir
	 *
	 * @var string
	 */
	protected static $wp_content_dir;

	/**
	 * Scan config files
	 *
	 * @return void
	 */
	protected static function initialization() {
		// Search for config files.
		self::$wp_content_dir = defined( 'WP_CONTENT_DIR' )
			? WP_CONTENT_DIR
			: ( defined( 'WP_CONTENT_FOLDERNAME' )
				? ABSPATH . WP_CONTENT_FOLDERNAME
				: ABSPATH . 'wp-content' );

		$possible_configs     = array(
			self::$wp_content_dir . '/.htaccess',
			self::$wp_content_dir . '/cache/.htaccess',
		);
		if ( is_dir( self::$wp_content_dir . '/cache' ) ) {
			foreach ( scandir( self::$wp_content_dir . '/cache' ) as $name ) {
				if ( '.' === $name || '..' === $name || ! is_dir( self::$wp_content_dir . "/cache/{$name}" ) ) {
					continue;
				}

				$possible_configs[] = ( self::$wp_content_dir . "/cache/{$name}/.htaccess" );
			}
		}

		// Save search results.
		foreach ( $possible_configs as $config ) {
			if ( file_exists( $config ) ) {
				self::$configs[] = $config;
			}
		}
	}


	/**
	 * Revert changes in patched configs
	 *
	 * @return void
	 */
	public static function uninstall() {
		self::initialization();

		foreach ( self::$configs as $config ) {
			$path    = self::get_config_filepath( $config );
			$content = is_readable( $path ) ? file_get_contents( $path ) : '';
			if ( false !== strpos( $content, self::$begin_label ) ) {
				do {
					$content = self::remove_config_patch( $content );
				} while ( false !== strpos( $content, self::$begin_label ) );
				self::write_to_file( $path, $content );
			}
		}
	}

	/**
	 * Remove our settings from content of config file.
	 *
	 * @param string $content File content.
	 *
	 * @return string
	 */
	protected static function remove_config_patch( $content ) {
		return preg_replace( '/^(.*?)[^\n\r]+?' . self::$begin_label . '[\s\S]+?' . self::$end_label . '(.*)$/s', '$1$2', $content );
	}

	/**
	 * Backup config file and write into it new settings.
	 *
	 * @param string $path Path of file.
	 * @param string $content File content.
	 *
	 * @return bool
	 */
	protected static function write_to_file( $path, $content ) {
		// Create backup.
		if ( ! file_exists( $path ) ) {
			return false;
		}

		$backup_time = time();
		$backup_file = $path . ".nrbackup.{$backup_time}";
		if ( ! @rename( $path, $backup_file ) ) {
			// Cannot proceed without backup.
			return false;
		}

		// Delete old backups.
		$backedup_file = basename( $path );
		$backup_dir    = dirname( $path );
		$list          = scandir( $backup_dir );
		foreach ( $list as $filename ) {
			if (
				preg_match( "/{$backedup_file}\.nrbackup\.\d+$/", $filename ) === 1 &&
				! preg_match( "/{$backedup_file}\.nrbackup\.{$backup_time}$/", $filename )
			) {
				unlink( $backup_dir . '/' . $filename );
			}
		}

		// Rewrite config file.
		return @file_put_contents( $path, $content ) > 0;
	}

	/**
	 * Get config filepath.
	 *
	 * @param string $config Config content.
	 *
	 * @return string
	 */
	protected static function get_config_filepath( $config ) {
		if ( '.htaccess' === $config ) {
			return ABSPATH . '.htaccess';
		}

		if ( '.user.ini' === $config ) {
			$filename = @ini_get( 'user_ini.filename' );

			return ( false !== $filename && strlen( $filename ) > 0 )
				? ABSPATH . $filename
				: ABSPATH . '.user.ini';
		}

		return $config;
	}
}
