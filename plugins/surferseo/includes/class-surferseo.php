<?php
/**
 *  Main object to controll plugin.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO;

use SurferSEO\Autoloader;
use SurferSEO\Seo_Manager;
use SurferSEO\Surfer\Surfer;
use SurferSEO\Admin\Surfer_Settings;
use SurferSEO\Admin\Surfer_Admin;
use SurferSEO\Surfer\Surfer_Tracking;
use SurferSEO\Surfer\Surfer_Sidebar;

/**
 * General object to controll plugin.
 */
class Surferseo {

	/**
	 * Object Singleton
	 *
	 * @var Surferseo
	 */
	protected static $instance = null;

	/**
	 * Current version of the plugin
	 *
	 * @var string
	 */
	public $version = null;

	/**
	 * Basedir to the plugin (example: public_html/wp-content/plugins/surferseo/src/)
	 *
	 * @var string
	 */
	protected $basedir = null;

	/**
	 * URL to the plugin (example: https://example.com/wp-content/plugins/surferseo/src/)
	 *
	 * @var string
	 */
	protected $baseurl = null;

	/**
	 * Object that contain all Surfer features.
	 *
	 * @var Surfer
	 */
	protected $surfer = null;

	/**
	 * Object that contain wp-admin functions.
	 *
	 * @var Surfer_Admin
	 */
	protected $surfer_admin = null;

	/**
	 * Object to manage Surfer forms.
	 *
	 * @var Surfer_Forms
	 */
	protected $surfer_forms = null;

	/**
	 * Contains configuration.
	 *
	 * @var Surfer_Settings
	 */
	protected $surfer_settings = null;

	/**
	 * Contains things related to SEO but not connected to Surfer directly.
	 *
	 * @var Seo_Manager
	 */
	protected $seo_manager = null;

	/**
	 * Class to handle PHP files auto load.
	 *
	 * @var Autoloader
	 */
	protected $autoloader = null;

	/**
	 * Class to hangle all integrations.
	 *
	 * @var Surfer_Tracking
	 */
	protected $surfer_tracking = null;

	/**
	 * Class to hangle all integrations.
	 *
	 * @var Surfer_Sidebar
	 */
	protected $surfer_sidebar = null;

	/**
	 * URL to WPSurfer documentation page.
	 *
	 * @var string
	 */
	public $url_wpsurfer_docs = 'https://docs.surferseo.com/en/collections/3548643-wordpress-plugin';

	/**
	 * URL to Surfer contact page.
	 *
	 * @var string
	 */
	public $url_wpsurfer_support = 'https://surferseo.com/contact/';

	/**
	 * Object constructor.
	 */
	protected function __construct() {

		$this->basedir = dirname( __DIR__ );
		$this->baseurl = plugin_dir_url( __DIR__ );

		$this->version = SURFER_VERSION;

		$this->init_hooks();

		add_action( 'init', array( $this, 'register_surfer_backup_status' ) );

		add_filter( 'plugin_action_links_surferseo/surferseo.php', array( $this, 'add_actions_links' ) );

		add_filter( 'safe_style_css', array( $this, 'allow_display' ) );
		add_filter( 'cron_schedules', array( $this, 'add_monthly_schedule' ) );

		$this->make_imports();
	}

	/**
	 * Singleton
	 *
	 * Creates if NULL and returns Surferseo instance.
	 *
	 * @return Surferseo
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Returns plugin basedir.
	 *
	 * @return string
	 */
	public function get_basedir() {
		return $this->basedir;
	}

	/**
	 * Returns plugin base url.
	 *
	 * @return string
	 */
	public function get_baseurl() {
		return $this->baseurl;
	}

	/**
	 * Returns general surfer object.
	 *
	 * @return Surfer
	 */
	public function get_surfer() {
		return $this->surfer;
	}

	/**
	 * Returns object that manage forms.
	 *
	 * @return Surfer_Forms
	 */
	public function get_surfer_forms() {
		return $this->surfer_forms;
	}

	/**
	 * Returns object that manage settings
	 *
	 * @return Surfer_Settings
	 */
	public function get_surfer_settings() {
		return $this->surfer_settings;
	}

	/**
	 * Returns object that manage SEO related things not connected to Surfer.
	 *
	 * @return Seo_Manager
	 */
	public function get_seo_manager() {
		return $this->seo_manager;
	}

