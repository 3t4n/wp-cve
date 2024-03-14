<?php

namespace Tussendoor\OpenRDW\Includes;

use Tussendoor\OpenRDW\Admin\VehiclePlateInformationAdmin;
use Tussendoor\OpenRDW\Front\VehiclePlateInformationFront;


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @see       http://www.tussendoor.nl
 * @since      2.0.0
 *
 */
class VehiclePlateInformation
{
    public $dot_config;
    
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    2.0.0
     * @var VehiclePlateInformationLoader $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    2.0.0
     * @var string $open_rdw_kenteken_voertuiginformatie    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    2.0.0
     * @var string $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function __construct()
    {
        global $dot_config;
        $this->dot_config = $dot_config;

        $this->plugin_name = $this->dot_config['plugin.name'];
        $this->version = $this->dot_config['plugin.version'];
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    2.0.0
     */
    public function run()
    {
        $this->loader->run();
        if (function_exists('wpcf7_add_form_tag')) {
            $wpcf7 = new VehiclePlateInformationCF7();
            wpcf7_add_form_tag(['open_rdw', 'open_rdw*'], [$wpcf7, 'shortcode_handler'], true);
        }

        add_shortcode('open_rdw_quform', [$this, 'quform_shortcode']);
    }

    /**
     * Adds the QuForm shortcode and functionality
     *
     * @since    2.0.1
     */
    public function quform_shortcode($fields)
    {
        $license_key = array_search('Kenteken', $fields);
        if ($license_key) {
            $license = $license_key;
            unset($fields[$license_key]);


            $data = [
                'license'   => $license,
                'fields'    => array_flip($fields),
                'url'       => admin_url('admin-ajax.php'),
                'images'    => [
                    'loading' => $this->dot_config['plugin.url'] . '/public/images/ajax-loader.gif',
                    'warning' => $this->dot_config['plugin.url'] . '/public/images/warning-icon.png',
                    'success' => $this->dot_config['plugin.url'] . '/public/images/accepted-icon.png'
                ]
            ];
            wp_register_script('open_rdw_quform_script', $this->dot_config['plugin.url'] . '/public/js/open-rdw-quform.js', ['jquery'], '1.0.4');
            wp_localize_script('open_rdw_quform_script', 'ajax', $data);
            wp_enqueue_script('open_rdw_quform_script');
        }
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     2.0.0
     * @return string The name of the plugin.
     */
    public function get_open_rdw_kenteken_voertuiginformatie()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     2.0.0
     * @return VehiclePlateInformationLoader Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     2.0.0
     * @return string The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - VehiclePlateInformationLoader. Orchestrates the hooks of the plugin.
     * - VehiclePlateInformationI18n. Defines internationalization functionality.
     * - VehiclePlateInformationAdmin. Defines all hooks for the admin area.
     * - VehiclePlateInformationFront. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    2.0.0
     */
    private function load_dependencies()
    {
        $this->loader = new VehiclePlateInformationLoader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the VehiclePlateInformationI18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    2.0.0
     */
    private function set_locale()
    {
        $plugin_i18n = new VehiclePlateInformationI18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain', 99);
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    2.0.0
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new VehiclePlateInformationAdmin($this->get_open_rdw_kenteken_voertuiginformatie(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    2.0.0
     */
    private function define_public_hooks()
    {
        $plugin_public = new VehiclePlateInformationFront($this->get_open_rdw_kenteken_voertuiginformatie(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }
}
