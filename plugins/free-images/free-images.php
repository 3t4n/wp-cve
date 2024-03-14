<?php
/**
 * Free Assets Library Plugin.
 *
 * @package      FAL
 * @copyright    Copyright (C) 2014-2022, Surror - admin@surror.com
 * @link         https://surror.com
 * @since        2.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Free Assets Library
 * Version:           2.2.1
 * Plugin URI:        https://surror.com/free-asset-library/
 * Description:       Stunning free images from Openverse, Pixabay for your own blog. Find suitable image as per your need from the millions royalty free images library.
 * Author:            Surror
 * Author URI:        https://surror.com/
 * License:           GPL v3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       fal
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

/**
 * FAL class.
 *
 * @class Main class of the plugin.
 */
final class FAL {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = '2.2.1';

	/**
	 * Hold objects.
	 *
	 * @var bool
	 */
	private $container = [];

	/**
	 * The single instance of the class.
	 *
	 * @var FAL
	 */
	protected static $instance = null;

	/**
	 * Retrieve main FAL instance.
	 *
	 * Ensure only one instance is loaded or can be loaded.
	 *
	 * @see fal()
	 * @return FAL
	 */
	public static function get() {
		if ( is_null( self::$instance ) && ! ( self::$instance instanceof FAL ) ) {
			self::$instance = new FAL();
			self::$instance->setup();
		}

		return self::$instance;
	}

	/**
	 * Instantiate the plugin.
	 */
	private function setup() {
		// Define plugin constants.
		$this->define_constants();

		// Include required files.
		$this->includes();

		// Instantiate classes.
		$this->instantiate();

		// Loaded action.
		do_action( 'fal/loaded' );
	}

	/**
	 * Define the plugin constants.
	 */
	private function define_constants() {
		define( 'FAL_VERSION', $this->version );
		define( 'FAL_FILE', __FILE__ );
		define( 'FAL_BASE', plugin_basename( FAL_FILE ) );
		define( 'FAL_DIR', plugin_dir_path( FAL_FILE ) );
		define( 'FAL_URI', plugins_url( '/', FAL_FILE ) );
	}

	/**
	 * Include the required files.
	 */
	private function includes() {
		include dirname( __FILE__ ) . '/vendor/autoload.php';
	}

	/**
	 * Instantiate classes.
	 */
	private function instantiate() {
		new \FAL\Page();
		new \FAL\RestAPI();
		new \FAL\Track();
		new \FAL\Media_Popup();
		new \FAL\Notices();

		new \FAL\Surror\Dashboard();
	}

}

/**
 * Returns the main instance of FAL to prevent the need to use globals.
 *
 * @return FAL
 */
function fal() {
	return FAL::get();
}

// Start it.
fal();
