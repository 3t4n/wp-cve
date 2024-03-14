<?php

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
 * @package    wugrat
 * @subpackage wugrat/includes
 * @author     wupo
 */
class Wugrat
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Wugrat_Loader    $loader    Maintains and registers all hooks for the plugin.
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
        if ( defined( 'WUGRAT_NAME' ) ) {
            $this->plugin_name = WUGRAT_NAME;
        }
        
        if ( defined( 'WUGRAT_VERSION' ) ) {
            $this->version = WUGRAT_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Wugrat_Loader. Orchestrates the hooks of the plugin.
     * - Wugrat_i18n. Defines internationalization functionality.
     * - Wugrat_Admin. Defines all hooks for the admin area.
     * - Wugrat_Public. Defines all hooks for the public side of the site.
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
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wugrat-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wugrat-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wugrat-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wugrat-public.php';
        $this->loader = new Wugrat_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Wugrat_Pro_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Wugrat_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
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
        $plugin_admin = new Wugrat_Admin( 'wugrat', $this->get_version() );
        // Free Version
        // ************
        // Datamodel
        $this->loader->add_action( 'init', $plugin_admin, 'register_taxonomy_wugrat_group' );
        // Scripts
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_style' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_script' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'enqueue_script_function' );
        // Admin UI
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'submenu_group_add' );
        $this->loader->add_action( 'parent_file', $plugin_admin, 'submenu_group_highlight_when_active' );
        //        $this->loader->add_filter('woocommerce_settings_tabs_array', $plugin_admin, 'wc_settings_wugrat_tab_add', 50);
        //        $this->loader->add_filter('woocommerce_settings_tabs_wugrat_settings_tab', $plugin_admin, 'wc_settings_wugrat_tab');
        //        $this->loader->add_filter('woocommerce_update_options_wugrat_settings_tab', $plugin_admin, 'wc_settings_wugrat_tab_save');
        $this->loader->add_action( 'admin_init', $plugin_admin, 'wc_settings_wugrat_tab_save_initial' );
        $this->loader->add_filter( 'plugin_action_links_' . WUGRAT_BASENAME, $plugin_admin, 'add_link_to_settings_on_plugin_page' );
        $this->loader->add_action(
            'admin_menu',
            $plugin_admin,
            'wugrat_add_submenu',
            80
        );
        // Admin Wugrat Group
        $this->loader->add_action( 'admin_init', $plugin_admin, 'group_customize_table' );
        $this->loader->add_filter(
            'get_terms',
            $plugin_admin,
            'group_table_sorting',
            10,
            3
        );
        $this->loader->add_action( 'create_wugrat_group', $plugin_admin, 'group_create_term' );
        $this->loader->add_action( 'delete_wugrat_group', $plugin_admin, 'group_delete_term' );
        // Admin Attributes Edit
        $this->loader->add_action(
            'woocommerce_attribute_updated',
            $plugin_admin,
            'group_update_children_reference',
            10,
            3
        );
        // Admin Product Edit
        $this->loader->add_action( 'woocommerce_product_options_attributes', $plugin_admin, 'product_edit_add_group_attribute_toolbar' );
        $this->loader->add_action(
            'woocommerce_after_product_attribute_settings',
            $plugin_admin,
            'product_edit_add_group_name_to_single_attribute_metabox',
            10,
            2
        );
        // Admin AJAX
        $this->loader->add_action( 'wp_ajax_wugrat_add_attribute_group', $plugin_admin, 'ajax_add_attribute_group' );
        $this->loader->add_action( 'wp_ajax_wugrat_group_attributes_ordering', $plugin_admin, 'ajax_group_attributes_ordering' );
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
        // Free Version
        // ************
        $plugin_public = new Wugrat_Public( 'wugrat', $this->get_version() );
        $is_plugin_active = get_option( 'wc_wugrat_settings_tab_general_enable_wugrat' );
        // Scripts
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        // Product rendering
        
        if ( $is_plugin_active === 'yes' ) {
            $layout_id = get_option( 'wc_wugrat_settings_tab_styling_layout' );
            if ( wugrat_fs()->is_free_plan() || $layout_id == 1 ) {
                $this->loader->add_filter(
                    'woocommerce_display_product_attributes',
                    $plugin_public,
                    'wugrat_display_product_attributes',
                    10,
                    2
                );
            }
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
     * @return    Wugrat_Loader    Orchestrates the hooks of the plugin.
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

}