<?php

declare(strict_types=1);

/**
 * The file that defines the core plugin class.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @see  https://mailup.it
 * @since 1.2.6
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
 * @since      1.2.6
 *
 * @author     Your Name <email@example.com>
 */
class Mailup
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since  1.2.6
     *
     * @var Mailup_Loader maintains and registers all hooks for the plugin
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since  1.2.6
     *
     * @var string the string used to uniquely identify this plugin
     */
    protected $mailup;

    /**
     * The current version of the plugin.
     *
     * @since  1.2.6
     *
     * @var string the current version of the plugin
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.2.6
     */
    public function __construct()
    {
        if (defined('PLUGIN_NAME_VERSION')) {
            $this->version = PLUGIN_NAME_VERSION;
        } else {
            $this->version = '1.2.6';
        }
        $this->mailup = 'mailup';

        $this->load_dependencies();
        $this->set_locale();

        $this->define_admin_hooks();

        $this->define_public_hooks();
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.2.6
     */
    public function run(): void
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since  1.2.6
     *
     * @return string the name of the plugin
     */
    public function get_mailup()
    {
        return $this->mailup;
    }

    public static function MAILUP_NAME()
    {
        return (new self())->get_mailup();
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since  1.2.6
     *
     * @return Mailup_Loader orchestrates the hooks of the plugin
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since  1.2.6
     *
     * @return string the version number of the plugin
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
     * - Mailup_Loader. Orchestrates the hooks of the plugin.
     * - Mailup_i18n. Defines internationalization functionality.
     * - Mailup_Admin. Defines all hooks for the admin area.
     * - Mailup_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since  1.2.6
     */
    private function load_dependencies(): void
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        include_once plugin_dir_path(__DIR__).'includes/class-mailup-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        include_once plugin_dir_path(__DIR__).'includes/class-mailup-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        include_once plugin_dir_path(__DIR__).'admin/class-mailup-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        include_once plugin_dir_path(__DIR__).'public/class-mailup-public.php';

        $this->loader = new Mailup_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Mailup_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since  1.2.6
     */
    private function set_locale(): void
    {
        $plugin_i18n = new Mailup_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since  1.2.6
     */
    private function define_admin_hooks(): void
    {
        $plugin_admin = new Mailup_Admin($this->get_mailup(), $this->get_version());

        $this->loader->add_action('admin_menu', $plugin_admin, 'create_admin_page');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_save_forms', $plugin_admin, 'save_forms');
        $this->loader->add_action('wp_ajax_autocomplete_group', $plugin_admin, 'autocomplete_group');
        $this->loader->add_action('admin_head', $plugin_admin, 'mup_admin_head');
        $this->loader->add_action('widgets_init', $plugin_admin, 'register_widgets');
        $this->loader->add_action('wp_before_admin_bar_render', $plugin_admin, 'wpml_remove_admin_bar_menu', 99);
        $this->loader->add_action('plugins_loaded', $plugin_admin, 'check_update_version');
        // $this->loader->add_action('admin_notices', $plugin_admin, 'show_admin_notice');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since  1.2.6
     */
    private function define_public_hooks(): void
    {
        $plugin_public = new Mailup_Public($this->get_mailup(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_mupwp_save_contact', $plugin_public, 'mupwp_save_contact');
        $this->loader->add_action('wp_ajax_nopriv_mupwp_save_contact', $plugin_public, 'mupwp_save_contact');
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
    }
}