	/**
	 * Returns object that handle tracking features
	 *
	 * @return Surfer_Tracking;
	 */
	public function get_surfer_tracking() {
		return $this->surfer_tracking;
	}

	/**
	 * Instalation hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {

		require_once $this->basedir . '/includes/class-surfer-installer.php';
		$installer = new Surfer_Installer();

		register_activation_hook( SURFER_PLUGIN_FILE, array( $installer, 'install' ) );

		add_action( 'upgrader_process_complete', array( $installer, 'surfer_upgrade_completed' ), 10, 2 );
	}

	/**
	 * Register new post status, to allow to store backup copies of posts imported from Surfer.
	 *
	 * @return void
	 */
	public function register_surfer_backup_status() {

		register_post_status(
			'surfer-backup',
			array(
				'label'                     => _x( 'Surfer Backup', 'post', 'surferseo' ),
				'public'                    => false,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => false,
				'show_in_admin_status_list' => true,
				/* translators: %s - number */
				'label_count'               => _n_noop( 'Surfer Backup <span class="count">(%s)</span>', 'Surfer Backups <span class="count">(%s)</span>', 'surferseo' ),
			)
		);
	}

	/**
	 * Adds links in wp-admin -> Plugins page.
	 *
	 * @param array $actions - default actions.
	 * @return array
	 */
	public function add_actions_links( $actions ) {

		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=surfer' ) . '">' . __( 'Settings', 'surferseo' ) . '</a>',
			'<a href="' . $this->url_wpsurfer_support . '" target="_blank">' . __( 'Support', 'surferseo' ) . '</a>',
		);

		$actions = array_merge( $actions, $mylinks );
		return $actions;
	}

	/**
	 * Loads textdomain for translation.
	 *
	 * @return void
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'surferseo', false, plugin_basename( __DIR__ ) );
	}

	/**
	 * Function that includes all required classes.
	 *
	 * @return void
	 */
	private function make_imports() {

		$this->import_general_imports();

		if ( is_admin() ) {
			$this->import_admin_imports();
		} else {
			$this->import_frontend_imports();
		}
	}

	/**
	 * Makes general imports for the plugin.
	 */
	private function import_general_imports() {

		require_once $this->basedir . '/includes/functions.php';
		require_once $this->basedir . '/includes/class-autoloader.php';
		$this->autoloader = new Autoloader();

		$this->surfer_tracking = new Surfer_Tracking();
		$this->surfer_settings = new Surfer_Settings();
		$this->surfer          = new Surfer();
		$this->seo_manager     = new Seo_Manager();
		$this->surfer_sidebar  = new Surfer_Sidebar();
	}

	/**
	 * Makes imports related to wp-admin section.
	 */
	private function import_admin_imports() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		$this->surfer_admin = new Surfer_Admin();
	}

	/**
	 * Includes styles and scripts in wp-admin
	 *
	 * @param string $hook - page where code is executed.
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook ) {

		wp_enqueue_style( 'surfer-components', $this->baseurl . 'assets/css/components.css', array(), SURFER_VERSION );
		wp_enqueue_style( 'surfer-admin', $this->baseurl . 'assets/css/admin.css', array( 'surfer-components' ), SURFER_VERSION );
		wp_enqueue_style( 'surfer-styles', $this->baseurl . 'assets/css/surferseo.css', array( 'surfer-components' ), SURFER_VERSION );
	}

	/**
	 * Makes imports related to front-end.
	 */
	private function import_frontend_imports() {
	}

	/**
	 * Allow to use display style in wp_kses.
	 *
	 * @param array $styles - array of safe styles.
	 * @return array
	 */
	public function allow_display( $styles ) {

		$styles[] = 'display';
		return $styles;
	}

	/**
	 * Adds monthly schedule to WP Cron.
	 *
	 * @param array $schedules - array of schedules.
	 * @return array
	 */
	public function add_monthly_schedule( $schedules ) {

		if ( ! isset( $schedules['monthly'] ) ) {
			$schedules['monthly'] = array(
				'interval' => 30 * DAY_IN_SECONDS,
				'display'  => __( 'Monthly', 'surferseo' ),
			);
		}

		return $schedules;
	}
}
