<?php
if ( !defined('ABSPATH') ) {
    exit();
}

/**
 * The file that defines the core plugin class
 *
 * @since      1.0.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/includes
 */
class WC_Swiss_Qr_Bill {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var     WC_Swiss_Qr_Bill_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

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
        if ( defined('WC_SWISS_QR_BILL_VER') ) {
            $this->version = WC_SWISS_QR_BILL_VER;
        } else {
            $this->version = '1.2.4';
        }
        $this->plugin_name = 'swiss-qr-bill';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();

        // Checks with WooCommerce is installed.
        if ( $this->is_wc_activated() ) {
            $this->load_woocommerce_dependencies();
        }

    }

    /**
     * Load the required dependencies for this plugin.
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wc-swiss-qr-bill-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wc-swiss-qr-bill-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wc-swiss-qr-bill-admin.php';

        /**
         * The class responsible for extending the setting fields of product category
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-settings-wsqb-product-cat.php';

        $this->loader = new WC_Swiss_Qr_Bill_Loader();

    }

    /**
     * Load woocommerce dependent dependencies
     *
     * @return void
     */
    public function load_woocommerce_dependencies() {
        /**
         * This class is responsible for the initialization of Payment Gateway
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/gateway/abstract-wc-gateway-swiss-qr-bill.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/gateway/class-wc-gateway-swiss-qr-bill.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/gateway/class-wc-gateway-swiss-qr-bill-classic.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/gateway/class-wc-swiss-qr-bill-generate.php';
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the WC_Swiss_Qr_Bill_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        WC_Swiss_Qr_Bill_i18n::load_plugin_textdomain();
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        // Initialize the WC_Swiss_Qr_Bill_Admin class
        $plugin_admin = new WC_Swiss_Qr_Bill_Admin($this->get_plugin_name(), $this->get_version());

        // Load admin scripts and styles
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');


        // Display admin notices if WC is not activated
        if ( !$this->is_wc_activated() ) {

            $this->loader->add_filter('admin_notices', $plugin_admin, 'woocommerce_missing_notice', 99);

        } else {

            $plugin_admin->admin_hooks($this->loader);

        }

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
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return   WC_Swiss_Qr_Bill_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Check if woocommerce plugin is activates and it's version is > 3.0
     *
     * @return boolean
     */
    public static function is_wc_activated() {
        return defined('WC_VERSION') && version_compare(WC_VERSION, '3.0', '>=');
    }

}
