<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       Appointy.com
 * @since      3.0.1
 *
 * @package    Appointy_appointment_scheduler
 * @subpackage Appointy_appointment_scheduler/includes
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
 * @since      3.0.1
 * @package    Appointy_appointment_scheduler
 * @subpackage Appointy_appointment_scheduler/includes
 * @author     Appointy <lav@appointy.com>
 * @author     Appointy <shikhar.v@appointy.com>
 */
class Appointy_appointment_scheduler
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    3.0.1
     * @access   protected
     * @var      Appointy_appointment_scheduler_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    3.0.1
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    3.0.1
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
     * @since    3.0.1
     */
    public function __construct()
    {
        if (defined('APPOINTY_APPOINTMENT_SCHEDULER_VERSION')) {
            $this->version = APPOINTY_APPOINTMENT_SCHEDULER_VERSION;
        } else {
            $this->version = '3.0.1';
        }
        $this->plugin_name = 'appointy_appointment_scheduler';

        $this->load_dependencies();
        $helper = new Appointy_helper_functions();
        $this->define_admin_hooks($helper);
        $this->define_public_hooks($helper);

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Appointy_appointment_scheduler_Loader. Orchestrates the hooks of the plugin.
     * - Appointy_appointment_scheduler_i18n. Defines internationalization functionality.
     * - Appointy_appointment_scheduler_Admin. Defines all hooks for the admin area.
     * - Appointy_appointment_scheduler_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    3.0.1
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-appointy-appointment-scheduler-loader.php';

        /**
         * The class responsible for providing helper function to admin and public area
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-appointy-helper-functions.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-appointy-appointment-scheduler-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-appointy-appointment-scheduler-public.php';

        $this->loader = new Appointy_appointment_scheduler_Loader();

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    3.0.1
     * @access   private
     */
    private function define_admin_hooks($helper)
    {

        $plugin_admin = new Appointy_appointment_scheduler_Admin($this->get_plugin_name(), $this->get_version(), $helper);

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_menu', $plugin_admin, 'appointy_calendar_config_page');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');



    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    3.0.1
     * @access   private
     */
    private function define_public_hooks($helper)
    {

        $plugin_public = new Appointy_appointment_scheduler_Public($this->get_plugin_name(), $this->get_version(), $helper);

        $this->loader->add_action('widgets_init', $plugin_public, 'appointy_widget_init');
        $this->loader->add_filter('the_content', $plugin_public, 'appointy_content');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    3.0.1
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     3.0.1
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Appointy_appointment_scheduler_Loader    Orchestrates the hooks of the plugin.
     * @since     3.0.1
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     3.0.1
     */
    public function get_version()
    {
        return $this->version;
    }

}
