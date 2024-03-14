<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.wpgoaltracker.com/
 * @since      1.0.0
 *
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/includes
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
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/includes
 * @author     yuvalo <support@wpgoaltracker.com>
 */
class Wp_Goal_Tracker_Ga
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Wp_Goal_Tracker_Ga_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected  $loader ;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected  $plugin_name ;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected  $version ;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->version = WP_CUSTOM_EVENTS_TRACKER_VERSION;
        $this->plugin_name = 'wp-goal-tracker-ga';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->loader->add_action( 'plugins_loaded', $this, 'db_upgrade' );
        #$options  = get_option('wp_goal_tracker_ga_options');
        #var_dump($options);
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Wp_Goal_Tracker_Ga_Loader. Orchestrates the hooks of the plugin.
     * - Wp_Goal_Tracker_Ga_i18n. Defines internationalization functionality.
     * - Wp_Goal_Tracker_Ga_Admin. Defines all hooks for the admin area.
     * - Wp_Goal_Tracker_Ga_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * Plugin Core Functions.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-goal-tracker-ga-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-goal-tracker-ga-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-goal-tracker-ga-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-goal-tracker-ga-public.php';
        $this->loader = new Wp_Goal_Tracker_Ga_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Wp_Goal_Tracker_Ga_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Wp_Goal_Tracker_Ga_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    
    /**
     * Register the post types for storing the configuration in posts DB.
     *
     *
     * @since    1.0.0
     * @access   private
     */
    private function cet_register_post_types()
    {
        $pt_args = array(
            'label'               => 'Goal_Tracker_Ga Post Type',
            'public'              => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_ui'             => false,
            'show_in_menu'        => false,
            'has_archive'         => true,
            'rewrite'             => true,
            'query_var'           => true,
        );
        register_post_type( "cet_click", $pt_args );
        register_post_type( "cet_visibility", $pt_args );
    }
    
    public function add_option_management_capability()
    {
        return 'manage_gtga';
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Wp_Goal_Tracker_Ga_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_resources' );
        $this->loader->add_action( 'rest_api_init', $plugin_admin, 'api_init' );
        $this->loader->add_action( 'cet_resgister_post_types', $plugin_admin, 'cet_register_post_types' );
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'gtga_show_review_notice' );
        $this->loader->add_action( 'wp_ajax_gtga_dismiss_review_notice', $plugin_admin, 'gtga_dismiss_review_notice' );
        #$this->loader->add_filter( 'option_page_capability_gt_events_options_group', 'add_option_management_capability' ,10,1 );
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Wp_Goal_Tracker_Ga_Public( $this->get_plugin_name(), $this->get_version() );
        $no_snippet = false;
        $general_settings = wp_goal_tracker_ga_get_options( "generalSettings" );
        if ( isset( $general_settings['noSnippet'] ) ) {
            $no_snippet = $general_settings['noSnippet'];
        }
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        if ( !$no_snippet ) {
            $this->loader->add_action( 'wp_head', $plugin_public, 'wp_goal_tracker_ga_add_ga4_code_snippet' );
        }
    }
    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Wp_Goal_Tracker_Ga_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }
    
    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
    
    /**
     * DB upgrade procedure
     *
     *
     * @since    1.0.2
     *
     * @access   public
     */
    public function db_upgrade()
    {
        $options = get_option( 'wp_goal_tracker_ga_options' );
        
        if ( $options === false ) {
            $options = array();
            // Initialize as an empty array if the option does not exist
        }
        
        
        if ( !isset( $options["db_version"] ) ) {
            # it is the first DB convert from version 1.0.0 to 1.0.2
            $options["db_version"] = "1.1.3";
            # set the db_version param for the first time
            # move general settings parameters from the options root to a "general_settings" array.
            $general_settings = array();
            
            if ( isset( $options["measurementID"] ) ) {
                $general_settings["measurementID"] = $options["measurementID"];
                unset( $options["measurementID"] );
            }
            
            
            if ( isset( $options["disableTrackingForAdmins"] ) ) {
                $general_settings["disableTrackingForAdmins"] = $options["disableTrackingForAdmins"];
                unset( $options["disableTrackingForAdmins"] );
            }
            
            
            if ( isset( $options["trackEmailLinks"] ) ) {
                $general_settings["trackEmailLinks"] = $options["trackEmailLinks"];
                unset( $options["trackEmailLinks"] );
            }
            
            
            if ( isset( $options["trackLinks"] ) ) {
                $general_settings["trackLinks"] = $options["trackLinks"];
                unset( $options["trackLinks"] );
            }
            
            
            if ( isset( $options["gaDebug"] ) ) {
                $general_settings["gaDebug"] = $options["gaDebug"];
                unset( $options["gaDebug"] );
            }
            
            
            if ( isset( $options["disablePageView"] ) ) {
                $general_settings["disablePageView"] = $options["disablePageView"];
                unset( $options["disablePageView"] );
            }
            
            $options['generalSettings'] = $general_settings;
            $default_options = wp_goal_tracker_ga_default_options();
            $options = array_merge( $default_options, $options );
            update_option( 'wp_goal_tracker_ga_options', $options );
            #var_dump($options);
        }
    
    }
    
    /**
     * Add management capabilities to roles
     *
     *
     * @since    1.0.2
     *
     * @access   public
     */
    public static function add_caps( $roles = array( "{'id':'administrator'}" ) )
    {
        // Loop through either roles from DB or roles in param roles and add
        // plugin management capability
        global  $wp_roles ;
        foreach ( (array) $roles as $role ) {
            error_log( json_encode( $role["id"] ) );
            $wp_roles->add_cap( $role["id"], 'manage_gtga' );
        }
    }
    
    /**
     * Remove management capabilities from roles
     *
     *
     * @since    1.0.2
     *
     * @access   public
     */
    public static function remove_caps( $roles )
    {
        // delete manage_wpgae capability from all roles
        global  $wp_roles ;
        foreach ( (array) $roles as $role ) {
            $wp_roles->remove_cap( $role["id"], 'manage_gtga' );
        }
    }

}