<?php

namespace PlatiOnlinePO6\Inc\Core;


/**
 * @link              https://plati.online
 * @since             6.0.0
 * @package           PlatiOnlinePO6
 *
 */
class WC_PlatiOnline extends \WC_Payment_Gateway
{
    public static $po_order_statuses;
    public static $po_order_map;

    public function __construct()
    {
        self::$po_order_statuses = array(
            'wc-po-autorizata' => __('PO Payment Authorized', 'plationline'),
            'wc-po-autorizare' => __('PO Pending Authorization', 'plationline'),
            'wc-po-refuzata' => __('PO Payment Declined', 'plationline'),
            'wc-po-eroare' => __('PO Authorization Error', 'plationline'),
            'wc-po-onhold' => __('PO On-Hold', 'plationline'),
            'wc-po-incasare' => __('PO Pending Settle', 'plationline'),
            'wc-po-incasata' => __('PO Settled', 'plationline'),
            'wc-po-creditare' => __('PO Pending Refund', 'plationline'),
            'wc-po-creditata' => __('PO Refunded', 'plationline'),
            'wc-po-refuz-plata' => __('PO Chargeback', 'plationline'),
            'wc-po-anulare' => __('PO Pending Void', 'plationline'),
            'wc-po-anulata' => __('PO Voided', 'plationline'),
            'wc-po-expira' => __('PO Expired', 'plationline'),
            'wc-po-posta' => __('PO Pending cash payment at Posta Romana', 'plationline'),
        );
        self::$po_order_map = array(
            'wc-po-autorizata' => array('2'),
            'wc-po-autorizare' => array('1'),
            'wc-po-refuzata' => array('8'),
            'wc-po-eroare' => array('10', '16', '17'),
            'wc-po-onhold' => array('13'),
            'wc-po-incasare' => array('3'),
            'wc-po-creditare' => array('5-1'),
            'wc-po-creditata' => array('5-2'),
            'wc-po-refuz-plata' => array('5-3'),
            'wc-po-incasata' => array('5-4'),
            'wc-po-anulare' => array('6'),
            'wc-po-anulata' => array('7'),
            'wc-po-expira' => array('9'),
            'wc-po-posta' => array('12'),
        );
        $this->id = 'plationline';
        $this->method_title = __('PlatiOnline', 'plationline');
        $this->method_description = __('Process payments with PlatiOnline', 'plationline');
        $this->has_fields = false;
        $this->init_form_fields();
        $this->init_settings();
        $this->icon = (!empty($this->settings['show_logos']) && $this->settings['show_logos'] === 'yes') ? 'https://media.plationline.ro/images/logo-po-visa-mastercard.png' : '';
        $this->title = $this->settings['title'];
        $this->description = $this->settings['description'];

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_filter('woocommerce_settings_api_sanitized_fields_' . $this->id, array($this, 'tracking_script'));
        add_filter('woocommerce_available_payment_gateways', array($this, 'plationline_filter_woocommerce_available_payment_gateways'), 10, 1);
        add_filter('woocommerce_thankyou_' . $this->id, array('PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_Process', 'po_order_received'), 10, 1);

        if (version_compare(WC_VERSION, '7.9.0', '>=')) {
            add_filter('woocommerce_order_email_verification_required', '__return_false');
        }
        if (version_compare(WC_VERSION, '8.4.0', '>=')) {
            add_filter('woocommerce_order_received_verify_known_shoppers', '__return_false');
        }

        if (!$this->is_valid_for_use()) {
            $this->enabled = 'no';
        }
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Active', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Activate PlatiOnline payment method', 'plationline'),
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Payment method title', 'plationline'),
                'type' => 'text',
                'description' => __('This title will be shown in frontend to the customer', 'plationline'),
                'default' => __('Online payment by card (Visa/Maestro/Mastercard)', 'plationline'),
            ),
            'description' => array(
                'title' => __('Payment method description', 'plationline'),
                'type' => 'textarea',
                'default' => __('You will be redirected to PlatiOnline page', 'plationline'),
            ),
            'show_logos' => array(
                'title' => __('Show payment logos in frontend', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Show logos', 'plationline'),
                'description' => __('Show PlatiOnline/Visa/Mastercard logos in checkout payment method', 'plationline'),
                'default' => 'yes',
            ),
            'merchant_id_ron' => array(
                'title' => __('Merchant Login ID API F_Login (RON)', 'plationline'),
                'type' => 'text',
                'description' => __('Obtained from PlatiOnline merchant interface', 'plationline'),
            ),
            'merchant_id_eur' => array(
                'title' => __('Merchant Login ID API F_Login (EURO)', 'plationline'),
                'type' => 'text',
                'description' => __('Set this in case you selected separate EURO account settlement upon PlatiOnline contract signing', 'plationline'),
            ),
            'merchant_id_usd' => array(
                'title' => __('Merchant Login ID API F_Login (USD)', 'plationline'),
                'type' => 'text',
                'description' => __('Set this in case you selected separate USD account settlement upon PlatiOnline contract signing', 'plationline'),
            ),
            'rsa_auth' => array(
                'title' => __('Authorization RSA Public Key', 'plationline'),
                'type' => 'textarea',
                'description' => __('Obtained from PlatiOnline merchant interface', 'plationline'),
            ),
            'rsa_itsn' => array(
                'title' => __('ITSN RSA Private Key', 'plationline'),
                'type' => 'textarea',
                'description' => __('Obtained from PlatiOnline merchant interface', 'plationline'),
            ),
            'iv_auth' => array(
                'title' => __('Authorization Init Vector', 'plationline'),
                'type' => 'text',
                'description' => __('Obtained from PlatiOnline merchant interface', 'plationline'),
            ),
            'iv_itsn' => array(
                'title' => __('ITSN Init Vector', 'plationline'),
                'type' => 'text',
                'description' => __('Obtained from PlatiOnline merchant interface', 'plationline'),
            ),
            'relay_response_url' => array(
                'title' => __('Relay response URL', 'plationline'),
                'type' => 'text',
                'description' => __('The URL where PlatiOnline will notify your shop regarding transaction status.<br />Leave blank if you want to use the standard Woocommerce <b>order received</b> page.<br />If you need a custom response page, add a new Wordpress page, copy this shortcode <b>[plationline_response]</b> in its content and copy the <b>page permalink</b> here.', 'plationline'),
            ),
            'relay_method' => array(
                'title' => __('Relay Method', 'plationline'),
                'description' => \sprintf(__('More information %s. <b>Use PTOR only if you have SSL certificate installed</b>', 'plationline'), '<a href="https://wiki.plationline.eu/index.php?title=Authorization_relay_response" rel="noopener noreferrer" target="_blank"><b>' . \sprintf(__('HERE', 'plationline')) . '</b></a>'),
                'type' => 'select',
                'default' => 'POST_S2S_PO_PAGE',
                'options' => array(
                    'POST_S2S_PO_PAGE' => 'POST_S2S_PO_PAGE',
                    'PTOR' => 'PTOR',
                    'SOAP_PO_PAGE' => 'SOAP_PO_PAGE',
                ),
            ),
            'test_mode' => array(
                'title' => __('Account type', 'plationline'),
                'type' => 'select',
                'default' => 'DEMO',
                'options' => array(
                    'DEMO' => __('DEMO ACCOUNT', 'plationline'),
                    'LIVE' => __('LIVE ACCOUNT', 'plationline'),
                ),
            ),
            'tracking_script' => array(
                'title' => __('JS Tracking script', 'plationline'),
                'type' => 'textarea',
                'description' => __('Will be displayed only if you use <b>PTOR</b> relay method and only if the payment is <b>Authorized</b>', 'plationline'),
            ),
            'authorized_order_status' => array(
                'title' => __('Authorized order status', 'plationline'),
                'type' => 'select',
                'default' => 'po-autorizata',
                'options' => array(
                    'po-autorizata' => __('PO Payment Authorized', 'plationline'),
                    'processing' => __('Processing', 'woocommerce'),
                    'completed' => __('Completed', 'woocommerce'),
                ),
            ),
            'completed_final_status' => array(
                'title' => __('Set Completed as final status', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Don\'t update order status to <b>Pending Settle</b> or <b>Settled</b> if current order status is <b>Completed</b>', 'plationline'),
                'default' => 'no',
            ),
            'daysofvalability' => array(
                'title' => __('Order can be paid within (days)', 'plationline'),
                'type' => 'number',
                'custom_attributes' => array('min' => 1, 'max' => 365, 'step' => 1),
                'description' => __('Number of days the payment link is valid', 'plationline'),
            ),
            'disable_retry_payment_out_of_stock' => array(
                'title' => __('Disable retry payment if any product is out of stock', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Disable retry payment in customer order page if any product is out of stock and stock management is enabled', 'plationline'),
                'default' => 'no',
                'description' => \sprintf(__('Woocommerce manage stock enabled: <b>%s</b>', 'plationline'), __(\strtoupper(get_option('woocommerce_manage_stock')), 'plationline')),
            ),
            'enabled_login' => array(
                'title' => __('Use Login with Plati.Online', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Activate Login with Plati.Online', 'plationline'),
                'default' => 'no',
            ),
            'rsa_login' => array(
                'title' => __('Login with Plati.Online RSA Public Key', 'plationline'),
                'type' => 'textarea',
                'description' => __('Obtained from PlatiOnline merchant interface', 'plationline'),
            ),
            'test_mode_login' => array(
                'title' => __('Login with Plati.Online account type', 'plationline'),
                'type' => 'select',
                'default' => 'DEMO',
                'options' => array(
                    'DEMO' => __('DEMO ACCOUNT', 'plationline'),
                    'LIVE' => __('LIVE ACCOUNT', 'plationline'),
                ),
            ),
        );
    }

    public function init_settings()
    {
        parent::init_settings(); // TODO: Change the autogenerated stub
        if (!empty($this->settings['tracking_script'])) {
            $this->settings['tracking_script'] = stripslashes(base64_decode($this->settings['tracking_script']));
        }
    }

    public function is_valid_for_use()
    {
        return in_array(get_woocommerce_currency(), array('RON', 'EUR', 'USD'));
    }

    public function get_woo_status_by_po_status($primary, $secondary = '')
    {
        return self::recursive_array_search($primary . ($secondary ? '-' . $secondary : ''), self::$po_order_map);
    }

    private static function recursive_array_search($needle, $haystack)
    {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value || (\is_array($value) && self::recursive_array_search($needle, $value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

    public function tracking_script($data)
    {
        $tracking_script = isset($_POST['woocommerce_plationline_tracking_script']) ? $_POST['woocommerce_plationline_tracking_script'] : '';
        $data['tracking_script'] = base64_encode($tracking_script);
        return $data;
    }

    public function admin_options()
    {
        echo '<h2>' . \esc_html(__('PlatiOnline 6.0 Payment Gateway', 'plationline'));
        \wc_back_link(__('Return to payments', 'woocommerce'), \admin_url('admin.php?page=wc-settings&tab=checkout'));
        echo '</h2>';
        echo '<div class="inline error"><p>' . \sprintf(__('For additional information click %s.', 'plationline'), '<a href="https://wiki.plationline.eu" target="_blank" rel="noopener noreferrer"><b>' . __('HERE', 'plationline') . '</b></a>') . '</p>';
        echo '<p class="alert">' . \sprintf(__('<b>INFO:</b> To set-up the ITSN URL, please login to your merchant account (%s) <b>Settings</b> section, <b>Instant status notification</b>. Copy <b>%s</b> and select <b>POST</b> method', 'plationline'), '<a href="https://merchants.plationline.ro" target="_blank" rel="noopener noreferrer"><b>https://merchants.plationline.ro</b></a>', get_bloginfo('url') . '/wc-api/wc_plationline') . '</p></div>';
        echo '<table class="form-table">';
        // Generate the HTML For the settings form.
        $this->generate_settings_html();
        echo '</table>';
    }

    /**
     *  There are no payment fields for plationline, but we want to show the description if set.
     **/
    public function payment_fields()
    {
        if ($this->description) {
            echo wpautop(wptexturize($this->description));
        }
    }

    /**
     * Process the payment and return the result
     **/
    public function process_payment($order_id)
    {
        $order = new \WC_Order($order_id);
        $order->update_status('pending', __('PO Pending Authorization', 'plationline'));
        return array('result' => 'success', 'redirect' => $order->get_checkout_payment_url(true));
    }

    public function plationline_filter_woocommerce_available_payment_gateways($gateways)
    {
        if (is_checkout()) {
            if (!empty(\WC()->cart->recurring_carts)) {
                // daca e cos cu Woocommerce Subscriptions elimin plata simpla
                unset($gateways[$this->id]);
            }
        }
        return $gateways;
    }

}
