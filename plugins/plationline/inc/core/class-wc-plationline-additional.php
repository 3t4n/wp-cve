<?php

namespace PlatiOnlinePO6\Inc\Core;

use PlatiOnlinePO6\Inc\Libraries\PO5 as PO5;

/**
 * @link              https://plati.online
 * @since             6.3.0
 * @package           PlatiOnlinePO6
 *
 */
class WC_PlatiOnline_Additional extends \WC_Payment_Gateway
{
    public function __construct()
    {
        $this->id = 'plationline_additional';
        $this->method_title = __('PlatiOnline Additional Payment Method', 'plationline');
        $this->method_description = __('Additional payment method', 'plationline');
        $this->has_fields = false;
        $this->init_form_fields();
        $this->init_settings();

        $this->icon = '';
        $this->title = $this->settings['title'];
        $this->description = $this->settings['description'];

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_filter('woocommerce_settings_api_sanitized_fields_' . $this->id, array($this, 'tracking_script'));
        add_filter('woocommerce_thankyou_' . $this->id, array($this, 'po_additional_order_received'), 10, 1);

        if (!$this->is_valid_for_use()) {
            $this->enabled = 'no';
        }
    }

    public function init_settings()
    {
        parent::init_settings();
        if (!empty($this->settings['tracking_script'])) {
            $this->settings['tracking_script'] = stripslashes(base64_decode($this->settings['tracking_script']));
        }
    }

    public function is_valid_for_use()
    {
        return in_array(get_woocommerce_currency(), array('RON', 'EUR', 'USD'));
    }

