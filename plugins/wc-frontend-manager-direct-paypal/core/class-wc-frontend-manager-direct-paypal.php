<?php

use WCFM\PaypalMarketplace\Client;
use WCFM\PaypalMarketplace\Helper;
use WCFM\PaypalMarketplace\WebhookHandler;
use Automattic\WooCommerce\Utilities\OrderUtil;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WCFM PG Direct PayPal Pay plugin core
 *
 * Plugin intiate
 *
 * @package wc-frontend-manager-direct-paypal
 * @author WC Lovers
 * @since 1.0.0
 */

class WCFM_PG_Direct_PayPal {

    public $file;
    public $plugin_base_name;
    public $plugin_url;
    public $plugin_path;
    public $version;
    public $token;
    public $text_domain;
    public $webhook_handler;

    /**
     * Constructor
     *
     * @param $file __FILE__
     */
    public function __construct($file) {
        $this->file = $file;
        $this->plugin_base_name = plugin_basename($file);
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->token = WCFMpgdp_TOKEN;
        $this->text_domain = WCFMpgdp_TEXT_DOMAIN;
        $this->version = WCFMpgdp_VERSION;
        $this->webhook_handler = new WebhookHandler();

        add_action('wcfm_init', [$this, 'init'], 10);
        add_action('plugins_loaded', [$this, 'init_payment_gateway_class']);
        add_action('woocommerce_loaded', [$this, 'init_woocommerce_hooks']);
    }

    /**
     *  Runs after WCFM loaded
     */
    public function init() {
        global $WCFM, $WCFMmp;

        // Init Text Domain
        $this->load_plugin_textdomain();

        add_filter('wcfm_marketplace_withdrwal_payment_methods', [$this, 'wcfm_register_withdrawal_method']);

        add_action('wcfm_vendor_end_settings_payment', [$this, 'wcfm_paypal_marketplace_fields']);

        /**
         * TODO: add payment fields support in vendor details page & setup wizard
         */
        // add_filter( 'wcfm_marketplace_settings_fields_billing', [ $this, 'wcfm_paypal_marketplace_fields' ], 10, 2 );

        add_action('after_wcfm_load_styles', [$this, 'wcfm_frontend_styles']);

        add_action('after_wcfm_load_scripts', [$this, 'wcfm_frontend_scripts']);

        add_action('wp_ajax_wcfm_paypal_marketplace_connect', [$this, 'wcfm_paypal_marketplace_connect']);

        add_action('wp_ajax_wcfm_paypal_marketplace_connect_success', [$this, 'wcfm_paypal_marketplace_connect_success']);

        add_action('wp_ajax_wcfm_paypal_marketplace_disconnect', [$this, 'wcfm_paypal_marketplace_disconnect']);

        add_filter('wcfmmp_auto_withdrawal_exclude_payment_methods', [$this, 'wcfm_exclude_paypal_auto_withdrawal'], 10);

        add_action('wcfm_paypal_capture_payment_completed', [$this, 'wcfm_process_withdrawal'], 10, 3);

        add_filter('wcfm_is_allow_api_refund', [$this, 'disable_api_refund_for_paypal_marketplace'], 10, 2);

        add_filter('wcfm_enabled_payment_gateways_for_order_refund_action', [$this, 'enable_order_refund_action_for_paypal_marketplace']);

        add_action('admin_head', [$this, 'disable_admin_order_refund_button_wc_lt_6_4_0']);
    }

    /**
     * Load payment gateway
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function init_payment_gateway_class() {
        require_once $this->plugin_path . '/gateway/class-wcfmmp-gateway-paypal-marketplace.php';
    }

    /**
     * Defines WooCommerce specific hooks
     *
     * @since 2.0.1
     *
     * @return void
     */
    public function init_woocommerce_hooks() {
        add_filter('woocommerce_payment_gateways', [$this, 'wcfm_register_gateway']);
        add_action('woocommerce_after_checkout_validation', [$this, 'after_checkout_validation'], 15, 2);
        add_action('woocommerce_admin_order_should_render_refunds', [$this, 'disable_admin_order_refund_button'], 10, 3);
        add_action('woocommerce_admin_order_data_after_order_details', [$this, 'display_paypal_transaction_details']);
    }

