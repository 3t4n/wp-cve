<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.upcasted.com
 * @since      1.0.0
 *
 * @package    Upcasted_S3_Offload
 * @subpackage Upcasted_S3_Offload/includes
 */

/**
 * Class Upcasted_Offload
 */
class Upcasted_Offload
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Upcasted_S3_Offload_Loader $loader Maintains and registers all hooks for the plugin.
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
    public function __construct()
    {
        $this->version = defined('UPCASTED_S3_OFFLOAD_VERSION') ? UPCASTED_S3_OFFLOAD_VERSION : '1.0.0';
        $this->plugin_name = 'upcasted-s3-offload';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Upcasted_S3_Offload_Loader. Orchestrates the hooks of the plugin.
     * - Upcasted_S3_Offload_i18n. Defines internationalization functionality.
     * - Upcasted_S3_Offload_Admin. Defines all hooks for the admin area.
     * - Upcasted_S3_Offload_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        $plugin_dir_path = plugin_dir_path(dirname(__FILE__));


        require_once $plugin_dir_path . 'admin/CloudApplication.php';

        require_once $plugin_dir_path . 'admin/interfaces/iCloudActions.php';

        require_once $plugin_dir_path . 'admin/interfaces/iCloudManipulator.php';

        require_once $plugin_dir_path . 'admin/services/CronManagement.php';

        require_once $plugin_dir_path . 'admin/services/CloudCredentialsEncryption.php';

        require_once $plugin_dir_path . 'admin/repositories/CloudRepository.php';

        require_once $plugin_dir_path . 'admin/providers/AmazonCloudManipulator.php';

        require_once $plugin_dir_path . 'admin/services/CloudActions.php';

        require_once $plugin_dir_path . 'admin/controllers/CloudToolsController.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once $plugin_dir_path . 'includes/class-upcasted-offload-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once $plugin_dir_path . 'includes/class-upcasted-offload-i18n.php';

        /**
         * The class responsible for seamlessly syncing Media Library with S3.
         */
        require_once $plugin_dir_path . 'admin/class-upcasted-offload-init.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once $plugin_dir_path . 'admin/class-upcasted-offload-admin.php';

        $this->loader = new Upcasted_S3_Offload_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Upcasted_S3_Offload_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Upcasted_S3_Offload_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

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
        try {
            $cloudToolsController = new CloudToolsController();
            $plugin_admin = new Upcasted_S3_Offload_Admin($this->get_plugin_name(), $this->get_version());
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
            $this->loader->add_action('admin_menu', $plugin_admin, 'register_sub_menu');
            $this->loader->add_action('admin_init', $plugin_admin, 'settings_page_init');
            $this->loader->add_action('manage_media_columns', $plugin_admin, 'add_cloudindicator_column');
            $this->loader->add_action('manage_media_custom_column', $plugin_admin, 'add_cloudindicator_value', 10, 2);
            $this->loader->add_action('wp_ajax_upcasted_offload_connect', $cloudToolsController, 'upcasted_offload_connect');
            $this->loader->add_action('wp_ajax_nopriv_upcasted_offload_connect', $cloudToolsController, 'upcasted_offload_connect');
            $this->loader->add_action('wp_ajax_upcasted_init', $cloudToolsController, 'upcasted_init');
            $this->loader->add_action('wp_ajax_nopriv_upcasted_init', $cloudToolsController, 'upcasted_init');
            $this->loader->add_action('wp_ajax_upcasted_create_bucket', $cloudToolsController, 'upcasted_create_bucket__premium_only');
            $this->loader->add_action('wp_ajax_nopriv_upcasted_create_bucket', $cloudToolsController, 'upcasted_create_bucket__premium_only');

            (Upcasted_S3_Offload_Init::getInstance())->define_admin_hooks();

            uso_fs()->add_action('after_uninstall', array($this, 'remove_plugin'));
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function remove_plugin()
    {
        delete_post_meta_by_key('bucket');
        delete_option(UPCASTED_S3_OFFLOAD_SETTINGS);
        wp_unschedule_event(wp_next_scheduled('upcasted_move_unsynced_files_to_remote_job_hook'),
            'upcasted_move_unsynced_files_to_remote_job_hook');
        wp_unschedule_event(wp_next_scheduled('upcasted_move_from_cloud_to_local_using_cron'),
            'upcasted_move_files_to_local_job_hook');
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
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name(): string
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Upcasted_S3_Offload_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader(): Upcasted_S3_Offload_Loader
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version(): string
    {
        return $this->version;
    }

}
