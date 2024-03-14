<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://about.me/bharatkambariya
 * @since      2.1.0
 *
 * @package    Donations_Block
 * @subpackage Donations_Block/includes
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
 * @since      2.1.0
 * @package    Donations_Block
 * @subpackage Donations_Block/includes
 * @author     bharatkambariya <bharatkambariya@gmail.com>
 */
class Donations_Block {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.1.0
	 * @access   protected
	 * @var      Donations_Block_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    2.1.0
	 */

    /**
     * Initialize the plugin public actions.
     */
    public $paypal_doantion_table_name;

	public function __construct() {
        global $wpdb;

		if ( defined( 'DONATIONS_BLOCK_VERSION' ) ) {
			$this->version = DONATIONS_BLOCK_VERSION;
		} else {
			$this->version = '2.1.0';
		}
		$this->plugin_name = 'donations-block';

        $this->paypal_doantion_table_name = $wpdb->prefix . 'pdb_paypal_doantion_block';
        if(!($wpdb->get_var("SHOW TABLES LIKE '".$this->paypal_doantion_table_name."'") == $this->paypal_doantion_table_name)) {
            $this->create_table();
        }

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

    /**
     * Create table function for create pdb_load_plugin_textdomain table in DB.
     */
    public function create_table() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->paypal_doantion_table_name} (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                donner_name varchar(255) DEFAULT NULL,
                donner_email varchar(255) DEFAULT NULL,
                donner_phone varchar(255) DEFAULT NULL,
                transection_id varchar(255) DEFAULT NULL,
                donation_amount int(11) DEFAULT 0,
                donation_currency varchar(255) DEFAULT NULL,
                donation_purpose varchar(255) DEFAULT NULL,
                transection_status varchar(255) DEFAULT 'Pending',
                created_at_time timestamp DEFAULT current_timestamp,
				PRIMARY KEY  (id)
				) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Donations_Block_Loader. Orchestrates the hooks of the plugin.
	 * - Donations_Block_i18n. Defines internationalization functionality.
	 * - Donations_Block_Admin. Defines all hooks for the admin area.
	 * - Donations_Block_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-donations-block-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-donations-block-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-donations-block-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-donations-block-public.php';

		$this->loader = new Donations_Block_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Donations_Block_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Donations_Block_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Donations_Block_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $plugin_admin, 'add_admin_shortcode' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'pdb_create_pages' );
//        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_my_setting' );
//        $this->loader->add_action( 'admin_menu', $plugin_admin, 'wpdocs_register_my_custom_menu_page' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Donations_Block_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.1.0
	 * @return    Donations_Block_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