    /**
     * Register payment gateway
     *
     * @since 2.0.0
     *
     * @param array $methods
     *
     * @return array $methods
     */
    public function wcfm_register_gateway($methods) {
        $methods[] = 'WCFMmp_Gateway_Paypal_Marketplace';

        return $methods;
    }

    /**
     * Register the withdrawal method
     *
     * @param array $marketplace_withdrawal_payment_methods
     * @return array $marketplace_withdrawal_payment_methods
     */
    public function wcfm_register_withdrawal_method($marketplace_withdrawal_payment_methods) {
        if (Helper::is_enabled() && !isset($marketplace_withdrawal_payment_methods['paypal_marketplace'])) {
            $marketplace_withdrawal_payment_methods['paypal_marketplace'] = __('WCFM PayPal Marketplace', 'wc-frontend-manager-direct-paypal');
        }

        return $marketplace_withdrawal_payment_methods;
    }

    /**
     * Adds the paypal marketplace fields
     *
     * @param int $user_id
     * @return void
     */
    public function wcfm_paypal_marketplace_fields($user_id) {
        global $wcfmpgdp;

        $withdrawal_payment_methods = get_wcfm_marketplace_active_withdrwal_payment_methods();

        if (array_key_exists('paypal_marketplace', $withdrawal_payment_methods)) {
            $vendor_data        = get_user_meta($user_id, 'wcfmmp_profile_settings', true);
            $paypal_email       = isset($vendor_data['payment']['paypal_marketplace']['email']) ? esc_attr($vendor_data['payment']['paypal_marketplace']['email']) : '';
            $paypal_settings    = get_user_meta($user_id, Helper::get_paypal_marketplace_settings_key(), true);
            $paypal_connected   = isset($paypal_settings['connection_status'])
                ? (('success' === $paypal_settings['connection_status']) ? true : false)
                : false;

            include $wcfmpgdp->plugin_path . 'views/wcfmdp-view-paypal-marketplace-settings-fields.php';
        }
    }

    /**
     * Enqueues frontend styles
     *
     * * @since 2.0.0
     */
    public function wcfm_frontend_styles($endpoint) {
        global $wcfmpgdp;

        if ('wcfm-settings' !== $endpoint) {
            return;
        }

        $suffix  = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

        wp_enqueue_style('wcfm_paypal_frontend', $wcfmpgdp->plugin_url . 'assets/css/wcfm-paypal-frontend' . $suffix . '.css', array(), $wcfmpgdp->version);
    }

