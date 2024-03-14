<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 *
 * @package    Lpac_DPS
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
 * @package    Lpac_DPS
 * @subpackage Lpac_DPS/includes
 * @author_name    Uriahs Victor <info@soaringleads.com>
 */
namespace Lpac_DPS\Bootstrap;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Bootstrap\Loader ;
use  Lpac_DPS\Helpers\Functions ;
use  Lpac_DPS\Bootstrap\I18n ;
use  Lpac_DPS\Bootstrap\AdminEnqueues ;
use  Lpac_DPS\Bootstrap\FrontendEnqueues ;
use  Lpac_DPS\Bootstrap\SetupCron ;
use  Lpac_DPS\Views\Admin\Settings_Panel\RenderSettingsPanel ;
use  Lpac_DPS\Views\Frontend\OrderDetails as OrderDetailsView ;
use  Lpac_DPS\Views\Admin\Order as AdminOrderView ;
use  Lpac_DPS\Views\Admin\Admin as AdminView ;
use  Lpac_DPS\Controllers\Checkout_Page\OrderData as OrderDataController ;
use  Lpac_DPS\Controllers\Checkout_Page\Validate as CheckoutPageValidation ;
use  Lpac_DPS\Controllers\Checkout_Page\Ajax\Handlers as AjaxHandlers ;
use  Lpac_DPS\Controllers\Checkout_Page\ShippingMethods as ShippingMethodsController ;
use  Lpac_DPS\Controllers\CSF\Overrides as CSFOverrides ;
use  Lpac_DPS\Controllers\Email\Reminders ;
use  Lpac_DPS\Controllers\Email\OrderEmails as OrderEmailsController ;
use  Lpac_DPS\Controllers\Checkout_Page\Fees\TimeSlotFees ;
use  Lpac_DPS\Models\BaseModel ;
use  Lpac_DPS\Models\Plugin_Settings\GeneralSettings ;
use  Lpac_DPS\Notices\Loader as NoticesLoader ;
use  Lpac_DPS\Notices\Notice ;
use  Automattic\WooCommerce\Utilities\OrderUtil ;
/**
 * Class Main.
 *
 * Class responsible for firing public and admin hooks.
 */
