<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intel
 * @subpackage Intel/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Intel
 * @subpackage Intel/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class Intel {

	/**
	 * Singleton container
	 *
	 */
	private static $instance;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Intel_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	public $created;

	public $admin;

	public $tracker;

	public $gapi;

	protected $page_title;

	public $q;

	public $request_time;

	public $time_delta;

	public $is_network_active;

  public $is_network_framework_mode;

	protected $js_inline = array();


	protected $js_settings = array();

	// whether or not url schema is https
	public $is_https;

	// domain of website, e.g. sub.example.com (no protocol)
	public $domain;

	// url to reach domain where CMS is installed, e.g. https://sub.example.com
	public $base_url;

	// https: version of base_url
	public $base_secure_url;

	// http: version of base_url
	public $base_insecure_url;

	// path after domain where the CMS is installed
	public $base_path;

	// path after domain where the admin pages are accessed
	public $base_path_admin;

	// path after domain where the front pages are accessed
	public $base_path_front;

	public $base_root;

	public $admin_url;

	public $admin_dir;

  public $is_network_admin;

	public $vtk;

	public $gacid;

	private $session_hash;

	public $system_meta;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->created = microtime(TRUE);

		$this->plugin_name = 'intel';
		$this->version = INTEL_VER;
		$this->includedLibraryFiles = array();
		$this->time_delta = get_option('intel_time_delta', 0);
		$this->request_time = $this->time();

		// setup globals
		$this->is_https = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';

		// Create base URL.
		$http_protocol = $this->is_https ? 'https' : 'http';
		$this->domain = $_SERVER['HTTP_HOST'];
		$this->base_root = $http_protocol . '://' . $_SERVER['HTTP_HOST'];

		$this->base_url = $this->base_root;

		$this->base_secure_url = str_replace('http://', 'https://', $this->base_url);
		$this->base_insecure_url = str_replace('https://', 'http://', $this->base_url);

		$this->home_url = home_url();
		$this->admin_url = admin_url();
		$this->admin_dir = ABSPATH . 'wp-admin/';

    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
      require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    $this->is_network_admin = 0;
    $this->is_network_active = is_plugin_active_for_network('intelligence/intel.php' );

    $this->is_network_framework_mode = FALSE;
    if ($this->is_network_active) {
      $this->is_network_framework_mode = get_site_option('intel_framework_mode', FALSE);
      $this->is_network_admin = is_network_admin();
    }

		// determine admin paths
		$this->base_path = '';
		$a = explode($this->domain, admin_url());
		if (isset($a[1])) {
			$this->base_path = $a[1];
		}
		if (substr($this->base_path, -1) != '/') {
			$this->base_path .= '/';
		}
		if (!$this->base_path || substr($this->base_path, -1) != '/') {
			$this->base_path .= '/';
		}
		$this->base_path_admin = $this->base_path;

		// process front paths
		$this->base_path_front = '';
		$a = explode($this->domain, $this->home_url);
		if (isset($a[1])) {
			$this->base_path_front = $a[1];
		}
		if (substr($this->base_path_front, -1) != '/') {
			$this->base_path_front .= '/';
		}

		if (!$this->base_path_front || substr($this->base_path_front , -1) != '/') {
			$this->base_path_front .= '/';
		}

		// initialize constants
		self::setup();

		require_once INTEL_DIR . 'includes/class-intel-tracker.php';

		$this->tracker = Intel_Tracker::getInstance();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_global_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		// check if updates needed
		$this->system_meta = get_option('intel_system_meta', array());
	}

	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new Intel();
			self::$instance->run();
		}
		return self::$instance;
	}

	public function setup() {
		// Plugin Path
		if ( ! defined( 'INTEL_DIR' ) ) {
			define( 'INTEL_DIR', plugin_dir_path( dirname( __FILE__ ) ) );
		}

		// Plugin URL
		if ( ! defined( 'INTEL_URL' ) ) {
			define( 'INTEL_URL', plugin_dir_url( dirname( __FILE__ ) ) );
		}

		// Plugin main File
		if ( ! defined( 'INTEL_FILE' ) ) {
			define( 'INTEL_FILE', dirname( __FILE__ ) );
		}

		if ( ! defined( 'REQUEST_TIME' ) ) {
			define( 'REQUEST_TIME', time());
		}

		// include core files
		/**
		 * Include core functions
		 */
		//require INTEL_DIR . 'includes/class-intel-df.php';
		require_once INTEL_DIR . 'intel.module.php';
		require_once INTEL_DIR . 'intel.df.php';
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Intel_Loader. Orchestrates the hooks of the plugin.
	 * - Intel_i18n. Defines internationalization functionality.
	 * - Intel_Admin. Defines all hooks for the admin area.
	 * - Intel_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-intel-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-intel-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-intel-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-intel-public.php';

		/**
		 * Crud super class
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-intel-entity-controller.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-intel-entity.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/intel.IntelEntityController.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/intel.IntelEntity.php';

		$this->loader = new Intel_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Intel_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Intel_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_global_hooks() {
		add_filter('intel_theme_info', 'intel_theme_info');
		add_filter('intel_theme_info', 'intel_df_theme_info');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->admin = $plugin_admin = new Intel_Admin( $this->get_plugin_name(), $this->get_version() );

		// testing intel_form init
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init');

		$this->loader->add_action( 'admin_init', $this, 'setup_role_caps');

		$this->loader->add_action( 'admin_init', $this, 'setup_cron');

		// on intel admin pages, buffer page output and create sessions
		if (self::is_intel_admin_page()) {
			$this->loader->add_action( 'admin_init', $plugin_admin, 'init_menu_routing' );

			// note there is no admin_enqueue_styles hook, so this is a hack to
			// enqueue_styles
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			// page buffer management hooks
			$this->loader->add_action( 'admin_init', $plugin_admin, 'ob_start' );
			$this->loader->add_action( 'admin_footer', $plugin_admin, 'ob_end' );

			// session management hooks
			$this->loader->add_action( 'admin_init', $plugin_admin, 'session_start' );
			$this->loader->add_action( 'wp_login', $plugin_admin, 'session_end' );
			$this->loader->add_action( 'wp_logout', $plugin_admin, 'session_end' );

		}

		// site menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'site_menu' );

		$this->is_network_active;

		if ($this->is_network_active) {
      $this->loader->add_action( 'network_admin_menu', $plugin_admin, 'network_site_menu' );
    }

		// column headers
		$this->loader->add_filter('manage_intelligence_page_intel_visitor_columns', $plugin_admin, 'contacts_column_headers');

		// admin notices
  	$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );

		// plugin action links
		$this->loader->add_action( 'plugin_action_links_' . plugin_basename(INTEL_DIR . 'intel.php'), $plugin_admin, 'plugin_action_links', 10, 2 );

		// add js_settings processing
		//add_action( 'admin_footer', array( $this, 'process_js_footer' ), 10 );

		// admin notices
		$this->loader->add_action( 'activated_plugin', $plugin_admin, 'activated_plugin' );


		// add tracker processing
		add_action( 'admin_head', array( $this->tracker, 'tracking_admin_head' ), 10 );
		add_action( 'admin_footer', array( $this->tracker, 'tracking_admin_footer' ), 99 );
	}

	public function setup_cron() {
		// setup intel_cron_hook
		$timestamp = wp_next_scheduled('intel_cron_hook');
		if ($timestamp == FALSE) {
			wp_schedule_event(time(), 'intel_cron_interval', 'intel_cron_hook');
		}

		// setup intel_cron_queue_hook
		$timestamp = wp_next_scheduled('intel_cron_queue_hook');
		if ($timestamp == FALSE) {
			wp_schedule_event(time(), 'intel_cron_queue_interval', 'intel_cron_queue_hook');
		}
	}

	public function is_intel_admin_page() {
		$flag = 0;
		$pages = array(
			'intel_admin' => 1,
			'intel_reports' => 1,
			'intel_visitor' => 1,
			'intel_annotation' => 1,
			'intel_config' => 1,
			'intel_util' => 1,
			'intel_help' => 1,
		);
		if (is_admin() && !empty($_GET['page']) && !empty($pages[$_GET['page']])) {
			$flag = 1;
		}
		$flag = apply_filters('is_intel_admin_page_alter', $flag);
		return $flag;
	}

	/**
	 * Assigns capacity to roles based on hook_permissions
	 */
	public function setup_role_caps() {
		$roles = array();
		$permissions = intel_permission();

		foreach ($permissions as $perm_name => $info) {
			if (!isset($info['roles'])) {
				$info['roles'] = array();
			}
			if (!in_array('administrator', $info['roles'])) {
				$info['roles'][] = 'administrator';
			}
			foreach ($info['roles'] as $role_name) {
				if (!isset($roles[$role_name])) {
					$roles[$role_name] = get_role($role_name);
				}
				$roles[$role_name]->add_cap($perm_name);
			}
		}
	}



	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Intel_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $this, 'setup_cron');

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// add tracker processing
		add_action( 'wp_head', array( $this->tracker, 'tracking_head' ), 10 );
		add_action( 'wp_footer', array( $this->tracker, 'tracking_footer' ), 99 );

		$this->loader->add_action( 'init', $this, 'quick_session_init' );
		$this->loader->add_filter( 'wp_redirect', $this, 'wp_redirect_quick_session_cache', 10, 2 );

		//$this->loader->add_action( 'wp_head', $plugin_public, 'admin_bar_menu_styles' );

		$this->loader->add_action( 'admin_bar_menu', $plugin_public, 'admin_bar_menu', 100);



		//$this->loader->add_action( 'wp_footer', $this, 'process_js_settings' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Intel_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function is_debug() {
		return !empty($_GET['debug']);
	}

	public function quick_session_init($options = array()) {
		include_once INTEL_DIR . 'includes/intel.IntelVisitor.php';

		$_SESSION['intel'] = !empty($_SESSION['intel']) ? $_SESSION['intel'] : array();

		$this->vtk = IntelVisitor::extractVtk();

		$this->gacid = IntelVisitor::extractCid();

		$this->session_hash = !empty($this->vtk) ? $this->vtk : $this->gacid;

//Intel_Df::watchdog('quick_session_init() hash', $this->session_hash);
		if (!empty($this->session_hash)) {

			$this->session_hash = substr($this->session_hash, 0, 20);
			$cache = get_transient('intel_session_' . $this->session_hash);

//Intel_Df::watchdog('quick_session_init() cache', print_r($cache, 1));
			if ($cache !== FALSE) {
				$_SESSION['intel'] = $cache;
				delete_transient('intel_session_' . $this->session_hash);
			}
		}

		if (!empty($_SESSION['intel_quick_cache'])) {
			$_SESSION['intel_quick_cache'] = array();
		}
	}

	public function quick_session_cache() {
//Intel_Df::watchdog('quick_session_cache() hash', $this->session_hash);
		if (empty($this->session_hash)) {
			return;
		}

		// check if cache already has values
		//$cache = get_transient('intel_session_' . $this->session_hash);
		$cache = isset($_SESSION['intel_quick_cache']) ? $_SESSION['intel_quick_cache'] : array();

		if (!empty($_SESSION['intel_pushes']) && is_array($_SESSION['intel_pushes'])) {
			if (!empty($cache)) {
				$cache = Intel_Df::drupal_array_merge_deep($cache, $_SESSION['intel_pushes']);
			}
			else {
				$cache = $_SESSION['intel_pushes'];
			}
		}

//Intel_Df::watchdog('quick_session_cache() cache', print_r($cache, 1));
		if (!empty($cache)) {
			// save data that saved to quick_cache incase second call is made
			$_SESSION['intel_quick_cache'] = $cache;
			set_transient('intel_session_' . $this->session_hash, $cache, 1800);
		}
	}

	public function wp_redirect_quick_session_cache($location, $status) {
//Intel_Df::watchdog('wp_redirect_quick_session_cache() location', $location);
		$this->quick_session_cache();
		return $location;
	}

	// TODO
	public function time() {
		return time() + $this->time_delta;
	}

	public function request_time() {
		return $this->request_time;
	}

	public function set_page_title($title) {
		$this->page_title = $title;
	}

	public function get_page_title() {
		return $this->page_title;
	}

	public function add_js($data = NULL, $options = array()) {
		$script_count = &Intel_Df::drupal_static(__FUNCTION__, 0);
		if (is_string($options)) {
			$options = array(
				'type' => $options,
			);
		}
		$options += array(
			'type' => 'file',
			'group' => 0,
			'every_page' => FALSE,
			'weight' => 0,
			'requires_jquery' => TRUE,
			'scope' => 'header',
			'cache' => TRUE,
			'defer' => FALSE,
			'preprocess' => TRUE,
			'version' => NULL,
			'data' => $data,
			'name' => 'intel-' . $script_count,
		);
		if ($options['type'] == 'file') {
			wp_enqueue_script($options['name'], $data);
		}
		elseif ($options['type'] == 'external') {
			wp_enqueue_script($options['name'], $data);
		}
		elseif ($options['type'] == 'inline') {
			$this->js_inline[] = $options;
			//wp_add_inline_script($options['name'], $options['data']);
		}
		elseif ($options['type'] == 'setting') {
			$this->js_settings = array_merge_recursive ( $data , $this->js_settings);
			$script_count--;
		}
		$script_count++;
	}

	public function get_js_settings() {
		return $this->js_settings;
	}

	public function process_js_header() {

		$js_settings = $this->get_js_settings();

		print "<script>var wp_intel = wp_intel || {}; wp_intel.settings = " . json_encode($js_settings) . "</script>;\n";

		foreach ($this->js_inline as $js) {
			if ($js['scope'] == 'header' ) {
				print $js['data'] . "\n";
			}
		}
	}

	public function process_js_settings() {
		print "<!-- intel js settings start ->\n";
		$js_settings = $this->get_js_settings();

		$script = '<script>';
		$script .= "<script>var wp_intel_settings = " . json_encode($js_settings) . "</script>;\n";
		print "<!-- intel js settings end ->\n";
	}

	public function get_js_settings_json() {
		$settings = array(
			'intel_settings' => $this->js_settings,
		);
		return json_encode($settings);
	}

	public function libraries_get_path($name) {
		$name = strtolower($name);
		if ($name == 'levelten') {
			return INTEL_DIR . 'vendor/levelten/';
		}
		elseif ($name == 'timeline') {
			return INTEL_DIR . 'vendor/TimelineJS/';
		}
	}

	// alias of get_entity_controller
	public function entity_get_controller($entity_type) {
		return get_entity_controller($entity_type);
	}

	public function get_entity_controller($entity_type) {
		$files = &Intel_Df::drupal_static(__FUNCTION__, array());
		$entity_info = self::entity_info();

		if (empty($entity_info[$entity_type])) {
			return FALSE;
		}
		$info = $entity_info[$entity_type];

		// include required files
		if (!empty($info['file'])) {
			if (is_array($info['file'])) {
				foreach ($info['file'] as $file) {
					if (empty($files[$file])) {
						include_once INTEL_DIR . $file;
						$files[$file] = 1;
					}
				}
			}
		}

		if (!empty($info['controller class']) && class_exists($info['controller class'])) {
			$controller_class = $info['controller class'];
		}
		else {
			$controller_class = 'Intel_Entity_Controller';
		}

		return new $controller_class($entity_type, $info);
	}


	public function build_info($type) {
		static $infos = array();
		if (!isset($infos[$type])) {

			$infos[$type] = array();

			// implement hook_TYPE_info to enable plugins to add info data
			$infos[$type] = apply_filters('intel_' . $type .'_info', $infos[$type]);

			// implement hook_TYPE_info_alter to allow plugins to alter info
			$infos[$type] = apply_filters('intel_' . $type .'_info_alter', $infos[$type]);
		}
		return $infos[$type];
	}

	public function system_info($name = NULL) {
		$info = self::build_info('system');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : NULL;
	}

	public function addon_info($name = NULL) {
		$info = self::build_info('addon');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : NULL;
	}

	public function intel_script_info($name = NULL) {
		$info = self::build_info('intel_script');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : NULL;
	}

	public function cron_queue_info($name = NULL) {
		$info = self::build_info('cron_queue');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : NULL;
	}

	/**
	 * Provides visitor_property_info
	 */
	public function element_info($name = NULL) {
		$info = self::build_info('element');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : array();
	}

	public function entity_info($name = NULL) {
  	$info = self::build_info('entity');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : array();
	}

	/**
	 * Provides menu_info
	 */
	public function menu_info() {
		$menu_info = &Intel_Df::drupal_static(__FUNCTION__, array());

		if (!empty($menu_info)) {
			return $menu_info;
		}

		// allow plugins to add menu_info
		$menu_info = apply_filters('intel_menu_info', $menu_info);

		// allow plugins to alter menu_info
		$menu_info = apply_filters('intel_menu_info_alter', $menu_info);

		$i = 0;
		foreach ($menu_info as $k => $v) {
			$menu_info[$k]['key'] = $k;
			$menu_info[$k]['key_args'] = explode('/', $k);
			$menu_info[$k]['key_args_count'] = count($menu_info[$k]['key_args']);
			$menu_info[$k]['_index'] = $i++;
		}

		return $menu_info;
	}

	/**
	 * Provides theme_info
	 */
	public function theme_info() {
		$theme_info = &Intel_Df::drupal_static(__FUNCTION__, array());

		if (!empty($theme_info)) {
			return $theme_info;
		}

		// allow plugins to add theme_info
		$theme_info = apply_filters('intel_theme_info', $theme_info);

		// allow plugins to alter theme_info
		$theme_info = apply_filters('intel_theme_info_alter', $theme_info);

		return $theme_info;
	}

	/**
	 * Provides visitor_property_info
	 */
	public function visitor_property_info($name = NULL) {
		static $info;
		if (!isset($info)) {
			$info = self::build_info('visitor_property');
			// set defaults
			foreach ($info as $k => $v) {
				if (!isset($v['variables'])) {
					$info[$k]['variables'] = array(
						'@value' => NULL,
					);
				}
			}
		}

		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : NULL;
	}

	public function visitor_property_webform_info($name = NULL) {
		$info = self::build_info('visitor_property_webform');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : array();
	}

	public function plugin_path_info($name = NULL) {
		$info = self::build_info('plugin_path');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : NULL;
	}

	public function intel_event_info($name = NULL, $options = array()) {
		// requires additional processing, so standard build_info not used
		$info = intel_get_intel_event_info();
		return !empty($info[$name]) ? $info[$name] : NULL;
	}

	public function form_type_info($name = NULL) {
		$info = self::build_info('form_type');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : NULL;
	}

	public function form_type_form_info($form_type = NULL, $name = NULL) {
		global $wp_filter;
		$form_type_info = self::form_type_info($form_type);
		$info = array();
		foreach ($form_type_info as $ft => $v) {
			$info[$ft] = self::build_info('form_type_' . $ft . '_form');
		}

		if (!isset($form_type)) {
			return $info;
		}
		return !empty($info[$form_type]) ? $info[$form_type] : NULL;
	}

	// deprecated
	public function form_type_forms_info($name = NULL) {
		$info = self::build_info('form_type_forms');
		if (!isset($name)) {
			return $info;
		}
		return !empty($info[$name]) ? $info[$name] : NULL;
	}

	/**
	 * Generates tracking code
	 */
	public function tracking_code() {
		$ga_tid = get_option( 'intel_ga_tid' );
		require_once INTEL_DIR . 'public/partials/intel-tracking-code.php';
	}


}
