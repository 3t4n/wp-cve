<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes;

use Dotdigital_WordPress\Admin\Dotdigital_WordPress_Admin;
use Dotdigital_Wordpress\Includes\Cron\Dotdigital_Wordpress_Integration_Insights;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
use Dotdigital_WordPress\Pub\Dotdigital_WordPress_Public;
use Dotdigital_WordPress\Includes\Rest\Dotdigital_WordPress_Signup_Widget_Controller;
use Dotdigital_WordPress\Includes\Rest\Dotdigital_WordPress_Surveys_Controller;
class Dotdigital_WordPress
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var      Dotdigital_WordPress_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    /**
     * The unique identifier of this plugin.
     *
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;
    /**
     * The current version of the plugin.
     *
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     */
    public function __construct()
    {
        $this->version = DOTDIGITAL_WORDPRESS_VERSION;
        $this->plugin_name = DOTDIGITAL_WORDPRESS_PLUGIN_NAME;
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_widgets_hooks();
        $this->define_blocks_hooks();
        $this->define_rest_hooks();
        $this->define_cron_schedules();
    }
    /**
     * Run the loader to execute all the hooks with WordPress.
     */
    public function run()
    {
        $this->loader->run();
    }
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Dotdigital_WordPress_Loader. Orchestrates the hooks of the plugin.
     * - Dotdigital_WordPress_I18n. Defines internationalization functionality.
     * - Dotdigital_WordPress_Admin. Defines all hooks for the admin area.
     * - Dotdigital_WordPress_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'includes/class-dotdigital-wordpress-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'includes/class-dotdigital-wordpress-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(__DIR__) . 'admin/class-dotdigital-wordpress-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(__DIR__) . 'public/class-dotdigital-wordpress-public.php';
        $this->loader = new \Dotdigital_WordPress\Includes\Dotdigital_WordPress_Loader();
    }
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Dotdigital_WordPress_I18n class in order to set the domain and to register the hook
     * with WordPress.
     */
    private function set_locale()
    {
        $plugin_i18n = new \Dotdigital_WordPress\Includes\Dotdigital_WordPress_I18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }
    /**
     * @return void
     */
    private function define_rest_hooks()
    {
        $signup_widget_controller = new Dotdigital_WordPress_Signup_Widget_Controller();
        $this->loader->add_action('rest_api_init', $signup_widget_controller, 'register');
        $surveys_controller = new Dotdigital_WordPress_Surveys_Controller();
        $this->loader->add_action('rest_api_init', $surveys_controller, 'register');
    }
    /**
     * Define the widget hooks for this plugin.
     */
    private function define_widgets_hooks()
    {
        $this->loader->add_action('widgets_init', $this, 'widgets_init');
    }
    /**
     * Register the widget hooks for this  widgets.
     */
    public function widgets_init()
    {
        register_widget(\Dotdigital_WordPress\Includes\Widget\Dotdigital_WordPress_Sign_Up_Widget::class);
        register_widget(\DM_Widget::class);
    }
    /**
     * Define the blocks hooks for this plugin.
     */
    private function define_blocks_hooks()
    {
        $this->loader->add_action('init', $this, 'register_blocks');
    }
    /**
     * Register the blocks for this plugin.
     */
    public function register_blocks()
    {
        register_block_type(DOTDIGITAL_WORDPRESS_PLUGIN_PATH . '/build/signup-form');
        register_block_type(DOTDIGITAL_WORDPRESS_PLUGIN_PATH . '/build/pages-and-forms');
    }
    /**
     * Register all the hooks related to the admin area functionality
     * of the plugin.
     */
    private function define_admin_hooks()
    {
        if (!is_admin()) {
            return;
        }
        $plugin_admin = new Dotdigital_WordPress_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menus');
        $this->loader->add_action('admin_init', $plugin_admin, 'add_plugin_admin_page_actions', 5);
        $this->loader->add_action('admin_init', $plugin_admin, 'add_plugin_page_tabs', 5);
    }
    /**
     * Register all the hooks related to the public-facing functionality
     * of the plugin.
     */
    private function define_public_hooks()
    {
        if (is_admin()) {
            return;
        }
        $plugin_public = new Dotdigital_WordPress_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_public, 'add_plugin_public_actions');
        $this->loader->add_action('init', $plugin_public, 'add_plugin_public_shortcodes');
    }
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     */
    private function get_plugin_name()
    {
        return $this->plugin_name;
    }
    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     */
    private function get_version()
    {
        return $this->version;
    }
    /**
     * Define cron schedules.
     *
     * @return void
     */
    private function define_cron_schedules()
    {
        $integration_insights = new Dotdigital_Wordpress_Integration_Insights();
        $this->loader->add_action('integration_insights', $integration_insights, 'send_integration_insights');
        if (!wp_next_scheduled('integration_insights') && get_option(Dotdigital_WordPress_Config::SETTING_INTEGRATION_INSIGHTS, \true)) {
            wp_schedule_event(\time(), 'daily', 'integration_insights');
        }
    }
}
