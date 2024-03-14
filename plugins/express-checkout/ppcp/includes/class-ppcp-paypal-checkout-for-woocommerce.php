<?php

/**
 * @since      1.0.0
 * @package    PPCP_Paypal_Checkout_For_Woocommerce
 * @subpackage PPCP_Paypal_Checkout_For_Woocommerce/ppcp/includes
 * @author     PayPal <mbjwebdevelopment@gmail.com>
 */
class PPCP_Paypal_Checkout_For_Woocommerce {

    protected $loader;
    protected $plugin_name;
    protected $version;
    public $button_manager;

    public function __construct() {
        if (defined('EXPRESS_CHECKOUT_VERSION')) {
            $this->version = EXPRESS_CHECKOUT_VERSION;
        } else {
            $this->version = '5.1.2';
        }
        $this->plugin_name = 'express-checkout';
        add_filter('woocommerce_payment_gateways', array($this, 'ppcp_woocommerce_payment_gateways'),9999);
        $prefix = is_network_admin() ? 'network_admin_' : '';
        add_filter("{$prefix}plugin_action_links_" . EXPRESS_CHECKOUT_BASENAME, array($this, 'ppcp_plugin_action_links'), 10, 1);
        $this->load_dependencies();
        $this->set_locale();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once EXPRESS_CHECKOUT_DIR . '/ppcp/includes/ppcp-paypal-checkout-for-woocommerce-function.php';
        require_once EXPRESS_CHECKOUT_DIR . '/ppcp/includes/class-ppcp-paypal-checkout-for-woocommerce-loader.php';
        require_once EXPRESS_CHECKOUT_DIR . '/ppcp/includes/class-ppcp-paypal-checkout-for-woocommerce-i18n.php';
        require_once EXPRESS_CHECKOUT_DIR . '/ppcp/public/class-ppcp-paypal-checkout-for-woocommerce-button-manager.php';
        require_once EXPRESS_CHECKOUT_DIR . '/ppcp/includes/class-ppcp-paypal-checkout-for-woocommerce-product.php';
        require_once EXPRESS_CHECKOUT_DIR . '/ppcp/includes/class-ppcp-paypal-checkout-for-woocommerce-pay-later-messaging.php';
        $this->loader = new PPCP_Paypal_Checkout_For_Woocommerce_Loader();
    }

    private function set_locale() {
        $plugin_i18n = new PPCP_Paypal_Checkout_For_Woocommerce_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    private function define_public_hooks() {
        $this->button_manager = PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager::instance();
        PPCP_Paypal_Checkout_For_Woocommerce_Pay_Later::instance();
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_loader() {
        return $this->loader;
    }

    public function get_version() {
        return $this->version;
    }

    public function ppcp_woocommerce_payment_gateways($methods) {
        include_once EXPRESS_CHECKOUT_DIR . '/ppcp/includes/class-ppcp-paypal-checkout-for-woocommerce-gateway.php';
        $methods[] = 'PPCP_Paypal_Checkout_For_Woocommerce_Gateway';
        $methods = array_reverse($methods);
        return $methods;
    }

    public function ppcp_plugin_action_links($actions) {
        $custom_actions = array(
            'configure' => sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=wc-settings&tab=checkout&section=ppcp_paypal_checkout'), __('Configure', 'express-checkout')),
            'support' => '<a href="' . esc_url('https://wordpress.org/support/plugin/ppcp-paypal-checkout-for-woocommerce/') . '" aria-label="' . esc_attr__('Support', 'express-checkout') . '">' . esc_html__('Support', 'express-checkout') . '</a>',
            'reviews' => '<a href="' . esc_url('https://wordpress.org/support/plugin/ppcp-paypal-checkout-for-woocommerce/reviews/#new-post') . '" aria-label="' . esc_attr__('Write a Review', 'express-checkout') . '">' . esc_html__('Write a Review', 'express-checkout') . '</a>',
        );
        return array_merge($custom_actions, $actions);
    }
}
