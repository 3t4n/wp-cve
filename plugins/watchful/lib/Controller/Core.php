<?php
/**
 * Controller for upgrading WP core.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful\Controller;

/**
 * WP REST API Menu routes
 *
 * @package WP_API_Menus
 */
use Watchful\Exception;
use Watchful\Helpers\BackupPluginHelper;
use Watchful\Helpers\Authentification;
use Watchful\Helpers\InstalledPlugins;
use Watchful\Skins\SkinCoreUpgrader;
use \WP_REST_Server;
use \Core_Upgrader;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WP Core upgrade class.
 */
class Core implements BaseControllerInterface  {
	/**
	 * Current WordPress version.
	 *
	 * @var string
	 */
	private static $wp_version;

	/**
	 * Register watchful routes for WP API v2.
	 *
	 * @since  1.2.0
	 */
	public function register_routes() {
		register_rest_route(
			'watchful/v1',
			'/core/update',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_core' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => Authentification::get_arguments(),
				),
			)
		);
	}

	/**
	 * Store the WordPress core version that is currently defined.
	 * This is useful to do it the earliest possible in the execution because
	 * some poorly written plugins or themes may accidentally (or intentionally) change it.
	 */
	public static function remember_wp_version() {
		static::$wp_version = (string) get_bloginfo( 'version' );
	}

	/**
	 * Get the WordPress core version. If it was previously stored using
	 * remember_wp_version(), the stored version will be returned. If it wasn't, the
	 * version will retrieved using get_bloginfo().
	 *
	 * @return string
	 */
	public static function get_wp_version() {
		if ( static::$wp_version ) {
			return static::$wp_version;
		}

		return (string) get_bloginfo( 'version' );
	}

	/**
	 * Update WP core.
	 *
	 * @return boolean
	 *
	 * @throws Exception If the response is a WP_Error object.
	 */
	public function update_core() {
		$core     = new Core();
		$response = $core->upgrade_core();

		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_code(), 500, $response->get_error_data() );
		}

		return $response;
	}

	/**
	 * Create and return a standard status class.
	 */
	public function get_status() {
		$status = new \stdClass();

        $settings    = get_option( 'watchfulSettings' );
        $maintenance = isset($settings['watchful_maintenance']) ? $settings['watchful_maintenance'] : false;

		$status->access      = true;
		$status->maintenance = $maintenance;
		$status->can_update  = true;

		return $status;
	}

	/**
	 * Get latest versions of WP, php, server, and MySQL.
	 *
	 * @return object
	 */
	public function get_versions() {
		$version = new \stdClass();

		$version->j_version      = static::get_wp_version();
		$version->jUpd_version   = $this->get_latest_update(); // phpcs:ignore WordPress.NamingConventions.ValidVariableName
		$version->php_version    = phpversion();
		$version->server_version = $this->get_server_version();
		$version->mysql_version  = $this->get_db_version();

		return $version;
	}

	/**
	 * Upgrade WP core.
	 *
	 * @return boolean
	 *
	 * @throws Exception If file mods are not allowed.
	 */
	private function upgrade_core() {

		if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) {
			throw new Exception( 'file modification is disabled (DISALLOW_FILE_MODS)', 403 );
		}

		include_once ABSPATH . 'wp-admin/includes/admin.php';
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		include_once ABSPATH . WPINC . '/update.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		// Check for filesystem access.
		if ( false === $this->check_filesystem_access() ) {
			throw new Exception( 'filesystem not writable', 403 );
		}

		// Force refresh.
		wp_version_check();

		$updates = get_core_updates();

		if ( is_wp_error( $updates ) || ! $updates ) {
			throw new Exception( 'core is already up to date', 400 );
		}

		$update = reset( $updates );

		if ( ! $update ) {
			throw new Exception( 'core is already up to date', 400 );
		}

		$skin = new SkinCoreUpgrader();

		$upgrader = new Core_Upgrader( $skin );
		$result   = $upgrader->upgrade( $update );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		global $wp_current_db_version, $wp_db_version;

		// We have to include version.php so $wp_db_version
		// will take the version of the updated version of WordPress.
		require_once ABSPATH . WPINC . '/version.php';

		wp_upgrade();

		return true;
	}

	/**
	 * Check file system access.
	 *
	 * @return boolean
	 */
	private function check_filesystem_access() {
		ob_start();
		$success = request_filesystem_credentials( '' );
		ob_end_clean();
		return (bool) $success;
	}

	/**
	 * Get the DB version.
	 *
	 * @return string
	 */
	private function get_db_version() {
		global $wpdb;
		return preg_replace( '/[^0-9.].*/', '', $wpdb->get_var( 'SELECT VERSION()' ));
	}

	/**
	 * Get the server version.
	 *
	 * @return string
	 */
	private function get_server_version() {
		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) );
		}
		if ( ( getenv( 'SERVER_SOFTWARE' ) ) ) {
			return getenv( 'SERVER_SOFTWARE' );
		}

		return 'NOT_FOUND';
	}

    /**
     * Get data for some important system files
     *
     * @return array
     */
	public function get_files_properties() {
		$files_properties = array();

        $files = $this->get_files_to_check();

		foreach ( $files as $file ) {
			$checksum       = 'NOT_FOUND';
			$fstat['size']  = 'NOT_FOUND';
			$fstat['mtime'] = 'NOT_FOUND';

			// If the file exists.
			if ( file_exists( $file ) ) {
				$fp    = fopen( $file, 'r' );
				$fstat = fstat( $fp );
				fclose( $fp );
				$checksum = md5_file( $file );
			}

			// Special handling of wp-config.php file, which may be placed one level above for increased security
			if ( $checksum === 'NOT_FOUND' && $file === ABSPATH . '/wp-config.php' ) {
				$file = ABSPATH . '/../wp-config.php';
				if ( file_exists( $file )) {
					$fp	= fopen( $file, 'r' );
					$fstat = fstat( $fp );
					fclose( $fp );
					$checksum = md5_file( $file );
				}
			}

			$file               = array(
				'rootpath'         => $file,
				'size'             => $fstat['size'],
				'modificationtime' => $fstat['mtime'],
				'checksum'         => $checksum,
			);
			$files_properties[] = $file;
		}

		return $files_properties;
	}

    /**
     * Get the latest backup date.
     * @param array $site_backups_data
     * @return string The date of the latest backup or an empty string if not set
     */
	public function get_latest_backup_info($site_backups_data = array()) {
        if (empty($site_backups_data)) {
            return '';
        }

        $date = (new BackupPluginHelper())
            ->get_last_backup_date($site_backups_data);
        return $date ? $date->format('Y-m-d H:i:s') : '';
    }

	/**
	 * Get the latest WP update.
	 *
	 * @return string
	 */
	private function get_latest_update() {
        require_once ABSPATH . '/wp-admin/includes/update.php';

        if ( function_exists( 'get_core_updates' ) ){
            $core_update_response = get_core_updates();
            if ( !empty( $core_update_response ) ){
                return $core_update_response[0]->version;
            }
        }
        return get_bloginfo( 'version' );
	}

    /**
     * Get an array containing full path of files to check
     *
     * @return array
     */
    private function get_files_to_check() {
        // Files to check.
        $files = array(
            ABSPATH . '/index.php',
            ABSPATH . '/wp-config.php',
            ABSPATH . '/.htaccess',
            ABSPATH . '/wp-admin/index.php',
        );

        foreach (search_theme_directories() as $theme_dir => $theme_data) {
            $theme_full_path = $theme_data['theme_root']."/" . $theme_dir;
            array_push($files, $theme_full_path . '/index.php');
            array_push($files, $theme_full_path . '/footer.php');
            array_push($files, $theme_full_path . '/functions.php');
            array_push($files, $theme_full_path . '/header.php');
            array_push($files, $theme_full_path . '/style.css');
        }
        return $files;
    }

}