    public function tracking_script($data)
    {
        $tracking_script = isset($_POST['woocommerce_plationline_additional_tracking_script']) ? $_POST['woocommerce_plationline_additional_tracking_script'] : '';
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

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Active', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Activate PlatiOnline additional payment method', 'plationline'),
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
        );
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

    public static function setFRequestAuth($order_id, $f_action = 2)
    {
        $order = new \WC_Order($order_id);

        $f_request = array();
        $f_request['f_order_number'] = $order_id;
        $f_request['f_amount'] = \round($order->get_total(), 2);
        $f_request['f_currency'] = $order->get_currency();

        if (\strtolower($f_request['f_currency']) == 'lei') {
            $f_request['f_currency'] = 'RON';
        }

        $f_request['f_language'] = \strtolower(\substr(get_bloginfo('language'), 0, 2));

        $permitted_languages = array('en', 'ro', 'it', 'fr', 'de', 'es');
        if (!in_array($f_request['f_language'], $permitted_languages)) {
            $f_request['f_language'] = 'en';
        }

        $customer_info = array();

        //contact
        if ($order->get_billing_email()) {
            $customer_info['contact']['f_email'] = $order->get_billing_email();
        }
        if (\strlen($order->get_billing_phone()) >= 4) {
            $customer_info['contact']['f_phone'] = $order->get_billing_phone();
            $customer_info['contact']['f_mobile_number'] = $order->get_billing_phone();
        }
        $customer_info['contact']['f_send_sms'] = 1; // 1 - sms client notification 0 - no notification
        if (\substr($order->get_billing_first_name(), 0, 50)) {
            $customer_info['contact']['f_first_name'] = \substr($order->get_billing_first_name(), 0, 50);
        }
        if (\substr($order->get_billing_last_name(), 0, 50)) {
            $customer_info['contact']['f_last_name'] = \substr($order->get_billing_last_name(), 0, 50);
        }

        if (empty($customer_info['contact']['f_first_name']) || empty($customer_info['contact']['f_last_name'])) {
            $name = explode(' ', $order->get_billing_first_name(), 2);
            $customer_info['contact']['f_first_name'] = $name[0];
            $customer_info['contact']['f_last_name'] = $name[1];
        }

        if (empty($customer_info['contact']['f_first_name'])) {
            unset($customer_info['contact']['f_first_name']);
        }

        if (empty($customer_info['contact']['f_last_name'])) {
            unset($customer_info['contact']['f_last_name']);
        }

        //$customer_info['contact']['f_middle_name'] 	 = '';

        //invoice
        $customer_info['invoice']['f_company'] = $order->get_billing_company() ? \substr($order->get_billing_company(), 0, 50) : '-';
        $customer_info['invoice']['f_cui'] = '-';
        $customer_info['invoice']['f_reg_com'] = '-';
        $customer_info['invoice']['f_cnp'] = '-';
        $customer_info['invoice']['f_zip'] = $order->get_billing_postcode() ?: '-';

        if ($order->get_billing_country()) {
            $customer_info['invoice']['f_country'] = $order->get_billing_country();
        }
        if ($order->get_billing_state()) {
            $customer_info['invoice']['f_state'] = $order->get_billing_state();
        }
        if ($order->get_billing_city()) {
            $customer_info['invoice']['f_city'] = $order->get_billing_city();
        }
        if (\substr($order->get_billing_address_1() . $order->get_billing_address_2(), 0, 100)) {
            $customer_info['invoice']['f_address'] = \substr($order->get_billing_address_1() . ' ' . $order->get_billing_address_2(), 0, 100);
        }

        $f_request['customer_info'] = $customer_info;
        if ($order->has_shipping_address()) {
            $shipping_info = array();

            $shipping_info['same_info_as'] = 0; // 0 - different info, 1- same info as customer_info

            //contact
            $shipping_info['contact']['f_email'] = $order->get_billing_email();

            if (\strlen($order->get_billing_phone()) >= 4) {
                $shipping_info['contact']['f_phone'] = $order->get_billing_phone();
                $shipping_info['contact']['f_mobile_number'] = $order->get_billing_phone();
            }

            $shipping_info['contact']['f_send_sms'] = 1; // 1 - sms client notification 0 - no notification
            if ($order->get_shipping_first_name()) {
                $shipping_info['contact']['f_first_name'] = \substr($order->get_shipping_first_name(), 0, 50);
            }
            if ($order->get_shipping_last_name()) {
                $shipping_info['contact']['f_last_name'] = \substr($order->get_shipping_last_name(), 0, 50);
            }
            $shipping_info['contact']['f_middle_name'] = '';

            if (empty($shipping_info['contact']['f_first_name']) || empty($shipping_info['contact']['f_last_name'])) {
                $name = explode(' ', $order->get_shipping_first_name(), 2);
                $shipping_info['contact']['f_first_name'] = $name[0];
                $shipping_info['contact']['f_last_name'] = $name[1];
            }

            if (empty($shipping_info['contact']['f_first_name'])) {
                unset($shipping_info['contact']['f_first_name']);
            }

            if (empty($shipping_info['contact']['f_last_name'])) {
                unset($shipping_info['contact']['f_last_name']);
            }

            //address
            $shipping_info['address']['f_company'] = $order->get_shipping_company() ? \substr($order->get_shipping_company(), 0, 50) : '-';
            $shipping_info['address']['f_zip'] = $order->get_shipping_postcode() ?: '-';
            if ($order->get_shipping_country()) {
                $shipping_info['address']['f_country'] = $order->get_shipping_country();
            }
            if ($order->get_shipping_state()) {
                $shipping_info['address']['f_state'] = $order->get_shipping_state();
            }
            if ($order->get_shipping_city()) {
                $shipping_info['address']['f_city'] = $order->get_shipping_city();
            }
            if (\substr($order->get_shipping_address_1() . $order->get_shipping_address_2(), 0, 100)) {
                $shipping_info['address']['f_address'] = \substr($order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2(), 0, 100);
            }

            $f_request['shipping_info'] = $shipping_info;
        }

        $po5_settings = (new WC_PlatiOnline_Additional())->settings;

        $transaction_relay_response = array();
        $transaction_relay_response['f_relay_response_url'] = !empty($po5_settings['relay_response_url']) ? $po5_settings['relay_response_url'] : $order->get_checkout_order_received_url();
        $transaction_relay_response['f_relay_method'] = $po5_settings['relay_method'];
        $transaction_relay_response['f_post_declined'] = 1;
        $transaction_relay_response['f_relay_handshake'] = 1;

        $f_request['transaction_relay_response'] = $transaction_relay_response;

        $f_request['f_order_cart'] = array();

        foreach ($order->get_items() as $product) {
            $item = array();
            $item['prodid'] = $product['product_id'];
            $item['name'] = \substr(\strip_tags(\html_entity_decode($product['name'], ENT_QUOTES)), 0, 250);
            $item['description'] = '';
            $item['qty'] = $product['qty'];
            $item['itemprice'] = \round($product['line_subtotal'] / $product['qty'], 2);
            $item['vat'] = \round($product['line_subtotal_tax'], 2);
            $item['stamp'] = \date('Y-m-d');
            $item['prodtype_id'] = 0;
            $f_request['f_order_cart'][] = $item;
        }

        if (!empty($order->get_fees())) {
            foreach ($order->get_fees() as $fee) {
                if ($fee->get_total() > 0) {
                    //adaug doar costurile aditionale, reducerile mai tarziu
                    $item = array();
                    $item['prodid'] = 'fee';
                    $item['name'] = \substr(\strip_tags(\html_entity_decode($fee->get_name(), ENT_QUOTES)), 0, 250);
                    $item['description'] = '';
                    $item['qty'] = 1;
                    $item['itemprice'] = \round($fee->get_total(), 2);
                    $item['vat'] = \round($fee->get_total_tax(), 2);
                    $item['stamp'] = \date('Y-m-d');
                    $item['prodtype_id'] = 0;
                    $f_request['f_order_cart'][] = $item;
                }
            }
        }

        $i = 1;
        if ($order->get_total_discount()) {
            $coupon = array();
            $coupon['key'] = 'cart discount';
            $coupon['value'] = \round($order->cart_discount, 2);
            $coupon['percent'] = 0;
            $coupon['workingname'] = 'Cart discount';
            $coupon['type'] = 0;
            $coupon['scop'] = 0;
            $coupon['vat'] = \round($order->cart_discount_tax, 2);
            $f_request['f_order_cart']['coupon' . $i] = $coupon;
            $i++;
        }

        if (!empty($order->get_fees())) {
            foreach ($order->get_fees() as $fee) {
                if ($fee->get_total() < 0) {
                    // e reducere
                    $coupon = array();
                    $coupon['key'] = 'fee discount';
                    $coupon['value'] = \round(abs($fee->get_total()), 2);
                    $coupon['percent'] = 0;
                    $coupon['workingname'] = \substr(\strip_tags(\html_entity_decode($fee->get_name(), ENT_QUOTES)), 0, 250);
                    $coupon['type'] = 0;
                    $coupon['scop'] = 0;
                    $coupon['vat'] = \round(abs($fee->get_total_tax()), 2);
                    $f_request['f_order_cart']['coupon' . $i] = $coupon;
                    $i++;
                }
            }
        }

        $shipping = array();
        $shipping['name'] = \substr($order->get_shipping_method(), 0, 250);
        $shipping['price'] = \round($order->get_shipping_total(), 2);
        $shipping['pimg'] = 0;
        $shipping['vat'] = \round($order->get_shipping_tax(), 2);

        $f_request['f_order_cart']['shipping'] = $shipping;
        $f_request['f_order_string'] = \sprintf(__('Order #%s on website %s', 'plationline'), $order_id, get_bloginfo('url'));

        if (!empty($po5_settings['daysofvalability']) && (int)$po5_settings['daysofvalability'] > 0) {
            $f_request['paylink'] = array(
                'email2client' => 0,
                'sms2client' => 0,
                'daysofvalability' => (int)$po5_settings['daysofvalability'],
            );
            $order->update_meta_data('_plationline_daysofvalability', (int)$po5_settings['daysofvalability']);
        }

        if (!empty(WC()->session->get('x_payment_token'))) {
            $f_request['oauth_payment_token'] = WC()->session->get('x_payment_token');
        }

        $po = new PO5();
        $po->f_login = $po5_settings['merchant_id_' . \strtolower($f_request['f_currency'])];
        if (empty($po->f_login)) {
            wp_die('F_LOGIN is empty for currency: ' . $f_request['f_currency']);
        }
        $urlparts = parse_url(home_url());
        $domain = preg_replace('/www\./i', '', $urlparts['host']);
        $f_request['f_website'] = $domain;
        $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
        $po->setIV($po5_settings['iv_auth']);
        $po->test_mode = ($po5_settings['test_mode'] == 'DEMO' ? 1 : 0);
        global $woocommerce;
        $mailer = $woocommerce->mailer();
        // Email client with new order email
        $email = $mailer->emails['WC_Email_Customer_On_Hold_Order'];
        $email->trigger($order_id);

        // Email admin with new order email
        $email = $mailer->emails['WC_Email_New_Order'];
        $email->trigger($order_id);

        $response = $po->auth($f_request, $f_action);

        if (!empty($response['redirect_url'])) {
            $order->update_meta_data('plationline_redirect_url', $response['redirect_url']);
            $order->set_transaction_id($response['x_trans_id']);
            $order->update_status('po-autorizare', __('PO Pending Authorization', 'plationline'));
            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-autorizare');
            \WC()->cart->empty_cart();
            if (wp_redirect($response['redirect_url'])) {
                exit;
            }
        }
    }

    public function po_additional_order_received($order_id)
    {
        $po5_settings = (new WC_PlatiOnline_Additional())->settings;
        $relay_method = $po5_settings['relay_method'];
        $response = null;
        $soap_xml = null;
        if ($relay_method === 'SOAP_PO_PAGE') {
            $soap_xml = file_get_contents("php://input");
        } else {
            $response = $_POST;
        }

        $this->processPOResponseAdditional($response, $soap_xml);
    }

    public function processPOResponseAdditional($response = null, $soap_xml = null)
    {
        if (empty($response['F_Relay_Message'])) {
            return;
        }

        $po5_settings = (new WC_PlatiOnline_Additional())->settings;
        $po = new PO5();
        $po->setRSAKeyDecrypt($po5_settings['rsa_itsn']);
        $po->setIVITSN($po5_settings['iv_itsn']);
        $po_f_relay_method = $po5_settings['relay_method'];

        if (empty($po5_settings['authorized_order_status'])) {
            $po5_settings['authorized_order_status'] = 'po-autorizata';
        }

        switch ($po_f_relay_method) {
            case 'PTOR':
                $authorization_response = $po->auth_response(sanitize_text_field($response['F_Relay_Message']), sanitize_text_field($response['F_Crypt_Message']));
                $X_RESPONSE_CODE = $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_CODE');
                $order_id = $po->get_xml_tag_content($authorization_response, 'F_ORDER_NUMBER');
                $trans_id = $po->get_xml_tag_content($authorization_response, 'X_TRANS_ID');
                $order = new \WC_Order($order_id);

                switch ($X_RESPONSE_CODE) {
                    case '2':
                        //	authorized
                        \printf('<h2 style="text-align:center">' . __('Congratulations, the transaction for order #%s was successfully authorized!', 'plationline') . '</h2>', $order->get_order_number());
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                            $order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($order_id);
                            \wc_update_coupon_usage_counts($order_id);
                            if (!empty($po5_settings['tracking_script'])) {
                                echo $po5_settings['tracking_script'];
                            }
                        }
                        break;
                    case '13':
                        //	on hold
                        \printf('<h2 style="text-align:center">' . __('The transaction for order #%s is on hold, additional verification is needed!', 'plationline') . '</h2>', $order->get_order_number());
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-onhold', __('PO On-Hold', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-onhold');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                        }
                        break;
                    case '8':
                        //	declined
                        \printf('<h2 style="text-align:center">' . __('The transaction for order #%s was declined! Reason: %s', 'plationline') . '</h2>', $order->get_order_number(), $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_REASON_TEXT'));
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-refuzata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                        }
                        break;
                    case '10':
                    case '16':
                    case '17':
                        //	error
                        \printf('<h2 style="text-align:center">' . __('An error was encountered in authorization process for order #%s', 'plationline') . '</h2>', $order->get_order_number());
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-eroare');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Authorization Error');
                        }
                        break;
                }
                break;

            case 'POST_S2S_PO_PAGE':
                $authorization_response = $po->auth_response(sanitize_text_field($response['F_Relay_Message']), sanitize_text_field($response['F_Crypt_Message']));
                $X_RESPONSE_CODE = $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_CODE');
                $order_id = $po->get_xml_tag_content($authorization_response, 'F_ORDER_NUMBER');
                $trans_id = $po->get_xml_tag_content($authorization_response, 'X_TRANS_ID');
                $order = new \WC_Order($order_id);

                $raspuns_procesat = true;
                switch ($X_RESPONSE_CODE) {
                    case '2':
                        //	authorized
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                            $order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($order_id);
                            \wc_update_coupon_usage_counts($order_id);
                        }
                        break;
                    case '13':
                        //	on hold
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-onhold', __('PO On-Hold', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-onhold');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                        }
                        break;
                    case '8':
                        //	declined
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-refuzata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                        }
                        break;
                    case '10':
                    case '16':
                    case '17':
                        //	error
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-eroare');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Authorization Error');
                        }
                        break;
                    default:
                        $raspuns_procesat = false;
                }

                \header('User-Agent:Mozilla/5.0 (Plati Online Relay Response Service)');

                if ($raspuns_procesat) {
                    \header('PO_Transaction_Response_Processing: true');
                } else {
                    \header('PO_Transaction_Response_Processing: retry');
                }
                break;

            case 'SOAP_PO_PAGE':
                $soap_parsed = $po->parse_soap_response($soap_xml);
                $authorization_response = $po->auth_response(sanitize_text_field($soap_parsed['PO_RELAY_REPONSE']['F_RELAY_MESSAGE']), sanitize_text_field($soap_parsed['PO_RELAY_REPONSE']['F_CRYPT_MESSAGE']));
                $X_RESPONSE_CODE = $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_CODE');
                $order_id = $po->get_xml_tag_content($authorization_response, 'F_ORDER_NUMBER');
                $trans_id = $po->get_xml_tag_content($authorization_response, 'X_TRANS_ID');
                $order = new \WC_Order($order_id);

                $raspuns_procesat = true;

                switch ($X_RESPONSE_CODE) {
                    case '2':
                        //	authorized
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                            $order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($order_id);
                            \wc_update_coupon_usage_counts($order_id);
                        }
                        break;
                    case '13':
                        //	on hold
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-onhold', __('PO On-Hold', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-onhold');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                        }
                        break;
                    case '8':
                        //	declined
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-refuzata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                        }
                        break;
                    case '10':
                    case '16':
                    case '17':
                        //	error
                        if (!WC_PlatiOnline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                            WC_PlatiOnline_Process::plationline_update_order_status($order, 'po-eroare');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Authorization Error');
                        }
                        break;
                    default:
                        $raspuns_procesat = false;
                }

                \header('User-Agent:Mozilla/5.0 (Plati Online Relay Response Service)');

                if ($raspuns_procesat) {
                    \header('PO_Transaction_Response_Processing: true');
                } else {
                    \header('PO_Transaction_Response_Processing: retry');
                }
                break;
        }
    }
}
