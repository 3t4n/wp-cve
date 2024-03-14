<?php
/**
 * SecurePay.
 *
 * @author  SecurePay Sdn Bhd
 * @license GPL-2.0+
 *
 * @see    https://securepay.net
 */
\defined('ABSPATH') || exit;

class WC_Gateway_SecurePay extends \WC_Payment_Gateway
{
    public $id = 'securepay';
    public $has_fields;

    private $log;
    private $asset;
    private $slug;
    private $endpoint;
    private $endpoint_pub;

    public function __construct()
    {
        $this->register_setting();
        $this->register_hooks();
    }

    private function is_sandbox()
    {
        return 'yes' === (string) $this->get_option('sandbox_mode') ? true : false;
    }

    private function is_testmode()
    {
        return 'yes' === (string) $this->get_option('test_mode') ? true : false;
    }

    private function checkout_url()
    {
        return \function_exists('wc_get_checkout_url') ? esc_url(wc_get_checkout_url()) : esc_url($GLOBALS['woocommerce']->cart->get_checkout_url());
    }

    private function get_bank_list($force = false)
    {
        $bank_list = $force ? false : get_transient($this->slug.'_wc_gw_banklist');

        if (empty($bank_list)) {
            $remote = wp_remote_get(
                $this->endpoint_pub.'/banks/b2c?status',
                [
                    'timeout' => 10,
                    'user-agent' => $this->slug.'/'.SECUREPAY_VER,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Referer' => home_url(),
                    ],
                ]
            );

            if (!is_wp_error($remote) && isset($remote['response']['code']) && 200 === $remote['response']['code'] && !empty($remote['body'])) {
                $data = json_decode($remote['body'], true);
                if (!empty($data) && \is_array($data) && !empty($data['fpx_bankList'])) {
                    $list = $data['fpx_bankList'];
                    foreach ($list as $arr) {
                        $status = 1;
                        if (empty($arr['status_format2']) || 'offline' === $arr['status_format1']) {
                            $status = 0;
                        }

                        $bank_list[$arr['code']] = [
                            'name' => $arr['name'],
                            'status' => $status,
                        ];
                    }

                    if (!empty($bank_list) && \is_array($bank_list)) {
                        set_transient($this->slug.'_wc_gw_banklist', $bank_list, 300);
                    }
                }
            }
        }

