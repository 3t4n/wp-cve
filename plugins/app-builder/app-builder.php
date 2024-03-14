<?php
/**
 *
 * @link              https://appcheap.io
 * @since             1.0.0
 * @package           AppBuilder
 * @category          includes
 *
 * @wordpress-plugin
 * Plugin Name:       App Builder - Create Native Android & iOS Apps On The Flight
 * Plugin URI:        https://appcheap.io/docs
 * Description:       The App Builder has it all: drag and drop mobile app, power and control over your app. Get started now!.
 * Version:           3.8.8
 * Author:            Appcheap.io
 * Author URI:        https://appcheap.io
 * Text Domain:       app-builder
 * Domain Path:       /languages
 *
 * App Builder is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * App Builder is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'APP_BUILDER_PLUGIN_FILE' ) ) {
	define( 'APP_BUILDER_PLUGIN_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Main AppBuilder Class.
 *
 * @class AppBuilder
 *
 * @since 1.0.0
 */
final class AppBuilder {
	/**
	 * AppBuilder version.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $version = '3.5.6';

	/**
	 * AppBuilder JS version.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $js_version = '3.5.6';

	/**
	 * Support Cirilla stable version.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $cirilla_version = '3.8.0';

	/**
	 * Support Mdelicious stable version.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $mdelicious_version = '1.0.0';

	/**
	 * The plugin url.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $plugin_url;

	/**
	 * The plugin path.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $plugin_path;

	/**
	 * AppCheap Schema version.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $db_version = '1';

	/**
	 * The single instance of the class.
	 *
	 * @var AppBuilder
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Instances of services
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $services = array();

	/**
	 * Main AppBuilder Instance.
	 *
	 * Ensures only one instance of AppCheap is loaded or can be loaded.
	 *
	 * @return AppBuilder - Main instance.
	 * @since 1.0.0
	 * @static
	 */
	public static function instance(): ?AppBuilder {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * AppBuilder Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->define_constants();
		$this->init_hooks();
	}

	/**
	 * Define AppBuilder Constants.
	 *
	 * @since 1.0.0
	 */
	private function define_constants() {
		$upload_dir = wp_upload_dir( null, false );

		$this->define( 'APP_BUILDER_NAME', 'app_builder' );
		$this->define( 'APP_BUILDER_DOMAIN', 'app-builder' );
		$this->define( 'APP_BUILDER_ABSPATH', dirname( APP_BUILDER_PLUGIN_FILE ) . DIRECTORY_SEPARATOR );
		$this->define( 'APP_BUILDER_PLUGIN_BASENAME', plugin_basename( APP_BUILDER_PLUGIN_FILE ) );
		$this->define( 'APP_BUILDER_ASSETS', plugin_dir_url( APP_BUILDER_PLUGIN_FILE ) . 'assets' );
		$this->define( 'APP_BUILDER_PREVIEW_DIR', $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'app-builder' );
		$this->define( 'APP_BUILDER_VERSION', $this->version );
		$this->define( 'APP_BUILDER_CIRILLA_VERSION', $this->cirilla_version );
		$this->define( 'APP_BUILDER_JS_VERSION', $this->js_version );
		$this->define( 'APP_BUILDER_CIRILLA_VERSION', $this->cirilla_version );
		$this->define( 'APP_BUILDER_CDN_JS', 'https://cdnjs.appcheap.io/' );
		$this->define( 'APP_BUILDER_API', 'https://us-central1-app-builder-82388.cloudfunctions.net/' );
		$this->define( 'APP_BUILDER_TOKEN_PARAM_NAME', 'app-builder-token' );
		$this->define( 'APP_BUILDER_DECODE', 'app-builder-decode' );
		$this->define( 'APP_BUILDER_CHECKOUT_BODY_CLASS', 'app-builder-checkout-body-class' );
		$this->define( 'APP_BUILDER_REST_BASE', 'app-builder' );
		$this->define( 'APP_BUILDER_SHOW_UI', false );
		$this->define( 'APP_BUILDER_CART_TABLE', 'app_builder_cart' );
		$this->define( 'APP_BUILDER_CAPABILITY', 'manage_options' );
		$this->define( 'FONTAWESOME_API_URL', 'https://api.fontawesome.com' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name Constant name.
	 * @param string|bool $value Constant value.
	 *
	 * @since 1.0.0
	 */
	private function define( string $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		register_activation_hook( APP_BUILDER_PLUGIN_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( APP_BUILDER_PLUGIN_FILE, array( $this, 'deactivate' ) );
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), - 1 );
		add_action( 'init', array( $this, 'create_gutenberg_blocks' ) );
	}

	public function create_gutenberg_blocks() {
		register_block_type( __DIR__ . '/blocks/build' );
	}

	/**
	 * The plugin loaded
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function plugins_loaded() {
		if ( is_admin() ) {
			new AppBuilder\Admin();
		} else {
			new AppBuilder\Frontend();
		}
		new AppBuilder\PostTypes();
		new AppBuilder\Api();
		new AppBuilder\I18n();
		new AppBuilder\Cart();
		new AppBuilder\Hook();
	}

	/**
	 *
	 * Returns instance Setting
	 *
	 * @return AppBuilder\Setting
	 */
	public function settings(): AppBuilder\Setting {
		if ( ! isset( $this->services['settings'] ) ) {
			$this->services['settings'] = new AppBuilder\Setting();
		}

		return $this->services['settings'];
	}

	/**
	 *
	 * Returns instance Addons
	 *
	 * @return AppBuilder\Addons
	 */
	public function addons(): AppBuilder\Addons {
		if ( ! isset( $this->services['addons'] ) ) {
			$this->services['addons'] = new AppBuilder\Addons();
		}

		return $this->services['addons'];
	}

	/**
	 * The plugin activation function.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function activate() {
		$activator = new AppBuilder\Activator();
		$activator->activate();
	}

	/**
	 * The plugin deactivation function.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function deactivate() {
		$deactivated = new AppBuilder\Deactivator();
		$deactivated->deactivate();
	}
}

/**
 * Returns the main instance of AppBuilder.
 *
 * @return AppBuilder
 * @since  1.0.0
 */
function appBuilder(): ?AppBuilder {
	return AppBuilder::instance();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
$GLOBALS['app_builder'] = appBuilder();
