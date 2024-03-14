<?php
/*
  Plugin Name: Payment Gateway - Alpha Bank for WooCommerce 
  Plugin URI: https://www.papaki.com
  Description: Alpha Bank Payment Gateway allows you to accept payment through various channels such as American Express, Visa, Mastercard, Maestro, Diners Club cards On your Woocommerce Powered Site.
  Version: 1.3.7
  Author: Papaki
  Author URI: https://www.papaki.com
  License: GPL-3.0+
  License URI: http://www.gnu.org/licenses/gpl-3.0.txt
  WC tested up to: 6.2.0
  Text Domain: woo-alpha-bank-payment-gateway
  Domain Path: /languages
*/


if (!defined('ABSPATH')) {
    exit;
}
require_once plugin_dir_path(__FILE__) . 'encryption.php';
add_action('plugins_loaded', 'woocommerce_alphabank_init', 0);

function woocommerce_alphabank_init() {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }
    load_plugin_textdomain('woo-alpha-bank-payment-gateway', false, dirname(plugin_basename(__FILE__)) . '/languages/');


    /**
     *  Gateway Class 
     */
    class WC_alphabank_Gateway extends WC_Payment_Gateway {

        public function __construct() {
            global $woocommerce;

            $this->id = 'alphabank_gateway';
            $this->has_fields = true;
            $this->notify_url = WC()->api_request_url('WC_alphabank_Gateway');
            $this->method_description = __('Alpha bank Payment Gateway allows you to accept payment through various channels such as Visa, Mastercard, Maestro, American Express, Diners, Discover cards on your Woocommerce Powered Site.', 'woo-alpha-bank-payment-gateway');
            $this->redirect_page_id = $this->get_option('redirect_page_id');
            $this->method_title = 'Alpha Bank Gateway';

            // Load the form fields.
            $this->init_form_fields();
            
            global $wpdb;

            if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "alphabank_transactions'") === $wpdb->prefix . 'alphabank_transactions') {
                // The database table exist
            } else {
                // Table does not exist
                $query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'alphabank_transactions (id int(11) unsigned NOT NULL AUTO_INCREMENT,merchantreference varchar(30) not null, reference varchar(100) not null, trans_ticket varchar(100) not null , timestamp datetime default null, PRIMARY KEY (id))';
                $wpdb->query($query);
            }

            // Load the settings
            $this->init_settings();
        

            // Define User set Variables 

            $this->title = sanitize_text_field($this->get_option('title'));
            $this->description = sanitize_text_field($this->get_option('description'));
            $this->ab_merchantId = sanitize_text_field($this->get_option('ab_merchantId'));
            $this->ab_sharedSecretKey = sanitize_text_field($this->get_option('ab_sharedSecretKey'));
            $this->ab_environment = sanitize_text_field($this->get_option('ab_environment'));
            $this->ab_installments = absint($this->get_option('ab_installments'));
            $this->ab_installments_variation = sanitize_text_field($this->get_option('ab_installments_variation'));
            $this->ab_transactionType = sanitize_text_field($this->get_option('ab_transactionType'));
            $this->ab_allowMasterpass = sanitize_text_field($this->get_option('ab_allowMasterpass'));
            $this->ab_render_logo = $this->get_option('ab_render_logo');
            $this->ab_enable_log = sanitize_text_field($this->get_option('ab_enable_log'));
            $this->ab_order_note = $this->get_option('ab_order_note');



            //Actions
            add_action('woocommerce_receipt_alphabank_gateway', array($this, 'receipt_page'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            // Payment listener/API hook
            add_action('woocommerce_api_wc_alphabank_gateway', array($this, 'check_alphabank_response'));

            if($this->ab_render_logo == "yes") {
                $this->icon = apply_filters('alphabank_icon', plugins_url('img/alpha_cards.png', __FILE__));
            }
        }

        /**
         *  Admin Panel Options
         */
        public function admin_options() {
            echo '<h3>' . __('Alpha Bank Gateway', 'woo-alpha-bank-payment-gateway') . '</h3>';
            echo '<p>' . __('Alpha Bank Gateway allows you to accept payment through various channels such as Visa, Mastercard, Maestro, American Express, Diners, Discover cards.', 'woo-alpha-bank-payment-gateway') . '</p>';
            echo '<div style="background: #f1e5bc; padding: 0.3rem 1rem; max-width:900px;"> <p>' . __('In order to enable <strong>AlphaBank MasterPass</strong> you should go to <a style="color:#000" href="/wp-admin/admin.php?page=wc-settings&tab=checkout">Woocommerce Payment methods</a> and enable the «<a style="color:#000" href="/wp-admin/admin.php?page=wc-settings&tab=checkout&section=alphabank_masterpass">Alpha Bank Masterpass</a>» payment method', 'woo-alpha-bank-payment-gateway') . '</p></div>';
            echo '<table class="form-table">';
            $this->generate_settings_html();
            echo '</table>';    
        }

        function register_session(){
            if( !session_id() )
                session_start();
        }
        /**
         * Initialise Gateway Settings Form Fields
         * */
        function init_form_fields() {

            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enable Alpha Bank Gateway', 'woo-alpha-bank-payment-gateway'),
                    'description' => __('Enable or disable the gateway.', 'woo-alpha-bank-payment-gateway'),
                    'desc_tip' => true,
                    'default' => 'yes'
                ),
                'ab_environment' => array(
                    'title' => __('Test Environment', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enable Alpha Bank Test Environment', 'woo-alpha-bank-payment-gateway'),
                    'default' => 'yes',
                ),
                'title' => array(
                    'title' => __('Title', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'woo-alpha-bank-payment-gateway'),
                    'desc_tip' => false,
                    'default' => __('Credit card via Alpha Bank', 'woo-alpha-bank-payment-gateway')
                ),
                'description' => array(
                    'title' => __('Description', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'woo-alpha-bank-payment-gateway'),
                    'default' => __('Pay Via Alpha Bank: Accepts Visa, Mastercard, Maestro, American Express, Diners, Discover', 'woo-alpha-bank-payment-gateway')
                ),
                'ab_render_logo' => array(
                    'title' => __('Display the logo of Alpha Bank', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enable to display the logo of Alpha Bank during checkout', 'woo-alpha-bank-payment-gateway'),
                    'default' => 'yes'
                ),
                'ab_merchantId' => array(
                    'title' => __('Alpha Bank Merchant ID', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'text',
                    'description' => __('Enter Your Alpha Bank Merchant ID', 'woo-alpha-bank-payment-gateway'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'ab_sharedSecretKey' => array(
                    'title' => __('Alpha Bank Shared Secret key', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'password',
                    'description' => __('Enter your Shared Secret key', 'woo-alpha-bank-payment-gateway'),
                    'default' => '',
                    'desc_tip' => true
                ), 
                'ab_installments' => array(
                    'title' => __('Maximum number of installments regardless of the total order amount', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'select',
                    'options' => $this->ab_get_installments('Select Installments'),
                    'description' => __('1 to 24 Installments,1 for one time payment. You must contact Alpha Bank first<br /> If you have filled the "Max Number of installments depending on the total order amount", the value of this field will be ignored.', 'woo-alpha-bank-payment-gateway')
                ),
                'ab_installments_variation' => array(
                    'title' => __('Maximum number of installments depending on the total order amount', 'woo-alpha-bank-payment-gateway'),
                    'type'  => 'text',
                    'description' => __('Example 80:2, 160:4, 300:8</br> total order greater or equal to 80 -> allow 2 installments, total order greater or equal to 160 -> allow 4 installments, total order greater or equal to 300 -> allow 8 installments</br> Leave the field blank if you do not want to limit the number of installments depending on the amount of the order.', 'woo-alpha-bank-payment-gateway')
                ),
                'ab_transactionType' => array(
                    'title' => __('Pre-Authorize', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enable to capture preauthorized payments', 'woo-alpha-bank-payment-gateway'),
                    'default' => 'no'
                ),
                'redirect_page_id' => array(
                    'title' => __('Return page URL <br />(Successful or Failed Transactions)', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'select',
                    'options' => $this->ab_get_pages('Select Page'),
                    'description' => __('We recommend you to select the default “Thank You Page”, in order to automatically serve both successful and failed transactions, with the latter also offering the option to try the payment again.<br /> If you select a different page, you will have to handle failed payments yourself by adding custom code.', 'woo-alpha-bank-payment-gateway'),
                    'default' => "-1"
                ),
                'ab_enable_log' => array(
                    'title' => __('Enable Debug mode', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enabling this will log certain information', 'woo-alpha-bank-payment-gateway'),
                    'default' => 'no',
                    'description' => __('Enabling this (and the debug mode from your wp-config file) will log information, e.g. bank responses, which will help in debugging issues.', 'woo-alpha-bank-payment-gateway')
                ),
                'ab_order_note' => array(
                    'title' => __('Enable 2nd “payment received” email', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enable sending Customer order note with transaction details', 'woo-alpha-bank-payment-gateway'),
                    'default' => 'no',
                    'description' => __('Enabling this will send an email with the support reference id and transaction id to the customer, after the transaction has been completed (either on success or failure)', 'woo-alpha-bank-payment-gateway')
                )
            );
        }

        function ab_get_pages($title = false, $indent = true) {
            $wp_pages = get_pages('sort_column=menu_order');
            $page_list = array();
            if ($title)
                $page_list[] = $title;
            foreach ($wp_pages as $page) {
                $prefix = '';
                // show indented child pages?
                if ($indent) {
                    $has_parent = $page->post_parent;
                    while ($has_parent) {
                        $prefix .= ' - ';
                        $next_page = get_page($has_parent);
                        $has_parent = $next_page->post_parent;
                    }
                }
                // add to page list array array
                $page_list[$page->ID] = $prefix . $page->post_title;
            }
            $page_list[-1] = __('Thank you page', 'woo-alpha-bank-payment-gateway');
            return $page_list;
        }

        function ab_get_installments($title = false, $indent = true) {


            for ($i = 1; $i <= 24; $i++) {
                $installment_list[$i] = $i;
            }
            return $installment_list;
        }

        public function get_option($key, $empty_value = null)
        {
            $option_value = parent::get_option($key, $empty_value);
            if ($key == 'ab_sharedSecretKey') {
                $decrypted = WC_Payment_Gateway_KeyEncryption_Alpha::decrypt(base64_decode($option_value), substr(NONCE_KEY, 0, 32));
                $option_value = $decrypted;
            }
            return $option_value;
        }

        public function validate_ab_sharedSecretKey_field($key, $value)
        { 
            $encrypted = WC_Payment_Gateway_KeyEncryption_Alpha::encrypt($value, substr(NONCE_KEY, 0, 32));
            return base64_encode($encrypted);
        }

        function payment_fields() {
            global $woocommerce;
            
            //get: order or cart total, to compute max installments number.
            if(absint(get_query_var('order-pay'))) {
                $order_id = absint(get_query_var('order-pay'));
                $order = new WC_Order($order_id);
                $Amount = $order->get_total();
            } else if(!$woocommerce->cart->is_empty()) {
                $Amount = $woocommerce->cart->total;
            }

            if ($description = $this->get_description()) {
                echo wpautop(wptexturize($description));
            }
            $max_installments = $this->ab_installments;
            $installments_variation = $this->ab_installments_variation; 

            if(!empty($installments_variation)) {
                $max_installments = 1; // initialize the max installments 
                if(isset($installments_variation) && !empty($installments_variation)) {
                    $installments_split = explode(',', $installments_variation);
                    foreach($installments_split as $key => $value) {
                        $installment = explode(':', $value);
                        if(is_array($installment) && count($installment) != 2) {
                            // not valid rule for installments
                            continue;
                        }
                        if(!is_numeric($installment[0]) || !is_numeric($installment[1])) {
                            // not valid rule for installments                            
                            continue;
                        }
                        if($Amount >= ($installment[0])) {
                            $max_installments = $installment[1];
                        }
                    }
                }
            }

            if ($max_installments > 1) {

                $doseis_field = '<p class="form-row ">
                    <label for="' . esc_attr($this->id) . '-card-doseis">' . __('Choose Installments', 'woo-alpha-bank-payment-gateway') . ' <span class="required">*</span></label>
                                <select id="' . esc_attr($this->id) . '-card-doseis" name="' . esc_attr($this->id) . '-card-doseis" class="input-select wc-credit-card-form-card-doseis">
                                ';
                        for ($i = 1; $i <= $max_installments; $i++) {
                            $doseis_field .= '<option value="' . $i . '">' . ($i == 1 ? __('Without installments', 'woo-alpha-bank-payment-gateway') : $i). '</option>';
                        }
                        $doseis_field .= '</select>
                        </p>'; // <img width="100%" height="100%" style="max-height:100px!important" src="'. plugins_url('img/alpha_cards.png', __FILE__) .'" >
                
                echo $doseis_field;
            }
        }
   
        function calculate_digest($input) {

            $digest = base64_encode(hash('sha256', ($input), true));
            return $digest;
        }

        function generate_alphabank_form($order_id) {
            global $woocommerce;
            global $wpdb;

            $locale = get_locale();
            if($locale == 'el') {
                $lang = 'el';
            } else {
                $lang = 'en';
            }

            $version = 2;
            $currency = 'EUR';
            if($this->ab_environment == "yes") {
                $post_url = "https://alphaecommerce-test.cardlink.gr/vpos/shophandlermpi";
            } else {
                $post_url = "https://www.alphaecommerce.gr/vpos/shophandlermpi";
            }

            if($this->ab_transactionType == 'yes') {
                $trType = 2;
            } else {
                $trType = 1;
            }

            $order = new WC_Order($order_id);

            if (method_exists($order, 'get_meta')) {
                $installments = $order->get_meta('_doseis');
                if ($installments == '') {
                    $installments = 1;
                }
            } else {
                $installments = get_post_meta($order_id, '_doseis', 1);
            }

            $countries_obj = new WC_Countries();
            $country = $order->get_billing_country();
            $country_states_array = $countries_obj->get_states();
            $state_code = $order->get_billing_state();  
            $state = $country_states_array[$country][$state_code];

            $wpdb->insert($wpdb->prefix . 'alphabank_transactions', array('trans_ticket' => $order_id, 'merchantreference' => $order_id, 'timestamp' => current_time('mysql', 1)));

            wc_enqueue_js('
            $.blockUI({
            message: "' . esc_js(__('Thank you for your order. We are now redirecting you to Alpha Bank to make payment.', 'woo-alpha-bank-payment-gateway')) . '",
            baseZ: 99999,
            overlayCSS:
            {
            background: "#fff",
            opacity: 0.6
            },
            css: {
            padding:        "20px",
            zindex:         "9999999",
            textAlign:      "center",
            color:          "#555",
            border:         "3px solid #aaa",
            backgroundColor:"#fff",
            cursor:         "wait",
            lineHeight:		"24px",
            }
            });
            jQuery("#ab_payment_form").submit();
            ');

            $_SESSION['order_id'] = $order_id;
            WC()->session->set('ab_order_id', $order_id);


            if($country != 'GR' ){
                if($installments > 1) {
                    $form_data_array = array(
                        'version' => $version,
                        'mid' => $this->ab_merchantId,
                        'lang' => $lang,
                        'orderid' => $order_id . 'at' . date('Ymdhisu'),
                        'orderDesc' => 'Order #' . $order_id,
                        'orderAmount' => $order->get_total(),
                        'currency' => $currency,
                        'payerEmail' => $order->get_billing_email(),
                        'billCountry' => $country,
                        'billState' => $state_code, 
                        'billZip' => $order->get_billing_postcode(),
                        'billCity' => $order->get_billing_city(),
                        'billAddress' => $order->get_billing_address_1(),
                        'trType' => $trType,
                        'extInstallmentoffset' => 0,
                        'extInstallmentperiod' => $installments,
                        'confirmUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=success",
                        'cancelUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=failure",
                        'var2' =>  $order_id,
                    );
                } else {
                    $form_data_array = array(
                        'version' => $version,
                        'mid' => $this->ab_merchantId,
                        'lang' => $lang,
                        'orderid' => $order_id . 'at' . date('Ymdhisu'),
                        'orderDesc' => 'Order #' . $order_id,
                        'orderAmount' => $order->get_total(),
                        'currency' => $currency,
                        'payerEmail' => $order->get_billing_email(),
                        'billCountry' => $country,
                        'billState' => $state_code,
                        'billZip' => $order->get_billing_postcode(),
                        'billCity' => $order->get_billing_city(),
                        'billAddress' => $order->get_billing_address_1(),
                        'trType' => $trType,
                        'confirmUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=success",
                        'cancelUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=failure",
                        'var2' =>  $order_id,
                    );
                }
            } else {
                if($installments > 1) {
                    $form_data_array = array(
                        'version' => $version,
                        'mid' => $this->ab_merchantId,
                        'lang' => $lang,
                        'orderid' => $order_id . 'at' . date('Ymdhisu'),
                        'orderDesc' => 'Order #' . $order_id,
                        'orderAmount' => $order->get_total(),
                        'currency' => $currency,
                        'payerEmail' => $order->get_billing_email(),
                        'billCountry' => $country,
                        'billZip' => $order->get_billing_postcode(),
                        'billCity' => $order->get_billing_city(),
                        'billAddress' => $order->get_billing_address_1(),
                        'trType' => $trType,
                        'extInstallmentoffset' => 0,
                        'extInstallmentperiod' => $installments,
                        'confirmUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=success",
                        'cancelUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=failure",
                        'var2' =>  $order_id,
                    );
                } else {
                    $form_data_array = array(
                        'version' => $version,
                        'mid' => $this->ab_merchantId,
                        'lang' => $lang,
                        'orderid' => $order_id . 'at' . date('Ymdhisu'),
                        'orderDesc' => 'Order #' . $order_id,
                        'orderAmount' => $order->get_total(),
                        'currency' => $currency,
                        'payerEmail' => $order->get_billing_email(),
                        'billCountry' => $country,
                        'billZip' => $order->get_billing_postcode(),
                        'billCity' => $order->get_billing_city(),
                        'billAddress' => $order->get_billing_address_1(),
                        'trType' => $trType,
                        'confirmUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=success",
                        'cancelUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=failure",
                        'var2' =>  $order_id,
                    );
                }
            }

            $form_secret = $this->ab_sharedSecretKey;
            $form_data = iconv('utf-8', 'utf-8//IGNORE', implode("", $form_data_array)). $form_secret;           
            $digest = $this->calculate_digest($form_data);

            if($this->ab_enable_log == 'yes') {
                error_log( '---- Alpha Bank Transaction digest -----');
                error_log( 'Data: ' );
                error_log( print_r($form_data, true ) );
                error_log( 'Digest: ' );
                error_log( print_r($digest, true ) );
                error_log( '---- End of Alpha Bank Transaction digest ----');
            }

            $html = '<form action="'. esc_url($post_url) .'" method="POST" id="ab_payment_form" target="_top" accept-charset="UTF-8">';

            foreach($form_data_array as $key => $value) {
                $html .= '<input type="hidden" id ="' . $key . '" name ="' . $key . '" value="' . iconv('utf-8', 'utf-8//IGNORE', $value) . '"/>';
            }

            $html .= '<input type="hidden" id="digest" name ="digest" value="' . esc_attr($digest) . '"/>';
            $html .= '<!-- Button Fallback -->
            <div class="payment_buttons">
                <input type="submit" class="button alt" id="submit_alphabank_payment_form" value="' . __('Pay via Alpha Bank', 'woo-alpha-bank-payment-gateway') . '" /> <a class="button cancel" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'woo-alpha-bank-payment-gateway') . '</a>
            </div>
            <script type="text/javascript">
                jQuery(".payment_buttons").hide();
            </script>';
            $html .= '</form>';
            return $html;
        }

        
        function process_payment($order_id)
        {

            /*
            get_permalink was used instead of $order->get_checkout_payment_url in redirect in order to have a fixed checkout page to provide to Alphabank
             */

            $order = new WC_Order($order_id);
            $doseis = intval($_POST[esc_attr($this->id) . '-card-doseis']);
            if ($doseis > 0) {
                $this->generic_add_meta($order_id, '_doseis', $doseis);
            }
            $current_version = get_option( 'woocommerce_version', null );
            if (version_compare( $current_version, '2.2.0', '<' )) { //older version
                return array('result' => 'success', 'redirect' => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay')))));
            } else if (version_compare( $current_version, '2.4.0', '<' )) { //older version
                return array
                    (
                        'result' => 'success',
                        'redirect'	=> add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))
                    );
            } else if (version_compare( $current_version, '3.0.0', '<' )) { //older version
                return array
                    (
                        'result' => 'success',
                        'redirect'	=> add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, wc_get_page_permalink( 'checkout' )))
                    );
            } else {
                return array('result' => 'success', 
                'redirect' => add_query_arg('order-pay', $order->get_id(), add_query_arg('key', $order->get_order_key(), wc_get_page_permalink('checkout'))));
            }

            return array('result' => 'success', 
            'redirect' => add_query_arg('order-pay', $order->get_id(), add_query_arg('key', $order->get_order_key(), wc_get_page_permalink('checkout'))));
        }
        function receipt_page($order) {
            echo '<p>' . __('Thank you for your order. We are now redirecting you to Alpha Bank Paycenter to make payment.', 'woo-alpha-bank-payment-gateway') . '</p>';
            echo $this->generate_alphabank_form($order);
        }
        /**
         * Verify a successful Payment!
         * */
        function check_alphabank_response() {
            global $woocommerce;
            global $wpdb;

            if($this->ab_enable_log == 'yes') {
                error_log( '---- Alpha Bank Response -----');
                error_log( print_r( $_POST, true ) );
                error_log( '---- End of Alpha Bank Response ----');
            }

            $mid = filter_var($_POST['mid'], FILTER_SANITIZE_NUMBER_INT);
            
            $orderid_session = WC()->session->get('ab_order_id');
            $orderid_post = filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);

            $reg = preg_match('/^(.*?)at/', $orderid = $orderid_post, $matches);
            
            if(!empty($matches)) {
                $orderid = $matches[1];
            } else {
                $orderid = $orderid_session;
            }

            if($orderid == ''){
                $orderid = $orderid_post;
                error_log("Alpha Bank: something went wrong with order id " );
                error_log(print_r($_POST, true));
                error_log(print_r($matches, true));
                error_log($orderid_session);
            }
            
            $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
            $orderAmount = filter_var($_POST['orderAmount'], FILTER_SANITIZE_NUMBER_FLOAT);
            $currency = filter_var($_POST['currency'], FILTER_SANITIZE_STRING);
            $paymentTotal = filter_var($_POST['paymentTotal'],FILTER_SANITIZE_NUMBER_FLOAT );
            $message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_STRING) : '';
            $riskScore = isset($_POST['riskScore']) ? filter_var($_POST['riskScore'], FILTER_SANITIZE_NUMBER_FLOAT) : '';
            $payMethod = isset($_POST['payMethod']) ? filter_var($_POST['payMethod'], FILTER_SANITIZE_STRING) : '';
            $txId = isset($_POST['txId']) ? filter_var($_POST['txId'],FILTER_SANITIZE_NUMBER_FLOAT ) : '';
            $paymentRef = isset($_POST['paymentRef']) ? filter_var($_POST['paymentRef'], FILTER_SANITIZE_STRING) : '';
            $digest = filter_var($_POST['digest'], FILTER_SANITIZE_STRING);

            $form_data = '';            
            foreach($_POST as $k => $v) {
                if (!in_array($k, array('_charset_', 'digest', 'submitButton'))) {
                    $form_data .= filter_var($_POST[$k], FILTER_SANITIZE_STRING);
                }
            }

            $form_data .= $this->ab_sharedSecretKey;
            $computed_digest = $this->calculate_digest($form_data);

            $order = new WC_Order($orderid);

            if($digest != $computed_digest) {
                $message = __('A technical problem occured. <br />The transaction wasn\'t successful, payment wasn\'t received.', 'woo-alpha-bank-payment-gateway');
                $message_type = 'error';
                $ab_message = array('message' => $message, 'message_type' => $message_type);
                $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
                //Update the order status
                $order->update_status('failed', 'DIGEST');
                if(version_compare(WOOCOMMERCE_VERSION, '2.5', '<')) {
                    $checkout_url = $woocommerce->cart->get_checkout_url();
                } else {
                    $checkout_url = wc_get_checkout_url();
                }
                wp_redirect($checkout_url);
                exit;
            }

            if($status == 'CAPTURED' || $status == 'AUTHORIZED') {
                $order->payment_complete($paymentRef);        


                if ($order->get_status() == 'processing') {

                    $order->add_order_note(__('Payment Via Alpha Bank<br />Transaction ID: ', 'woo-alpha-bank-payment-gateway') . $paymentRef);
                    $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is currently being processed.', 'woo-alpha-bank-payment-gateway');

                    if($this->ab_order_note == 'yes') {
                        //Add customer order note
                        $order->add_order_note(__('Payment Received.<br />Your order is currently being processed.<br />We will be shipping your order to you soon.<br />Alpha Bank Bank ID: ', 'woo-alpha-bank-payment-gateway') . $paymentRef, 1);

                    }
                } else if  ($order->get_status() == 'completed') {
                    $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is now complete.', 'woo-alpha-bank-payment-gateway');
                    if($this->ab_order_note == 'yes') {
                        //Add customer order note
                        $order->add_order_note(__('Payment Received.<br />Your order is now complete.<br />Alpha Bank Transaction ID: ', 'woo-alpha-bank-payment-gateway') . $paymentRef, 1);
                    }
                }     
                $message_type = 'success';

                $ab_message = array('message' => $message, 'message_type' => $message_type);

                $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
                
                // Empty cart
                WC()->cart->empty_cart();
  
            } else if($status == 'CANCELED') {
                // if(version_compare(WOOCOMMERCE_VERSION, '2.5', '<')) {
                //     $checkout_url = $woocommerce->cart->get_checkout_url();
                // } else {
                //     $checkout_url = wc_get_checkout_url();
                // }
                $message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment was cancelled.', 'woo-alpha-bank-payment-gateway');
                $message_type = 'notice';
                $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
				$order->update_status('failed', 'ERROR ' . $message);

            //    wc_add_notice($message, $message_type);
                //wp_redirect($checkout_url);
                //exit ;
            } else if ($status == 'REFUSED') {
                $client_message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-alpha-bank-payment-gateway');
                    $message_type = 'error';
                    $ab_message = array('message' => $client_message, 'message_type' => $message_type);
                    $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
                    //wc_add_notice( $message, $message_type );
                    $order->update_status('failed', 'REFUSED ' . $message );
            } else if($status == 'ERROR') {
                $client_message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-alpha-bank-payment-gateway');
                $message_type = 'error';
                $ab_message = array('message' => $client_message, 'message_type' => $message_type);
                $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
                //wc_add_notice( $message, $message_type );
                $order->update_status('failed', 'ERROR ' . $message );
            } else {
                $client_message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-alpha-bank-payment-gateway');
                $message_type = 'error';
                $ab_message = array('message' => $client_message, 'message_type' => $message_type);
                $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
                //wc_add_notice( $message, $message_type );
                $order->update_status('failed', 'Unknown: ' . $message);
            }

            
            if ($this->redirect_page_id == "-1") {
                $redirect_url = $this->get_return_url($order);
            } else {
                $redirect_url = ($this->redirect_page_id == "" || $this->redirect_page_id == 0) ? $this->get_return_url($order) : get_permalink($this->redirect_page_id);
            }
            wp_redirect($redirect_url);

            exit;
        }

        function generic_add_meta($orderid, $key, $value) {
            $order = new WC_Order($orderid);
            if (method_exists($order, 'add_meta_data') && method_exists($order, 'save_meta_data')) {
                $order->add_meta_data($key, $value, true);
                $order->save_meta_data();
            } else {
                update_post_meta($orderid, $key, $value);
            }
        }
    }

    class WC_alphabank_Gateway_masterpass extends WC_Payment_Gateway {

        public function __construct() {
            global $woocommerce;
            $this->id = 'alphabank_masterpass';
            $this->has_fields = true;
            $this->notify_url = WC()->api_request_url('WC_alphabank_Gateway_masterpass');
            $this->method_description = __('Alpha bank Payment Masterpass allows you to accept payment through MasterPass.', 'woo-alpha-bank-payment-gateway');
            $this->method_title = 'Alpha Bank Masterpass';

            $this->init_form_fields();
            global $wpdb;

            if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "alphabank_transactions'") === $wpdb->prefix . 'alphabank_transactions') {
                // The database table exist
            } else {
                // Table does not exist
                $query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'alphabank_transactions (id int(11) unsigned NOT NULL AUTO_INCREMENT,merchantreference varchar(30) not null, reference varchar(100) not null, trans_ticket varchar(100) not null , timestamp datetime default null, PRIMARY KEY (id))';
                $wpdb->query($query);
            }

            $this->init_settings();
            $alpha_settings = get_option('woocommerce_alphabank_gateway_settings');
            $this->title = sanitize_text_field($this->get_option('masterpass_title'));
            $this->description = sanitize_text_field($this->get_option('masterpass_description'));
            $this->ab_merchantId = $alpha_settings['ab_merchantId'];
            $this->ab_sharedSecretKey = $this->decrypt_ab_sharedSecretKey($alpha_settings['ab_sharedSecretKey']);
            $this->ab_environment = $alpha_settings['ab_environment'];
            $this->ab_installments = $alpha_settings['ab_installments'];
            $this->ab_installments_variation = $alpha_settings['ab_installments_variation'];
            $this->ab_transactionType = $alpha_settings['ab_transactionType'];
            $this->redirect_page_id = $alpha_settings['redirect_page_id'];
            $this->ab_order_note = $alpha_settings['ab_order_note'];


            
            add_action('woocommerce_receipt_alphabank_masterpass', array($this, 'receipt_page'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            // Payment listener/API hook
            add_action('woocommerce_api_wc_alphabank_gateway', array($this, 'check_alphabank_response'));
        }

        public function decrypt_ab_sharedSecretKey($secretKey) {
            $decrypted = WC_Payment_Gateway_KeyEncryption_Alpha::decrypt(base64_decode($secretKey), substr(NONCE_KEY, 0, 32));
                return $decrypted;
        }

        public function get_option($key, $empty_value = null)
        { 
            $option_value = parent::get_option($key, $empty_value);
            if ($key == 'ab_sharedSecretKey') {
                $decrypted = WC_Payment_Gateway_KeyEncryption_Alpha::decrypt(base64_decode($option_value), substr(NONCE_KEY, 0, 32));
                $option_value = $decrypted;
            }
            return $option_value;
        }
        public function admin_options() {
            echo '<h3>' . __('Alpha Bank MasterPass', 'woo-alpha-bank-payment-gateway') . '</h3>';
            echo '<p>' . __('Alpha Bank MasterPass allows you to pay with your MasterPass.', 'woo-alpha-bank-payment-gateway') . '</p>';
            echo '<table class="form-table">';
            $this->generate_settings_html();
            echo '</table>';    
        }

        function register_session(){
            if( !session_id() )
                session_start();
        }

        function init_form_fields() {
            $this->form_fields = array(
                'masterpass_enabled' => array(
                    'title' => __('Enable/Disable', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'checkbox',
                    'label' => __('Enable Alpha Bank MasterPass', 'woo-alpha-bank-payment-gateway'),
                    'description' => __('Enable or disable the gateway.', 'woo-alpha-bank-payment-gateway'),
                    'desc_tip' => true,
                    'default' => 'yes'
                ),
                'masterpass_title' => array(
                    'title' => __('Title', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'woo-alpha-bank-payment-gateway'),
                    'desc_tip' => false,
                    'default' => __('Pay via MasterPass', 'woo-alpha-bank-payment-gateway')
                ),
                'masterpass_description' => array(
                    'title' => __('Description', 'woo-alpha-bank-payment-gateway'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'woo-alpha-bank-payment-gateway'),
                    'default' => __('Pay Via Alpha Bank MasterPass', 'woo-alpha-bank-payment-gateway')
                )
            );
        }
        
        function calculate_digest($input) {
            $digest = base64_encode(hash('sha256', ($input), true));
            return $digest;
        }
        function ab_get_pages($title = false, $indent = true) {
            $wp_pages = get_pages('sort_column=menu_order');
            $page_list = array();
            if ($title)
                $page_list[] = $title;
            foreach ($wp_pages as $page) {
                $prefix = '';
                // show indented child pages?
                if ($indent) {
                    $has_parent = $page->post_parent;
                    while ($has_parent) {
                        $prefix .= ' - ';
                        $next_page = get_page($has_parent);
                        $has_parent = $next_page->post_parent;
                    }
                }
                // add to page list array array
                $page_list[$page->ID] = $prefix . $page->post_title;
            }
            $page_list[-1] = __('Thank you page', 'woo-alpha-bank-payment-gateway');
            return $page_list;
        }
        function ab_get_installments($title = false, $indent = true) {


            for ($i = 1; $i <= 24; $i++) {
                $installment_list[$i] = $i;
            }
            return $installment_list;
        }

        function payment_fields() {
            global $woocommerce; 

            if(absint(get_query_var('order-pay'))) {
                $order_id = absint(get_query_var('order-pay'));
                $order = new WC_Order($order_id);
                $Amount = $order->get_total();
            } else if(!$woocommerce->cart->is_empty()) {
                $Amount = $woocommerce->cart->total;
            }

            if ($description = $this->get_description()) {
                echo wpautop(wptexturize($description));
            }

            $max_installments = $this->ab_installments;
            $installments_variation = $this->ab_installments_variation; 

            if(!empty($installments_variation)) {
                $max_installments = 1; // initialize the max installments 
                if(isset($installments_variation) && !empty($installments_variation)) {
                    $installments_split = explode(',', $installments_variation);
                    foreach($installments_split as $key => $value) {
                        $installment = explode(':', $value);
                        if(is_array($installment) && count($installment) != 2) {
                            // not valid rule for installments
                            continue;
                        }
                        if(!is_numeric($installment[0]) || !is_numeric($installment[1])) {
                            // not valid rule for installments                            
                            continue;
                        }
                        if($Amount >= ($installment[0])) {
                            $max_installments = $installment[1];
                        }
                    }
                }
            }
            if ($max_installments > 1) {

                $doseis_field = '<p class="form-row ">
                    <label for="' . esc_attr($this->id) . '-card-doseis">' . __('Choose Installments', 'woo-alpha-bank-payment-gateway') . ' <span class="required">*</span></label>
                                <select id="' . esc_attr($this->id) . '-card-doseis" name="' . esc_attr($this->id) . '-card-doseis" class="input-select wc-credit-card-form-card-doseis">
                                ';
                        for ($i = 1; $i <= $max_installments; $i++) {
                            $doseis_field .= '<option value="' . $i . '">'  . ($i == 1 ? __('Without installments','woo-alpha-bank-payment-gateway') : $i). '</option>';
                        }
                        $doseis_field .= '</select>
                        </p>';
                
                echo $doseis_field;
            }

        }

        function generate_alphabank_masterpass_form($order_id) {
            global $woocommerce;
            global $wpdb;

            $locale = get_locale();
            if($locale == 'el') {
                $lang = 'el';
            } else {
                $lang = 'en';
            }

            $version = 2;
            $currency = 'EUR';
            if($this->ab_environment == "yes") {
                $post_url = "https://alphaecommerce-test.cardlink.gr/vpos/shophandlermpi";
            } else {
                $post_url = "https://www.alphaecommerce.gr/vpos/shophandlermpi";
            }

            if($this->ab_transactionType == 'yes') {
                $trType = 2;
            } else {
                $trType = 1;
            }

            $order = new WC_Order($order_id);

            if (method_exists($order, 'get_meta')) {
                $installments = $order->get_meta('_doseis');
                if ($installments == '') {
                    $installments = 1;
                }
            } else {
                $installments = get_post_meta($order_id, '_doseis', 1);
            }

            $countries_obj = new WC_Countries();
            $country = $order->get_billing_country();
            $country_states_array = $countries_obj->get_states();
            $state_code = $order->get_billing_state();  
            $state = $country_states_array[$country][$state_code];

            $wpdb->insert($wpdb->prefix . 'alphabank_transactions', array('trans_ticket' => $order_id, 'merchantreference' => $order_id, 'timestamp' => current_time('mysql', 1)));

            wc_enqueue_js('
            $.blockUI({
            message: "' . esc_js(__('Thank you for your order. We are now redirecting you to Alpha Bank MasterPass to make payment.', 'woo-alpha-bank-payment-gateway')) . '",
            baseZ: 99999,
            overlayCSS:
            {
            background: "#fff",
            opacity: 0.6
            },
            css: {
            padding:        "20px",
            zindex:         "9999999",
            textAlign:      "center",
            color:          "#555",
            border:         "3px solid #aaa",
            backgroundColor:"#fff",
            cursor:         "wait",
            lineHeight:		"24px",
            }
            });
            jQuery("#ab_payment_masterpass_form").submit();
            ');

            $_SESSION['order_id'] = $order_id;
            WC()->session->set('ab_order_id', $order_id);


            if($country != 'GR' ) {
                if($installments > 1) {
                    $form_data_array = array(
                        'version' => $version,
                        'mid' => $this->ab_merchantId,
                        'lang' => $lang,
                        'orderid' => $order_id . 'at' . date('Ymdhisu'),
                        'orderDesc' => 'Order #' . $order_id,
                        'orderAmount' => $order->get_total(),
                        'currency' => $currency,
                        'payerEmail' => $order->get_billing_email(),
                        'billCountry' => $country,
                        'billState' => $state_code, 
                        'billZip' => $order->get_billing_postcode(),
                        'billCity' => $order->get_billing_city(),
                        'billAddress' => $order->get_billing_address_1(),
                        'payMethod' => 'auto:MasterPass',
                        'trType' => $trType,
                        'extInstallmentoffset' => 0,
                        'extInstallmentperiod' => $installments,
                        'confirmUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=success",
                        'cancelUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=failure",
                        'var2' =>  $order_id,
                    );
                } else {
                    $form_data_array = array(
                        'version' => $version,
                        'mid' => $this->ab_merchantId,
                        'lang' => $lang,
                        'orderid' => $order_id . 'at' . date('Ymdhisu'),
                        'orderDesc' => 'Order #' . $order_id,
                        'orderAmount' => $order->get_total(),
                        'currency' => $currency,
                        'payerEmail' => $order->get_billing_email(),
                        'billCountry' => $country,
                        'billState' => $state_code,
                        'billZip' => $order->get_billing_postcode(),
                        'billCity' => $order->get_billing_city(),
                        'billAddress' => $order->get_billing_address_1(),
                        'payMethod' => 'auto:MasterPass',
                        'trType' => $trType,
                        'confirmUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=success",
                        'cancelUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=failure",
                        'var2' =>  $order_id,
                    );
                }
    
            } else {
                if($installments > 1) {
                    $form_data_array = array(
                        'version' => $version,
                        'mid' => $this->ab_merchantId,
                        'lang' => $lang,
                        'orderid' => $order_id . 'at' . date('Ymdhisu'),
                        'orderDesc' => 'Order #' . $order_id,
                        'orderAmount' => $order->get_total(),
                        'currency' => $currency,
                        'payerEmail' => $order->get_billing_email(),
                        'billCountry' => $country,
                        'billZip' => $order->get_billing_postcode(),
                        'billCity' => $order->get_billing_city(),
                        'billAddress' => $order->get_billing_address_1(),
                        'payMethod' => 'auto:MasterPass',
                        'trType' => $trType,
                        'extInstallmentoffset' => 0,
                        'extInstallmentperiod' => $installments,
                        'confirmUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=success",
                        'cancelUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=failure",
                        'var2' =>  $order_id,
                    );
                } else {
                    $form_data_array = array(
                        'version' => $version,
                        'mid' => $this->ab_merchantId,
                        'lang' => $lang,
                        'orderid' => $order_id . 'at' . date('Ymdhisu'),
                        'orderDesc' => 'Order #' . $order_id,
                        'orderAmount' => $order->get_total(),
                        'currency' => $currency,
                        'payerEmail' => $order->get_billing_email(),
                        'billCountry' => $country,
                        'billZip' => $order->get_billing_postcode(),
                        'billCity' => $order->get_billing_city(),
                        'billAddress' => $order->get_billing_address_1(),
                        'payMethod' => 'auto:MasterPass',
                        'trType' => $trType,
                        'confirmUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=success",
                        'cancelUrl' => get_site_url() . "/?wc-api=WC_alphabank_Gateway&result=failure",
                        'var2' =>  $order_id,
                    );
                }
    
            }

            $form_secret = $this->ab_sharedSecretKey;
            $form_data = iconv('utf-8', 'utf-8//IGNORE', implode("", $form_data_array)). $form_secret;           
            $digest = $this->calculate_digest($form_data);

            $html = '<form action="'. esc_url($post_url) .'" method="POST" id="ab_payment_masterpass_form" target="_top" accept-charset="UTF-8">';

            foreach($form_data_array as $key => $value) {
                $html .= '<input type="hidden" id ="' . $key . '" name ="' . $key . '" value="' . iconv('utf-8', 'utf-8//IGNORE', $value) . '"/>';
            }

            $html .= '<input type="hidden" id="digest" name ="digest" value="' . esc_attr($digest) . '"/>';
            $html .= '<!-- Button Fallback -->
            <div class="payment_buttons">
                <input type="submit" class="button alt" id="submit_alphabank_payment_masterpass_form" value="' . __('Pay via Alpha Bank', 'woo-alpha-bank-payment-gateway') . '" /> <a class="button cancel" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'woo-alpha-bank-payment-gateway') . '</a>
            </div>
            <script type="text/javascript">
                jQuery(".payment_buttons").hide();
            </script>';
            $html .= '</form>';
            return $html;
        }
        function process_payment($order_id)
        {

            /*
            get_permalink was used instead of $order->get_checkout_payment_url in redirect in order to have a fixed checkout page to provide to Alphabank
             */

            $order = new WC_Order($order_id);
            $doseis = intval($_POST[esc_attr($this->id) . '-card-doseis']);
            if ($doseis > 0) {
                $this->generic_add_meta($order_id, '_doseis', $doseis);
            }
            $current_version = get_option( 'woocommerce_version', null );
            if (version_compare( $current_version, '2.2.0', '<' )) { //older version
                return array('result' => 'success', 'redirect' => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay')))));
            } else if (version_compare( $current_version, '2.4.0', '<' )) { //older version
                return array
                    (
                        'result' => 'success',
                        'redirect'	=> add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))
                    );
            } else if (version_compare( $current_version, '3.0.0', '<' )) { //older version
                return array
                    (
                        'result' => 'success',
                        'redirect'	=> add_query_arg('order-pay', $order->id, add_query_arg('key', $order->order_key, wc_get_page_permalink( 'checkout' )))
                    );
            } else {
                return array('result' => 'success', 
                'redirect' => add_query_arg('order-pay', $order->get_id(), add_query_arg('key', $order->get_order_key(), wc_get_page_permalink('checkout'))));
            }

            return array('result' => 'success', 
            'redirect' => add_query_arg('order-pay', $order->get_id(), add_query_arg('key', $order->get_order_key(), wc_get_page_permalink('checkout'))));
        }
        function receipt_page($order) {
            echo '<p>' . __('Thank you for your order. We are now redirecting you to Alpha Bank MasterPass Paycenter to make payment.', 'woo-alpha-bank-payment-gateway') . '</p>';
            echo $this->generate_alphabank_masterpass_form($order);
        }
        /**
         * Verify a successful Payment!
         * */
        function check_alphabank_masterpass_response() {
            global $woocommerce;
            global $wpdb;

            $mid = filter_var($_POST['mid'], FILTER_SANITIZE_NUMBER_INT);
            
            $orderid_session =filter_var(WC()->session->get('ab_order_id'), FILTER_SANITIZE_STRING);
            $orderid_post = filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);

            $reg = preg_match('/^(.*?)at/', $orderid = $orderid_post, $matches);

            if(!empty($matches)) {
                $orderid = $matches[1];
            } else {
                $orderid = $orderid_session;
            }

            if($orderid == ''){
                $orderid = $orderid_post;
                error_log("Alpha Bank: something went wrong with order id " );
                error_log(print_r($_POST, true));
                error_log(print_r($matches, true));
                error_log($orderid_session);
            }
            
            $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
            $orderAmount = filter_var($_POST['orderAmount'], FILTER_SANITIZE_NUMBER_FLOAT);
            $currency = filter_var($_POST['currency'], FILTER_SANITIZE_STRING);
            $paymentTotal = filter_var($_POST['paymentTotal'],FILTER_SANITIZE_NUMBER_FLOAT );
            $message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_STRING) : '';
            $riskScore = isset($_POST['riskScore']) ? filter_var($_POST['riskScore'], FILTER_SANITIZE_NUMBER_FLOAT) : '';
            $payMethod = isset($_POST['payMethod']) ? filter_var($_POST['payMethod'], FILTER_SANITIZE_STRING) : '';
            $txId = isset($_POST['txId']) ? filter_var($_POST['txId'],FILTER_SANITIZE_NUMBER_FLOAT ) : '';
            $paymentRef = isset($_POST['paymentRef']) ? filter_var($_POST['paymentRef'], FILTER_SANITIZE_STRING) : '';
            $digest = filter_var($_POST['digest'], FILTER_SANITIZE_STRING);

            $form_data = '';            
            foreach($_POST as $k => $v) {
                if (!in_array($k, array('_charset_', 'digest', 'submitButton'))) {
                    $form_data .= filter_var($_POST[$k], FILTER_SANITIZE_STRING);
                }
            }

            $form_data .= $this->ab_sharedSecretKey;
            $computed_digest = $this->calculate_digest($form_data);

            $order = new WC_Order($orderid);

            if($digest != $computed_digest) {
                $message = __('A technical problem occured. <br />The transaction wasn\'t successful, payment wasn\'t received.', 'woo-alpha-bank-payment-gateway');
                $message_type = 'error';
                $ab_message = array('message' => $message, 'message_type' => $message_type);
                $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
                //Update the order status
                $order->update_status('failed', 'DIGEST');
                if(version_compare(WOOCOMMERCE_VERSION, '2.5', '<')) {
                    $checkout_url = $woocommerce->cart->get_checkout_url();
                } else {
                    $checkout_url = wc_get_checkout_url();
                }
                wp_redirect($checkout_url);
                exit;
            }

            if($status == 'CAPTURED' || $status == 'AUTHORIZED') {
                $order->payment_complete($paymentRef);        
                if ($order->get_status() == 'processing') {

                    $order->add_order_note(__('Payment Via Alpha Bank<br />Transaction ID: ', 'woo-alpha-bank-payment-gateway') . $paymentRef);
                    $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is currently being processed.', 'woo-alpha-bank-payment-gateway');

                    if($this->ab_order_note == 'yes') {
                        //Add customer order note
                        $order->add_order_note(__('Payment Received.<br />Your order is currently being processed.<br />We will be shipping your order to you soon.<br />Alpha Bank ID: ', 'woo-alpha-bank-payment-gateway') . $paymentRef, 1);

                    }
                } else if  ($order->get_status() == 'completed') {
                    $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is now complete.', 'woo-alpha-bank-payment-gateway');
                    if($this->ab_order_note == 'yes') {
                        //Add customer order note
                        $order->add_order_note(__('Payment Received.<br />Your order is now complete.<br />Alpha Bank Transaction ID: ', 'woo-alpha-bank-payment-gateway') . $paymentRef, 1);
                    }
                }      
                $message_type = 'success';

                $ab_message = array('message' => $message, 'message_type' => $message_type);

                $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
 
                // Empty cart
                WC()->cart->empty_cart();
    
            } else if($status == 'CANCELED') {
                // if(version_compare(WOOCOMMERCE_VERSION, '2.5', '<')) {
                //     $checkout_url = $woocommerce->cart->get_checkout_url();
                // } else {
                //     $checkout_url = wc_get_checkout_url();
                // }
                $message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment was cancelled.', 'woo-alpha-bank-payment-gateway');
                $message_type = 'notice';
				$order->update_status('failed', 'ERROR ' . $message);

            //    wc_add_notice($message, $message_type);
                //wp_redirect($checkout_url);
                //exit ;
            } else if ($status == 'REFUSED') {
                $client_message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-alpha-bank-payment-gateway');
                    $message_type = 'error';
                    $ab_message = array('message' => $client_message, 'message_type' => $message_type);
                    $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
                    //wc_add_notice( $message, $message_type );
                    $order->update_status('failed', 'REFUSED ' . $message );
            } else if($status == 'ERROR') {
                $client_message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-alpha-bank-payment-gateway');
                $message_type = 'error';
                $ab_message = array('message' => $client_message, 'message_type' => $message_type);
                $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
                //wc_add_notice( $message, $message_type );
                $order->update_status('failed', 'ERROR ' . $message );
            } else {
                $client_message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-alpha-bank-payment-gateway');
                $message_type = 'error';
                $ab_message = array('message' => $client_message, 'message_type' => $message_type);
                $this->generic_add_meta($orderid, '_alphabank_message', $ab_message);
                //wc_add_notice( $message, $message_type );
                $order->update_status('failed', 'Unknown: ' . $message);
            }

            
            if ($this->redirect_page_id == "-1") {
                $redirect_url = $this->get_return_url($order);
            } else {
                $redirect_url = ($this->redirect_page_id == "" || $this->redirect_page_id == 0) ? $this->get_return_url($order) : get_permalink($this->redirect_page_id);
            }
            wp_redirect($redirect_url);

            exit;
        }

        function generic_add_meta($orderid, $key, $value) {
            $order = new WC_Order($orderid);
            if (method_exists($order, 'add_meta_data') && method_exists($order, 'save_meta_data')) {
                $order->add_meta_data($key, $value, true);
                $order->save_meta_data();
            } else {
                update_post_meta($orderid, $key, $value);
            }
        }
    } // end of masterpass

    function alphabank_message() {
        $order_id = absint(get_query_var('order-received'));
        $order = new WC_Order($order_id);
        if (method_exists($order, 'get_payment_method')) {
            $payment_method = $order->get_payment_method();
        } else {
            $payment_method = $order->payment_method;
        }
        if (is_order_received_page() && ('alphabank_gateway' == $payment_method)) {
            if (method_exists($order, 'get_meta')) {
                $alphabank_message = $order->get_meta('_alphabank_message', true);
            } else {
                $alphabank_message = get_post_meta($order_id, '_alphabank_message');
            }

            if (!empty($alphabank_message)) {
                $message = $alphabank_message['message'];
                $message_type = $alphabank_message['message_type'];
                if (method_exists($order, 'delete_meta_data')) {
                    $order->delete_meta_data('_alphabank_message');
                    $order->save_meta_data();
                } else {
                    delete_post_meta($order_id, '_alphabank_message');
                }
                wc_add_notice($message, $message_type);
            }
        }
    }

    add_action('wp', 'alphabank_message');

    function woocommerce_add_alphabank_gateway($methods)
    {
        $methods[] = 'WC_alphabank_Gateway';
        $methods[] = 'WC_alphabank_Gateway_masterpass';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_alphabank_gateway');

    /**
     * Add Settings link to the plugin entry in the plugins menu for WC below 2.1
     * */
    if (version_compare(WOOCOMMERCE_VERSION, "2.1") <= 0) {

        add_filter('plugin_action_links', 'alphabank_plugin_action_links', 10, 2);

        function alphabank_plugin_action_links($links, $file) {
            static $this_plugin;

            if (!$this_plugin) {
                $this_plugin = plugin_basename(__FILE__);
            }

            if ($file == $this_plugin) {
                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=woocommerce_settings&tab=payment_gateways&section=WC_alphabank_Gateway">Settings</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }

    }
    /**
     * Add Settings link to the plugin entry in the plugins menu for WC 2.1 and above
     * */ else {
        add_filter('plugin_action_links', 'alphabank_plugin_action_links', 10, 2);

        function alphabank_plugin_action_links($links, $file) {
            static $this_plugin;

            if (!$this_plugin) {
                $this_plugin = plugin_basename(__FILE__);
            }

            if ($file == $this_plugin) {
                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=WC_alphabank_Gateway">Settings</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }
    }
}
