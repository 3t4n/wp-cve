<?php

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
 * @package    Express_Checkout
 * @subpackage Express_Checkout/includes
 * @author     wpgateways <wpgateways@gmail.com>
 */
class Express_Checkout {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Express_Checkout_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    public $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    public $version;
    protected $express_checkout_utility;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'express-checkout';
        $this->version = '5.1.2';

        $this->load_dependencies();
        $this->set_locale();
        $this->woo_gateway_hooks();
        add_action('parse_request', array($this, 'handle_api_requests'), 0);
        add_action('express_checkout_api_ipn_handler', array($this, 'express_checkout_api_ipn_handler'));
        $prefix = is_network_admin() ? 'network_admin_' : '';
        add_filter("{$prefix}plugin_action_links_" . EXPRESS_CHECKOUT_BASENAME, array($this, 'express_checkout_plugin_link'), 10, 4);

        //add_action('after_plugin_row_' . EXPRESS_CHECKOUT_BASENAME, array($this, 'pec_upgrade_notice'), 0, 3);
        //add_action('wp_ajax_pec_dismiss_pec_upgrade_notice', array($this, 'pec_upgrade_notice_dismiss_ajax'));
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Express_Checkout_Loader. Orchestrates the hooks of the plugin.
     * - Express_Checkout_i18n. Defines internationalization functionality.
     * - Express_Checkout_Admin. Defines all hooks for the admin area.
     * - Express_Checkout_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-express-checkout-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-express-checkout-i18n.php';

