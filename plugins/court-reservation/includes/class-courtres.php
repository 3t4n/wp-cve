<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://webmuehle.at
 * @since      1.0.3
 *
 * @package    Courtres
 * @subpackage Courtres/includes
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
 * @since      1.0.3
 * @package    Courtres
 * @subpackage Courtres/includes
 * @author     Webmühle e.U. <office@webmuehle.at>
 */
class Courtres {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.3
	 * @access   protected
	 * @var      Courtres_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.3
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.3
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The version of assets of this plugin.
	 *
	 * @since    1.5.1
	 * @var      string    $version    The current version of this plugin.
	 */
	private $assets_version;

	/**
	 * Default Settings of the plugin.
	 *
	 * @since    1.5.1
	 * @access   protected
	 * @var      array    $default_settings    Default Settings of the plugin.
	 */
	private static $default_settings = array(
		'email_template' => array(
			'old'   => 'Confirmation of the reservation of [court_name] on [date_on] at [hours_from_till] for [player_name_creator], [player_name_1], [player_name_2], [player_name_3], [player_name_4].',
			'1.5.1' => 'Confirmation of the reservation of [court_name] on [date_on] at [hours_from_till] for [player_name_creator] and [players_list]',
		),
	);

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.3
	 */
	public function __construct() {
		if ( defined( 'Court_Reservation' ) ) {
			$this->version = Court_Reservation;
		} else {
			$this->version = '1.5.1';
		}
		$this->plugin_name    = 'courtres';
		$this->assets_version = $this->version . '.01';

		$this->load_dependencies();
		$this->set_locale();
		$this->check_version();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_query_vars();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Courtres_Loader. Orchestrates the hooks of the plugin.
	 * - Courtres_i18n. Defines internationalization functionality.
	 * - Courtres_Admin. Defines all hooks for the admin area.
	 * - Courtres_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.3
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-courtres-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-courtres-i18n.php';

		/**
		 * The class responsible for defining notification (mailing) functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-courtres-notices.php';

		/**
		 * The class responsible for load defaults by update
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-courtres-activator.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-courtres-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-courtres-public.php';

		/**
		 * The class responsible for defining all actions that occur in the piramids public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-piramids-public.php';

		$this->loader = new Courtres_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Courtres_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.3
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Courtres_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Courtres_Admin( $this->get_plugin_name(), $this->get_version() );

		// 28.01.2019, astoian
		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
		}
		// 27.01.2019, astoian
		if ( ! class_exists( 'WP_Users_List_Table' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php';
		}

		// 17.01.2019, astoian
		$this->loader->add_action( 'admin_post_add_court', $plugin_admin, 'isCourtsAddRedirect' );
		$this->loader->add_action( 'admin_post_add_piramid', $plugin_admin, 'isPiramidsAddRedirect' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_post_add_reservation', $plugin_admin, 'add_reservation' );
		// Enable the user with no privileges to run ajax_login() in AJAX
		$this->loader->add_action( 'wp_ajax_nopriv_ajax_login', $plugin_admin, 'ajax_login' );
		// $this->loader->add_action( 'wp_ajax_ajax_login', $plugin_admin, 'ajax_login' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_page' );

		// add reservation type in settings tab
		$this->loader->add_action( 'wp_ajax_edit_reservation_type', $plugin_admin, 'edit_reservation_type' );
		$this->loader->add_action( 'wp_ajax_nopriv_edit_reservation_type', $plugin_admin, 'edit_reservation_type' );

				$this->loader->add_action( 'admin_post_get_players_select_options', $plugin_admin, 'get_players_select_options' );
		$this->loader->add_action( 'admin_post_nopriv_get_players_select_options', $plugin_admin, 'get_players_select_options' ); // for non-autorized users

		// export expired reservations to csv
		$this->loader->add_action( 'wp_ajax_download_csv', $plugin_admin, 'download_csv' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Courtres_Public( $this->get_plugin_name(), $this->get_version() );

		if ( ! session_id() ) {
			session_start();
		}
		session_write_close();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_shortcode( 'courtreservation', $plugin_public, 'public_shortcode' );
		$this->loader->add_shortcode( 'courtreservation-full-view', $plugin_public, 'public_shortcode_full_view' );

		$this->loader->add_action( 'wp_ajax_ajax_cr_navigator', $plugin_public, 'ajax_cr_navigator' );
		$this->loader->add_action( 'wp_ajax_ajax_cr_navigator2', $plugin_public, 'ajax_cr_navigator2' );
		$this->loader->add_action( 'wp_ajax_ajax_cr_navigator_full_view', $plugin_public, 'ajax_cr_navigator_full_view' );
		$this->loader->add_action( 'wp_ajax_ajax_cr_navigator_calendar', $plugin_public, 'ajax_cr_navigator_calendar' );
		$this->loader->add_action( 'wp_ajax_nopriv_ajax_cr_navigator', $plugin_public, 'ajax_cr_navigator' );

		// get_player_select_html in add reservation popup
		$this->loader->add_action( 'wp_ajax_get_more_rows_html', $plugin_public, 'get_more_rows_html' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_more_rows_html', $plugin_public, 'get_more_rows_html' );

		// Work with piramids
		$plugin_piramid_public = new Piramids_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( 'courtpyramid', $plugin_piramid_public, 'public_shortcode_courtpyramid' );
		$this->loader->add_shortcode( 'courtchallenges', $plugin_piramid_public, 'public_shortcode_courtchallenges' );

		$this->loader->add_action( 'wp_ajax_create_challenge', $plugin_piramid_public, 'create_challenge' );
		$this->loader->add_action( 'wp_ajax_nopriv_create_challenge', $plugin_piramid_public, 'accept_challenge' );

		$this->loader->add_action( 'wp_ajax_accept_challenge', $plugin_piramid_public, 'accept_challenge' );
		$this->loader->add_action( 'wp_ajax_nopriv_accept_challenge', $plugin_piramid_public, 'accept_challenge' );

		$this->loader->add_action( 'wp_ajax_get_court', $plugin_public, 'ajax_get_court' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_court', $plugin_public, 'ajax_get_court' );

		$this->loader->add_action( 'wp_ajax_schedule_challenge', $plugin_piramid_public, 'schedule_challenge' );
		$this->loader->add_action( 'wp_ajax_nopriv_schedule_challenge', $plugin_piramid_public, 'schedule_challenge' );

		$this->loader->add_action( 'wp_ajax_delete_challenge', $plugin_piramid_public, 'delete_challenge' );
		$this->loader->add_action( 'wp_ajax_nopriv_delete_challenge', $plugin_piramid_public, 'delete_challenge' );

		$this->loader->add_action( 'wp_ajax_enter_challenge_result', $plugin_piramid_public, 'enter_challenge_result' );
		$this->loader->add_action( 'wp_ajax_nopriv_enter_challenge_result', $plugin_piramid_public, 'enter_challenge_result' );

		$this->loader->add_action( 'template_redirect', $plugin_piramid_public, 'accept_challenge_by_email_link', 1 );

		// Work with email notifications
		$plugin_public_notices = new Courtres_Notices( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'after_challenge_created', $plugin_public_notices, 'after_challenge_created', 10, 2 );

	}


	/**
	 * Check version, if no or old version, then load defaults
	 *
	 * @since    1.4.1
	 * @access   private
	 */
	private function check_version() {

		$this->loader->add_action( 'plugins_loaded', 'Courtres_Activator', 'defaults' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.3
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.3
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.3
	 * @return    Courtres_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.3
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the default settings of the plugin.
	 *
	 * @since     1.5.1
	 * @param     string   the name of setting
	 * @return    array    Default settings of the plugin
	 */
	public static function get_default_settings( $name = false ) {
		return $name != false && isset( self::$default_settings[ $name ] ) ? self::$default_settings[ $name ] : self::$default_settings;
	}

	/**
	 * Adding new query vars
	 *
	 * @since     1.5.1
	 */
	private static function define_query_vars() {
		add_filter(
			'query_vars',
			function( $vars ) {
				$vars[] = 'cr-challenge';
				$vars[] = 'cr-action';
				$vars[] = 'challenge';
				$vars[] = 'action';
				return $vars;
			}
		);
	}

}
