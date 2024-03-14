<?php
/**
 * Abstract Assets loader.
 *
 * @since       1.0.3
 * @subpackage  Abstracts
 * @package     EverAccounting
 */

namespace EverAccounting\Abstracts;

/**
 * Class Assets
 *
 * @package EverAccounting\Abstracts
 */
abstract class Assets {
	/**
	 * Text domain.
	 *
	 * @var string
	 */
	protected $text_domain = null;

	/**
	 * Plugin path.
	 *
	 * @var string
	 */
	protected $plugin_file = null;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected $plugin_version = null;

	/**
	 * Assets constructor.
	 *
	 * @param string|null $plugin_file Plugin file.
	 */
	public function __construct( $plugin_file = null ) {
		$plugin_file = is_null( $plugin_file ) ? EACCOUNTING_PLUGIN_FILE : $plugin_file;
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		$plugin_data          = get_plugin_data( $plugin_file );
		$this->text_domain    = $plugin_data['TextDomain'];
		$this->plugin_version = $plugin_data['Version'];
		$this->plugin_file    = $plugin_file;
		add_action( 'wp_enqueue_scripts', array( $this, 'public_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'public_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueue public styles.
	 *
	 * @version 1.0.3
	 */
	public function public_styles() {

	}

	/**
	 * Enqueue public scripts.
	 *
	 * @version 1.0.3
	 */
	public function public_scripts() {

	}

	/**
	 * Enqueue admin styles.
	 *
	 * @version 1.0.3
	 */
	public function admin_styles() {

	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @version 1.0.3
	 */
	public function admin_scripts() {

	}

	/**
	 * Register style.
	 *
	 * @param string $handle style handler.
	 * @param string $file_path style file path.
	 * @param array  $dependencies style dependencies.
	 * @param bool   $has_rtl support RTL?.
	 */
	protected function register_style( $handle, $file_path, $dependencies = array(), $has_rtl = true ) {
		$filename = is_null( $file_path ) ? $handle : $file_path;
		$filename = str_replace( [ '.min', '.css' ], '', $filename );
		$file_url = $this->get_asset_dist_url( $filename, '.css' );
		$version  = $this->plugin_version;
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$version = time();
		}

		wp_register_style( $handle, $file_url, $dependencies, $version );

		if ( $has_rtl && function_exists( 'wp_style_add_data' ) ) {
			wp_style_add_data( $handle, 'rtl', 'replace' );
		}
	}

	/**
	 * Registers a script according to `wp_register_script`, additionally loading the translations for the file.
	 *
	 * @param string $handle Name of the script. Should be unique.
	 * @param string $file_path file path from dist directory.
	 * @param array  $dependencies Optional. An array of registered script handles this script depends on. Default empty array.
	 * @param bool   $has_i18n Optional. Whether to add a script translation call to this file. Default 'true'.
	 *
	 * @since 1.0.0
	 */
	protected function register_script( $handle, $file_path = null, $dependencies = array(), $has_i18n = true ) {
		$filename             = is_null( $file_path ) ? $handle : $file_path;
		$filename             = str_replace( [ '.min', '.js' ], '', $filename );
		$file_url             = $this->get_asset_dist_url( $filename );
		$dependency_file_path = $this->get_asset_dist_path( $filename . '.asset', 'php' );
		$version              = false;
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$version = time();
		}
		if ( file_exists( $dependency_file_path ) ) {
			$asset        = require $dependency_file_path;
			$dependencies = isset( $asset['dependencies'] ) ? array_merge( $asset['dependencies'], $dependencies ) : $dependencies;
			$version      = ! empty( $asset['version'] ) ? $asset['version'] : $version;
		}
		wp_register_script( $handle, $file_url, $dependencies, $version, true );

		if ( $has_i18n && function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( $handle, $this->text_domain, dirname( __DIR__ ) . '/languages' );
		}
	}


	/**
	 * Returns the appropriate asset url
	 *
	 * @param string $filename Filename for asset url (without extension).
	 * @param string $type File type (.css or .js).
	 *
	 * @return  string The generated path.
	 */
	protected function get_asset_dist_url( $filename, $type = 'js' ) {
		return plugins_url( "/dist/$filename.$type", $this->plugin_file );
	}

	/**
	 * Returns the appropriate asset url
	 *
	 * @param string $filename Filename for asset url (without extension).
	 * @param string $type File type (.css or .js).
	 *
	 * @return  string The generated path.
	 */
	protected function get_asset_dist_path( $filename, $type = 'js' ) {
		$plugin_path = untrailingslashit( plugin_dir_path( $this->plugin_file ) );

		return $plugin_path . "/dist/$filename.$type";
	}
}
