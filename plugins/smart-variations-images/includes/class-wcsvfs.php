<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.smart-variations.com
 * @since      1.0.0
 *
 * @package    Wcsvfs
 * @subpackage Wcsvfs/includes
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
 * @package    Wcsvfs
 * @subpackage Wcsvfs/includes
 * @author     David Rosendo <david@rosendo.pt>
 */
class Wcsvfs
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Wcsvfs_Loader    $loader    Maintains and registers all hooks for the plugin.
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

        /**
     * Options for the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $options    Options for the plugin.
     */
    protected $options;

    /**
     * The single instance of the class
     *
     * @var Wcsvfs
     */
    protected static $instance = null;

    /**
     * Extra attribute types
     *
     * @var array
     */
    public $types = array();

    /**
     * Main instance
     *
     * @return Wcsvfs
     */
    public static function instance()
    {
        if (null == self::$instance) {
            self::$instance = new self(WC_SVINST()->options);
        }

        return self::$instance;
    }


    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct($reduxOptions)
    {
        if (defined('WCSVFS_VERSION')) {
            $this->version = WCSVFS_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'wcsvfs';
        $this->types = array(
            'color' => esc_html__('Color', 'wcsvfs'),
            'image' => esc_html__('Image', 'wcsvfs'),
            'label' => esc_html__('Label', 'wcsvfs'),
        );

        $this->options = $reduxOptions;

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
     * - Wcsvfs_Loader. Orchestrates the hooks of the plugin.
     * - Wcsvfs_i18n. Defines internationalization functionality.
     * - Wcsvfs_Admin. Defines all hooks for the admin area.
     * - Wcsvfs_Public. Defines all hooks for the public side of the site.
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-smart-variations-images-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-smart-variations-images-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wcsvfs-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wcsvfs-public.php';

        $this->loader = new Smart_Variations_Images_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Wcsvfs_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Smart_Variations_Images_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Defines initial register hooks
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Wcsvfs_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_print_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_print_scripts', $plugin_admin, 'enqueue_scripts');

        $this->loader->add_filter('product_attributes_type_selector', $this, 'add_attribute_types');

        if (!function_exists('WC')) {
            $this->loader->add_action('admin_notices', $plugin_admin, 'missing_wc_notice');
        } else {
            $this->loader->add_action('woocommerce_product_option_terms', $plugin_admin, 'product_option_terms', 10, 2);
            $this->loader->add_action('wp_ajax_wcsvfs_add_new_attribute', $plugin_admin, 'add_new_attribute_ajax');
            $this->loader->add_action('admin_footer', $plugin_admin, 'add_attribute_term_template');
            $this->loader->add_action('admin_init', $plugin_admin, 'init_attribute_hooks');
            $this->loader->add_action('wcsvfs_product_attribute_field', $plugin_admin, 'attribute_fields', 10, 3);
        }
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

        $plugin_public = new Wcsvfs_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 100);

        $this->loader->add_filter('woocommerce_dropdown_variation_attribute_options_html', $plugin_public, 'get_swatch_html', 100, 2);
        $this->loader->add_filter('wcsvfs_swatch_html', $plugin_public, 'swatch_html', 5, 6);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        if (!property_exists($this->options, 'default_swatches') || !$this->options->default_swatches)
            return;

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
     * @return    Wcsvfs_Loader    Orchestrates the hooks of the plugin.
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
     * Add extra attribute types
     * Add color, image and label type
     *
     * @param array $types
     *
     * @return array
     */
    public function add_attribute_types($types)
    {
        $types = array_merge($types, $this->types);

        return $types;
    }

    /**
     * Get attribute's properties
     *
     * @param string $taxonomy
     *
     * @return object
     */
    public function get_tax_attribute($taxonomy)
    {
        global $wpdb;

        $attr = substr($taxonomy, 3);
        $attr = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attr'");

        return $attr;
    }
}
