<?php
if (!defined('ABSPATH')) {
    exit;
};

class WC_Gateway_PayTRCheckout extends WC_Payment_Gateway
{
    protected $text_domain = 'paytr-sanal-pos-woocommerce-iframe-api';

    public $paytr_merchant_id;
    public $paytr_merchant_key;
    public $paytr_merchant_salt;
    public $paytr_installment;
    public $paytr_installment_list;
    public $paytr_lang;

    protected $category_full = array();
    protected $category_installment = array();

    public function __construct()
    {
        $this->id = 'paytrcheckout';
        $this->has_fields = true;
        $this->method_title = __('PayTR Virtual POS WooCommerce - iFrame API', $this->text_domain);
        $this->method_description = __('Open your website to shopping with PayTR benefits. Receive your payments safely.', $this->text_domain);
        $this->supports = array('refunds');

        $this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->paytr_merchant_id = trim($this->get_option('paytr_merchant_id'));
        $this->paytr_merchant_key = trim($this->get_option('paytr_merchant_key'));
        $this->paytr_merchant_salt = trim($this->get_option('paytr_merchant_salt'));
        $this->paytr_installment = trim($this->get_option('paytr_installment'));
        $this->paytr_lang = trim($this->get_option('paytr_lang'));

        if ($this->get_option('logo') == 'yes') {
            $this->icon = PAYTRSPI_PLUGIN_URL . '/assets/img/' . sanitize_file_name('paytr_logo.svg');
        }

        # Filters
        add_filter('woocommerce_settings_form_sanitized_field_' . $this->id, array($this, 'sanitize_form_fields'));

        # Hooks
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(
            $this,
            'process_admin_options'
        ));