class Main
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Lpac_DPS_Loader    $loader    Maintains and registers all hooks for the plugin.
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
     * Plugin instance
     *
     * @var mixed
     */
    private static  $instance ;
    /**
     * Gets an instance of our plugin.
     *
     * @return Main()
     */
    public static function get_instance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
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
    private function __construct()
    {
        $this->version = LPAC_DPS_VERSION;
        $this->plugin_name = LPAC_DPS_PLUGIN_NAME;
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
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
    private function load_dependencies()
    {
        $this->loader = new Loader();
        if ( is_admin() && !wp_doing_cron() && !wp_doing_ajax() ) {
            new RenderSettingsPanel();
        }
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Lpac_DPS_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new I18n();
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
        
        if ( !is_admin() && !wp_doing_cron() ) {
            return;
            // Bail if not admin request and not doing cron.
        }
        
        $plugin_admin = new AdminEnqueues();
        $admin_order_view = new AdminOrderView();
        $plugin_admin_view = new AdminView();
        $controller_csf_overrides = new CSFOverrides();
        $bootstrap_cron_setup = new SetupCron();
        $notices_loader = new NoticesLoader();
        $notice = new Notice();
        $email_reminders_controller = new Reminders();
        $controller_emails = new OrderEmailsController();
        $this->loader->add_action( 'admin_menu', $this, 'create_admin_menu' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_filter(
            'plugin_action_links',
            $this,
            'add_plugin_action_links',
            PHP_INT_MAX,
            2
        );
        // Custom admin order columns
        
        if ( Functions::usingHPOS() ) {
            $this->loader->add_filter( 'woocommerce_shop_order_list_table_columns', $plugin_admin_view, 'addDatetimeAdminListColumn' );
            $this->loader->add_action(
                'woocommerce_shop_order_list_table_custom_column',
                $plugin_admin_view,
                'addDatetimeAdminListColumnContent',
                10,
                2
            );
        } else {
            $this->loader->add_filter( 'manage_edit-shop_order_columns', $plugin_admin_view, 'addDatetimeAdminListColumn' );
            $this->loader->add_action(
                'manage_shop_order_posts_custom_column',
                $plugin_admin_view,
                'addDatetimeAdminListColumnContent',
                10,
                2
            );
        }
        
        // Notices.
        $this->loader->add_action( 'admin_notices', $notices_loader, 'load_notices' );
        // Notices Ajax dismiss method (uncomment if making use of notice class)
        $this->loader->add_action( 'wp_ajax_lpac_dps_dismiss_notice', $notice, 'dismiss_notice' );
        // Metaboxes.
        $this->loader->add_action( 'add_meta_boxes', $admin_order_view, 'create_metabox' );
        // Codestar Framework overrides.
        $csf_id = LPAC_DPS_CSF_ID;
        $this->loader->add_filter(
            "csf_{$csf_id}_save",
            $controller_csf_overrides,
            'handle_order_type_switchers',
            10,
            2
        );
        // Cron tasks.
        $this->loader->add_action( 'admin_init', $bootstrap_cron_setup, 'set_cron_tasks' );
        // Email Reminders
        $this->loader->add_action( 'dps_for_wc_email_reminder', $email_reminders_controller, 'sendReminder' );
        /*
         * Display date and time in order emails.
         */
        $enable_datetime_in_emails = GeneralSettings::getIncludeDateTimeInEmailsSetting();
        
        if ( $enable_datetime_in_emails ) {
            $datetime_location = GeneralSettings::getDateTimeInEmailsLocation();
            $this->loader->add_action(
                $datetime_location,
                $controller_emails,
                'addDateTimeToEmails',
                20,
                4
            );
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
        
        if ( is_admin() && !wp_doing_ajax() ) {
            return;
            // Bail if is admin request and not doing ajax.
        }
        
        $plugin_public = new FrontendEnqueues();
        $controller_checkout_order_data = new OrderDataController();
        $controller_checkout_page_validation = new CheckoutPageValidation();
        $controller_ajax_handlers = new AjaxHandlers();
        $controller_shipping_methods = new ShippingMethodsController();
        $controller_emails = new OrderEmailsController();
        $view_order_details_page = new OrderDetailsView();
        $controller_time_slot_fees = new TimeSlotFees();
        /*
         * If plugin not enabled don't continue
         */
        $plugin_enabled = filter_var( get_option( 'lpac_dps' )['general__enable_dps_plugin'] ?? false, FILTER_VALIDATE_BOOLEAN );
        if ( false === $plugin_enabled ) {
            return;
        }
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        /**
         * Set WC session data for plugin.
         */
        $this->loader->add_action( 'woocommerce_checkout_update_order_review', $controller_shipping_methods, 'setupSessionData' );
        /**
         * Output our fields on the checkout page.
         */
        $output_location = BaseModel::get_setting( 'general__fields_display_location', 'woocommerce_review_order_before_payment' );
        $output_location = apply_filters( 'dps_fields_checkout_page_location', $output_location );
        $class = new \Lpac_DPS\Views\Frontend\CheckoutPage();
        $priority = apply_filters( 'dps_fields_display_location_filter_priority', 10 );
        $this->loader->add_action(
            $output_location,
            $class,
            'add_checkout_fields',
            $priority
        );
        /**
         * Ajax Handler for getting available delivery times when a day of the week is selected.
         */
        $this->loader->add_action( 'wp_ajax_lpac_dps_get_times', $controller_ajax_handlers, 'getTimesAjaxHandler' );
        $this->loader->add_action( 'wp_ajax_nopriv_lpac_dps_get_times', $controller_ajax_handlers, 'getTimesAjaxHandler' );
        /**
         * Validate our checkout fields.
         */
        $this->loader->add_action(
            'woocommerce_after_checkout_validation',
            $controller_checkout_page_validation,
            'validate_future_date',
            10,
            2
        );
        $this->loader->add_action(
            'woocommerce_after_checkout_validation',
            $controller_checkout_page_validation,
            'validate_date_field',
            10,
            2
        );
        $this->loader->add_action(
            'woocommerce_after_checkout_validation',
            $controller_checkout_page_validation,
            'validate_time_field',
            10,
            2
        );
        /**
         * Save delivery/pickup date and time
         */
        $this->loader->add_action( 'woocommerce_checkout_update_order_meta', $controller_checkout_order_data, 'save_dps_data' );
        /*
         * Output LPAC DPS details on thank you and view order pages
         */
        $this->loader->add_action(
            'woocommerce_order_details_after_order_table',
            $view_order_details_page,
            'outputDeliveryPickupDetails',
            9
        );
        /**
         * Clear the shipping rate cache.
         */
        $this->loader->add_filter(
            'woocommerce_cart_shipping_packages',
            $controller_shipping_methods,
            'clearShippingRateCache',
            PHP_INT_MAX,
            2
        );
        /**
         * Show respective shipping methods based on the order type selected.
         * We need DPS to always be the last filter that runs on the package shipping rates because it ultimate decides which flatrate or local pickup rates should show.
         */
        $this->loader->add_filter(
            'woocommerce_package_rates',
            $controller_shipping_methods,
            'alterShippingMethods',
            PHP_INT_MAX,
            2
        );
        /*
         * Display date and time in order emails.
         */
        $enable_datetime_in_emails = GeneralSettings::getIncludeDateTimeInEmailsSetting();
        
        if ( $enable_datetime_in_emails ) {
            $datetime_location = GeneralSettings::getDateTimeInEmailsLocation();
            $this->loader->add_action(
                $datetime_location,
                $controller_emails,
                'addDateTimeToEmails',
                20,
                4
            );
        }
        
        /**
         * Timeslot fees
         */
        $this->loader->add_action(
            'woocommerce_cart_calculate_fees',
            $controller_time_slot_fees,
            'setAdditionalFee',
            100
        );
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
     * @return    Lpac_DPS_Loader    Orchestrates the hooks of the plugin.
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
     * Add action Links for plugin.
     *
     * @param array  $plugin_actions Plugin actions.
     * @param string $plugin_file Plugin file name.
     * @return array
     */
    public function add_plugin_action_links( $plugin_actions, $plugin_file )
    {
        $new_actions = array();
        
        if ( LPAC_DPS_BASE_FILE . '/delivery-and-pickup-scheduling.php' === $plugin_file ) {
            $new_actions['dps_wc_settings'] = sprintf( __( '<a href="%s">Settings</a>', 'delivery-and-pickup-scheduling-for-woocommerce' ), esc_url( admin_url( 'admin.php?page=lpac-dps-menu' ) ) );
            if ( false === dps_fs()->can_use_premium_code() ) {
                $new_actions['dps_upgrade_link'] = sprintf( __( '%1$sCheck out PRO%2$s', 'delivery-and-pickup-scheduling-for-woocommerce' ), '<a style="color: green; font-weight: bold" href="https://chwazidatetime.com/pricing?utm_source=plugin_actions_links&utm_medium=wp_plugins_area" target="_blank">', '</a>' );
            }
        }
        
        return array_merge( $plugin_actions, $new_actions );
    }
    
    /**
     * Create our SoaringLeads menu item.
     *
     * @return void
     * @since 1.0.3
     */
    public function create_admin_menu() : void
    {
        $icon = file_get_contents( LPAC_DPS_PLUGIN_ASSETS_DIR . 'admin/img/menu-icon.svg' );
        $icon = 'data:image/svg+xml;base64,' . base64_encode( $icon );
        $main_menu = menu_page_url( 'sl-plugins-menu', false );
        
        if ( !empty($main_menu) ) {
            return;
            // Menu already added by another SoarngLeads plugin
        }
        
        add_menu_page(
            __( 'SoaringLeads Plugins', 'delivery-and-pickup-scheduling-for-woocommerce' ),
            'SoaringLeads',
            'manage_options',
            'sl-plugins-menu',
            array( $this, 'output_root_submenu_upsells' ),
            $icon,
            '57.10'
        );
    }
    
    /**
     * HTML for root SoaringLeads page.
     *
     * Populate with upsell content.
     *
     * @since 1.0.3
     */
    public function output_root_submenu_upsells()
    {
        ?>
		<h1><?php 
        esc_html_e( 'Check out our available plugins', 'delivery-and-pickup-scheduling-for-woocommerce' );
        ?></h1>
		<hr style='margin-bottom: 40px'/>
		
		<div style='margin-bottom: 40px'>
		<a href='https://printus.cloud/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' target='_blank'><img src='<?php 
        echo  esc_attr( LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'admin/img/printus.png' ) ;
        ?>' /></a>
		<p style='font-size: 18px; font-weight: 700;'><?php 
        esc_html_e( 'Automatically print order invoices, receipts, package slips and labels to your local printer.', 'delivery-and-pickup-scheduling-for-woocommerce' );
        ?></p>	
		<a href='https://printus.cloud/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' target='_blank' class='button-primary'><?php 
        esc_html_e( 'Learn More', 'delivery-and-pickup-scheduling-for-woocommerce' );
        ?></a>
		</div>
		
		<div style='margin-bottom: 40px'>
		<a href='https://lpacwp.com/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' target='_blank'><img src='<?php 
        echo  esc_attr( LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'admin/img/lpac.png' ) ;
        ?>' /></a>
		<p style='font-size: 18px; font-weight: 700;'><?php 
        esc_html_e( 'Let customers choose their shipping or pickup location using a map during checkout.', 'delivery-and-pickup-scheduling-for-woocommerce' );
        ?></p>	
		<a href='https://lpacwp.com/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' target='_blank' class='button-primary'><?php 
        esc_html_e( 'Learn More', 'delivery-and-pickup-scheduling-for-woocommerce' );
        ?></a>
		</div>

		<div style='margin-bottom: 40px'>
		<a href='https://chwazidatetime.com/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' target='_blank'><img src='<?php 
        echo  esc_attr( LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'admin/img/delivery-and-pickup-scheduling.png' ) ;
        ?>' /></a>
		<p style='font-size: 18px; font-weight: 700;'><?php 
        echo  esc_html( 'Allow customers to set their delivery/pickup date and time during order checkout.', 'delivery-and-pickup-scheduling-for-woocommerce' ) ;
        ?></p>	
		<a href='https://chwazidatetime.com/?utm_source=wpadmin&utm_medium=sl-plugins-page&utm_campaign=plugins-upsell' target='_blank' class='button-primary'><?php 
        esc_html_e( 'Learn More', 'delivery-and-pickup-scheduling-for-woocommerce' );
        ?></a>
		</div>

		<?php 
    }

}