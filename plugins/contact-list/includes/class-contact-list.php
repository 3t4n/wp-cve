<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://anssilaitila.fi
 * @since      1.0.0
 *
 * @package    Contact_List
 * @subpackage Contact_List/includes
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
 * @package    Contact_List
 * @subpackage Contact_List/includes
 * @author     Anssi Laitila <anssi.laitila@gmail.com>
 */
class Contact_List
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Contact_List_Loader    $loader    Maintains and registers all hooks for the plugin.
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
        if ( defined( 'CONTACT_LIST_VERSION' ) ) {
            $this->version = CONTACT_LIST_VERSION;
        }
        $this->plugin_name = 'contact-list';
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
     * - Contact_List_Loader. Orchestrates the hooks of the plugin.
     * - Contact_List_i18n. Defines internationalization functionality.
     * - Contact_List_Admin. Defines all hooks for the admin area.
     * - Contact_List_Public. Defines all hooks for the public side of the site.
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
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-contact-list-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-contact-list-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-admin-send-email.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-admin-mail-log.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-admin-list.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-admin-inline-styles.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-admin-inline-scripts.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-settings.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-shortcodes.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-help-support.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-import-export.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-printable.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-notifications.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-query.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-cpt.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-taxonomy.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-admin-maintenance.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cl-admin-operations.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public-send-mail.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public-ajax.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public-helpers.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public-helpers-default.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public-helpers-simple.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public-load.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public-card.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public-pagination.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cl-public-hooks.php';
        /**
         * The class responsible for defining custom fields for the custom post type
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-contact-list-custom-fields.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-contact-list-helpers.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode_contact_list.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode_contact_list_simple.php';
        $this->loader = new Contact_List_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Contact_List_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Contact_List_i18n();
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
        $s = get_option( 'contact_list_settings' );
        $plugin_admin = new Contact_List_Admin( $this->get_plugin_name(), $this->get_version() );
        $plugin_admin_send_email = new ContactListAdminSendEmail();
        $plugin_admin_mail_log = new ContactListAdminMailLog();
        $plugin_admin_list = new ContactListAdminList();
        $plugin_admin_maintenance = new ContactListAdminMaintenance();
        $plugin_admin_operations = new ContactListAdminOperations();
        $plugin_admin_inline_styles = new ContactListAdminInlineStyles();
        $plugin_admin_inline_scripts = new ContactListAdminInlineScripts();
        $plugin_settings = new ContactListSettings();
        $plugin_shortcodes = new ContactListShortcodes();
        $plugin_help_support = new ContactListHelpSupport();
        $plugin_import_export = new ContactListImportExport();
        $plugin_printable = new ContactListPrintable();
        $plugin_query = new ContactListQuery();
        $plugin_cpt = new ContactListCPT();
        $plugin_taxonomy = new ContactListTaxonomy();
        $plugin_custom_fields = new ContactListCustomFields();
        $plugin_notifications = new ContactListNotifications();
        $this->loader->add_action( 'plugins_loaded', $plugin_settings, 'update_db_check' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        // CPT
        $this->loader->add_action( 'init', $plugin_cpt, 'create_custom_post_type_contact' );
        // Admin list
        $this->loader->add_filter(
            'manage_contact_posts_columns',
            $plugin_admin_list,
            'contact_custom_columns',
            10
        );
        $this->loader->add_action(
            'manage_contact_posts_custom_column',
            $plugin_admin_list,
            'contact_custom_columns_content',
            10,
            2
        );
        $this->loader->add_action( 'pre_get_posts', $plugin_admin_list, 'contact_list_custom_orderby' );
        $this->loader->add_filter( 'manage_edit-contact_sortable_columns', $plugin_admin_list, 'set_custom_contact_list_sortable_columns' );
        // Custom taxonomy
        $this->loader->add_action(
            'init',
            $plugin_taxonomy,
            'create_contact_list_custom_taxonomy',
            0
        );
        $this->loader->add_action(
            'contact-group_edit_form_fields',
            $plugin_taxonomy,
            'contact_group_taxonomy_custom_fields',
            10,
            2
        );
        $this->loader->add_action(
            'edited_contact-group',
            $plugin_taxonomy,
            'save_taxonomy_custom_fields',
            10,
            2
        );
        $this->loader->add_filter( 'manage_edit-contact-group_columns', $plugin_taxonomy, 'theme_columns' );
        $this->loader->add_filter(
            'manage_contact-group_custom_column',
            $plugin_taxonomy,
            'add_contact_group_column_content',
            10,
            3
        );
        // Maintenance
        $this->loader->add_action( 'plugins_loaded', $plugin_admin_maintenance, 'actions' );
        $this->loader->add_action( 'init', $plugin_admin_maintenance, 'update_db_check_v2' );
        // Admin operations
        $this->loader->add_filter( 'admin_init', $plugin_admin_operations, 'operations' );
        // Import & export
        $this->loader->add_action( 'admin_menu', $plugin_import_export, 'register_import_page' );
        $this->loader->add_action( 'admin_menu', $plugin_import_export, 'register_export_page' );
        // Printable list
        $this->loader->add_action( 'admin_menu', $plugin_printable, 'register_printable_page' );
        // Send email
        $this->loader->add_action( 'admin_menu', $plugin_admin_send_email, 'register_send_email_page' );
        // Mail log
        if ( !isset( $s['disable_mail_log'] ) ) {
            $this->loader->add_action( 'admin_menu', $plugin_admin_mail_log, 'register_mail_log_page' );
        }
        // Settings
        $this->loader->add_action( 'admin_menu', $plugin_settings, 'add_settings_link' );
        $this->loader->add_action( 'admin_menu', $plugin_settings, 'contact_list_add_admin_menu' );
        $this->loader->add_action( 'admin_init', $plugin_settings, 'contact_list_settings_init' );
        // Shortcodes
        $this->loader->add_action( 'admin_menu', $plugin_shortcodes, 'register_shortcodes_page' );
        // Help & support
        $this->loader->add_action( 'admin_menu', $plugin_help_support, 'register_support_page' );
        // Notifications
        $this->loader->add_action(
            'admin_notices',
            $plugin_notifications,
            'notifications_html',
            8
        );
        $this->loader->add_action( 'admin_init', $plugin_notifications, 'process_notifications' );
        // Query
        $this->loader->add_filter(
            'wp_insert_post_data',
            $plugin_query,
            'modify_post_title',
            '99',
            1
        );
        // Inline styles
        //    $this->loader->add_action('admin_head', $plugin_admin_inline_scripts, 'inline_scripts', 100);
        // Upgrade link
        if ( ContactListHelpers::isPremium() == 0 ) {
            $this->loader->add_action( 'admin_menu', $plugin_settings, 'add_upgrade_link' );
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
        $plugin_public = new Contact_List_Public( $this->get_plugin_name(), $this->get_version() );
        $plugin_public_send_mail = new ContactListPublicSendMail();
        $plugin_public_ajax = new ContactListPublicAjax();
        $plugin_public_load = new ContactListPublicLoad();
        $plugin_admin_send_email = new ContactListAdminSendEmail();
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_load, 'public_inline_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public_load, 'public_inline_scripts' );
        $this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
        // Send mail to a single contact
        $this->loader->add_action( 'wp_ajax_nopriv_cl_send_mail_public', $plugin_public_send_mail, 'cl_send_mail_public' );
        $this->loader->add_action( 'wp_ajax_cl_send_mail_public', $plugin_public_send_mail, 'cl_send_mail_public' );
        // Ajax query
        $this->loader->add_action( 'wp_ajax_nopriv_cl_get_contacts', $plugin_public_ajax, 'cl_get_contacts' );
        $this->loader->add_action( 'wp_ajax_cl_get_contacts', $plugin_public_ajax, 'cl_get_contacts' );
        $this->loader->add_action( 'wp_ajax_nopriv_cl_get_contacts_simple', $plugin_public_ajax, 'cl_get_contacts_simple' );
        $this->loader->add_action( 'wp_ajax_cl_get_contacts_simple', $plugin_public_ajax, 'cl_get_contacts_simple' );
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
     * @return    Contact_List_Loader    Orchestrates the hooks of the plugin.
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