        add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));
        add_action('woocommerce_api_wc_gateway_paytrcheckout', array($this, 'paytrcheckout_response'));
    }

    function admin_options()
    {
        parent::admin_options();
    }

    function is_valid_for_use()
    {
        return true;
    }

    /**
     * Admin Form
     */
    function init_form_fields()
    {
        $order_statues = wc_get_order_statuses();

        $this->form_fields = array(
            'callback' => array(
                'title' => __('Callback URL', $this->text_domain),
                'type' => 'title',
                'description' => sprintf(__('You must add the following callback url <strong>%s</strong> to your <a href="https://www.paytr.com/magaza/ayarlar" target="_blank">Callback URL Settings.</a>', $this->text_domain), get_home_url() . '/index.php?wc-api=wc_gateway_paytrcheckout')
            ),
            'enabled' => array(
                'title' => __('Enable/Disable', $this->text_domain),
                'label' => __('Enable PayTR Virtual POS iFrame API', $this->text_domain),
                'type' => 'checkbox',
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Title', $this->text_domain),
                'type' => 'text',
                'description' => __('The title your customers will see during checkout.', $this->text_domain),
                'default' => __('Kredi \ Banka Kartı (PayTR)', $this->text_domain),
                'desc_tip' => true,
                'required' => true,
            ),
            'description' => array(
                'title' => __('Description', $this->text_domain),
                'type' => 'textarea',
                'description' => __('The description your customers will see during checkout.', $this->text_domain),
                'default' => __("Bu ödeme yöntemini seçtiğinizde Tüm Kredi Kartlarına taksit imkanı bulunmaktadır.", $this->text_domain),
                'desc_tip' => true
            ),
            'logo' => array(
                'title' => __('Logo', $this->text_domain),
                'label' => __('Enable/Disable', $this->text_domain),
                'type' => 'checkbox',
                'default' => 'no',
            ),
            'paytr_merchant_id' => array(
                'title' => __('Merchant ID', $this->text_domain),
                'type' => 'text',
                'description' => __('You will find this value under the PayTR Merchant Panel > Information Tab.', $this->text_domain),
                'desc_tip' => true,
                'required' => true,
            ),
            'paytr_merchant_key' => array(
                'title' => __('Merchant Key', $this->text_domain),
                'type' => 'text',
                'description' => __('You will find this value under the PayTR Merchant Panel > Information Tab.', $this->text_domain),
                'desc_tip' => true,
                'required' => true,
            ),
            'paytr_merchant_salt' => array(
                'title' => __('Merchant Salt', $this->text_domain),
                'type' => 'text',
                'description' => __('You will find this value under the PayTR Merchant Panel > Information Tab.', $this->text_domain),
                'desc_tip' => true,
                'required' => true,
            ),
            'paytr_order_status' => array(
                'title' => __('Order Status', $this->text_domain),
                'type' => 'select',
                'description' => __('Order status when payment is successful. Recommended processing.', $this->text_domain),
                'desc_tip' => true,
                'default' => 'wc-processing',
                'options' => $order_statues,

            ),
            'paytr_ins_difference' => array(
                'title' => __('Installment Difference', $this->text_domain),
                'label' => __('Enable/Disable', $this->text_domain),
                'type' => 'checkbox',
                'description' => __('When payment completed with the installment then adds Installment Difference to the order as a fee and recalculates the order total.', $this->text_domain),
                'desc_tip' => true,
                'default' => 'no'
            ),
            'paytr_lang' => array(
                'title' => __('Language', $this->text_domain),
                'type' => 'select',
                'default' => '0',
                'options' => array(
                    '0' => __('Automatic', $this->text_domain),
                    '1' => __('Turkish', $this->text_domain),
                    '2' => __('English', $this->text_domain),
                ),
            ),
            'paytr_installment' => array(
                'title' => __('Installment', $this->text_domain),
                'type' => 'select',
                'default' => '0',
                'options' => array(
                    '0' => __('All Installment Options', $this->text_domain),
                    '1' => __('One Shot (No Installment)', $this->text_domain),
                    '2' => __('Up to 2 Installment', $this->text_domain),
                    '3' => __('Up to 3 Installment', $this->text_domain),
                    '4' => __('Up to 4 Installment', $this->text_domain),
                    '5' => __('Up to 5 Installment', $this->text_domain),
                    '6' => __('Up to 6 Installment', $this->text_domain),
                    '7' => __('Up to 7 Installment', $this->text_domain),
                    '8' => __('Up to 8 Installment', $this->text_domain),
                    '9' => __('Up to 9 Installment', $this->text_domain),
                    '10' => __('Up to 10 Installment', $this->text_domain),
                    '11' => __('Up to 11 Installment', $this->text_domain),
                    '12' => __('Up to 12 Installment', $this->text_domain),
                    '13' => __('Category Based', $this->text_domain),
                ),

            ),
        );

        if ($this->get_option('paytr_installment') == 13) {
            $installment_arr = array(
                '0' => __('All Installment Options', $this->text_domain),
                '1' => __('One Shot (No Installment)', $this->text_domain),
                '2' => __('Up to 2 Installment', $this->text_domain),
                '3' => __('Up to 3 Installment', $this->text_domain),
                '4' => __('Up to 4 Installment', $this->text_domain),
                '5' => __('Up to 5 Installment', $this->text_domain),
                '6' => __('Up to 6 Installment', $this->text_domain),
                '7' => __('Up to 7 Installment', $this->text_domain),
                '8' => __('Up to 8 Installment', $this->text_domain),
                '9' => __('Up to 9 Installment', $this->text_domain),
                '10' => __('Up to 10 Installment', $this->text_domain),
                '11' => __('Up to 11 Installment', $this->text_domain),
                '12' => __('Up to 12 Installment', $this->text_domain),
            );

            $tree = $this->category_parser();
            $finish = array();
            $this->category_parser_clear($tree, 0, array(), $finish);

            foreach ($finish as $key => $item) {
                $this->form_fields['paytr_installment_cat_' . $key] = array(
                    'title' => __($item, $this->text_domain),
                    'type' => 'select',
                    'default' => '0',
                    'options' => $installment_arr,
                );

                $this->paytr_installment_list[$key] = ($this->get_option('paytr_installment_cat_' . $key) ? $this->get_option('paytr_installment_cat_' . $key) : 0);
            }
        }
    }

    /**
     * Admin Form Controls
     *
     * @param $settings
     *
     * @return
     */
    public function sanitize_form_fields($settings)
    {
        if (isset($settings)) {

            if (isset($settings['title'])) {
                $settings['title'] = _sanitize_text_fields($settings['title']);
            }

            if (isset($settings['description'])) {
                $settings['description'] = _sanitize_text_fields($settings['description']);
            }
            if (isset($settings['paytr_merchant_id'])) {
                $settings['paytr_merchant_id'] = _sanitize_text_fields($settings['paytr_merchant_id']);
            }
            if (isset($settings['paytr_merchant_key'])) {
                $settings['paytr_merchant_key'] = _sanitize_text_fields($settings['paytr_merchant_key']);
            }
            if (isset($settings['paytr_merchant_salt'])) {
                $settings['paytr_merchant_salt'] = _sanitize_text_fields($settings['paytr_merchant_salt']);
            }

            return $settings;
        }
    }

    public function validate_paytr_merchant_id_field($key, $value)
    {
        $value = $this->validate_text_field($key, $value);
        $value = ltrim($value);
        $value = rtrim($value);
        $value = trim($value);

        if (!empty($value) && !intval($value)) {
            WC_Admin_Settings::add_error(__('Merchant ID must be numeric.', $this->text_domain));

            return false;
        }

        return $value;
    }

    public function validate_paytr_merchant_key_field($key, $value)
    {
        $value = $this->validate_text_field($key, $value);
        $value = ltrim($value);
        $value = rtrim($value);
        $value = trim($value);

        if (!empty($value)) {
            if (strlen($value) < 16 || strlen($value) > 16) {
                WC_Admin_Settings::add_error(__('Merchant Key is wrong. Must be max 16 char.', $this->text_domain));

                return false;
            }
        }

        return $value;
    }

    public function validate_paytr_merchant_salt_field($key, $value)
    {
        $value = $this->validate_text_field($key, $value);
        $value = ltrim($value);
        $value = rtrim($value);
        $value = trim($value);

        if (!empty($value)) {
            if (strlen($value) < 16 || strlen($value) > 16) {
                WC_Admin_Settings::add_error(__('Merchant Salt is wrong. Must be max 16 char.', $this->text_domain));

                return false;
            }
        }

        return $value;
    }

    /**
     * Process Payment
     *
     * @param null $order_id
     *
     * @return array
     */
    public function process_payment($order_id = null)
    {
        $order = wc_get_order($order_id);

        return array(
            'result' => 'success',
            'redirect' => $order->get_checkout_payment_url(true),
        );
    }

    /**
     * Receipt Page
     *
     * @param $order
     */
    public function receipt_page($order)
    {
        echo $this->generate_paytrcheckout_form($order);
    }

    /**
     * Checkout Form
     *
     * @param $order_id
     */
    private function generate_paytrcheckout_form($order_id)
    {
        $merchant = array();

        $this->category_parser_prod();

        // Get Order
        $order = new WC_Order($order_id);

        $merchant['merchant_oid'] = time() . 'PAYTRWOO' . $order_id;
        $merchant['user_ip'] = $this->GetIP();

        $country = sanitize_text_field($order->get_billing_country());
        $state = sanitize_text_field($order->get_billing_state());
        $get_country = sanitize_text_field(WC()->countries->get_states($country)[$state]);

        $merchant['email'] = sanitize_email(substr($order->get_billing_email(), 0, 100));
        $merchant['payment_amount'] = $order->get_total() * 100;
        $merchant['user_name'] = sanitize_text_field(substr($order->get_billing_first_name() . ' ' . $order->get_billing_last_name(), 0, 60));
        $merchant['user_address'] = substr($order->get_billing_address_1() . ' ' . $order->get_billing_address_2() . ' ' . $order->get_billing_city() . ' ' . $get_country . ' ' . $order->get_billing_postcode(), 0, 300);
        $merchant['user_phone'] = sanitize_text_field(substr($order->get_billing_phone(), 0, 20));

        // Basket
        $user_basket = array();
        $item_loop = 0;

        if (sizeof($order->get_items()) > 0) {
            $installment = array();

            foreach ($order->get_items() as $item) {
                if ($item['qty']) {
                    $item_loop++;

                    $product = $item->get_product();

                    $item_name = $item['name'];

                    // WC_Order_Item_Meta is deprecated since WooCommerce version 3.1.0
                    if (defined('WOOCOMMERCE_VERSION') && version_compare(WOOCOMMERCE_VERSION, '3.1.0', '>=')) {
                        $item_name .= wc_display_item_meta($item, array(
                            'before' => '',
                            'after' => '',
                            'separator' => ' | ',
                            'echo' => false,
                            'autop' => false
                        ));
                    } else {
                        $item_meta = new WC_Order_Item_Meta($item['item_meta']);
                        if ($meta = $item_meta->display(true, true)) {
                            $item_name .= ' ( ' . $meta . ' )';
                        }
                    }

                    $item_total_inc_tax = $order->get_item_subtotal($item, true);
                    $sku = '';

                    if ($product->get_sku()) {
                        $sku = '[STK:' . $product->get_sku() . ']';
                    }

                    $user_basket[] = array(
                        str_replace(':', ' = ', $sku) . ' ' . $item_name,
                        $item_total_inc_tax,
                        $item['qty'],
                    );

                    if ($this->paytr_installment == 13) {
                        $this->category_installment = $this->paytr_installment_list;
                        $categorys = get_the_terms($item['product_id'], 'product_cat');

                        foreach ($categorys as $cat) {
                            if (array_key_exists($cat->term_id, $this->paytr_installment_list)) {
                                $installment[$cat->term_id] = $this->paytr_installment_list[$cat->term_id];
                            } else {
                                $installment[$cat->term_id] = $this->cat_search_prod($cat->term_id);
                            }
                        }
                    }
                }
            }
        }

        // Category Based
        if ($this->paytr_installment != 13) {
            $merchant['max_installment'] = in_array($this->paytr_installment, range(0, 12)) ? $this->paytr_installment : 0;
        } else {
            $installment = count(array_diff($installment, array(0))) > 0 ? min(array_diff($installment, array(0))) : 0;
            $merchant['max_installment'] = $installment ? $installment : 0;
        }

        $merchant['user_basket'] = base64_encode(json_encode($user_basket));
        $merchant['no_installment'] = ($merchant['max_installment'] == 1) ? 1 : 0;
        $merchant['debug_on'] = 1;
        $merchant['currency'] = strtoupper(get_woocommerce_currency());

        $hash_str = $this->paytr_merchant_id . $merchant['user_ip'] . $merchant['merchant_oid'] . $merchant['email'] . $merchant['payment_amount'] . $merchant['user_basket'] . $merchant['no_installment'] . $merchant['max_installment'] . $merchant['currency'];
        $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $this->paytr_merchant_salt, $this->paytr_merchant_key, true));

        $post_data = array(
            'merchant_id' => $this->paytr_merchant_id,
            'user_ip' => $merchant['user_ip'],
            'merchant_oid' => $merchant['merchant_oid'],
            'email' => $merchant['email'],
            'payment_amount' => $merchant['payment_amount'],
            'paytr_token' => $paytr_token,
            'user_basket' => $merchant['user_basket'],
            'debug_on' => $merchant['debug_on'],
            'no_installment' => $merchant['no_installment'],
            'max_installment' => $merchant['max_installment'],
            'user_name' => $merchant['user_name'],
            'user_address' => $merchant['user_address'],
            'user_phone' => $merchant['user_phone'],
            'currency' => $merchant['currency'],
            'merchant_fail_url' => wc_get_cart_url(),
        );

        $post_data['merchant_ok_url'] = $order->get_checkout_order_received_url();

        if ($this->paytr_lang == 0) {
            $lang_arr = array(
                'tr',
                'tr-tr',
                'tr_tr',
                'turkish',
                'turk',
                'türkçe',
                'turkce',
                'try',
                'trl',
                'tl'
            );
            $post_data['lang'] = (in_array(strtolower(get_locale()), $lang_arr) ? 'tr' : 'en');
        } else {
            $post_data['lang'] = ($this->paytr_lang == 1 ? 'tr' : 'en');
        }

        $wpCurlArgs = array(
            'method' => 'POST',
            'body' => $post_data,
            'httpversion' => '1.0',
            'sslverify' => true,
            'timeout' => 90,
        );
        $result = wp_remote_post('https://www.paytr.com/odeme/api/get-token', $wpCurlArgs);
        $body = wp_remote_retrieve_body($result);
        $response = json_decode($body, 1);

        if ($response['status'] == 'success') {
            $token = $response['token'];

            // Save Transaction
            global $wpdb, $table_prefix;

            $wpdb->query($wpdb->prepare(
                "INSERT INTO {$table_prefix}paytr_iframe_transaction 
                (order_id, merchant_oid, total, is_order, date_added) 
                VALUES (%d, %s, %f, %d, %s)",
                $order_id, $merchant['merchant_oid'], $order->get_total(), 1, current_time('mysql')
            ));

            $order->update_meta_data('paytr_order_id', $merchant['merchant_oid']);
            $order->save_meta_data();
        } else {
            wp_die("PAYTR IFRAME failed. reason:" . $response['reason']);
        }

        wp_enqueue_script('script', PAYTRSPI_PLUGIN_URL . '/assets/js/payTRiframeResizer.js', false, '2.0', true);

        ?>
        <iframe src="https://www.paytr.com/odeme/guvenli/<?php echo $token; ?>" id="paytriframe" frameborder="0"
                style="width: 100%;"></iframe>
        <script type="text/javascript">
            setInterval(function () {
                iFrameResize({}, '#paytriframe');
            }, 1000);
        </script>
        <?php
    }

    /**
     * Process Refund
     *
     * @param int $order_id
     * @param null $amount
     * @param string $reason
     *
     * @return bool|WP_Error
     */
    function process_refund($order_id, $amount = null, $reason = '')
    {
        global $wpdb, $table_prefix;

        $amount = sanitize_text_field($amount);
        $reason = sanitize_text_field($reason);

        if (is_null($amount) or $amount <= 0) {
            return new WP_Error('paytr_refund_error', __('The amount is empty or less than 0.', $this->text_domain));
        }

        $options = get_option('woocommerce_paytrcheckout_settings');

        $order = wc_get_order($order_id);
        $merchant_oid = $order->get_meta('paytr_order_id');

        if (!$merchant_oid) {
            return new WP_Error('paytr_refund_error', __('PayTR Order number not found.', $this->text_domain));
        }

        $paytr_order = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table_prefix}paytr_iframe_transaction WHERE merchant_oid = %s", $merchant_oid));

        if (!$paytr_order->is_completed) {
            return new WP_Error('paytr_refund_error', __('The notification process has not been completed yet.', $this->text_domain));
        }

        if ($paytr_order->is_failed) {
            return new WP_Error('paytr_refund_error', __('Can not refund the failed orders.', $this->text_domain));
        }

        if ($amount == $paytr_order->total_paid) {
            $amount = $paytr_order->total;
        }

        $paytr_token = base64_encode(hash_hmac('sha256', $options['paytr_merchant_id'] . $merchant_oid . $amount . $options['paytr_merchant_salt'], $options['paytr_merchant_key'], true));

        $post_data = array(
            'merchant_id' => $options['paytr_merchant_id'],
            'merchant_oid' => $merchant_oid,
            'return_amount' => $amount,
            'paytr_token' => $paytr_token
        );

        $wpCurlArgs = array(
            'method' => 'POST',
            'body' => $post_data,
            'httpversion' => '1.0',
            'sslverify' => true,
            'timeout' => 90,
        );
        $result = wp_remote_post('https://www.paytr.com/odeme/iade', $wpCurlArgs);
        $body = wp_remote_retrieve_body($result);
        $response = json_decode($body, 1);

        if (sanitize_text_field($response['status']) == 'success') {

            // Note Start
            $note = __('PAYTR NOTIFICATION - Refund', $this->text_domain) . "\n";
            $note .= __('Status', $this->text_domain) . ': ' . $response['status'] . "\n";
            $note .= __('PayTR Order ID', $this->text_domain) . ': <a href="https://www.paytr.com/magaza/satislar?merchant_oid=' . $merchant_oid . '" target="_blank">' . $merchant_oid . '</a>' . "\n";
            $note .= __('Refund Amount', $this->text_domain) . ': ' . wc_price($response['return_amount'], array('currency' => $order->get_currency())) . "\n";

            if ($reason != '') {
                $note .= 'Reason of Refund : ' . $reason;
            }

            if ($paytr_order) {
                $refund_status = 'partial';
                $refund_amount = 0;

                if ($paytr_order->total == $amount && $paytr_order->total == $response['return_amount']) {
                    $refund_status = 'full';
                    $refund_amount = $response['return_amount'];
                } else {
                    if ($paytr_order->is_refunded && $paytr_order->refund_status == 'partial') {
                        $refund_amount = $paytr_order->refund_amount + $response['return_amount'];

                        if ($refund_amount == $paytr_order->total) {
                            $refund_status = 'full';
                            $refund_amount = $paytr_order->total;
                        }
                    } else {
                        $refund_amount = $response['return_amount'];
                    }
                }

                $data = [
                    'is_refunded' => 1,
                    'refund_status' => $refund_status,
                    'refund_amount' => $refund_amount,
                    'date_updated' => current_time('mysql')
                ];
                $where = ['merchant_oid' => $merchant_oid];
                $wpdb->update($table_prefix . 'paytr_iframe_transaction', $data, $where);
            } else {

                $note .= __('Attention! The refund status can not reflected to PayTR Table. Reason: Order not found in PayTR Table.', $this->text_domain);
                // Note End
            }

            $order->add_order_note($note);

            return true;
        } else {
            $note = $response['status'] . ' - ' . $response['err_no'] . ' - ' . $response['err_msg'];

            return new WP_Error('paytr_refund_error', __('An error occurred when refunded. Reason;' . "\n" . $note, $this->text_domain));
        }
    }

    /**
     * Get IP
     */
    private function GetIP()
    {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        return $ip;
    }

    /**
     * Callback URL
     */
    function paytrcheckout_response()
    {
        if (empty($_POST)) {
            die();
        }

        if (!isset($_POST['payment_type']) && $_POST['status'] == 'info') {
            require_once PAYTRSPI_PLUGIN_PATH . '/includes/class-paytrspi-callback-eft.php';
            PaytrCheckoutCallbackEft::callback_eft_interim($_POST);
            exit;
        }


        if ($_POST['payment_type'] == 'eft') {
            require_once PAYTRSPI_PLUGIN_PATH . '/includes/class-paytrspi-callback-eft.php';
            PaytrCheckoutCallbackEft::callback_eft($_POST);
        } else {
            require_once PAYTRSPI_PLUGIN_PATH . '/includes/class-paytrspi-callback-iframe.php';
            PaytrCheckoutCallbackIframe::callback_iframe($_POST);
        }
    }

    /**
     * Category Based Options
     */
    function category_parser()
    {
        $all_cats = get_terms('product_cat', array());
        $cats = array();

        foreach ($all_cats as $cat) {
            $cats[] = array('id' => $cat->term_id, 'parent_id' => $cat->parent, 'name' => $cat->name);
        }

        $cat_tree = array();

        foreach ($cats as $key => $item) {
            if ($item['parent_id'] == 0) {
                $cat_tree[$item['id']] = array('id' => $item['id'], 'name' => $item['name']);
                $this->parent_category_parser($cats, $cat_tree[$item['id']]);
            }
        }

        return $cat_tree;
    }

    function parent_category_parser(&$cats = array(), &$cat_tree = array())
    {
        foreach ($cats as $key => $item) {
            if ($item['parent_id'] == $cat_tree['id']) {
                $cat_tree['parent'][$item['id']] = array('id' => $item['id'], 'name' => $item['name']);
                $this->parent_category_parser($cats, $cat_tree['parent'][$item['id']]);
            }
        }
    }

    function category_parser_clear($tree, $level = 0, $arr = array(), &$finish_him = array())
    {
        foreach ($tree as $id => $item) {
            if ($level == 0) {
                unset($arr);
                $arr = array();
                $arr[] = $item['name'];
            } elseif ($level == 1 or $level == 2) {
                if (count($arr) == ($level + 1)) {
                    $deleted = array_pop($arr);
                }
                $arr[] = $item['name'];
            }

            if ($level < 3) {
                $nav = null;
                foreach ($arr as $key => $val) {
                    $nav .= $val . ($level != 0 ? ' > ' : null);
                }

                $finish_him[$item['id']] = rtrim($nav, ' > ') . '<br>';

                if (!empty($item['parent'])) {
                    $this->category_parser_clear($item['parent'], $level + 1, $arr, $finish_him);
                }
            }
        }
    }

    function category_parser_prod()
    {
        $all_cats = get_terms('product_cat', array());
        $cats = array();
        foreach ($all_cats as $cat) {
            $this->category_full[$cat->term_id] = $cat->parent;
        }
    }

    function cat_search_prod($category_id = 0)
    {

        $return = false;

        if (!empty($this->category_full[$category_id]) and array_key_exists($this->category_full[$category_id], $this->category_installment)) {
            $return = $this->category_installment[$this->category_full[$category_id]];
        } else {
            foreach ($this->category_full as $id => $parent) {
                if ($category_id == $id) {
                    if ($parent == 0) {
                        $return = 0;
                    } elseif (array_key_exists($parent, $this->category_installment)) {
                        $return = $this->category_installment[$parent];
                    } else {
                        $return = $this->cat_search_prod($parent);
                    }
                } else {
                    $return = 0;
                }
            }
        }

        return $return;
    }

}