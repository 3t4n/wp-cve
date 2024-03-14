<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://chillichalli.com
 * @since      1.1.4
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
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
 * @since      0.5.0
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 * @author     Christopher Graham <support@chillichalli.com>
 */
class Card_Oracle
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    0.5.0
     * @access   protected
     * @var      Card_Oracle_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected  $loader ;
    /**
     * The unique identifier of this plugin.
     *
     * @since    0.5.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected  $plugin_name ;
    /**
     * The current version of the plugin.
     *
     * @since    0.5.0
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
     * @since    0.16.0
     */
    public function __construct()
    {
        $this->define_constants();
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->create_files();
        // Check update when plugin loaded.
        $this->card_oracle_check_version();
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Card_Oracle_Loader. Orchestrates the hooks of the plugin.
     * - Card_Oracle_i18n. Defines internationalization functionality.
     * - Card_Oracle_Admin. Defines all hooks for the admin area.
     * - Card_Oracle_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    0.26.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once CARD_ORACLE_DIR . 'includes/class-card-oracle-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once CARD_ORACLE_DIR . 'includes/class-card-oracle-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once CARD_ORACLE_DIR . 'admin/class-card-oracle-admin.php';
        require_once CARD_ORACLE_DIR . 'admin/includes/class-card-oracle-admin-wizard.php';
        require_once CARD_ORACLE_DIR . 'admin/includes/class-card-oracle-notices.php';
        require_once CARD_ORACLE_DIR . 'admin/includes/class-card-oracle-demo-data.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once CARD_ORACLE_DIR . 'public/class-card-oracle-public.php';
        /**
         * Include the core functions for both the admin and public-facing sides
         */
        require_once CARD_ORACLE_DIR . 'includes/card-oracle-core-functions.php';
        require_once CARD_ORACLE_DIR . 'includes/class-co-logging.php';
        /**
         * The class responsible for defining all actions that occur in the meta boxes
         * side of the site.
         */
        $this->loader = new Card_Oracle_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Card_Oracle_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    0.5.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Card_Oracle_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    
    /**
     * Create files/directories.
     */
    private static function create_files()
    {
        // Install files and folders for uploading files and prevent hotlinking.
        $files = array( array(
            'base'    => CARD_ORACLE_LOG_DIR,
            'file'    => '.htaccess',
            'content' => 'deny from all',
        ), array(
            'base'    => CARD_ORACLE_LOG_DIR,
            'file'    => 'index.html',
            'content' => '',
        ) );
        foreach ( $files as $file ) {
            
            if ( wp_mkdir_p( $file['base'] ) && !file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
                $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'wb' );
                // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen
                
                if ( $file_handle ) {
                    fwrite( $file_handle, $file['content'] );
                    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite
                    fclose( $file_handle );
                    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
                }
            
            }
        
        }
    }
    
    /**
     * Define Card Oracle Constants.
     *
     * @since    1.1.6
     * @access   private
     */
    private function define_constants()
    {
        $upload_dir = wp_upload_dir( null, false );
        $this->define( 'CARD_ORACLE_DIR', plugin_dir_path( dirname( __FILE__ ) ) );
        $this->define( 'CARD_ORACLE_URL', plugin_dir_url( dirname( __FILE__ ) ) );
        $this->define( 'APPLICATION_JSON', 'application/json' );
        $this->define( 'CARD_ORACLE_ADMIN_CSS_URL', CARD_ORACLE_URL . 'admin/css/min/card-oracle-admin.min.css' );
        $this->define( 'CARD_ORACLE_CARD_LIMIT', 25 );
        $this->define( 'CARD_ORACLE_CHECK', '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill:#FFF;transform:;-ms-filter:"><path d="M10 15.586L6.707 12.293 5.293 13.707 10 18.414 19.707 8.707 18.293 7.293z"></path></svg>' );
        $this->define( 'CARD_ORACLE_CLIPPY', CARD_ORACLE_URL . 'assets/images/clippy.svg' );
        $this->define( 'CARD_ORACLE_CROSS', '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" style="fill:#FF0000;transform:;-ms-filter:"><path d="M16.192 6.344L11.949 10.586 7.707 6.344 6.293 7.758 10.535 12 6.293 16.242 7.707 17.656 11.949 13.414 16.192 17.656 17.606 16.242 13.364 12 17.606 7.758z"></path></svg>' );
        $this->define( 'CARD_ORACLE_CSS_PREMIUM_URL', CARD_ORACLE_URL . 'public/css/min/card-oracle-public__premium_only.min.css' );
        $this->define( 'CARD_ORACLE_CSS_PREMIUM', CARD_ORACLE_DIR . 'public/css/min/card-oracle-public__premium_only.min.css' );
        $this->define( 'CARD_ORACLE_CSS_URL', CARD_ORACLE_URL . 'public/css/min/card-oracle-public.min.css' );
        $this->define( 'CARD_ORACLE_CSS', CARD_ORACLE_DIR . 'public/css/min/card-oracle-public.min.css' );
        $this->define( 'CARD_ORACLE_DAILY_CARD', 'card_oracle_daily_card' );
        $this->define( 'CARD_ORACLE_EXTENDED_LAYOUT', CARD_ORACLE_DIR . 'public/layouts/extended.php' );
        $this->define( 'CARD_ORACLE_LOG_DIR', $upload_dir['basedir'] . '/card-oracle/' );
        $this->define( 'CARD_ORACLE_OPTION_PREFIX', 'card_oracle_' );
        $this->define( 'CARD_ORACLE_RANDOM_CARD', 'card_oracle_random_card' );
        $this->define( 'CARD_ORACLE_RANDOM_DAYS', 'card_oracle_random_days' );
        $this->define( 'CARD_ORACLE_TRANSIENT_LENGTH', 22 );
        $this->define( 'CARD_ORACLE_VERSION', '1.1.6' );
        $this->define( 'BEFORE_CARDS_TEXT', 'before_cards_text' );
        $this->define( 'CO_AMOUNT', '_co_amount' );
        $this->define( 'CO_AUTO_SUBMIT', '_co_auto_submit' );
        $this->define( 'CO_CARD_ID', '_co_card_id' );
        $this->define( 'CO_CARD_ORDER', '_co_card_order' );
        $this->define( 'CO_COMPLETED', 'Completed' );
        $this->define( 'CO_DECK_LAYOUT', '_co_deck_layout' );
        $this->define( 'CO_IPN_EMAIL', '_co_ipn_email' );
        $this->define( 'CO_IPN_TXN_ID', '_co_ipn_txn_id' );
        $this->define( 'CO_LAYOUT_TABLE', '_co_layout_table' );
        $this->define( 'CO_ORDER_STATUS', '_co_order_status' );
        $this->define( 'CO_POSITION_ID', '_co_position_id' );
        $this->define( 'CO_POSITION_TEXT', '_co_position_text' );
        $this->define( 'CO_PRESENTATION_LAYOUT', '_co_presentation_layout' );
        $this->define( 'CO_PRICE', '_co_price' );
        $this->define( 'CO_PURCHASE_NAME', '_co_purchase_name' );
        $this->define( 'CO_PURCHASE_SUBJECT', '_co_purchase_subject' );
        $this->define( 'CO_QUESTION_LAYOUT', '_co_question_layout' );
        $this->define( 'CO_READING_ID', '_co_reading_id' );
        $this->define( 'CO_REVERSE_DESCRIPTION', '_co_reverse_description' );
        $this->define( 'CO_REVERSE_PERCENT', '_co_reverse_percent' );
        $this->define( 'CO_SALES_TEXT', '_co_sales_text' );
        $this->define( 'CO_SUBSCRIBER_LIST', '_co_subscriber_list' );
        $this->define( 'CO_TARGET_BLANK', '_co_target_blank' );
        $this->define( 'CO_TXN_ID_LINK', '_co_txn_id_link' );
        $this->define( 'DEMO_DATA', 'Demo Data' );
        $this->define( 'DISPLAY_QUESTION', 'display_question' );
        $this->define( 'FOOTER_TEXT', 'footer_text' );
        $this->define( 'QUESTION_TEXT', 'question_text' );
        $this->define( 'STANDARD_MOBILE', 'standard_mobile' );
        $this->plugin_name = 'card-oracle';
        $this->version = CARD_ORACLE_VERSION;
    }
    
    /**
     * Define constant if not already set.
     *
     * @param string      $name  Constant name.
     * @param string|bool $value Constant value.
     */
    private function define( $name, $value )
    {
        if ( !defined( $name ) ) {
            define( $name, $value );
        }
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    0.13.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new CardOracleAdmin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        // Call Freemius uninstall.
        card_oracle_fs()->add_action( 'after_uninstall', $plugin_admin, 'card_oracle_fs_uninstall_cleanup' );
        // call custom post types.
        $this->loader->add_action( 'init', $plugin_admin, 'register_card_oracle_cpt' );
        // Custom columns for admin screens.
        $this->loader->add_filter( 'manage_edit-co_cards_columns', $plugin_admin, 'set_custom_cards_columns' );
        $this->loader->add_filter( 'manage_edit-co_cards_sortable_columns', $plugin_admin, 'set_custom_sortable_card_columns' );
        $this->loader->add_filter( 'manage_edit-co_descriptions_columns', $plugin_admin, 'set_custom_descriptions_columns' );
        $this->loader->add_filter( 'manage_edit-co_descriptions_sortable_columns', $plugin_admin, 'set_custom_sortable_description_columns' );
        $this->loader->add_filter( 'manage_edit-co_readings_columns', $plugin_admin, 'set_custom_readings_columns' );
        $this->loader->add_filter( 'manage_edit-co_positions_columns', $plugin_admin, 'set_custom_positions_columns' );
        $this->loader->add_filter( 'manage_edit-co_positions_sortable_columns', $plugin_admin, 'set_custom_sortable_position_columns' );
        $this->loader->add_action( 'manage_co_cards_posts_custom_column', $plugin_admin, 'custom_card_column' );
        $this->loader->add_action( 'manage_co_descriptions_posts_custom_column', $plugin_admin, 'custom_card_column' );
        $this->loader->add_action( 'manage_co_readings_posts_custom_column', $plugin_admin, 'custom_card_column' );
        $this->loader->add_action( 'manage_co_positions_posts_custom_column', $plugin_admin, 'custom_card_column' );
        $this->loader->add_filter( 'bulk_actions-edit-co_order', $plugin_admin, 'card_oracle_remove_from_bulk_actions' );
        // Add Menu items.
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'card_oracle_menu_items' );
        // Add Options items.
        $this->loader->add_action( 'admin_init', $plugin_admin, 'card_oracle_setup_general_options' );
        // Add metaboxes.
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes_for_readings_cpt' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes_for_positions_cpt' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes_for_cards_cpt' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes_for_descriptions_cpt' );
        $this->loader->add_action( 'do_meta_boxes', $plugin_admin, 'cpt_image_box' );
        $this->loader->add_action( 'save_post', $plugin_admin, 'save_card_oracle_meta_data' );
        // Demo Data.
        $this->loader->add_action( 'admin_action_demo_data', $plugin_admin, 'card_oracle_demo_data' );
        // Add links to description on plugin page.
        $this->loader->add_filter(
            'plugin_row_meta',
            $this,
            'card_oracle_plugin_row_meta',
            10,
            2
        );
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    0.5.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Card_Oracle_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        // Add the shortchodes.
        $this->loader->add_shortcode( 'card-oracle', $plugin_public, 'display_card_oracle_set' );
        $this->loader->add_shortcode( 'card-oracle-daily', $plugin_public, 'display_card_oracle_card_of_day' );
        $this->loader->add_shortcode( 'card-oracle-random', $plugin_public, 'display_card_oracle_random_card' );
        // Add Ajax for sending emails.
        $this->loader->add_action( 'wp_ajax_send_reading_email', $plugin_public, 'card_oracle_send_reading_email' );
        $this->loader->add_action( 'wp_ajax_nopriv_send_reading_email', $plugin_public, 'card_oracle_send_reading_email' );
        $this->loader->add_action( 'wp_mail_failed', $plugin_public, 'card_oracle_mail_error' );
    }
    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 0.5.0
     */
    public function run()
    {
        $this->loader->run();
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     0.5.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     0.5.0
     * @return    Card_Oracle_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }
    
    /**
     * Retrieve the version number of the plugin.
     *
     * @since     0.5.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
    
    /**
     * Update anything after the version number of the plugin changes.
     *
     * @since     0.18.0
     * @return    void
     */
    public function card_oracle_check_version()
    {
        // Update any settings that have changed.
        
        if ( get_option( 'card_oracle_paypal_button_text' ) ) {
            update_option( 'card_oracle_payment_button_text', get_option( 'card_oracle_paypal_button_text' ) );
            delete_option( 'card_oracle_paypal_button_text' );
        }
        
        
        if ( get_option( 'card_oracle_paypal_currency' ) ) {
            update_option( 'card_oracle_payment_currency', get_option( 'card_oracle_paypal_currency' ) );
            delete_option( 'card_oracle_paypal_currency' );
        }
        
        // if the version in the db and the plugin version are different do updates.
        $current_version = get_option( 'card_oracle_version' );
        
        if ( $this->get_version() !== $current_version ) {
            /**
             * Add any updates required to the DB or options here when
             * updating from one version to another.
             */
            // Update old card_oracle_mailchimp_send option to card_oracle_mailchimp_daily.
            
            if ( get_option( 'card_oracle_mailchimp_send' ) ) {
                update_option( 'card_oracle_mailchimp_daily', get_option( 'card_oracle_mailchimp_send' ) );
                delete_option( 'card_oracle_mailchimp_send' );
            }
            
            // Updated WordPress options after version 0.7.2, add card_oracle_ prefix to avoid clashes with other plugins.
            
            if ( version_compare( $current_version, '0.7.2', '<' ) ) {
                $old_options = array(
                    'multiple_positions',
                    'allow_email',
                    'from_email',
                    'from_email_name',
                    'email_subject',
                    'email_text',
                    'email_success'
                );
                foreach ( $old_options as $old_option ) {
                    
                    if ( get_option( $old_option ) ) {
                        // Update the option name by adding the new name and deleting the old option name.
                        update_option( 'card_oracle_' . $old_option, get_option( $old_option ) );
                        delete_option( $old_option );
                    }
                
                }
            }
            
            // Remove old options.
            if ( get_option( 'card_oracle_multiple_positions' ) ) {
                delete_option( 'card_oracle_multiple_positions' );
            }
            update_option( 'card_oracle_version', CARD_ORACLE_VERSION );
        }
    
    }
    
    /**
     * Remove data for the Card Oracle custom post types.
     *
     * @since     0.13.0
     */
    public function card_oracle_fs_uninstall_cleanup()
    {
        // Delete the Card Oracle Version from the options.
        delete_option( 'card_oracle_version' );
    }
    
    /**
     * Show row meta on the plugin screen.
     *
     * @param mixed $links Plugin Row Meta.
     * @param mixed $file  Plugin Base file.
     *
     * @return array
     */
    public static function card_oracle_plugin_row_meta( $links, $file )
    {
        
        if ( strpos( $file, 'card-oracle.php' ) !== false ) {
            $row_meta = array(
                'videos' => '<a href="' . esc_url( 'https://www.chillichalli.com/tarot-card-oracle-videos/' ) . '" aria-label="' . esc_attr__( 'Video Tutorials', 'card-oracle' ) . '">' . esc_html__( 'Video Tutorials', 'card-oracle' ) . '</a>',
            );
            return array_merge( $links, $row_meta );
        }
        
        return $links;
    }

}