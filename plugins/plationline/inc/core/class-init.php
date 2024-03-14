<?php

namespace PlatiOnlinePO6\Inc\Core;

/**
 * @link              https://plati.online
 * @since             6.0.0
 * @package           PlatiOnlinePO6
 *
 */

use PlatiOnlinePO6 as NS;
use PlatiOnlinePO6\Inc\Admin as Admin;
use PlatiOnlinePO6\Inc\Core\Internationalization_I18n as Internationalization_I18n;
use PlatiOnlinePO6\Inc\Core\WC_Plationline_Login as WC_Plationline_Login;
use PlatiOnlinePO6\Inc\Core\WC_Plationline_Process as WC_Plationline_Process;

class Init
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var      Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_base_name The string used to uniquely identify this plugin.
     */
    protected $plugin_basename;
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
     * The text domain of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $plugin_text_domain;

    /**
     * Initialize and define the core functionality of the plugin.
     */
    public function __construct()
    {
        $this->plugin_name = NS\PLUGIN_NAME;
        $this->version = NS\PLUGIN_VERSION;
        $this->plugin_basename = NS\PLUGIN_BASENAME;
        $this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;

        $this->load_dependencies();
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $this->loader->add_action('admin_notices', $this, 'plationline_missing_wc_notice');
            return;
        }
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->load_woocommerce_class();
    }

    /**
     * Loads the following required dependencies for this plugin.
     *
     * - Loader - Orchestrates the hooks of the plugin.
     * - Internationalization_I18n - Defines internationalization functionality.
     * - Admin - Defines all hooks for the admin area.
     * - Frontend - Defines all hooks for the public side of the site.
     *
     * @access    private
     */
    private function load_dependencies()
    {
        $this->loader = new Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Internationalization_I18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @access    private
     */
    private function set_locale()
    {
        $plugin_i18n = new Internationalization_I18n($this->plugin_text_domain);

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @access    private
     */
    private function define_admin_hooks()
    {
        $poInit = new WC_Plationline_Process();
        $plugin_admin = new Admin\Admin($this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain());
        $this->loader->add_filter('woocommerce_payment_gateways', $poInit, 'plationline_gateway');
        $this->loader->add_filter('woocommerce_reports_order_statuses', $poInit, 'add_po_statuses_to_reports_admin');
        $this->loader->add_filter('wc_order_statuses', $poInit, 'add_order_statuses');
        $this->loader->add_filter('init', $poInit, 'register_po_order_statuses');
        $this->loader->add_filter('wc_order_statuses', $poInit, 'add_order_statuses');
        $this->loader->add_action('woocommerce_receipt_plationline', $poInit, 'receipt_page');
        $this->loader->add_action('woocommerce_receipt_plationline_pr', $poInit, 'receipt_page_pr');
        $this->loader->add_action('woocommerce_receipt_plationline_recurrence', $poInit, 'receipt_page_recurrence');
        $this->loader->add_action('woocommerce_receipt_plationline_additional', $poInit, 'receipt_page_additional');
        if (in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $this->loader->add_action('woocommerce_receipt_plationline_woocommerce_subscriptions', $poInit, 'receipt_page_woocommerce_subscriptions');
        }
        $this->loader->add_action('valid-plationline-itsn-request', $poInit, 'itsn');
        $this->loader->add_action('valid-plationline-response', $poInit, 'po_response');
        $this->loader->add_action('woocommerce_api_wc_plationline', $poInit, 'check_itsn_response');
        $this->loader->add_shortcode('plationline_response', $poInit, 'check_plationline_response');
        $this->loader->add_action('woocommerce_email_order_meta', $poInit, 'plationline_email_payment_link', 10, 4);
        $this->loader->add_filter('woocommerce_email_format_string', $poInit, 'plationline_email_payment_link_format_string', 10, 2);
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_meta_box_po');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_query', $plugin_admin, 'query');
        $this->loader->add_action('wp_ajax_void', $plugin_admin, 'void');
        $this->loader->add_action('wp_ajax_cancel_recurrence', $plugin_admin, 'cancel_recurrence');
        $this->loader->add_action('wp_ajax_settle', $plugin_admin, 'settle');
        $this->loader->add_action('wp_ajax_refund', $plugin_admin, 'refund');
        $this->loader->add_action('wp_ajax_settle_amount', $plugin_admin, 'settle_amount');

        // send email for processing state admin
        $this->loader->add_action('woocommerce_order_status_changed', $plugin_admin, 'plationline_auth_to_processing', 10, 3);

        $this->loader->add_filter('woocommerce_endpoint_order-received_title', $poInit, 'plationline_change_order_received_title', 10, 2);
        $this->loader->add_filter('woocommerce_thankyou_order_received_text', $poInit, 'plationline_change_order_received_text', 10, 2);

        $this->loader->add_action('woocommerce_view_order', $poInit, 'plationline_retry_payment');
        $this->loader->add_filter('woocommerce_order_is_paid_statuses', $poInit, 'plationline_woocommerce_order_is_paid_statuses');

        $poRecurrence = new Admin\Admin_Recurrence($this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain());
        // add product data tab PlatiOnline for recurrence
        $this->loader->add_filter('woocommerce_product_data_tabs', $poRecurrence, 'plationline_create_plationline_tab_for_product');
        $this->loader->add_filter('woocommerce_product_data_panels', $poRecurrence, 'plationline_create_recurrence_checkbox_for_product');
        $this->loader->add_action('woocommerce_process_product_meta', $poRecurrence, 'plationline_save_recurrence_checkbox_for_product');

        // prevent delete master order
        $this->loader->add_action('wp_trash_post', $poRecurrence, 'plationline_recurrence_restrict_post_deletion');
        $this->loader->add_action('before_delete_post', $poRecurrence, 'plationline_recurrence_restrict_post_deletion');

        $this->loader->add_filter("plugin_action_links_" . $this->plugin_basename, $this, 'plationline_settings_link');

        add_action('before_woocommerce_init', function () {
            if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', $this->plugin_basename, true);
            }
        });
    }

    public function plationline_settings_link($links)
    {
        $settings_link = '<a href="' . \admin_url('admin.php?page=wc-settings&tab=checkout') . '">' . __('Settings', 'plationline') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    private function load_woocommerce_class()
    {
        $this->loader->add_action('plugins_loaded', new WC_Plationline_Process(), 'plationline_init');
    }

    private function define_public_hooks()
    {
        // login PO
        $poLogin = new WC_Plationline_Login($this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain());
        $this->loader->add_action('wp_enqueue_scripts', $poLogin, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $poLogin, 'enqueue_scripts');
        $this->loader->add_action('woocommerce_login_form_end', $poLogin, 'print_button');
        $this->loader->add_action('woocommerce_after_edit_account_address_form', $poLogin, 'import_button');
        $this->loader->add_action('woocommerce_register_form_start', $poLogin, 'print_button');
        $this->loader->add_action('woocommerce_api_wc_login_plationline', $poLogin, 'login_plationline');
        $this->loader->add_action('woocommerce_api_wc_login_plationline_edit_address', $poLogin, 'import_plationline');

        $this->loader->add_action('woocommerce_after_add_to_cart_form', $this, 'plationline_product_display_supports_recurrence');
        $this->loader->add_action('wp_enqueue_scripts', $this, 'enqueue_styles');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Retrieve the text domain of the plugin.
     *
     * @return    string    The text domain of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_text_domain()
    {
        return $this->plugin_text_domain;
    }

    public function plationline_product_display_supports_recurrence()
    {
        if (is_product()) {
            global $product;
            $poRecurrence = new WC_PlatiOnline_Recurrence();
            $plationline_recurrence_support = \get_post_meta($product->get_id(), '_plationline_enable_recurrence', true);
            if (\wc_string_to_bool($poRecurrence->enabled) && !empty($plationline_recurrence_support) && \wc_string_to_bool($plationline_recurrence_support)) {
                echo '<div class="plationline-supports-recurrence"><div><img alt="plationline supports recurrence" src="' . $poRecurrence->icon . '"/></div><div>' . __('This product supports PlatiOnline Recurring payments', 'plationline') . '</div></div>';
            }
        }
    }

    public function plationline_missing_wc_notice()
    {
        echo '<div class="error"><p><strong>' . __('PlatiOnline plugin requires WooCommerce to be installed and active', 'plationline') . '</strong></p></div>';
    }

    public function enqueue_styles()
    {
        \wp_enqueue_style('plationline_recurrence', \plugin_dir_url(__FILE__) . '../front/css/plationline-recurrence.css', array(), false, 'all');
    }
}
