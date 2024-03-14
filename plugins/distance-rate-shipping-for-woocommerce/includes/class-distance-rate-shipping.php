<?php
/**
 * Distance_Rate_Shipping
 * This class is core class of plugin.
 * It defines all the required code to laod all dependencies, locale, hooks.
 * 
 * @package Distance_Rate_Shipping
 * @subpackage Distance_Rate_Shipping/includes
 * @author tusharknovator
 * @since 1.0.0
 */
class Distance_Rate_Shipping{

    /**
     * Store the name of the plugin
     * @access protected
     * @var string $plugin_name
     */
    protected $plugin_name;

    /**
     * Store the version of the plugin
     * @access protected
     * @var string $plugin_version
     */
    protected $plugin_version;

    /**
     * Store the reference of the plugin's loader
     * @access protected
     * @var string $plugin_loader
     */
    protected $plugin_loader;

    /**
     * Store the reference of the plugin's config
     * @access protected
     * @var string $plugin_config
     */
    protected $plugin_config;

    /**
     * __constructor function
     * To initiate class variables and functions.
     * It runs on creation of class instance/object.
     * @return void
     * @since 1.0.0
     */
    public function __construct(){

        $this->load_dependencies();
        $this->set_configuration();
        $this->set_loader();
        $this->set_locale();
        $this->define_backend_hooks();
        $this->define_frontend_hooks();
        $this->define_woocommerce_hooks();
    }

    /**
     * __constructor function
     * To initiate class variables and functions.
     * It runs on creation of class instance/object.
     * @since 1.0.0
     */
    public function load_dependencies(){

        // include loader class
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-distance-rate-shipping-loader.php';

        // include config class
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-distance-rate-shipping-config.php';
        
        // include internationalization class
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-distance-rate-shipping-i18n.php';

        // include backend class
        require_once plugin_dir_path(dirname(__FILE__)) . 'backend/class-distance-rate-shipping-backend.php';

        // include frontend class
        require_once plugin_dir_path(dirname(__FILE__)) . 'frontend/class-distance-rate-shipping-frontend.php';

        //include distance basec custom WooCommerce shipping method
        require_once plugin_dir_path(dirname(__FILE__)) . 'woocommerce/class-distance-rate-shipping-main.php';

    }

    /**
     * set_configuration function
     * To set all configuration of plugin
     * @since 1.0.0
     */
    public function set_configuration(){
        $this->plugin_config = new Distance_Rate_Shipping_Config();

        $this->plugin_name = $this->plugin_config->get_plugin_name();
        $this->plugin_version = $this->plugin_config->get_plugin_version();
    }

    /**
     * set_loader function
     * To set plugin loader
     * @since 1.0.0
     */
    public function set_loader(){
        $this->plugin_loader = new Distance_Rate_Shipping_Loader();
    }
    /**
     * set_locale function
     * To load internationalization files
     * @since 1.0.0
     */
    public function set_locale(){
        $config = $this->plugin_config;

        $i18n = new Distance_Rate_Shipping_i18n($config->get_text_domain(), $config->get_text_domain_path());
        $i18n->load_textdomain();
        $this->plugin_loader->add_hook( 
            'action', 
            'plugins_loaded', 
            $i18n, 
            'load_textdomain');
        
    }

    /**
     * define_backend_hooks function
     * Here you can define hooks related to backend
     * @since 1.0.0
     */
    public function define_backend_hooks(){
        $backend = new Distance_Rate_Shipping_Backend($this->plugin_name, $this->plugin_version, $this->plugin_config);

        $this->plugin_loader->add_hook('action', 'admin_enqueue_scripts', $backend, 'enquque_styles');
        $this->plugin_loader->add_hook('action', 'admin_enqueue_scripts', $backend, 'enqueue_scripts');
        $this->plugin_loader->add_hook('action', 'admin_menu', $backend, 'add_backend_pages');
        $this->plugin_loader->add_hook('action', 'admin_init', $backend, 'register_backend_settings');
    }

    /**
     * define_frontend_hooks function
     * Here you can define hooks related to frontend
     * @since 1.0.0
     */
    public function define_frontend_hooks(){
        $frontend = new Distance_Rate_Shipping_Frontend($this->plugin_name, $this->plugin_version);

        $this->plugin_loader->add_hook('action', 'wp_enqueue_scripts', $frontend, 'enquque_styles');
        $this->plugin_loader->add_hook('action', 'wp_enqueue_scripts', $frontend, 'enqueue_scripts');

    }

    public function define_woocommerce_hooks(){
        $woocommerce = new Distance_Rate_Shipping_Main($this->plugin_name, $this->plugin_version, $this->plugin_config);

        $this->plugin_loader->add_hook('action','woocommerce_shipping_init', $woocommerce, 'load_distance_based_shipping_method');
        $this->plugin_loader->add_hook('action','woocommerce_shipping_methods', $woocommerce, 'register_distance_based_shipping_method');
        $this->plugin_loader->add_hook('filter','woocommerce_package_rates', $woocommerce, 'filter_shipping_methods', 10, 3);
    }
    /**
     * run function
     * To run plugin
     * @since 1.0.0
     */
    public function run(){
        $this->plugin_loader->load();       
    }

}