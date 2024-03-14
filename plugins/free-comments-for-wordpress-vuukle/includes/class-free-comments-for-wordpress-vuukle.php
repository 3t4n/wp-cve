<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/includes
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      1.0.0
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
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/includes
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      1.0.0
 */
class Free_Comments_For_Wordpress_Vuukle {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Free_Comments_For_Wordpress_Vuukle_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Plugin all needed properties in one place
	 *
	 * @since  5.0
	 * @access protected
	 * @var    array $attributes The array containing main attributes of the plugin.
	 */
	protected $attributes;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the main variables that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @param string $plugin_version
	 * @param string $plugin_dir_path
	 * @param string $plugin_dir_url
	 *
	 * @since 1.0.0
	 *
	 * Free_Comments_For_Wordpress_Vuukle constructor.
	 *
	 */
	public function __construct( $plugin_version, $plugin_dir_path, $plugin_dir_url, $plugin_base_name ) {
		// Define main attributes
		$this->attributes = [
			'name'                     => 'free-comments-for-wordpress-vuukle',
			'base_name'                => $plugin_base_name,
			'class_prefix'             => 'class-free-comments-for-wordpress-vuukle-',
			'version'                  => $plugin_version,
			'settings_name'            => 'Vuukle',
			'app_id_setting_name'      => 'Vuukle_App_Id',
			'dir_path'                 => $plugin_dir_path,
			'dir_url'                  => $plugin_dir_url,
			'includes_dir_path'        => $plugin_dir_path . 'includes/',
			'includes_dir_url'         => $plugin_dir_url . 'includes/',
			'admin_dir_path'           => $plugin_dir_path . 'admin/',
			'admin_dir_url'            => $plugin_dir_url . 'admin/',
			'public_dir_path'          => $plugin_dir_path . 'public/',
			'public_dir_url'           => $plugin_dir_url . 'public/',
			'public_partials_dir_path' => $plugin_dir_path . 'public/partials/',
			'log_dir'                  => $plugin_dir_path . 'log/',
			'ajax_dir_path'            => $plugin_dir_path . 'ajax/',
			'upload_dir_path'          => wp_upload_dir()['basedir'] . '/free-comments-for-wordpress-vuukle/',
			'upload_dir_url'           => wp_upload_dir()['baseurl'] . '/free-comments-for-wordpress-vuukle/',
		];
		/**
		 * Load dependencies
		 * Set locale
		 * Initialize depending on the request
		 */
		$this->loadDependencies();
		$this->upgradeLiveCheck();
		$this->setLocale();
		$this->init();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Free_Comments_For_Wordpress_Vuukle_Helper. Some methods helping all over the project.
	 * - Free_Comments_For_Wordpress_Vuukle_Loader. Orchestrates the hooks of the plugin.
	 * - Free_Comments_For_Wordpress_Vuukle_i18n. Defines internationalization functionality.
	 * - Free_Comments_For_Wordpress_Vuukle_Admin. Defines all hooks for the admin area.
	 * - Free_Comments_For_Wordpress_Vuukle_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access private
	 *
	 */
	private function loadDependencies() {
		/**
		 * The class responsible for specifying some useful methods all over the project
		 * core plugin.
		 */
		include_once $this->attributes['includes_dir_path'] . $this->attributes['class_prefix'] . 'helper.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		include_once $this->attributes['includes_dir_path'] . $this->attributes['class_prefix'] . 'loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		include_once $this->attributes['includes_dir_path'] . $this->attributes['class_prefix'] . 'i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		include_once $this->attributes['admin_dir_path'] . $this->attributes['class_prefix'] . 'admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		include_once $this->attributes['public_dir_path'] . $this->attributes['class_prefix'] . 'public.php';
		/**
		 * The class responsible for defining all actions that occur in the ajax related side
		 */
		include_once $this->attributes['ajax_dir_path'] . $this->attributes['class_prefix'] . 'ajax.php';
		$this->loader = new Free_Comments_For_Wordpress_Vuukle_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Free_Comments_For_Wordpress_Vuukle_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access private
	 *
	 */
	private function setLocale() {
		$plugin_i18n = new Free_Comments_For_Wordpress_Vuukle_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Upgrade live check. Needed for checking vuukle app id separate field existence.
	 * As we were unable to run this single time, that is why this will run always
	 * TODO possible solution is 'upgrader_process_complete' hook. Worst point of this hook is that it is running on old plugin instead of new updated one
	 */
	private function upgradeLiveCheck() {
		Free_Comments_For_Wordpress_Vuukle_Helper::upgradeLiveCheck();
	}

	/**
	 * Checks the request type and initialize necessary hooks and classes
	 *
	 * @return void
	 * @since  5.0
	 * @access private
	 */
	private function init() {
		if ( Free_Comments_For_Wordpress_Vuukle_Helper::is_request( 'admin' ) ) {
			$this->defineAdminHooks();
		} elseif ( Free_Comments_For_Wordpress_Vuukle_Helper::is_request( 'ajax' ) ) {
			$this->defineAjaxHooks();
		} elseif ( Free_Comments_For_Wordpress_Vuukle_Helper::is_request( 'public' ) ) {
			$this->definePublicHooks();
		}
	}

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access private
	 *
	 */
	private function defineAdminHooks() {
		$plugin_admin = new Free_Comments_For_Wordpress_Vuukle_Admin( $this->attributes );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'startSession' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'checkOneMonthPassed' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'tryQuickRegister' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'adminMenu' );
		$this->loader->add_action( 'admin_post_vuukleEnableFunction', $plugin_admin, 'enableCoupleConfigs' );
		$this->loader->add_action( 'admin_post_vuukleDeactivateFunction', $plugin_admin, 'deactivateAction' );
		$this->loader->add_action( 'admin_post_vuukleSaveSettings', $plugin_admin, 'saveSettings' );
		$this->loader->add_action( 'admin_post_vuukleResetSettings', $plugin_admin, 'resetSettings' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'activationModal' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'deactivationModal' );
	}

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access private
	 *
	 */
	private function definePublicHooks() {
		$plugin_public = new Free_Comments_For_Wordpress_Vuukle_Public( $this->attributes );
		// Short code
		$this->loader->add_action( 'init', $plugin_public, 'generateShortcode' );
		// Styles/scripts include
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueueStyles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueueScripts' );
		// Unregister couple widgets
		$this->loader->add_action( 'widgets_init', $plugin_public, 'registerRecentCommentsWidget' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'addDns' );
		// Comments related filters
		$this->loader->add_filter( 'comments_open', $plugin_public, 'commentsOpen', 20, 2 );
		// This won't disable the section. For example the navigation will still present here
//		$this->loader->add_filter( 'wp_list_comments_args', $plugin_public, 'listCommentsArgs', 20 );
		$this->loader->add_action( 'wp_head', $plugin_public, 'hideComments' );
		$this->loader->add_filter( 'pings_open', $plugin_public, 'pingsOpen', 20, 2 );
		$this->loader->add_filter( 'the_content', $plugin_public, 'commentBox', 300 );
		// Create platform
		$this->loader->add_action( 'wp_footer', $plugin_public, 'createPlatform' );
		// Emote related filters
		$this->loader->add_filter( 'the_content', $plugin_public, 'addEmote', 20 );
		// Share bar widget
		$this->loader->add_filter( 'the_content', $plugin_public, 'addShareBar', 11 );
		// Track page view
		$this->loader->add_action( 'wp_footer', $plugin_public, 'track_page_view' );
	}

	/**
	 * Register all the hooks related to the ajax functionality
	 * of the plugin.
	 *
	 * @return void
	 * @since  1.0.0
	 * @access private
	 *
	 */
	private function defineAjaxHooks() {
		$plugin_ajax = new Free_Comments_For_Wordpress_Vuukle_Ajax( $this->attributes );
		$this->loader->add_action( 'wp_ajax_exportComments', $plugin_ajax, 'exportComments' );
		$this->loader->add_action( 'wp_ajax_saveCommentToDb', $plugin_ajax, 'saveCommentToDb' );
		$this->loader->add_action( 'wp_ajax_nopriv_saveCommentToDb', $plugin_ajax, 'saveCommentToDb' );
		$this->loader->add_action( 'wp_ajax_quickRegister', $plugin_ajax, 'quickRegister' );
		$this->loader->add_action( 'wp_ajax_nopriv_quickRegister', $plugin_ajax, 'quickRegister' );
	}

	/**
	 * Run the loader to execute all the hooks with WordPress.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return Free_Comments_For_Wordpress_Vuukle_Loader    Orchestrates the hooks of the plugin.
	 * @since  1.0.0
	 */
	public function getLoader() {
		return $this->loader;
	}
}