        if (class_exists('WC_Payment_Gateway')) {
            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-express-checkout-gateway.php';
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/gateways/class-express-checkout-utility.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-express-checkout-api.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-express-checkout-api-request.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-express-checkout-api-response.php';

        $this->loader = new Express_Checkout_Loader();
        $this->express_checkout_utility = new Express_Checkout_Utility();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Express_Checkout_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Express_Checkout_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    private function woo_gateway_hooks() {

        add_filter('woocommerce_payment_gateways', array($this, 'add_express_checkout_gateway_class'), 10, 1);
        add_action('wp_footer', array($this, 'ex_hide_standard_checkout_button'));
        add_action('woocommerce_after_checkout_validation', array($this, 'ex_after_redirect'));
        add_action('wp', array($this, 'ex_cancel_url'));
        add_filter('woocommerce_add_to_cart_redirect', array($this, 'ex_woocommerce_add_to_cart_redirect'), 999, 1);

        if ($this->express_checkout_utility->express_checkout_get_option('show_on_cart_page') == "yes") {
            add_action('woocommerce_proceed_to_checkout', array($this, 'ex_express_checkout_button'), 22);
        }
        if ($this->express_checkout_utility->express_checkout_get_option('show_on_product_page') == "yes") {
            add_action('woocommerce_after_add_to_cart_button', array($this, 'ex_express_checkout_button'), 22);
        }
        if ($this->express_checkout_utility->express_checkout_get_option('show_on_checkout') == "yes") {
            add_action('woocommerce_before_checkout_form', array($this, 'ex_express_checkout_button'), 22);
        }
        add_action('wp_enqueue_scripts', array($this, 'ex_enqueue_scripts_product_page'));
        add_action('http_api_curl', array($this, 'http_api_curl_ex_add_curl_parameter'), 10, 3);
    }

    public function handle_api_requests() {

        global $wp;
        if (isset($_GET['action']) && $_GET['action'] == 'ipn_handler') {
            $wp->query_vars['Express_Checkout'] = wc_clean(wp_unslash($_GET['action']));
        }
        if (!empty($wp->query_vars['Express_Checkout'])) {
            ob_start();
            $api = strtolower(esc_attr($wp->query_vars['Express_Checkout']));
            do_action('express_checkout_api_' . strtolower($api));
            ob_end_clean();
            die('1');
        }
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Express_Checkout_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    public function add_express_checkout_gateway_class($methods) {
        $methods[] = 'Express_Checkout_Gateway';
        return $methods;
    }

    public function ex_hide_standard_checkout_button() {
        if ('no' == $this->express_checkout_utility->express_checkout_get_option('show_on_cart_page')) {
            return;
        }
        $is_button_hide = $this->express_checkout_utility->express_checkout_get_option('hide_standard_checkout_button');
        if ($this->express_checkout_utility->express_checkout_is_available() && $is_button_hide == 'yes') {
            ?>
            <style type="text/css">
                .woocommerce a.checkout-button,
                .woocommerce input.checkout-button,
                .cart input.checkout-button,
                .cart a.checkout-button,
                .widget_shopping_cart a.checkout {
                    display: none !important;
                }
            </style>
            <?php

        }
    }

    public function ex_express_checkout_button() {
        $this->express_checkout_utility->ex_express_checkout_button();
    }

    public function ex_after_redirect() {
        if (isset($_POST['payment_method']) && 'express_checkout' === $_POST['payment_method'] && $this->express_checkout_utility->ex_notice_count('error') == 0) {
            WC()->session->post_data = wc_clean(wp_unslash($_POST));
            $this->express_checkout_utility->ex_redirect_after_checkout();
        }
    }

    public function ex_enqueue_scripts_product_page() {

        if (is_checkout()) {
            wp_enqueue_script('ex-express-checkout-min-js', EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/js/ex-express-chekout.min.js', array(), $this->version, 'all');
            wp_enqueue_script('ex-express-checkout-custom', EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/js/ex-express-chekout-custom.js', array(), $this->version, 'all');
            wp_enqueue_style('ex-express-checkout-min-css', EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/css/ex-express-chekout.min.css', array(), $this->version, 'all');
            wp_localize_script('ex-express-checkout-min-js', 'ex_express_checkout', array('is_page_name' => 'checkout_page'));
            wp_localize_script('ex-express-checkout-custom', 'ex_express_checkout', array('is_page_name' => 'checkout_page'));
        }

        if (is_product()) {
            wp_enqueue_style('ex-express-checkout-min-css', EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/css/ex-express-chekout.min.css', array(), $this->version, 'all');
            wp_enqueue_script('ex-express-checkout-custom', EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/js/ex-express-chekout-custom.js', array(), $this->version, 'all');
            wp_localize_script('ex-express-checkout-custom', 'ex_express_checkout', array('is_page_name' => 'single_product_page'));
        }

        if (is_cart()) {
            wp_enqueue_script('ex-express-checkout-custom', EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/js/ex-express-chekout-custom.js', array(), $this->version, 'all');
            wp_localize_script('ex-express-checkout-custom', 'ex_express_checkout', array('is_page_name' => 'cart_page'));
            wp_enqueue_style('ex-express-checkout-min-css', EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/css/ex-express-chekout.min.css', array(), $this->version, 'all');
        }
        return true;
    }

    public function ex_cancel_url() {
        if (!empty($_GET['wc_paypal_express_clear_session'])) {
            unset(WC()->session->paypal_express_checkout);
            unset(WC()->session->paypal_express_terms);
            wc_add_notice(__('You have cancelled express checkout. Please try to process your order again.', 'express-checkout'), 'notice');
        }
    }

    public function ex_woocommerce_add_to_cart_redirect($url) {
        if (isset($_POST['express_checkout_button_product_page']) && !empty($_POST['express_checkout_button_product_page'])) {
            return wc_clean(wp_unslash($_POST['express_checkout_button_product_page']));
        } else {
            return $url;
        }
    }

    public function http_api_curl_ex_add_curl_parameter($handle, $r, $url) {
        if (strstr($url, 'https://') && strstr($url, '.paypal.com')) {
            curl_setopt($handle, CURLOPT_VERBOSE, 1);
            curl_setopt($handle, CURLOPT_SSLVERSION, 6);
        }
    }

    public function express_checkout_api_ipn_handler() {


        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-express-checkout-paypal-listner.php';
        $Express_Chekout_PayPal_listner = new Express_Chekout_PayPal_listner();
        if ($Express_Chekout_PayPal_listner->check_ipn_request()) {
            $Express_Chekout_PayPal_listner->successful_request($IPN_status = true);
        } else {
            $Express_Chekout_PayPal_listner->successful_request($IPN_status = false);
        }
    }

    public function express_checkout_plugin_link($actions, $plugin_file, $plugin_data, $context) {
        $custom_actions = array(
            'configure' => sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=wc-settings&tab=checkout&section=express_checkout'), __('Configure', 'express-checkout')),
            'docs' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://www.premiumdev.com/product/paypal-express-checkout-for-woocommerce/', __('Docs', 'express-checkout')),
            'support' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/plugin/express-checkout', __('Support', 'express-checkout')),
            'review' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/view/plugin-reviews/express-checkout', __('Write a Review', 'express-checkout')),
        );
        return array_merge($custom_actions, $actions);
    }

    public function pec_upgrade_notice($plugin_file, $plugin_data, $status) {
        if ('yes' === get_transient('pec-upgrade-notice-dismissed')) {
            return;
        }
        wp_enqueue_style('pec-upgrade-notice', EXPRESS_CHECKOUT_ASSET_URL . '/upgrade/pec-upgrade-notice.css', array(), $this->version);
        wp_enqueue_script('pec-upgrade-notice-js', EXPRESS_CHECKOUT_ASSET_URL . '/upgrade/pec-upgrade-notice.js', array(), $this->version, false);
        include_once plugin_dir_path(dirname(__FILE__)) . 'upgrade/psb-upgrade-notice.php';
    }

    public function pec_upgrade_notice_dismiss_ajax() {
        check_ajax_referer('pec-upgrade-notice-dismiss');
        set_transient('pec-upgrade-notice-dismissed', 'yes', MONTH_IN_SECONDS);
        wp_send_json_success();
    }

}
