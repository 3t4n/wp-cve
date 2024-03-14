<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.rosendo.pt
 * @since      1.0.0
 *
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/includes
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
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/includes
 * @author     David Rosendo <david@rosendo.pt>
 */
class Smart_Variations_Images
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Smart_Variations_Images_Loader    $loader    Maintains and registers all hooks for the plugin.
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
     * @var WordPressSettingsFramework
     */
    private  $wpsf ;
    /**
     * The single instance of the class
     *
     * @var Smart_Variations_Images
     */
    protected static  $instance = null ;
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
        $this->version = SMART_VARIATIONS_IMAGES_VERSION;
        $this->plugin_name = 'smart-variations-images';
        $this->load_dependencies();
        $this->options = $this->run_reduxMigration();
        $this->options->rtl = is_rtl();
        //fs_dd($this->options);
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    
    /**
     * Main instance
     *
     * @return Wcsvfs
     */
    public static function instance()
    {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Smart_Variations_Images_Loader. Orchestrates the hooks of the plugin.
     * - Smart_Variations_Images_i18n. Defines internationalization functionality.
     * - Smart_Variations_Images_Admin. Defines all hooks for the admin area.
     * - Smart_Variations_Images_Public. Defines all hooks for the public side of the site.
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
         * The class responsible for adding the options
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/library/wp-settings-framework/wp-settings-framework.php';
        $this->wpsf = new WordPressSettingsFrameworkSVI( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/library/wp-settings-framework/settings/svi-settings.php', 'woosvi_options' );
        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_settings_page' ), 20 );
        add_filter( $this->wpsf->get_option_group() . '_settings_validate', array( $this, 'validate_settings' ) );
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smart-variations-images-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smart-variations-images-i18n.php';
        /**
         * The class responsible for rendering HTML tags efficiently.
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/library/php-html-generator/Markup.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/library/php-html-generator/HtmlTag.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-smart-variations-images-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-smart-variations-images-public.php';
        $this->loader = new Smart_Variations_Images_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Smart_Variations_Images_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Smart_Variations_Images_i18n();
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
        $plugin_admin = new Smart_Variations_Images_Admin( $this->get_plugin_name(), $this->get_version(), $this->options );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action(
            'woocommerce_variation_options',
            $plugin_admin,
            'variation_btn_builder',
            10,
            3
        );
        $this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'images_section' );
        $panels = 'woocommerce_product_data_panels';
        if ( $this->version_check() ) {
            $panels = 'woocommerce_product_write_panels';
        }
        $this->loader->add_action( $panels, $plugin_admin, 'images_settings' );
        $this->loader->add_action( 'wp_ajax_woosvi_esc_html', $plugin_admin, 'woosvi_esc_html' );
        $this->loader->add_action( 'wp_ajax_woosvi_reloadselect', $plugin_admin, 'reloadSelect_json' );
        //AJAX LOADING TAB DATA
        $this->loader->add_action(
            'woocommerce_product_options_advanced',
            $plugin_admin,
            'sviDisableProduct_advancedTab',
            10,
            0
        );
        //OPTION TO DISABLE SVI FROM RUNNING IN SPECIFIC PRODUCT
        $this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'sviSaveData' );
        if ( svi_fs()->is_free_plan() ) {
            //SKIP CHANGES TO SVI DATA ON IMPORT
            $this->loader->add_filter( 'woocommerce_product_import_process_item_data', $plugin_admin, 'wc_ignore_svimeta_in_import' );
        }
        $this->loader->add_filter(
            'woocommerce_product_export_meta_value',
            $plugin_admin,
            'woo_handle_export',
            10,
            4
        );
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
        if ( !property_exists( $this->options, 'default' ) || !$this->options->default ) {
            return;
        }
        $plugin_public = new Smart_Variations_Images_Public( $this->get_plugin_name(), $this->get_version(), $this->options );
        if ( property_exists( $this->options, 'placeholder' ) && $this->options->placeholder ) {
            $this->options->placeholder_img = $plugin_public->imgtagger( wc_placeholder_img( $this->options->main_imagesize ) );
        }
        $this->loader->add_action(
            'wp_enqueue_scripts',
            $plugin_public,
            'load_scripts',
            99999
        );
        $this->loader->add_filter(
            'wc_get_template',
            $plugin_public,
            'filter_wc_get_template',
            1,
            5
        );
        $this->loader->add_action(
            'after_setup_theme',
            $plugin_public,
            'after_setup_theme',
            20
        );
        //REMOVE ACTIONS/FILTERS FROM THEMES THAT MAY CAUSE CONFLICT
        $this->loader->add_action(
            'woocommerce_before_single_product',
            $plugin_public,
            'remove_hooks',
            20
        );
        //REMOVE IMAGES GALLERIES
        $this->loader->add_action(
            'woocommerce_before_single_product_summary',
            $plugin_public,
            'render_frontend',
            20
        );
        //LOAD SVI IMAGE RENDER SPACE
        add_shortcode( 'svi_wcsc', [ $plugin_public, 'render_sc_frontend' ] );
        
        if ( property_exists( $this->options, 'variation_thumbnails' ) && $this->options->variation_thumbnails ) {
            $this->loader->add_action(
                'woocommerce_single_variation',
                $plugin_public,
                'render_before_add_to_cart_button',
                5
            );
            //LOAD VARIATION IMAGES UNDER DROP DOWNS
        }
        
        if ( property_exists( $this->options, 'loop_showcase' ) && $this->options->loop_showcase ) {
            $this->loader->add_action(
                'woocommerce_before_shop_loop_item_title',
                $plugin_public,
                'svi_product_tn_images',
                10
            );
        }
        $this->loader->add_action( 'wp_ajax_woosvi_slugify', $plugin_public, 'woosvi_slugify' );
        //$this->loader->add_action('wp_ajax_sviloadProduct', $plugin_public, 'loadProductAjax');
        $this->loader->add_action( 'wp_ajax_loadProduct', $plugin_public, 'render_quick_view_frontend' );
        $this->loader->add_action( 'wp_ajax_nopriv_loadProduct', $plugin_public, 'render_quick_view_frontend' );
        $this->loader->add_action( 'svi_before_images', $plugin_public, 'run_integrations' );
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
     * @return    Smart_Variations_Images_Loader    Orchestrates the hooks of the plugin.
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
     * Check WooCommerce version
     *
     * @since     1.0.0
     * @return    boolean
     */
    public function version_check( $version = '3.0' )
    {
        
        if ( class_exists( 'WooCommerce' ) ) {
            global  $woocommerce ;
            if ( version_compare( $woocommerce->version, $version, "<=" ) ) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Add settings page.
     */
    public function add_settings_page()
    {
        $this->wpsf->add_settings_page( array(
            'parent_slug' => 'woocommerce',
            'page_title'  => esc_html__( 'Smart Variations Images & Swatches for WooCommerce', 'text-domain' ),
            'menu_title'  => esc_html__( 'SVI', 'text-domain' ),
            'capability'  => 'edit_products',
            'page_slug'   => 'woocommerce_svi',
        ) );
    }
    
    public function run_reduxMigration()
    {
        $redux = get_option( 'woosvi_options' );
        $redux_imported = get_option( 'woosvi_options_settings_imported', false );
        
        if ( $redux && !$redux_imported ) {
            $wpsfsvi = $this->wpsf->get_settings();
            foreach ( $wpsfsvi as $k => $new ) {
                foreach ( $redux as $rx => $old ) {
                    if ( $this->str_ends_with( $k, $rx ) ) {
                        
                        if ( is_array( $old ) ) {
                            $wpsfsvi[$k] = array_keys( array_filter( $old ) );
                        } else {
                            $wpsfsvi[$k] = $old;
                        }
                    
                    }
                }
            }
            update_option( 'woosvi_options_settings_imported', true );
            update_option( 'woosvi_options_settings', $wpsfsvi );
        }
        
        return (object) $this->wpsf->get_settings( true );
    }
    
    public function str_ends_with( $string, $endString )
    {
        $len = strlen( $endString );
        if ( $len == 0 ) {
            return true;
        }
        return substr( $string, -$len ) === $endString;
    }
    
    /**
     * Validate settings.
     * 
     * @param $input
     *
     * @return mixed
     */
    public function validate_settings( $input )
    {
        // Do your settings validation here
        // Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
        return $input;
    }

}