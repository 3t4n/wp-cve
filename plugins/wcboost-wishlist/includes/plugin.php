<?php
namespace WCBoost\Wishlist;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin main class
 */
final class Plugin {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = '1.0.10';

	/**
	 * Query instance.
	 *
	 * @var \WCBoost\Wishlist\Query
	 */
	public $query;

	/**
	 * The single instance of the class.
	 *
	 * @var \WCBoost\Wishlist\Plugin
	 */
	protected static $_instance = null;

	/**
	 * Main instance. Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @static
	 * @return \WCBoost\Wishlist\Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'wcboost-wishlist' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'wcboost-wishlist' ), '1.0.0' );
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->includes();
		$this->init();
	}

	/**
	 * Plugin URL getter.
	 *
	 * @return string
	 */
	public function plugin_url( $path = '/' ) {
		return untrailingslashit( plugins_url( $path, dirname( __FILE__ ) ) );

	}

	/**
	 * Plugin path getter.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) );
	}

	/**
	 * Plugin base name
	 *
	 * @return string
	 */
	public function plugin_basename() {
		return dirname( plugin_basename( __FILE__ ), 2 ) . '/wcboost-wishlist.php';
	}

	/**
	 * Load files
	 *
	 * @return void
	 */
	protected function includes() {
		include_once 'helper.php';
		include_once 'query.php';
		include_once 'action-scheduler.php';
		include_once 'form-handler.php';
		include_once 'ajax-handler.php';
		include_once 'frontend.php';
		include_once 'shortcodes.php';
		include_once 'compatibility.php';
		include_once 'wishlist.php';
		include_once 'wishlist-item.php';
		include_once 'data-stores/wishlist.php';
		include_once 'data-stores/wishlist-item.php';
		include_once 'customizer/customizer.php';
		include_once 'widgets/wishlist.php';
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	protected function init() {
		$this->query = new Query();

		Install::init();
		Action_Scheduler::init();
		Shortcodes::init();
		Form_Handler::init();
		Ajax_Handler::init();

		Customize\Customizer::instance();
		Frontend::instance();

		$this->init_hooks();
	}

	/**
	 * Core hooks to run the plugin
	 */
	protected function init_hooks() {
		add_action( 'init', [ $this, 'load_translation' ] );
		add_action( 'switch_blog', [ $this, 'define_tables' ], 0 );

		add_filter( 'woocommerce_data_stores', [ $this, 'register_data_stores' ] );
		add_filter( 'woocommerce_get_wishlist_page_id', [ $this, 'wishlist_page_id' ] );

		add_filter( 'woocommerce_get_settings_pages', [ $this, 'setting_page' ] );

		add_action( 'widgets_init', [ $this, 'register_widgets' ] );
	}

	/**
	 * Load textdomain.
	 */
	public function load_translation() {
		load_plugin_textdomain( 'wcboost-wishlist', false, dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/' );
	}

	/**
	 * Register custom tables within $wpdb object.
	 */
	public function define_tables() {
		global $wpdb;

		// List of tables without prefixes.
		$tables = [
			'wishlists'      => 'wcboost_wishlists',
			'wishlist_items' => 'wcboost_wishlist_items',
		];

		foreach ( $tables as $name => $table ) {
			$wpdb->$name    = $wpdb->prefix . $table;
			$wpdb->tables[] = $table;
		}
	}

	/**
	 * Register custom plugin Data Stores classes
	 *
	 * @param array $data_stores
	 * @return array
	 */
	public function register_data_stores( $data_stores ) {
		$data_stores['wcboost_wishlist']      = '\WCBoost\Wishlist\DataStore\Wishlist';
		$data_stores['wcboost_wishlist_item'] = '\WCBoost\Wishlist\DataStore\Wishlist_Item';

		return $data_stores;
	}

	/**
	 * Get the wishlist page id
	 *
	 * @return int
	 */
	public function wishlist_page_id() {
		$page_id = get_option( 'wcboost_wishlist_page_id' );

		return $page_id;
	}

	/**
	 * Add new setting page to WooCommerce > Settings
	 *
	 * @param array $pages
	 * @return array
	 */
	public function setting_page( $pages ) {
		include_once 'admin/settings.php';

		$pages[] = new Settings();

		return $pages;
	}

	public function register_widgets() {
		register_widget( '\WCBoost\Wishlist\Widget\Wishlist' );
	}
}