    /**
     * Enqueues frontend scripts
     *
     * * @since 2.0.0
     */
    public function wcfm_frontend_scripts($endpoint) {
        global $wcfmpgdp;

        if ('wcfm-settings' !== $endpoint) {
            return;
        }

        $suffix  = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

        wp_enqueue_script('wcfm_paypal_frontend', $wcfmpgdp->plugin_url . 'assets/js/wcfm-paypal-frontend' . $suffix . '.js', array(), $wcfmpgdp->version, true);
        wp_localize_script('wcfm_paypal_frontend', 'wcfm_paypal_frontend_l10n', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'connect_nonce' => wp_create_nonce('wcfm-paypal-connect-nonce'),
            'disconnect_nonce' => wp_create_nonce('wcfm-paypal-disconnect-nonce'),
            'paypal_btn_txt' => esc_html__('Connect to Paypal', 'wc-frontend-manager-direct-paypal')
        ]);
    }

    /**
     * Connects vendor to paypal
     *
     * @since 2.0.0
     */
    public function wcfm_paypal_marketplace_connect() {
        if (!isset($_POST['_nonce']) || !wp_verify_nonce(sanitize_key($_POST['_nonce']), 'wcfm-paypal-connect-nonce')) {
            wp_send_json_error(
                [
                    'type'    => 'error',
                    'message' => __('Invalid Nonce!!!', 'wc-frontend-manager-direct-paypal'),
                ]
            );
        }

        $email         = isset($_POST['vendor_email']) ? sanitize_email(wp_unslash($_POST['vendor_email'])) : '';

        if (!$email) {
            wp_send_json_error(
                [
                    'type'    => 'error',
                    'message' => __('Email address field is required.', 'wc-frontend-manager-direct-paypal'),
                ]
            );
        } else if (!is_email($email)) {
            wp_send_json_error(
                [
                    'type'    => 'error',
                    'message' => __('Please enter a valid Email address.', 'wc-frontend-manager-direct-paypal'),
                ]
            );
        }

        $user_id        = get_wcfm_current_vendor_id();
        $vendor_data    = get_user_meta($user_id, 'wcfmmp_profile_settings', true);
        $tracking_id    = '_wcfm_paypal_' . wp_generate_password(13, false) . '_' . $user_id;

        /**
         * @see https://developer.paypal.com/docs/multiparty/seller-onboarding/onboarding-checklist/#link-products
         */
        $product_type = apply_filters('wcfm_paypal_marketplace_product_type', Helper::get_product_type($vendor_data['address']['country']));

        if (!$product_type) {
            wp_send_json_error(
                [
                    'type'    => 'error',
                    'message' => __('Vendor\'s country is not supported by PayPal.', 'wc-frontend-manager-direct-paypal'),
                ]
            );
        }

        $client = Client::init();
        $response = $client->generate_sign_up_link($email, $tracking_id, [$product_type]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }

        if (isset($response->links[1]) && 'action_url' === $response->links[1]->rel) {

            update_user_meta(
                $user_id,
                Helper::get_paypal_marketplace_settings_key(),
                [
                    'connection_started'    => true,
                    'connection_status'     => 'pending',
                    'email'                 => $email,
                    'tracking_id'           => $tracking_id,
                ]
            );

            wp_send_json_success([
                'url' => $response->links[1]->href,
                'reload'  => true,
                'message' => __('Successfully generated paypal connect link, redirecting to paypal...', 'wc-frontend-manager-direct-paypal')
            ]);
        }

        wp_send_json_error(['message' => __('Unable to process request', 'wc-frontend-manager-direct-paypal')]);
    }

    public function wcfm_paypal_marketplace_connect_success() {
        $response = $_GET;
        $vendor_id = get_wcfm_current_vendor_id();

        if (!wcfm_is_vendor($vendor_id)) {
            wp_send_json_error(
                [
                    'type'    => 'error',
                    'message' => __('Invalid vendor ID!!!', 'wc-frontend-manager-direct-paypal'),
                ]
            );
        }

        if (!isset($response['_wpnonce']) || !wp_verify_nonce(sanitize_key($response['_wpnonce']), 'wcfm-paypal-marketplace-connect-success')) {
            wp_send_json_error(
                [
                    'type'    => 'error',
                    'message' => __('Invalid Nonce!!!', 'wc-frontend-manager-direct-paypal'),
                ]
            );
        }

        $paypal_settings = get_user_meta($vendor_id, Helper::get_paypal_marketplace_settings_key(), true);

        if (isset($response['merchantIdInPayPal']) && !empty($response['merchantIdInPayPal'])) {
            $merchant_id = $response['merchantIdInPayPal'];
            update_user_meta($vendor_id, Helper::get_paypal_merchant_id_key(), esc_sql($merchant_id));
            $paypal_settings['connection_status'] = 'success';
            $paypal_settings['merchant_id'] = $merchant_id;

            $vendor_data    = get_user_meta($vendor_id, 'wcfmmp_profile_settings', true);
            $vendor_data['payment']['method'] = 'paypal_marketplace';
			update_user_meta($vendor_id, 'wcfmmp_profile_settings', $vendor_data);
        }

        update_user_meta($vendor_id, Helper::get_paypal_marketplace_settings_key(), $paypal_settings);

        // fetch info using merchant id
        $client = Client::init();
        $merchant_info = $client->get_merchant_info($merchant_id);

        if (is_wp_error($merchant_info)) {
            wcfm_paypal_log('[WCFM Paypal Marketplace] Unable to fetch merchant details: ' . print_r($merchant_info, true), 'error');
        } else {
            Helper::update_merchant_info($vendor_id, $merchant_info);
        }

        wp_redirect(wcfm_get_endpoint_url_payment_tab());
        exit();
    }

    public function wcfm_paypal_marketplace_disconnect() {
        if (!isset($_POST['_nonce']) || !wp_verify_nonce(sanitize_key($_POST['_nonce']), 'wcfm-paypal-disconnect-nonce')) {
            wp_send_json_error(
                [
                    'type'    => 'error',
                    'message' => __('Invalid Nonce!!!', 'wc-frontend-manager-direct-paypal'),
                ]
            );
        }

        $vendor_id = get_wcfm_current_vendor_id();
        $paypal_settings = get_user_meta($vendor_id, Helper::get_paypal_marketplace_settings_key(), true);

        if (!empty($vendor_id)) {
            $paypal_settings['connection_status'] = 'disconnected';

            unset(
                $paypal_settings['connection_started'],
                $paypal_settings['merchant_id'],
                $paypal_settings['email'],
                $paypal_settings['tracking_id']
            );

            update_user_meta($vendor_id, Helper::get_paypal_marketplace_settings_key(), $paypal_settings);

            delete_user_meta($vendor_id, Helper::get_paypal_merchant_id_key());
            delete_user_meta($vendor_id, Helper::get_paypal_enabled_for_received_payment_key());
            delete_user_meta($vendor_id, Helper::get_paypal_payments_receivable_key());
            delete_user_meta($vendor_id, Helper::get_paypal_primary_email_confirmed_key());
            delete_user_meta($vendor_id, Helper::get_paypal_enable_for_ucc_key());

            $vendor_data    = get_user_meta($vendor_id, 'wcfmmp_profile_settings', true);
            $vendor_data['payment']['method'] = '';
			update_user_meta($vendor_id, 'wcfmmp_profile_settings', $vendor_data);

            wp_send_json_success([
                'url'       => wcfm_get_endpoint_url_payment_tab(),
                'message'   => __('You have successfully disconnected from Paypal', 'wc-frontend-manager-direct-paypal')
            ]);
        }

        wp_send_json_error(['message' => __('Unable to process request', 'wc-frontend-manager-direct-paypal')]);
    }

    public function after_checkout_validation($data, $errors) {
        if (Helper::payment_gateway_id() !== $data['payment_method']) {
            return;
        }

        $cart_items = [];
        foreach (WC()->cart->get_cart() as $item) {
            $product_id = $item['data']->get_id();
            $vendor_id  = wcfm_get_vendor_id_by_post($product_id);
            $cart_items[$vendor_id][] = $item['data'];
        }

        /**
         * @see https://developer.paypal.com/docs/multiparty/checkout/multiseller-payments/#link-knowbeforeyoucode
         *
         * This feature supports a maximum of 10 purchase_unit objects.
         * There is a timeout limit of 20 seconds for the API response.
         * If the 10 purchase units do not all process within that 20 seconds, a 504 timeout response is returned.
         */
        if (count($cart_items) > 10) {
            $errors->add(
                'paypal-error-purchase_unit-limit',
                sprintf(
                    __('<strong>Error!</strong> %1$s Does not support more than 10 vendor products in the cart. Please remove some vendor products to continue purchasing with %1$s', 'wc-frontend-manager-direct-paypal'),
                    Helper::payment_gateway_title(),
                )
            );
        }

        foreach ($cart_items as $store_id => $products) {
            if (!Helper::is_connected_to_paypal($store_id)) {
                $vendor_products = [];
                foreach ($products as $product) {
                    $vendor_products[] = sprintf('<a href="%s">%s</a>', $product->get_permalink(), $product->get_name());
                }

                $store_user = wcfmmp_get_store($store_id);
                $store_info = $store_user->get_shop_info();

                $errors->add(
                    'paypal-error-vendor-' . $store_id,
                    sprintf(
                        __('Please remove <strong>%s</strong> from the cart to checkout, as the vendor <strong>%s</strong> is not able to receive payment via Paypal', 'wc-frontend-manager-direct-paypal'),
                        implode(',', $vendor_products),
                        $store_info['store_name']
                    )
                );
            }
        }
    }

    public function wcfm_exclude_paypal_auto_withdrawal($methods) {
        if (!in_array(Helper::payment_gateway_id(), $methods)) {
            $methods[] = Helper::payment_gateway_id();
        }

        return $methods;
    }

    public function wcfm_process_withdrawal($order, $purchase_units, $paypal_order_id) {
        global $wpdb, $WCFMmp, $WCFM;

        $order_id = $order->get_id();

        $vendor_wise_gross_sales = $WCFMmp->wcfmmp_commission->wcfmmp_split_pay_vendor_wise_gross_sales($order);

        foreach ($vendor_wise_gross_sales as $vendor_id => $gross_sales) {
            $capture_id = $order->get_meta('_wcfm_paypal_payment_charge_captured__for_vendor_' . $vendor_id, true);
            $store_name = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_name_by_vendor(absint($vendor_id));

            $re_total_commission = $wpdb->get_var($wpdb->prepare("SELECT SUM(total_commission) as total_commission FROM `{$wpdb->prefix}wcfm_marketplace_orders` WHERE order_id = %d AND vendor_id = %d", $order_id, $vendor_id));

            // Create vendor withdrawal Instance
            $commission_id_list = $wpdb->get_col($wpdb->prepare("SELECT ID FROM `{$wpdb->prefix}wcfm_marketplace_orders` WHERE order_id = %d AND vendor_id = %d", $order_id, $vendor_id));

            $withdrawal_id = $WCFMmp->wcfmmp_withdraw->wcfmmp_withdrawal_processed($vendor_id, $order_id, implode(',', $commission_id_list), 'paypal_marketplace', $gross_sales, $re_total_commission, 0, 'pending', 'by_paypal_marketplace', 0);

            // Withdrawal Processing
            $WCFMmp->wcfmmp_withdraw->wcfmmp_withdraw_status_update_by_withdrawal($withdrawal_id, 'completed', Helper::payment_gateway_title());

            // Withdrawal Meta
            $WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta($withdrawal_id, 'withdraw_amount', $re_total_commission);
            $WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta($withdrawal_id, 'currency', $order->get_currency());
            $WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta($withdrawal_id, 'transaction_id', $capture_id);
            $WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta($withdrawal_id, 'transaction_type', 'INSTANT');

            do_action('wcfmmp_withdrawal_request_approved', $withdrawal_id);

            $merchant_id = get_user_meta($vendor_id, Helper::get_paypal_merchant_id_key(), true);
            $paypal_fee = [
                'currency_code' => '',
                'value'         => ''
            ];

            foreach ($purchase_units as $purchase_unit) {
                if ($purchase_unit['payee']['merchant_id'] == $merchant_id) {
                    $paypal_fee = $purchase_unit['payments']['captures'][0]['seller_receivable_breakdown']['paypal_fee'];
                }
            }

            wcfm_paypal_log(sprintf(
                __('#%s - %s payment processing complete via %s for order %s. Amount: %s (Paypal fee: %s)', 'wc-frontend-manager-direct-paypal'),
                sprintf('%06u', $withdrawal_id),
                $store_name,
                Helper::payment_gateway_title(),
                $order_id,
                $re_total_commission . ' ' . $order->get_currency(),
                $paypal_fee['value'] . ' ' . $paypal_fee['currency_code']
            ), 'info');
        }
    }

    /**
     * Disables gateway process_refund function, instead use 'wcfmmp_refund_status_completed' filter
     *
     * @param bool $api_refund
     * @param string $payment_method
     * @return bool $api_refund
     */
    public function disable_api_refund_for_paypal_marketplace($api_refund, $payment_method) {
        if ($payment_method == Helper::payment_gateway_id()) {
            $api_refund = false;
        }

        return $api_refund;
    }

    /**
     * Enables order refund action for paypal marketplace payment gateway
     *
     * @param array $payment_methods
     * @return array $payment_methods
     */
    public function enable_order_refund_action_for_paypal_marketplace($payment_methods) {
        if (!in_array(Helper::payment_gateway_id(), $payment_methods)) {
            $payment_methods[] = Helper::payment_gateway_id();
        }

        return $payment_methods;
    }

    /**
     * Determine whether refunds UI should be rendered in the template.
     *
     * @param bool     $render_refunds If the refunds UI should be rendered.
     * @param int      $order_id       The Order ID.
     * @param WC_Order $order          The Order object.
     * @return bool    $render_refunds
     */
    public function disable_admin_order_refund_button($render_refunds, $order_id, $order) {
        if ($order->get_payment_method() == Helper::payment_gateway_id()) {
            $render_refunds = false;
        }

        return $render_refunds;
    }

    public function disable_admin_order_refund_button_wc_lt_6_4_0($order) {
        global $post;

        if (!is_admin()) {
            return;
        }

        if (strpos($_SERVER['REQUEST_URI'], 'post.php?post=') === false) {
            return;
        }

        if (empty($post) || 'shop_order' === OrderUtil::get_order_type( $post->ID )){
            return;
        }
        ?>
        <script>
            jQuery(function() {
                jQuery('.refund-items').hide();
                jQuery('.order_actions option[value=send_email_customer_refunded_order]').remove();
                if (jQuery('#original_post_status').val() == 'wc-refunded') {
                    jQuery('#s2id_order_status').html('Refunded');
                } else {
                    jQuery('#order_status option[value=wc-refunded]').remove();
                }
            });
        </script>
        <?php
    }

    public function display_paypal_transaction_details($order) {
        if ($order->get_payment_method() == Helper::payment_gateway_id()) {
            $vendor_id = apply_filters('wcfm_current_vendor_id', get_current_user_id());

            if (wcfm_is_vendor($vendor_id)) {
                $transaction_id = $order->get_meta('_wcfm_paypal_payment_charge_captured__for_vendor_' . $vendor_id, true);

                if ($transaction_id) {
                    echo '<p>' . sprintf(__('Transaction ID: %s', 'wc-frontend-manager-direct-paypal'), $transaction_id) . '</p>';
                }
            }
        }
    }

    /**
     * Load Localization files.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present
     *
     * @access public
     * @return void
     */
    public function load_plugin_textdomain() {
        $locale = function_exists('get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, 'wc-frontend-manager-direct-paypal');

        load_textdomain('wc-frontend-manager-direct-paypal', $this->plugin_path . "lang/wc-frontend-manager-direct-paypal-$locale.mo");
        load_textdomain('wc-frontend-manager-direct-paypal', ABSPATH . "wp-content/languages/plugins/wc-frontend-manager-direct-paypal-$locale.mo");
    }

    public function load_class($class_name = '') {
        if ('' != $class_name && '' != $this->token) {
            require_once('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }
}