        return !empty($bank_list) && \is_array($bank_list) ? $bank_list : false;
    }

    private function is_bank_list(&$bank_list = '')
    {
        if ('yes' === (string) $this->get_option('bank_list')) {
            $bank_list = $this->get_bank_list();

            return !empty($bank_list) && \is_array($bank_list) ? true : false;
        }

        $bank_list = '';

        return false;
    }

    public function custom_order_button_text($order_button_text)
    {
        $order_button_text = __('Place order', 'securepay');
        $chosen_payment_method = WC()->session->get('chosen_payment_method');

        if ($chosen_payment_method == $this->id) {
            $order_button_text = $this->get_option('place_order_text');
        }

        $code = '<script id="'.$chosen_payment_method.'-cobt" type="text/javascript">'.\PHP_EOL;
        $code .= file_get_contents(SECUREPAY_PATH.'/includes/admin/'.$this->slug.'-cobt.js');
        $code .= '</script>'.\PHP_EOL;
        echo $code;

        return $order_button_text;
    }

    public function payment_scripts()
    {
        if (\function_exists('is_checkout') && !is_checkout()) {
            return;
        }

        $version = SECUREPAY_VER.'a'.date('Ymdh');
        if (!wp_script_is('selectWoo-js', 'enqueued')) {
            wp_enqueue_style('select2-'.$this->slug, $this->asset.'min/select2.min.css', null, $version);
            wp_enqueue_script('select2-'.$this->slug, $this->asset.'min/select2.min.js', ['jquery'], $version, true);
        }

        wp_enqueue_script('checkout-'.$this->slug, $this->asset.$this->slug.'-checkout.js', ['jquery'], $version, true);
    }

    public function process_admin_options()
    {
        parent::process_admin_options();
        delete_transient($this->slug.'_wc_gw_banklist');

        if ('yes' === (string) $this->get_option('bank_list')) {
            $this->get_bank_list(true);
        }

        if ('yes' === (string) $this->get_option('test_mode')) {
            $this->update_option('sandbox_mode', 'no');
        }

        add_action(
            'shutdown',
            function () {
                delete_transient($this->slug.'_wc_gw_banklist');
            }
        );
    }

    public function admin_options()
    {
        echo '<h2>SecurePay ';
        wc_back_link(esc_html__('Return to payments', 'securepay'), admin_url('admin.php?page=wc-settings&tab=checkout'));
        echo '</h2>';
        echo '<p>';
        _e('Allow customers to pay with SecurePay Platform.', 'securepay');
        echo '</p>';

        echo '<table class="form-table">';
        $this->generate_settings_html();
        echo '</table>';
    }

    public function init_form_fields()
    {
        $form_fields = [
             'enabled' => [
                 'title' => esc_html__('Enable/Disable', 'securepay'),
                 'type' => 'checkbox',
                 'label' => esc_html__('Enable SecurePay payment platform', 'securepay'),
                 'description' => esc_html__('Enable SecurePay payment platform', 'securepay'),
                 'desc_tip' => true,
                 'default' => 'yes',
             ],

             'test_mode' => [
                 'title' => esc_html__('Test mode', 'securepay'),
                 'type' => 'checkbox',
                 'label' => esc_html__('Enable Test mode', 'securepay'),
                 'description' => esc_html__('Enable this option to test without credentials', 'securepay'),
                 'desc_tip' => true,
                 'default' => 'no',
             ],

             'title' => [
                 'title' => esc_html__('Title', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('This is the title the user sees during checkout.', 'securepay'),
                 'desc_tip' => true,
                 'default' => 'SecurePay',
                 'placeholder' => 'SecurePay',
             ],

             'description' => [
                 'title' => esc_html__('Description', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('This is the description the user sees during checkout.', 'securepay'),
                 'desc_tip' => true,
                 'default' => esc_html__('Pay for your items securely with <a href="https://securepay.my/api/securepay-bank.png">SecurePay</a>', 'securepay'),
             ],

             'live_token' => [
                 'title' => esc_html__('Live Token', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('Your Live Token.', 'securepay'),
                 'default' => '',
                 'desc_tip' => true,
                 'placeholder' => esc_html__('Your Live Token', 'securepay'),
             ],

             'live_checksum' => [
                 'title' => esc_html__('Live Checksum Token', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('Your Live Checksum.', 'securepay'),
                 'default' => '',
                 'desc_tip' => true,
                 'placeholder' => esc_html__('Your Live Checksum', 'securepay'),
             ],

             'live_uid' => [
                 'title' => esc_html__('Live UID', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('Your Live UID.', 'securepay'),
                 'default' => '',
                 'desc_tip' => true,
                 'placeholder' => esc_html__('Your Live UID', 'securepay'),
             ],

             'live_partner_uid' => [
                 'title' => esc_html__('Live Partner UID', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('Your Live Partner UID (Optional).', 'securepay'),
                 'default' => '',
                 'desc_tip' => true,
                 'placeholder' => esc_html__('Your Live Partner UID (Optional)', 'securepay'),
             ],

             'sandbox_mode' => [
                 'title' => esc_html__('Sandbox mode', 'securepay'),
                 'type' => 'checkbox',
                 'label' => esc_html__('Enable Sandbox mode', 'securepay'),
                 'description' => esc_html__('Enable Sandbox mode', 'securepay'),
                 'desc_tip' => true,
                 'default' => 'no',
             ],

             'sandbox_token' => [
                 'title' => esc_html__('Sandbox Token', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('Your Sandbox Token.', 'securepay'),
                 'default' => '',
                 'desc_tip' => true,
                 'placeholder' => esc_html__('Your Sandbox Token', 'securepay'),
             ],

             'sandbox_checksum' => [
                 'title' => esc_html__('Sandbox Checksum Token', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('Your Sandbox Checksum Token.', 'securepay'),
                 'default' => '',
                 'desc_tip' => true,
                 'placeholder' => esc_html__('Your Sandbox Checksum Token', 'securepay'),
             ],

             'sandbox_uid' => [
                 'title' => esc_html__('Sandbox UID', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('Your Sandbox UID.', 'securepay'),
                 'default' => '',
                 'desc_tip' => true,
                 'placeholder' => esc_html__('Your Sandbox UID', 'securepay'),
             ],

             'sandbox_partner_uid' => [
                 'title' => esc_html__('Sandbox Partner UID', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('Your Sandbox Partner UID (Optional).', 'securepay'),
                 'default' => '',
                 'desc_tip' => true,
                 'placeholder' => esc_html__('Your Sandbox Partner UID (Optional)', 'securepay'),
             ],

             'debug_mode' => [
                 'title' => 'Debug Mode',
                 'type' => 'checkbox',
                 'default' => '0',
                 'desc_tip' => true,
                 'description' => esc_html__('Logs additional information. <br>Log file path: Your admin panel -> WooCommerce -> System Status -> Logs', 'securepay'),
             ],

             'bank_list' => [
                 'title' => 'Show Bank List',
                 'type' => 'checkbox',
                 'default' => 'no',
                 'desc_tip' => true,
                 'description' => esc_html__('SecurePay Supported Banks.', 'securepay'),
             ],

             'fpxbank_logo' => [
                 'title' => 'Use supported banks logo',
                 'type' => 'checkbox',
                 'default' => 'no',
                 'desc_tip' => true,
                 'description' => esc_html__('SecurePay Supported Banks Logo.', 'securepay'),
             ],

             'securepay_autoupdate' => [
                 'title' => 'Enable Auto-updates',
                 'type' => 'checkbox',
                 'default' => 'yes',
                 'desc_tip' => true,
                 'description' => esc_html__('SecurePay plugin Auto-updates.', 'securepay'),
             ],

             'place_order_text' => [
                 'title' => esc_html__('Place order text', 'securepay'),
                 'type' => 'text',
                 'description' => esc_html__('This is the text for Place Order button.', 'securepay'),
                 'desc_tip' => true,
                 'default' => esc_html__('Pay with SecurePay', 'securepay'),
                 'placeholder' => esc_html__('This is the text for Place Order button', 'securepay'),
             ],
         ];

        $this->form_fields = $form_fields;
    }

    public function process_payment($order_id)
    {
        $order = new \WC_Order($order_id);
        $orderId = $order->get_id();

        $order->set_payment_method_title($this->slug);
        $order->set_payment_method($this->slug);

        if ($this->is_testmode()) {
            $checksum = '3faa7b27f17c3fb01d961c08da2b6816b667e568efb827544a52c62916d4771d';
            $token = 'GFVnVXHzGEyfzzPk4kY3';
            $uid = '4a73a364-6548-4e17-9130-c6e9bffa3081';
            $partner_uid = '';
        } else {
            $checksum = $this->is_sandbox() ? $this->get_option('sandbox_checksum') : $this->get_option('live_checksum');
            $token = $this->is_sandbox() ? $this->get_option('sandbox_token') : $this->get_option('live_token');
            $uid = $this->is_sandbox() ? $this->get_option('sandbox_uid') : $this->get_option('live_uid');
            $partner_uid = $this->is_sandbox() ? $this->get_option('sandbox_partner_uid') : $this->get_option('live_partner_uid');
        }

        $buyer_bank_code = WC()->session->get('securepay_buyerbankcode');

        $errors = '';

        if (empty($token)) {
            $errors .= '<li>Token parameter is empty</li>';
        }

        if (empty($uid)) {
            $errors .= '<li>UID parameter is empty</li>';
        }

        if (empty($checksum)) {
            $errors .= '<li>Checksum parameter is empty</li>';
        }

        if (!empty($errors)) {
            $error_log = str_replace('</li>', '', $errors);
            $error_log = str_replace('<li>', "\n", $error_log);
            $this->log('Error in settings:'.$error_log);

            return wp_send_json(
                [
                    'result' => 'failure',
                    'messages' => $errors,
                ]
            );
        }

        $product_description = 'Payment for order no '.$orderId;
        $order_data = $order->get_data();

        $buyer_name = $order_data['billing']['first_name'].' '.$order_data['billing']['last_name'];
        $buyer_email = $order_data['billing']['email'];
        $buyer_phone = $order_data['billing']['phone'];

        $total = wc_format_decimal($order->get_total(), 2);
        $redirect_url = get_site_url().'/?wc-api=wc_gateway_securepay_capture_response';

        $calculateSign = "$buyer_email|$buyer_name|$buyer_phone|$redirect_url|$orderId|$product_description|$redirect_url|$total|$uid";

        $sign = hash_hmac('sha256', $calculateSign, $checksum);

        $form = '<form style="display:none" name="frm_securepay_payment" id="frm_securepay_payment" method="post" action="'.$this->endpoint.'/payments">';
        $form .= "<input type='hidden' name='order_number' value='".$orderId."'>";
        $form .= "<input type='hidden' name='buyer_name' value='".$buyer_name."'>";
        $form .= "<input type='hidden' name='buyer_email' value='".$buyer_email."'>";
        $form .= "<input type='hidden' name='buyer_phone' value='".$buyer_phone."'>";
        $form .= "<input type='hidden' name='transaction_amount' value='".$total."'>";
        $form .= "<input type='hidden' name='product_description' value='".$product_description."'>";
        $form .= "<input type='hidden' name='callback_url' value='".$redirect_url."'>";
        $form .= "<input type='hidden' name='redirect_url' value='".$redirect_url."'>";
        $form .= "<input type='hidden' name='cancel_url' value='".wc_get_cart_url().'/?order_number='.$orderId."'>";
        $form .= "<input type='hidden' name='timeout_url' value='".wc_get_cart_url().'/?order_number='.$orderId."'>";
        $form .= "<input type='hidden' name='checksum' value='".$sign."'>";
        $form .= "<input type='hidden' name='token' value='".$token."'>";
        $form .= "<input type='hidden' name='partner_uid' value='".$partner_uid."'>";
        if ($this->is_bank_list()) {
            $form .= "<input type='hidden' name='payment_source' value='woocommerce_bank_list'>";
        } else {
            $form .= "<input type='hidden' name='payment_source' value='woocommerce'>";
        }

        if ($this->is_bank_list() && !empty($buyer_bank_code)) {
            $form .= "<input type='hidden' name='buyer_bank_code' value='".$buyer_bank_code."'>";
        }

        $form .= '<input type="submit">';

        $this->log('Payment Initiated for order ID '.$orderId);

        $result = [
            'result' => 'success',
            'form' => $form,
        ];
        if (isset($_POST['woocommerce_pay']) && isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'woocommerce-pay')) {
            wp_send_json($result);
            exit;
        }

        return $result;
    }

    private function sanitize_response()
    {
        $params = [
             'amount',
             'bank',
             'buyer_email',
             'buyer_name',
             'buyer_phone',
             'checksum',
             'client_ip',
             'created_at',
             'created_at_unixtime',
             'currency',
             'exchange_number',
             'fpx_status',
             'fpx_status_message',
             'fpx_transaction_id',
             'fpx_transaction_time',
             'id',
             'interface_name',
             'interface_uid',
             'merchant_reference_number',
             'name',
             'order_number',
             'payment_id',
             'payment_method',
             'payment_status',
             'receipt_url',
             'retry_url',
             'source',
             'status_url',
             'transaction_amount',
             'transaction_amount_received',
             'uid',
             'wc-api',
         ];

        $response_params = [];
        if (isset($_POST)) {
            foreach ($params as $k) {
                if (isset($_POST[$k])) {
                    $response_params[$k] = sanitize_text_field($_POST[$k]);
                }
            }
        }

        if (isset($_GET)) {
            foreach ($params as $k) {
                if (isset($_GET[$k])) {
                    $response_params[$k] = sanitize_text_field($_GET[$k]);
                }
            }
        }

        return $response_params;
    }

    public function capture_response()
    {
        $response_params = $this->sanitize_response();

        if (!empty($response_params) && isset($response_params['order_number'])) {
            $success = $this->response_status($response_params);

            $order = new \WC_Order($response_params['order_number']);
            $callback = $this->is_response_callback($response_params) ? 'Callback' : 'Redirect';
            $note = '';

            $receipt_link = !empty($response_params['receipt_url']) ? $response_params['receipt_url'] : '';
            $status_link = !empty($response_params['status_url']) ? $response_params['status_url'] : '';
            $retry_link = !empty($response_params['retry_url']) ? $response_params['retry_url'] : '';

            if ($success) {
                $this->log('Payment Successfully for order ID '.$response_params['order_number']." \n with return data \n".print_r($response_params, true));

                $order->payment_complete();
                $note = 'SecurePay payment successful<br>';
                $note .= 'Response from: '.$callback.'<br>';
                $note .= 'Payment ID: '.$response_params['merchant_reference_number'].'<br>';

                if (!empty($receipt_link)) {
                    $note .= 'Receipt link: <a href="'.$receipt_link.'" target=new rel="noopener">'.$receipt_link.'</a><br>';
                }

                if (!empty($status_link)) {
                    $note .= 'Status link: <a href="'.$status_link.'" target=new rel="noopener">'.$status_link.'</a><br>';
                }

                WC()->session->set('refresh_totals', true);
                $redirect_checkout = $this->get_return_url($order);
            } else {
                $this->log('Payment Failed for order ID '.$response_params['order_number']." \n with return data \n".print_r($response_params, true));

                wc_add_notice(esc_html__('SecurePay Payment failed.', 'securepay'), 'error');

                $note = 'SecurePay payment failed<br>';
                $note .= 'Response from: '.$callback.'<br>';
                $note .= 'Payment ID: '.$response_params['merchant_reference_number'].'<br>';

                if (!empty($retry_link)) {
                    $note .= 'Retry link: <a href="'.$retry_link.'" target=new rel="noopener">'.$retry_link.'</a><br>';
                }

                if (!empty($status_link)) {
                    $note .= 'Status link: <a href="'.$status_link.'" target=new rel="noopener">'.$status_link.'</a><br>';
                }

                $redirect_checkout = $this->checkout_url();
            }

            if (!empty($note)) {
                $order->add_order_note($note);
            }

            echo '<script>location.replace("'.$redirect_checkout.'");</script>';
            echo '<noscript><meta http-equiv="refresh" content="2; url='.$redirect_checkout.'">Redirecting..</noscript>';
            exit;
        }

        echo 'Failed to get response';
        exit;
    }

    private function response_status($response_params)
    {
        if ((isset($response_params['payment_status']) && 'true' === $response_params['payment_status']) || (isset($response_params['fpx_status']) && 'true' === $response_params['fpx_status'])) {
            return true;
        }

        return false;
    }

    private function is_response_callback($response_params)
    {
        if (isset($response_params['fpx_status'])) {
            return true;
        }

        return false;
    }

    public function payment_fields()
    {
        $html = '';

        if ($this->is_bank_list($bank_list)) {
            $bank_id = WC()->session->get('securepay_buyerbankcode');

            $html .= '<select name="buyer_bank_code" id="buyer_bank_code">";';
            $html .= "<option value=''>Please Select Bank</option>;";

            foreach ((array) $bank_list as $id => $arr) {
                $name = $arr['name'];
                $status = $arr['status'];

                $disabled = empty($status) ? ' disabled' : '';
                $offline = empty($status) ? ' (Offline)' : '';
                $selected = $id === $bank_id ? ' selected' : '';
                $html .= '<option value="'.$id.'"'.$selected.$disabled.'>'.$name.$offline.'</option>';
            }
            $html .= '</select>';

            $file_helper_css = SECUREPAY_PATH.'/includes/admin/'.$this->slug.'-helper.css';
            if (@is_file($file_helper_css)) {
                $html .= '<style id="'.$this->slug.'-helper-css">';
                $html .= '/*@'.time().'*/';
                $html .= file_get_contents($file_helper_css);
                $html .= '</style>'.\PHP_EOL;
            }

            $file_helper_js = SECUREPAY_PATH.'/includes/admin/'.$this->slug.'-helper.js';
            if (@is_file($file_helper_js)) {
                $html .= '<script id="'.$this->slug.'-helper-js" type="text/javascript">';
                $html .= '/*@'.time().'*/';
                $html .= file_get_contents($file_helper_js);
                $html .= 'if ( "function" === typeof('.$this->slug.'_bank_select) ) { '.$this->slug.'_bank_select(jQuery, "'.SECUREPAY_URL.'includes/admin/bnk/", "'.$this->checkout_url().'", '.time().', "'.SECUREPAY_VER.'"); }';
                $html .= '</script>';
            }

            $html .= '<br><br>';
        }

        if ('yes' === (string) $this->get_option('fpxbank_logo')) {
            $image = SECUREPAY_URL.'includes/admin/securepay-bank-alt.png';
            $html .= '<script id="'.$this->slug.'-fpxbank-js" type="text/javascript">';
            $html .= '/*@'.time().'*/';
            $html .= 'var bnkimg = "'.$image.'";';
            $html .= "var fpxsel = jQuery(document).find('label[for=payment_method_securepay]');";
            $html .= "fpxsel.find('img').remove();";
            $html .= "jQuery('<div id=\"spblogo-embed\" style=\"display:flex;width:100%;height:auto!important;\"><img src='+bnkimg+' style=\"display:block;max-height:none;height:100%!important;width:80%;margin-top:15px;margin-bottom:15px;margin-left:24px;\"></div>').insertAfter(fpxsel);";
            $html .= '</script>';
        }

        if ($this->description) {
            $html .= '<p>';
            if ($this->is_bank_list()) {
                $html .= 'Select Bank. ';
            }
            $html .= $this->description;
            $html .= '</p>';
        }

        echo trim($html);
    }

    public function log($messages, $is_force = false)
    {
        $is_debug = $this->get_option('debug_mode');

        if (!$is_debug && !$is_force) {
            return;
        }

        if (!class_exists('WC_Logger')) {
            include_once 'class-wc-logger.php';
        }

        if (empty($this->log)) {
            $this->log = new \WC_Logger();
        }

        $messages .= "\n-----------------------------------------------------------\n";
        $this->log->add($this->title, $messages);
    }

    private function register_setting()
    {
        $this->endpoint = $this->is_sandbox() || $this->is_testmode() ? SECUREPAY_ENDPOINT_SANDBOX : SECUREPAY_ENDPOINT_LIVE;
        $this->endpoint_pub = $this->is_sandbox() || $this->is_testmode() ? SECUREPAY_ENDPOINT_PUBLIC_SANDBOX : SECUREPAY_ENDPOINT_PUBLIC_LIVE;

        $this->endpoint = rtrim($this->endpoint, '/');
        $this->endpoint_pub = rtrim($this->endpoint_pub, '/');

        $this->slug = SECUREPAY_SLUG;
        $this->asset = SECUREPAY_URL.'includes/admin/';

        $this->has_fields = false;
        $this->icon = apply_filters('woocommerce_'.$this->id.'_icon', $this->asset.$this->slug.'-fpx.svg?ver='.SECUREPAY_VER);

        if (is_admin()) {
            $this->has_fields = true;
            $this->init_form_fields();
        }

        $this->title = $this->get_option('title', 'SecurePay');
        $this->description = $this->get_option('description', 'Pay for your items securely with <a href="https://securepay.my/api/securepay-bank.png">SecurePay</a>');
        $this->method_title = apply_filters('woocommerce_'.$this->id.'_method_title', 'SecurePay');
        $this->method_description = apply_filters('woocommerce_'.$this->id.'_method_description', 'Allow customers to pay with SecurePay Platform.');
    }

    private function register_hooks()
    {
        add_action('wp_enqueue_scripts', [$this, 'payment_scripts']);
        add_action('woocommerce_update_options_payment_gateways_'.$this->id, [$this, 'process_admin_options']);
        add_action('woocommerce_wc_gateway_securepay_capture_response', [&$this, 'capture_response']);
        add_filter('woocommerce_order_button_text', [&$this, 'custom_order_button_text'], 10, 1);
    }
}
