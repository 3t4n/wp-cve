<?php

/*
  Plugin Name: Piraeus Bank WooCommerce Payment Gateway
  Plugin URI: https://www.papaki.com
  Description: Piraeus Bank Payment Gateway allows you to accept payment through various channels such as Maestro, Mastercard, AMex cards, Diners  and Visa cards On your Woocommerce Powered Site.
  Version: 1.7.1
  Author: Papaki
  Author URI: https://www.papaki.com
  License: GPL-3.0+
  License URI: http://www.gnu.org/licenses/gpl-3.0.txt
  Tested up to: 6.4.3
  WC tested up to: 6.2.1
  Text Domain: woo-payment-gateway-for-piraeus-bank
  Domain Path: /languages
*/
 /*
 Based on original plugin "Piraeus Bank Greece Payment Gateway for WooCommerce" by emspace.gr [https://wordpress.org/plugins/woo-payment-gateway-piraeus-bank-greece/]
 */

if (!defined('ABSPATH'))
    exit;

add_action('plugins_loaded', 'woocommerce_piraeusbank_init', 0);
add_filter( 'woocommerce_states', 'piraeus_woocommerce_states' );


function piraeus_woocommerce_states( $states ) {

    $states['CY'] = array(
      '04' => __('Ammochostos', 'woo-payment-gateway-for-piraeus-bank'),
      '06' => __('Keryneia', 'woo-payment-gateway-for-piraeus-bank'),
      '03' => __('Larnaka', 'woo-payment-gateway-for-piraeus-bank'),
      '01' => __('Lefkosia', 'woo-payment-gateway-for-piraeus-bank'),
      '02' => __('Lemesos', 'woo-payment-gateway-for-piraeus-bank'),
      '05' => __('Pafos', 'woo-payment-gateway-for-piraeus-bank'),
    );
    $states['DE'] = array(      
        'BW' => __('Baden-Württemberg', 'woo-payment-gateway-for-piraeus-bank'),
        'BY' => __('Bayern', 'woo-payment-gateway-for-piraeus-bank'),
        'BE' => __('Berlin', 'woo-payment-gateway-for-piraeus-bank'),
        'BB' => __('Brandenburg', 'woo-payment-gateway-for-piraeus-bank'),
        'HB' => __('Bremen', 'woo-payment-gateway-for-piraeus-bank'),
        'HH' => __('Hamburg', 'woo-payment-gateway-for-piraeus-bank'),
        'HE' => __('Hessen', 'woo-payment-gateway-for-piraeus-bank'),
        'MV' => __('Mecklenburg-Vorpommern', 'woo-payment-gateway-for-piraeus-bank'),
        'NI' => __('Niedersachsen', 'woo-payment-gateway-for-piraeus-bank'),
        'NW' => __('Nordrhein-Westfalen', 'woo-payment-gateway-for-piraeus-bank'),
        'RP' => __('Rheinland-Pfalz', 'woo-payment-gateway-for-piraeus-bank'),
        'SL' => __('Saarland', 'woo-payment-gateway-for-piraeus-bank'),
        'SN' => __('Sachsen', 'woo-payment-gateway-for-piraeus-bank'),
        'ST' => __('Sachsen-Anhalt', 'woo-payment-gateway-for-piraeus-bank'),
        'SH' => __('Schleswig-Holstein', 'woo-payment-gateway-for-piraeus-bank'),
        'TH' => __('Thüringen', 'woo-payment-gateway-for-piraeus-bank'),
    );
    // __('Piraeus Bank Gateway', 'woo-payment-gateway-for-piraeus-bank')
    return $states;
  }

function woocommerce_piraeusbank_init() {

    if (!class_exists('WC_Payment_Gateway'))
        return;

    load_plugin_textdomain('woo-payment-gateway-for-piraeus-bank', false, dirname(plugin_basename(__FILE__)) . '/languages/');




    /**
     * Gateway class
     */
    class WC_Piraeusbank_Gateway extends WC_Payment_Gateway {

        public function __construct() {
            global $woocommerce;

            $this->id = 'piraeusbank_gateway';
            //$this->icon = apply_filters('piraeusbank_icon', plugins_url('img/PB_blue_GR.png', __FILE__));
            $this->has_fields = true;
            $this->notify_url = WC()->api_request_url('WC_Piraeusbank_Gateway');
            $this->method_description = __('Piraeus bank Payment Gateway allows you to accept payment through various channels such as Maestro, Mastercard, AMex cards, Diners  and Visa cards On your Woocommerce Powered Site.', 'woo-payment-gateway-for-piraeus-bank');
            $this->redirect_page_id = $this->get_option('redirect_page_id');
            $this->method_title = 'Piraeus bank  Gateway';

            // Load the form fields.
            $this->init_form_fields();



            global $wpdb;

            if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "piraeusbank_transactions'") === $wpdb->prefix . 'piraeusbank_transactions') {
                // The database table exist
            } else {
                // Table does not exist
                $query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'piraeusbank_transactions (id int(11) unsigned NOT NULL AUTO_INCREMENT, merch_ref varchar(50) not null, trans_ticket varchar(32) not null , timestamp datetime default null, PRIMARY KEY (id))';
                $wpdb->query($query);
            }


            // Load the settings.
            $this->init_settings();


            // Define user set variables
            $this->title = sanitize_text_field($this->get_option('title'));
            $this->description = sanitize_text_field($this->get_option('description'));
            $this->pb_PayMerchantId = absint($this->get_option('pb_PayMerchantId'));
            $this->pb_AcquirerId = absint($this->get_option('pb_AcquirerId'));
            $this->pb_PosId = absint($this->get_option('pb_PosId'));
            $this->pb_Username = sanitize_text_field($this->get_option('pb_Username'));
            $this->pb_Password = sanitize_text_field($this->get_option('pb_Password'));
            $this->pb_ProxyHost = $this->get_option('pb_ProxyHost');
            $this->pb_ProxyPort = $this->get_option('pb_ProxyPort');
            $this->pb_ProxyUsername = $this->get_option('pb_ProxyUsername');
            $this->pb_ProxyPassword = $this->get_option('pb_ProxyPassword');
            $this->pb_authorize = sanitize_text_field($this->get_option('pb_authorize'));
            $this->pb_installments = absint($this->get_option('pb_installments'));
            $this->pb_installments_variation = sanitize_text_field($this->get_option('pb_installments_variation'));
            $this->pb_render_logo = sanitize_text_field($this->get_option('pb_render_logo'));
            $this->pb_cardholder_name = sanitize_text_field($this->get_option('pb_cardholder_name'));
            $this->pb_enable_log = sanitize_text_field($this->get_option('pb_enable_log'));
            $this->pb_order_note = sanitize_text_field($this->get_option('pb_order_note'));


            //Actions
            add_action('woocommerce_receipt_piraeusbank_gateway', array($this, 'receipt_page'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            // Payment listener/API hook
            add_action('woocommerce_api_wc_piraeusbank_gateway', array($this, 'check_piraeusbank_response'));

            if(class_exists("SOAPClient") != true) {
                add_action( 'admin_notices', array($this, 'soap_error_notice'));
            }

            if ($this->pb_authorize == "yes") {
                add_action('admin_notices', array($this, 'authorize_warning_notice'));
            }
            if($this->pb_render_logo == "yes") {
                $this->icon = apply_filters('piraeusbank_icon', plugins_url('img/piraeusbank.svg', __FILE__));
            }

            $this->cardholderNameFunctionality();
        }

        public function cardholderNameFunctionality () {
            if($this->pb_cardholder_name == 'yes') {
                add_filter( 'woocommerce_billing_fields' , [$this, 'custom_override_checkout_fields']);
                add_filter( 'woocommerce_customer_meta_fields' , [$this, 'add_woocommerce_customer_meta_fields']);
                add_action( 'woocommerce_checkout_update_order_meta', [$this, 'my_custom_checkout_field_update_order_meta'] );

                wc_enqueue_js('
                    jQuery(function(){
                        jQuery( \'body\' )
                        .on( \'updated_checkout\', function() {
                            usingGateway();
                    
                            jQuery(\'input[name="payment_method"]\').change(function(){
                                usingGateway();
                            });
                        });
                    });

                    function usingGateway(){
                        if(jQuery(\'form[name="checkout"] input[name="payment_method"]:checked\').val() == \'piraeusbank_gateway\'){
                            jQuery("#cardholder_name_field").show();
                            document.getElementById("cardholder_name").scrollIntoView({behavior: "smooth", block: "center", inline: "nearest"});
                        }else{
                            jQuery("#cardholder_name_field").hide();
                        }
                    }  
                ');
            }
        }

        function custom_override_checkout_fields( $billing_fields ) {

            $billing_fields['cardholder_name'] = array(
                'type'     => 'text',
               'label'     => __('Cardholder Name', 'woo-payment-gateway-for-piraeus-bank'),
                'placeholder'   => __('Insert card holder name as required by Piraeus bank for validation', 'woo-payment-gateway-for-piraeus-bank'),
                'required'  => false,
                'class'     => array('form-row-wide'),
                'clear'     => true
            );
       
            return $billing_fields;
        }


        function my_custom_checkout_field_update_order_meta( $order_id ) {
            if ( ! empty( $_POST['cardholder_name'] ) ) {
                update_post_meta( $order_id, 'cardholder_name', sanitize_text_field( $_POST['cardholder_name'] ) );
            }
        }

        public function curl_get_contents($url) {
            // Initiate the curl session
            $ch = curl_init();
                // Set the URL
            curl_setopt($ch, CURLOPT_URL, $url);
                // Removes the headers from the output
            curl_setopt($ch, CURLOPT_HEADER, 0);
                // Return the output instead of displaying it directly
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // Execute the curl session
            $output = curl_exec($ch);
                // Close the curl session
            curl_close($ch);
                // Return the output as a variable
            return $output;
            }
        /*
         * Admin Panel Options
         * */
        public function admin_options() {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.ipify.org');
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);

            curl_close($ch);

            echo '<h3>' . __('Piraeus Bank Gateway', 'woo-payment-gateway-for-piraeus-bank') . '</h3>';
            echo '<p>' . __('Piraeus Bank Gateway allows you to accept payment through various channels such as Maestro, Mastercard, AMex cards, Diners  and Visa cards.', 'woo-payment-gateway-for-piraeus-bank') . '</p>';
            $base_url =  $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] :  $_SERVER['SERVER_NAME'];
            // $host = (is_ssl() == true ? 'https://' : 'http://') . $base_url . '/'; 
            $host = get_bloginfo('url') . '/';

            
            echo '<div style="border: 1px dashed #000; display: inline-block; padding: 10px;">';
            echo '<h4>' . __('Technical data to be submitted to Piraeus Bank', 'woo-payment-gateway-for-piraeus-bank') . '</h4>';
                echo '<p>' . __('The data to be submitted to Piraeus Bank(<a href="mailto:epayments@piraeusbank.gr">epayments@piraeusbank.gr</a>) in order to provide the necessary technical info (test/live account) for transactions are as follows', 'woo-payment-gateway-for-piraeus-bank') . ':</p>';
                echo '<ul>';
            echo '<li><strong>Website URL:</strong> ' . $host . '</li>';
            echo '<li><strong>Referrer url:</strong> '. $host . (get_option('permalink_structure') ? 'checkout/'  : 'checkout/' ) .' </li>';
            echo '<li><strong>Success page: </strong>'. $host . (get_option('permalink_structure') ? 'wc-api/WC_Piraeusbank_Gateway?peiraeus=success'  : '?wc-api=WC_Piraeusbank_Gateway&peiraeus=success' ) .' </li>';
            echo '<li><strong>Failure page:</strong> '. $host . (get_option('permalink_structure') ? 'wc-api/WC_Piraeusbank_Gateway?peiraeus=fail'  : '?wc-api=WC_Piraeusbank_Gateway&peiraeus=fail' ) .' </li>';
            echo '<li><strong>Backlink page:</strong> '. $host . (get_option('permalink_structure') ? 'wc-api/WC_Piraeusbank_Gateway?peiraeus=cancel'  : '?wc-api=WC_Piraeusbank_Gateway&peiraeus=cancel' ) .' </li>';
            echo '<li><strong>Response method :</strong> GET / POST  (Preferred one: POST)</li>';
            $ip = !empty($output) ? $output : gethostbyname($base_url);
            echo '<li><strong>Server Ip:</strong> ' . $ip . '</li>';
            echo '</ul>';
            echo '<p style="font-style:italic;">* Σημείωση: Τα urls Success, Failure, Backlink δημιουργούνται αυτόματα απο το plugin μας, ΔΕΝ χρείαζεται να δημιουργήσετε εσείς κάποια  επισπρόσθετη σελίδα</p>';
            echo '</div>';
            echo '<table class="form-table">';
            $this->generate_settings_html();
            echo '</table>';
           
            
        }
        function soap_error_notice() {
                echo '<div class="error notice">';
                echo '<p>'. __( '<strong>SOAP have to be enabled in your Server/Hosting</strong>, it is required for this plugin to work properly!', 'woo-payment-gateway-for-piraeus-bank') . '</p>';
                echo '</div>';
        }
        function authorize_warning_notice() {
                echo '<div class="notice-warning notice">';
                echo '<p>'. __( '<strong>Important Notice:</strong> Piraeus Bank has announced that it will gradually abolish the Preauthorized Payment Service for all merchants, beginning from the ones obtained MIDs from 29/1/2019 onwards.<br /> You are highly recommended to disable the preAuthorized Payment Service as soon as possible.', 'woo-payment-gateway-for-piraeus-bank') . '</p>';
                echo '</div>';
        }
        /**
         * Initialise Gateway Settings Form Fields
         * */
        function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'checkbox',
                    'label' => __('Enable Piraeus Bank Gateway', 'woo-payment-gateway-for-piraeus-bank'),
                    'description' => __('Enable or disable the gateway.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => true,
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => __('Piraeus Bank Gateway', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'description' => array(
                    'title' => __('Description', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => __('Pay Via Piraeus Bank: Accepts  Mastercard, Visa cards and etc.', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'pb_render_logo' => array(
                    'title' => __('Display the logo of Piraeus Bank', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'checkbox',
                    'description' => __('Enable to display the logo of Piraeus Bank next to the title which the user sees during checkout.', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => 'yes'
                ),
                'pb_PayMerchantId' => array(
                    'title' => __('Piraeus Bank Merchant ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Enter Your Piraeus Bank Merchant ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'pb_AcquirerId' => array(
                    'title' => __('Piraeus Bank Acquirer ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Enter Your Piraeus Bank Acquirer ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'pb_PosId' => array(
                    'title' => __('Piraeus Bank POS ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Enter your Piraeus Bank POS ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ), 'pb_Username' => array(
                    'title' => __('Piraeus Bank Username', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Enter your Piraeus Bank Username', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ), 'pb_Password' => array(
                    'title' => __('Piraeus Bank Password', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'password',
                    'description' => __('Enter your Piraeus Bank Password', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'pb_ProxyHost' => array(
                    'title' => __('HTTP Proxy Hostname', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Used when your server is not behind a static IP. Leave blank for normal HTTP connection.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => ''
                ),
                'pb_ProxyPort' => array(
                    'title' => __('HTTP Proxy Port', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Used with Proxy Host.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => ''
                ),
                'pb_ProxyUsername' => array(
                    'title' => __('HTTP Proxy Login Username', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Used with Proxy Host. Leave blank for anonymous connection.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => ''
                ),
                'pb_ProxyPassword' => array(
                    'title' => __('HTTP Proxy Login Password', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'password',
                    'description' => __(' Used with Proxy Host. Leave blank for anonymous connection.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => ''
                ),
                'pb_authorize' => array(
                    'title' => __('Pre-Authorize', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'checkbox',
                    'label' => __('Enable to capture preauthorized payments', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => 'no',
                    'description' => __('<strong>Important Notice:</strong> Piraeus Bank has announced that it will gradually abolish the Preauthorized Payment Service for all merchants, beginning from the ones obtained MIDs from 29/1/2019 onwards.<br /> Default payment method is Purchase, enable for Pre-Authorized payments. You will then need to accept them from Piraeus Bank AdminTool', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'redirect_page_id' => array(
                    'title' => __('Return page URL <br />(Successful or Failed Transactions)', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'select',
                    'options' => $this->pb_get_pages('Select Page'),
                    'description' => __('We recommend you to select the default “Thank You Page”, in order to automatically serve both successful and failed transactions, with the latter also offering the option to try the payment again.<br /> If you select a different page, you will have to handle failed payments yourself by adding custom code.', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => -1
                ),
                'pb_installments' => array(
                    'title' => __('Maximum number of installments regardless of the total order amount', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'select',
                    'options' => $this->pb_get_installments('Select Installments'),
                    'description' => __('1 to 24 Installments,1 for one time payment. You must contact Piraeus Bank first<br /> If you have filled the "Max Number of installments depending on the total order amount", the value of this field will be ignored.', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'pb_installments_variation' => array(
                    'title' => __('Maximum number of installments depending on the total order amount', 'woo-payment-gateway-for-piraeus-bank'),
                    'type'  => 'text',
                    'description' => __('Example 80:2, 160:4, 300:8</br> total order greater or equal to 80 -> allow 2 installments, total order greater or equal to 160 -> allow 4 installments, total order greater or equal to 300 -> allow 8 installments</br> Leave the field blank if you do not want to limit the number of installments depending on the amount of the order.', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'pb_cardholder_name' => array(
                    'title' => __('Enable Cardholder Name Field', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'checkbox',
                    'label' => __('Enabling this field allows customers to insert a cardholder name', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => 'yes',
                    'description' => __('According to Piraeus bank’s technical requirements related to 3D secure and SCA, the cardholder’s name must be sent before the customer is redirected to the bank’s payment environment. If you choose not to show this field, we will automatically send the full name inserted for the order, with the risk of having the bank refusing the transaction due to the validity of this field.', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'pb_enable_log' => array(
                    'title' => __('Enable Debug mode', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'checkbox',
                    'label' => __('Enabling this will log certain information', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => 'no',
                    'description' => __('Enabling this (and the debug mode from your wp-config file) will log information, e.g. bank responses, which will help in debugging issues.', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'pb_order_note' => array(
                    'title' => __('Enable 2nd “payment received” email', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'checkbox',
                    'label' => __('Enable sending Customer order note with transaction details', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => 'no',
                    'description' => __('Enabling this will send an email with the support reference id and transaction id to the customer, after the transaction has been completed (either on success or failure)', 'woo-payment-gateway-for-piraeus-bank')
                )
                
            );
        }

        function pb_get_pages($title = false, $indent = true) {
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
            $page_list[-1] = __('Thank you page', 'woo-payment-gateway-for-piraeus-bank');
            return $page_list;
        }

        function pb_get_installments($title = false, $indent = true) {


            for ($i = 1; $i <= 24; $i++) {
                $installment_list[$i] = $i;
            }
            return $installment_list;
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
            $max_installments = $this->pb_installments;
            $installments_variation = $this->pb_installments_variation; 

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
			<label for="' . esc_attr($this->id) . '-card-doseis">' . __('Choose Installments', 'woo-payment-gateway-for-piraeus-bank') . ' <span class="required">*</span></label>
                        <select id="' . esc_attr($this->id) . '-card-doseis" name="' . esc_attr($this->id) . '-card-doseis" class="input-select wc-credit-card-form-card-doseis">
                        ';
                for ($i = 1; $i <= $max_installments; $i++) {
                    $doseis_field .= '<option value="' . $i . '">' .  ($i == 1 ? __('Without installments','woo-payment-gateway-for-piraeus-bank') : $i) . '</option>';
                }
                $doseis_field .= '</select>
		</p>';
                echo $doseis_field;
            }
        }

        /**
         * Generate the  Piraeus Payment button link
         * */
        function generate_piraeusbank_form($order_id) {
            global $woocommerce;
            global $wpdb;

            // $currency = get_woocommerce_currency();
            $locale = get_locale();
            $availableLocales = array (
                'en' => 'en-US',
                'en_US' => 'en-US',
                'en_AU' => 'en-US',
                'en_CA' => 'en-US',
                'en_GB' => 'en-US',
                'en_NZ' => 'en-US',
                'en_ZA' => 'en-US',
                'el' => 'el-GR',
                'ru_RU' => 'ru-RU',
                'de_DE' => 'de-DE',
                'de_DE_formal' => 'de-DE',
                'de_CH' => 'de-DE',
                'de_CH_informal' => 'de-DE'
            );

            if(isset($availableLocales[$locale])) {
               $lang = $availableLocales[$locale];
            } else {
                $lang = 'en-US';
            }

            $order = new WC_Order($order_id);

            if ($this->pb_authorize == "yes") {
                $requestType = '00';
                $ExpirePreauth = '30';
            } else {
                $requestType = '02';
                $ExpirePreauth = '0';
            }
            $installments = 1;
            /* if ($this->pb_installments > 1) {
              $installments = intval($order->get_total() / 30);
              $installments = min($installments, $this->pb_installments);
              } */
            if (method_exists($order, 'get_meta')) {
                $installments = $order->get_meta('_doseis');
                if ($installments == '') {
                    $installments = 1;
                }
            } else {
                $installments = get_post_meta($order_id, '_doseis', 1);
            }
            try {

                if( $this->pb_ProxyHost!=''){

                    if($this->pb_ProxyUsername != '' && $this->pb_ProxyPassword != ''){
                        $soap = new SoapClient("https://paycenter.piraeusbank.gr/services/tickets/issuer.asmx?WSDL",
                        array(
                            'proxy_host'     => $this->pb_ProxyHost,
                            'proxy_port'     => intval($this->pb_ProxyPort),
                            'proxy_login'    => $this->pb_ProxyUsername,
                            'proxy_password' => $this->pb_ProxyPassword
                            )
                        );
                    }
                    else{
                        $soap = new SoapClient("https://paycenter.piraeusbank.gr/services/tickets/issuer.asmx?WSDL",
                        array(
                            'proxy_host'     => $this->pb_ProxyHost,
                            'proxy_port'     => intval($this->pb_ProxyPort)
                            )
                        );
                    }
                }
                else{
                    $soap = new SoapClient("https://paycenter.piraeusbank.gr/services/tickets/issuer.asmx?WSDL");
                }

                //initialize new 3DS information 
                $BillAddrCity = mb_substr($order->get_billing_city(), 0, 50); // TODO: add regexp for greek latin and special chars
                $BillAddrCountry = getCountryNumericCode($order->get_billing_country()); // TODO: add regexp for greek latin and special chars
                $BillAddrLine1 = mb_substr($order->get_billing_address_1(), 0 ,50);
                $BillAddrPostCode = $order->get_billing_postcode();
                $BillAddrState = $order->get_billing_state();
                $BillAddrStateCode = validateStateCode($BillAddrState, $order->get_billing_country());


                $ShipAddrCity = mb_substr(!empty($order->get_shipping_city()) ? $order->get_shipping_city() : $order->get_billing_city() , 0, 50);
                $ShipAddrCountry = !empty($order->get_shipping_country())? getCountryNumericCode($order->get_shipping_country()) : $BillAddrCountry; 
                $ShipAddrLine1 = mb_substr(!empty($order->get_shipping_address_1()) ? $order->get_shipping_address_1() : $order->get_billing_address_1(), 0 ,50);
                $ShipAddrPostCode = !empty($order->get_shipping_postcode()) ? $order->get_shipping_postcode(): $BillAddrPostCode;
                $ShipAddrState = !empty($order->get_shipping_state()) ? $order->get_shipping_state() : $BillAddrState;
                $ShipAddrStateCode = validateStateCode($ShipAddrState, !empty($order->get_shipping_country())? $order->get_shipping_country() : $order->get_billing_country());
                $Email = $order->get_billing_email();


                $HomePhone = validatePhoneNumberAllCountries($order->get_billing_phone(), $order->get_billing_country());
                $MobilePhone = validatePhoneNumberAllCountries($order->get_billing_phone(), $order->get_billing_country());
                $WorkPhone = validatePhoneNumberAllCountries($order->get_billing_phone(), $order->get_billing_country());

                $name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
                $CardholderName =  getCardholderName( $order->get_id(), $name, $this->pb_cardholder_name);

                $ticketRequest = array(
                    'Username' => $this->pb_Username,
                    'Password' => hash('md5', $this->pb_Password),
                    'MerchantId' => $this->pb_PayMerchantId,
                    'PosId' => $this->pb_PosId,
                    'AcquirerId' => $this->pb_AcquirerId,
                    'MerchantReference' => $order_id,
                    'RequestType' => $requestType,
                    'ExpirePreauth' => $ExpirePreauth,
                    'Amount' => $order->get_total(),
                    'CurrencyCode' => '978',
                    'Installments' => $installments,
                    'Bnpl' => '0',
                    'Parameters' => '',
                    'BillAddrCity' => $BillAddrCity,
                    'BillAddrCountry' => $BillAddrCountry,
                    'BillAddrLine1' => $BillAddrLine1,
                    'BillAddrPostCode' => $BillAddrPostCode,
                    'BillAddrState' => $BillAddrStateCode,
                    'ShipAddrCity' => $ShipAddrCity,
                    'ShipAddrCountry' => $ShipAddrCountry,
                    'ShipAddrLine1' => $ShipAddrLine1,
                    'ShipAddrPostCode' => $ShipAddrPostCode,
                    'ShipAddrState' => $ShipAddrStateCode,
                    'CardholderName' => $CardholderName,
                    'Email' => $Email,
                    'HomePhone' => $HomePhone,
                    'MobilePhone' => $MobilePhone,
                    'WorkPhone' => $WorkPhone
                );

                $xml = array(
                    'Request' => $ticketRequest
                );
                
                $oResult = $soap->IssueNewTicket($xml);

                if($this->pb_enable_log == 'yes') {
                    error_log( '---- Piraeus Transaction Ticket -----');
                    error_log( print_r( $ticketRequest, true ) );
                    error_log( '---- End ofPiraeus Transaction Ticket ----');
                }

                if ($oResult->IssueNewTicketResult->ResultCode == 0) {

                    //  store TranTicket in table
                    // $wpdb->delete($wpdb->prefix . 'piraeusbank_transactions', array('merch_ref' => $order_id));
                    $wpdb->insert($wpdb->prefix . 'piraeusbank_transactions', array('trans_ticket' => $oResult->IssueNewTicketResult->TranTicket, 'merch_ref' => $order_id, 'timestamp' => current_time('mysql', 1)));

                    //redirect to payment

                    wc_enqueue_js('
				$.blockUI({
						message: "' . esc_js(__('Thank you for your order. We are now redirecting you to Piraeus Bank to make payment.', 'woo-payment-gateway-for-piraeus-bank')) . '",
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
				jQuery("#submit_pb_payment_form").click();
			');

                  //  $LanCode = "el-GR";
                    $LanCode = $lang;
                    /*
                      Other available Language codes
                      en-US: English
                      ru-RU: Russian
                      de-DE: German
                     */

                    return '<form action="' . esc_url("https://paycenter.piraeusbank.gr/redirection/pay.aspx") . '" method="post" id="pb_payment_form" target="_top">

						<input type="hidden" id="AcquirerId" name="AcquirerId" value="' . esc_attr($this->pb_AcquirerId) . '"/>
						<input type="hidden" id="MerchantId" name="MerchantId" value="' . esc_attr($this->pb_PayMerchantId) . '"/>
						<input type="hidden" id="PosID" name="PosID" value="' . esc_attr($this->pb_PosId) . '"/>
						<input type="hidden" id="User" name="User" value="' . esc_attr($this->pb_Username) . '"/>
						<input type="hidden" id="LanguageCode"  name="LanguageCode" value="' . $LanCode . '"/>
						<input type="hidden" id="MerchantReference" name="MerchantReference"  value="' . esc_attr($order_id) . '"/>
					<!-- Button Fallback -->
					<div class="payment_buttons">
						<input type="submit" class="button alt" id="submit_pb_payment_form" value="' . __('Pay via Pireaus Bank', 'woo-payment-gateway-for-piraeus-bank') . '" /> <a class="button cancel" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'woo-payment-gateway-for-piraeus-bank') . '</a>

					</div>
					<script type="text/javascript">
					jQuery(".payment_buttons").hide();
					</script>
				</form>';
                } else {
                    echo __('An error occured, please contact the Administrator. ', 'woo-payment-gateway-for-piraeus-bank');
                    echo ('Result code is ' . filter_var($oResult->IssueNewTicketResult->ResultCode, FILTER_SANITIZE_STRING));
                    echo ('. : ' . filter_var($oResult->IssueNewTicketResult->ResultDescription, FILTER_SANITIZE_STRING));
                    $order->add_order_note(__('Error' . filter_var($oResult->IssueNewTicketResult->ResultCode, FILTER_SANITIZE_STRING) . ':' . filter_var($oResult->IssueNewTicketResult->ResultDescription, FILTER_SANITIZE_STRING), ''));
                }
            } catch (SoapFault $fault) {
                $order->add_order_note(__('Error' . sanitize_text_field($fault), ''));
                echo __('Error' . $fault, '');
            }
        }

        /**
         * Process the payment and return the result
         * */
        function process_payment($order_id) {
            /*
              get_permalink was used instead of $order->get_checkout_payment_url in redirect in order to have a fixed checkout page to provide to Piraeus Bank
             */

            $order = new WC_Order($order_id);

            $doseis = isset($_POST[esc_attr($this->id) . '-card-doseis']) ?  intval($_POST[esc_attr($this->id) . '-card-doseis']) : 1;
            // intval($_POST[esc_attr($this->id) . '-card-doseis']);
            if ($doseis > 0) {
                $this->generic_add_meta($order_id, '_doseis', $doseis);
            }

            return array(
                'result' => 'success',
                'redirect' => add_query_arg('order-pay', $order->get_id(), add_query_arg('key', $order->get_order_key(), wc_get_page_permalink('checkout')))
                //'redirect' => add_query_arg('order', $order->get_id(), add_query_arg('key', $order->get_order_key(), get_permalink(wc_get_page_id('checkout')).'order-pay/'.$order->get_id()))
            );
        }

        /**
         * Output for the order received page.
         * */
        function receipt_page($order) {
            echo '<p>' . __('Thank you - your order is now pending payment. You should be automatically redirected to Piraeus Paycenter to make payment.', 'woo-payment-gateway-for-piraeus-bank') . '</p>';
            echo $this->generate_piraeusbank_form($order);
        }

        /**
         * Verify a successful Payment!
         * */
        function check_piraeusbank_response() {


            global $woocommerce;
            global $wpdb;

            if($this->pb_enable_log == 'yes') {
                error_log( '---- Piraeus Response -----');
                error_log( print_r( $_REQUEST, true ) );
                error_log( '---- End of Piraeus Response ----');
            }

            if (isset($_REQUEST['peiraeus']) && ($_REQUEST['peiraeus'] == 'success')) {

                $ResultCode = filter_var($_REQUEST['ResultCode'], FILTER_SANITIZE_STRING);
                $order_id = filter_var($_REQUEST['MerchantReference'], FILTER_SANITIZE_STRING);
                $order = new WC_Order($order_id);

                if ($ResultCode != 0) {
                    $message = __('A technical problem occured. <br />The transaction wasn\'t successful, payment wasn\'t received.', 'woo-payment-gateway-for-piraeus-bank');
                    $message_type = 'error';
                    $pb_message = array(
                        'message' => $message,
                        'message_type' => $message_type
                    );
                    $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                    $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);
                    wc_add_notice(__('Payment error:', 'woo-payment-gateway-for-piraeus-bank') . $message, $message_type);
                    //Update the order status
                    $order->update_status('failed', '');
                    $checkout_url = $woocommerce->cart->get_checkout_url();
                    wp_redirect($checkout_url);
                    exit;
                }

                $ResponseCode = filter_var($_REQUEST['ResponseCode'], FILTER_SANITIZE_STRING);
                $StatusFlag = filter_var($_REQUEST['StatusFlag'], FILTER_SANITIZE_STRING);
                $HashKey = filter_var($_REQUEST['HashKey'], FILTER_SANITIZE_STRING);
                $SupportReferenceID = absint($_REQUEST['SupportReferenceID']);
                $ApprovalCode = filter_var($_REQUEST['ApprovalCode'], FILTER_SANITIZE_STRING);
                $Parameters = filter_var($_REQUEST['Parameters'], FILTER_SANITIZE_STRING);
                $AuthStatus = filter_var($_REQUEST['AuthStatus'], FILTER_SANITIZE_STRING);
                $PackageNo = absint($_REQUEST['PackageNo']);
                $TransactionId = isset($_REQUEST['TransactionId']) ? absint($_REQUEST['TransactionId']) : ''; 


                $ttquery = $wpdb->prepare(
                    'select trans_ticket from ' . $wpdb->prefix . 'piraeusbank_transactions' . ' where merch_ref = %s',
                    [                        
                        $order_id
                    ]
                );
                $tt = $wpdb->get_results($ttquery);
                if($this->pb_enable_log == 'yes') {
                    error_log( '---- ttquery -----');
                    error_log( print_r( array($ttquery,$tt), true ) );
                    error_log( '---- End of ttquery ----');
                }

                $hasHashKeyNotMatched = true;

                foreach($tt as $transaction) {

                    if(!$hasHashKeyNotMatched)
                        break;

                    $transticket = $transaction->trans_ticket;

                    $stcon = $transticket . $this->pb_PosId . $this->pb_AcquirerId . $order_id . $ApprovalCode . $Parameters . $ResponseCode . $SupportReferenceID . $AuthStatus . $PackageNo . $StatusFlag;

                    $conhash = strtoupper(hash('sha256', $stcon));

                    // $newHashKey
                    $stconHmac = $transticket . ';' . $this->pb_PosId . ';' .  $this->pb_AcquirerId . ';' .  $order_id . ';' .  $ApprovalCode . ';' .  $Parameters . ';' .  $ResponseCode . ';' .  $SupportReferenceID . ';' .  $AuthStatus . ';' .  $PackageNo . ';' .  $StatusFlag;
                    $consHashHmac = strtoupper(hash_hmac('sha256', $stconHmac, $transticket, false));

                        if($consHashHmac != $HashKey && $conhash != $HashKey) {
                            continue;
                        } else {
                            $hasHashKeyNotMatched= false;
                        }
                }


                if($hasHashKeyNotMatched) {

                    $message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-payment-gateway-for-piraeus-bank');
                    $message_type = 'error';
                    $pb_message = array(
                        'message' => $message,
                        'message_type' => $message_type
                    );
                    $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                    $this->generic_add_meta($order_id, '_piraeusbank_message_debug', array($pb_message, $consHashHmac . '!=' . $HashKey));
                    //wc_add_notice(__('Payment error:', 'woo-payment-gateway-for-piraeus-bank') . $message, $message_type);
                    //Update the order status
                    $order->update_status('failed', '');
                    $checkout_url = $woocommerce->cart->get_checkout_url();
                    wp_redirect($checkout_url);
                    exit;
                 }
                else {

                    if ($ResponseCode == 0 || $ResponseCode == 8 || $ResponseCode == 10 || $ResponseCode == 16) {

                        $order->payment_complete(  $TransactionId );             

                        //Add admin order note
                        $order->add_order_note(__('Payment Via Peiraeus Bank<br />Transaction ID: ', 'woo-payment-gateway-for-piraeus-bank') . $TransactionId .  __('<br />Support Reference ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID);
                        
                        if($order->get_status() == 'processing') {
                            $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is currently being processed.', 'woo-payment-gateway-for-piraeus-bank');

                            if($this->pb_order_note == 'yes') {
                                $order->add_order_note(__('Payment Received.<br />Your order is currently being processed.<br />We will be shipping your order to you soon.<br />Peiraeus Bank ID: ', 'woo-payment-gateway-for-piraeus-bank') . $TransactionId .  __('<br />Support Reference ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID, 1);
                            }
                        } else if  ($order->get_status() == 'completed') {
                            $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is now complete.', 'woo-payment-gateway-for-piraeus-bank');

                            if($this->pb_order_note == 'yes') {
                                $order->add_order_note(__('Payment Received.<br />Your order is now complete.<br />Peiraeus Transaction ID: ', 'woo-payment-gateway-for-piraeus-bank') . $TransactionId .  __('<br />Support Reference ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID, 1);
                            }

                        }
                        $message_type = 'success';

                
                        $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                        $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);


                        // Empty cart
                        WC()->cart->empty_cart();
                    } else if ($ResponseCode == 11) {

                        $message = __('Thank you for shopping with us.<br />Your transaction was previously received.<br />', 'woo-payment-gateway-for-piraeus-bank');
                        $message_type = 'success';


                        $pb_message = array(
                            'message' => $message,
                            'message_type' => $message_type
                        );
                        $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                        $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);
                    } else { //Failed Response codes

                        $message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-payment-gateway-for-piraeus-bank');
                        $message_type = 'error';
                        $pb_message = array(
                            'message' => $message,
                            'message_type' => $message_type
                        );
                        $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                        $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);
                        //Update the order status
                        $order->update_status('failed', '');
                    }
                }
            }
            if (isset($_REQUEST['peiraeus']) && ($_REQUEST['peiraeus'] == 'fail')) {

                if (isset($_REQUEST['MerchantReference'])) {
                    $order_id = filter_var($_REQUEST['MerchantReference'], FILTER_SANITIZE_STRING);
                    $order = new WC_Order($order_id);
                    $message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-payment-gateway-for-piraeus-bank');
                    $message_type = 'error';

                    $transaction_id = absint($_REQUEST['SupportReferenceID']);
                    if($this->pb_order_note == 'yes') {
                        //Add Customer Order Note
                        $order->add_order_note($message . '<br />Piraeus Bank Support Reference ID: ' . $transaction_id, 1);
                    }

                    //Add Admin Order Note
                    $order->add_order_note($message . '<br />Piraeus Bank Support Reference ID: ' . $transaction_id);


                    //Update the order status
                    $order->update_status('failed', '');

                    $pb_message = array(
                        'message' => $message,
                        'message_type' => $message_type
                    );

                    $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                    $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);
                }
            }
            if (isset($_REQUEST['peiraeus']) && ($_REQUEST['peiraeus'] == 'cancel')) {


                $checkout_url = $woocommerce->cart->get_checkout_url();
                wp_redirect($checkout_url);
                exit;
            }
            if ($this->redirect_page_id == "-1") {
                $redirect_url = $this->get_return_url($order);
            } else {
                $redirect_url = ($this->redirect_page_id == "" || $this->redirect_page_id == 0) ? get_site_url() . "/" : get_permalink($this->redirect_page_id);
                //For wooCoomerce 2.0
                $redirect_url = add_query_arg(array('msg' => urlencode($pb_message['message']), 'type' => $pb_message['class']), $redirect_url);
            }
            wp_redirect($redirect_url);

            exit;
        }

        function generic_add_meta($orderid, $key, $value) {
            $order = new WC_Order(sanitize_text_field($orderid));
            if (method_exists($order, 'add_meta_data') && method_exists($order, 'save_meta_data')) {
                $order->add_meta_data(sanitize_key($key), sanitize_text_field($value), true);
                $order->save_meta_data();
            } else {
                update_post_meta($orderid, sanitize_key($key), sanitize_text_field($value));
            }
        }

    }

    function piraeusbank_message() {
        $order_id = absint(get_query_var('order-received'));
        $order = new WC_Order($order_id);
        if (method_exists($order, 'get_payment_method')) {
            $payment_method = $order->get_payment_method();
        } else {
            $payment_method = $order->payment_method;
        }

        if (is_order_received_page() && ( 'piraeusbank_gateway' == $payment_method )) {

            $piraeusbank_message = '';
            if (method_exists($order, 'get_meta')) {
                $piraeusbank_message = $order->get_meta('_piraeusbank_message', true);
            } else {
                $piraeusbank_message = get_post_meta($order_id, '_piraeusbank_message', true);
            }
            if (!empty($piraeusbank_message)) {
                $message = $piraeusbank_message['message'];
                $message_type = $piraeusbank_message['message_type'];
                if (method_exists($order, 'delete_meta_data')) {
                    $order->delete_meta_data('_piraeusbank_message');
                    $order->save_meta_data();
                } else {
                    delete_post_meta($order_id, '_piraeusbank_message');
                }

                wc_add_notice($message, $message_type);
            }
        }
    }

    add_action('wp', 'piraeusbank_message');

    /**
     * Add Piraeus Bank Gateway to WC
     * */
    function woocommerce_add_piraeusbank_gateway($methods) {
        $methods[] = 'WC_Piraeusbank_Gateway';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_piraeusbank_gateway');

    function getCardholderName ($orderId, $name, $enabled) {
            //check if has the field
            if($enabled == 'yes') {
                $cardholder_field = get_post_meta( $orderId, 'cardholder_name', true );
                if(!empty($cardholder_field)) {
                    return convertNonLatinToLatin($cardholder_field);
                }
            }
            return convertNonLatinToLatin($name);
    }

    function getCountryNumericCode( $country) {
        $countries = array(
            "AF" => "004",
            "AL" => "008",
            "DZ" => "012",
            "AS" => "016",
            "AD" => "020",
            "AO" => "024",
            "AI" => "660",
            "AQ" => "010",
            "AG" => "028",
            "AR" => "032",
            "AM" => "051",
            "AW" => "533",
            "AU" => "036",
            "AT" => "040",
            "AZ" => "031",
            "BS" => "044",
            "BH" => "048",
            "BD" => "050",
            "BB" => "052",
            "BY" => "112",
            "BE" => "056",
            "BZ" => "084",
            "BJ" => "204",
            "BM" => "060",
            "BT" => "064",
            "BO" => "068",
            "BQ" => "535",
            "BA" => "070",
            "BW" => "072",
            "BV" => "074",
            "BR" => "076",
            "IO" => "086",
            "BN" => "096",
            "BG" => "100",
            "BF" => "854",
            "BI" => "108",
            "CV" => "132",
            "KH" => "116",
            "CM" => "120",
            "CA" => "124",
            "KY" => "136",
            "CF" => "140",
            "TD" => "148",
            "CL" => "152",
            "CN" => "156",
            "CX" => "162",
            "CC" => "166",
            "CO" => "170",
            "KM" => "174",
            "CD" => "180",
            "CG" => "178",
            "CK" => "184",
            "CR" => "188",
            "HR" => "191",
            "CU" => "192",
            "CW" => "531",
            "CY" => "196",
            "CZ" => "203",
            "CI" => "384",
            "DK" => "208",
            "DJ" => "262",
            "DM" => "212",
            "DO" => "214",
            "EC" => "218",
            "EG" => "818",
            "SV" => "222",
            "GQ" => "226",
            "ER" => "232",
            "EE" => "233",
            "SZ" => "748",
            "ET" => "231",
            "FK" => "238",
            "FO" => "234",
            "FJ" => "242",
            "FI" => "246",
            "FR" => "250",
            "GF" => "254",
            "PF" => "258",
            "TF" => "260",
            "GA" => "266",
            "GM" => "270",
            "GE" => "268",
            "DE" => "276",
            "GH" => "288",
            "GI" => "292",
            "GR" => "300",
            "GL" => "304",
            "GD" => "308",
            "GP" => "312",
            "GU" => "316",
            "GT" => "320",
            "GG" => "831",
            "GN" => "324",
            "GW" => "624",
            "GY" => "328",
            "HT" => "332",
            "HM" => "334",
            "VA" => "336",
            "HN" => "340",
            "HK" => "344",
            "HU" => "348",
            "IS" => "352",
            "IN" => "356",
            "ID" => "360",
            "IR" => "364",
            "IQ" => "368",
            "IE" => "372",
            "IM" => "833",
            "IL" => "376",
            "IT" => "380",
            "JM" => "388",
            "JP" => "392",
            "JE" => "832",
            "JO" => "400",
            "KZ" => "398",
            "KE" => "404",
            "KI" => "296",
            "KP" => "408",
            "KR" => "410",
            "KW" => "414",
            "KG" => "417",
            "LA" => "418",
            "LV" => "428",
            "LB" => "422",
            "LS" => "426",
            "LR" => "430",
            "LY" => "434",
            "LI" => "438",
            "LT" => "440",
            "LU" => "442",
            "MO" => "446",
            "MG" => "450",
            "MW" => "454",
            "MY" => "458",
            "MV" => "462",
            "ML" => "466",
            "MT" => "470",
            "MH" => "584",
            "MQ" => "474",
            "MR" => "478",
            "MU" => "480",
            "YT" => "175",
            "MX" => "484",
            "FM" => "583",
            "MD" => "498",
            "MC" => "492",
            "MN" => "496",
            "ME" => "499",
            "MS" => "500",
            "MA" => "504",
            "MZ" => "508",
            "MM" => "104",
            "NA" => "516",
            "NR" => "520",
            "NP" => "524",
            "NL" => "528",
            "NC" => "540",
            "NZ" => "554",
            "NI" => "558",
            "NE" => "562",
            "NG" => "566",
            "NU" => "570",
            "NF" => "574",
            "MP" => "580",
            "NO" => "578",
            "OM" => "512",
            "PK" => "586",
            "PW" => "585",
            "PS" => "275",
            "PA" => "591",
            "PG" => "598",
            "PY" => "600",
            "PE" => "604",
            "PH" => "608",
            "PN" => "612",
            "PL" => "616",
            "PT" => "620",
            "PR" => "630",
            "QA" => "634",
            "MK" => "807",
            "RO" => "642",
            "RU" => "643",
            "RW" => "646",
            "RE" => "638",
            "BL" => "652",
            "SH" => "654",
            "KN" => "659",
            "LC" => "662",
            "MF" => "663",
            "PM" => "666",
            "VC" => "670",
            "WS" => "882",
            "SM" => "674",
            "ST" => "678",
            "SA" => "682",
            "SN" => "686",
            "RS" => "688",
            "SC" => "690",
            "SL" => "694",
            "SG" => "702",
            "SX" => "534",
            "SK" => "703",
            "SI" => "705",
            "SB" => "090",
            "SO" => "706",
            "ZA" => "710",
            "GS" => "239",
            "SS" => "728",
            "ES" => "724",
            "LK" => "144",
            "SD" => "729",
            "SR" => "740",
            "SJ" => "744",
            "SE" => "752",
            "CH" => "756",
            "SY" => "760",
            "TW" => "158",
            "TJ" => "762",
            "TZ" => "834",
            "TH" => "764",
            "TL" => "626",
            "TG" => "768",
            "TK" => "772",
            "TO" => "776",
            "TT" => "780",
            "TN" => "788",
            "TR" => "792",
            "TM" => "795",
            "TC" => "796",
            "TV" => "798",
            "UG" => "800",
            "UA" => "804",
            "AE" => "784",
            "GB" => "826",
            "UM" => "581",
            "US" => "840",
            "UY" => "858",
            "UZ" => "860",
            "VU" => "548",
            "VE" => "862",
            "VN" => "704",
            "VG" => "092",
            "VI" => "850",
            "WF" => "876",
            "EH" => "732",
            "YE" => "887",
            "ZM" => "894",
            "ZW" => "716",
            "AX" => "248"
        );

        if(isset($countries[$country])) {
            return $countries[$country];
        }
        // if nothing found - return Greece
        return '300';
    }

function getCountryPhoneCode($country) {

    if(empty($country)) {
        $default_location = wc_get_customer_default_location(); 
        $country =  $default_location['country'];
    }

    $countries_phone_codes = array(
        "AF" => "93",
        "AL" => "355",
        "DZ" => "213",
        "AS" => "1684",
        "AD" => "376",
        "AO" => "244",
        "AI" => "1264",
        "AQ" => "672",
        "AG" => "1268",
        "AR" => "54",
        "AM" => "374",
        "AW" => "297",
        "AU" => "61",
        "AT" => "43",
        "AZ" => "994",
        "BS" => "1242",
        "BH" => "973",
        "BD" => "880",
        "BB" => "1246",
        "BY" => "375",
        "BE" => "32",
        "BZ" => "501",
        "BJ" => "229",
        "BM" => "1441",
        "BT" => "975",
        "BO" => "591",
        "BA" => "387",
        "BW" => "267",
        "BV" => "74",
        "BR" => "55",
        "BL" => "590",
        "BQ" => "599",
        "CW" => "599",
        "GG" => "44",
        "IO" => "246",
        "BN" => "673",
        "BG" => "359",
        "GR" => "30",
        "AX" => "358",
        "GB" => "44",
        "IM" => "44",
        "JE" => "44",
        "ME" => "382",
        "MF" => "1599",
        "PS" => "970",
        "RS" => "381",
        "SX" => "1721",
        "TL" => "670",
        "IR" => "98",
        "BF" => "226",
        "BI" => "257",
        "KH" => "855",
        "CM" => "237",
        "CA" => "1",
        "CV" => "238",
        "KY" => "1345",
        "CF" => "236",
        "TD" => "235",
        "CL" => "56",
        "CN" => "86",
        "CX" => "61",
        "CC" => "61",
        "CO" => "57",
        "KM" => "269",
        "CG" => "242",
        "CD" => "243",
        "CK" => "682",
        "CR" => "506",
        "CI" => "225",
        "HR" => "385",
        "CY" => "357",
        "CZ" => "420",
        "DK" => "45",
        "DJ" => "253",
        "DM" => "1767",
        "DO" => "1809",
        "EC" => "593",
        "EG" => "20",
        "SV" => "503",
        "GQ" => "240",
        "ER" => "291",
        "EE" => "372",
        "ET" => "251",
        "FK" => "500",
        "FO" => "298",
        "FJ" => "679",
        "FI" => "358",
        "FR" => "33",
        "GF" => "594",
        "PF" => "689",
        "TF" => "",
        "GA" => "241",
        "GM" => "220",
        "GE" => "995",
        "DE" => "49",
        "GH" => "233",
        "GI" => "350",
        "GL" => "299",
        "GD" => "1473",
        "GP" => "590",
        "GU" => "1671",
        "GT" => "502",
        "GN" => "224",
        "GW" => "245",
        "GY" => "592",
        "HT" => "509",
        "HM" => "",
        "VA" => "39",
        "HN" => "504",
        "HK" => "852",
        "HU" => "36",
        "IS" => "354",
        "IN" => "91",
        "ID" => "62",
        "IQ" => "964",
        "IE" => "353",
        "IL" => "972",
        "IT" => "39",
        "JM" => "1876",
        "JP" => "81",
        "JO" => "962",
        "KZ" => "7",
        "KE" => "254",
        "KI" => "686",
        "KR" => "82",
        "KW" => "965",
        "KG" => "996",
        "LA" => "856",
        "LV" => "371",
        "LB" => "961",
        "LS" => "266",
        "LR" => "231",
        "LI" => "423",
        "LT" => "370",
        "LU" => "352",
        "MO" => "853",
        "MK" => "389",
        "MG" => "261",
        "MW" => "265",
        "MY" => "60",
        "MV" => "960",
        "ML" => "223",
        "MT" => "356",
        "MH" => "692",
        "MQ" => "596",
        "MR" => "222",
        "MU" => "230",
        "YT" => "262",
        "MX" => "52",
        "FM" => "691",
        "MD" => "373",
        "MC" => "377",
        "MN" => "976",
        "MS" => "1664",
        "MA" => "212",
        "MZ" => "258",
        "NA" => "264",
        "NR" => "674",
        "NP" => "977",
        "NL" => "31",
        "NC" => "687",
        "NZ" => "64",
        "NI" => "505",
        "NE" => "227",
        "NG" => "234",
        "NU" => "683",
        "NF" => "672",
        "MP" => "1670",
        "NO" => "47",
        "OM" => "968",
        "PK" => "92",
        "PW" => "680",
        "PA" => "507",
        "PG" => "675",
        "PY" => "595",
        "PE" => "51",
        "PH" => "63",
        "PN" => "870",
        "PL" => "48",
        "PT" => "351",
        "PR" => "1",
        "QA" => "974",
        "RE" => "262",
        "RO" => "40",
        "RU" => "7",
        "RW" => "250",
        "SH" => "290",
        "KN" => "1869",
        "LC" => "1758",
        "PM" => "508",
        "VC" => "1784",
        "WS" => "685",
        "SM" => "378",
        "ST" => "239",
        "SA" => "966",
        "SN" => "221",
        "SC" => "248",
        "SL" => "232",
        "SG" => "65",
        "SK" => "421",
        "SI" => "386",
        "SB" => "677",
        "SO" => "252",
        "ZA" => "27",
        "GS" => "500",
        "ES" => "34",
        "LK" => "94",
        "SR" => "597",
        "SJ" => "47",
        "SZ" => "268",
        "SE" => "46",
        "CH" => "41",
        "TW" => "886",
        "TJ" => "992",
        "TZ" => "255",
        "TH" => "66",
        "TG" => "228",
        "TK" => "690",
        "TO" => "676",
        "TT" => "1868",
        "TN" => "216",
        "TR" => "90",
        "TM" => "993",
        "TC" => "1649",
        "TV" => "688",
        "UG" => "256",
        "UA" => "380",
        "AE" => "971",
        "UM" => "",
        "US" => "1",
        "UY" => "598",
        "UZ" => "998",
        "VU" => "678",
        "VE" => "58",
        "VN" => "84",
        "VG" => "1284",
        "VI" => "1340",
        "WF" => "681",
        "EH" => "",
        "YE" => "967",
        "ZM" => "260",
        "IC" => "34" 
    );
    if(isset($countries_phone_codes[$country])) {
        return $countries_phone_codes[$country];
    }
    // if nothing found - return Greece
    return '30';
}

function prefixPhone($phone, $country) {
    $country_prefix = getCountryPhoneCode($country);

    if($country_prefix !== '') {
        return $country_prefix . '-' . $phone;
    } 

    return '';
}
function validatePhoneNumberAllCountries($phone, $country) {
    $countries_phone_codes = array(
        "AF" => "93",
        "AL" => "355",
        "DZ" => "213",
        "AS" => "1684",
        "AD" => "376",
        "AO" => "244",
        "AI" => "1264",
        "AQ" => "672",
        "AG" => "1268",
        "AR" => "54",
        "AM" => "374",
        "AW" => "297",
        "AU" => "61",
        "AT" => "43",
        "AZ" => "994",
        "BS" => "1242",
        "BH" => "973",
        "BD" => "880",
        "BB" => "1246",
        "BY" => "375",
        "BE" => "32",
        "BZ" => "501",
        "BJ" => "229",
        "BM" => "1441",
        "BT" => "975",
        "BO" => "591",
        "BA" => "387",
        "BW" => "267",
        "BV" => "74",
        "BR" => "55",
        "BL" => "590",
        "BQ" => "599",
        "CW" => "599",
        "GG" => "44",
        "IO" => "246",
        "BN" => "673",
        "BG" => "359",
        "GR" => "30",
        "AX" => "358",
        "GB" => "44",
        "IM" => "44",
        "JE" => "44",
        "ME" => "382",
        "MF" => "1599",
        "PS" => "970",
        "RS" => "381",
        "SX" => "1721",
        "TL" => "670",
        "IR" => "98",
        "BF" => "226",
        "BI" => "257",
        "KH" => "855",
        "CM" => "237",
        "CA" => "1",
        "CV" => "238",
        "KY" => "1345",
        "CF" => "236",
        "TD" => "235",
        "CL" => "56",
        "CN" => "86",
        "CX" => "61",
        "CC" => "61",
        "CO" => "57",
        "KM" => "269",
        "CG" => "242",
        "CD" => "243",
        "CK" => "682",
        "CR" => "506",
        "CI" => "225",
        "HR" => "385",
        "CY" => "357",
        "CZ" => "420",
        "DK" => "45",
        "DJ" => "253",
        "DM" => "1767",
        "DO" => "1809",
        "EC" => "593",
        "EG" => "20",
        "SV" => "503",
        "GQ" => "240",
        "ER" => "291",
        "EE" => "372",
        "ET" => "251",
        "FK" => "500",
        "FO" => "298",
        "FJ" => "679",
        "FI" => "358",
        "FR" => "33",
        "GF" => "594",
        "PF" => "689",
        "GA" => "241",
        "GM" => "220",
        "GE" => "995",
        "DE" => "49",
        "GH" => "233",
        "GI" => "350",
        "GL" => "299",
        "GD" => "1473",
        "GP" => "590",
        "GU" => "1671",
        "GT" => "502",
        "GN" => "224",
        "GW" => "245",
        "GY" => "592",
        "HT" => "509",
        "VA" => "39",
        "HN" => "504",
        "HK" => "852",
        "HU" => "36",
        "IS" => "354",
        "IN" => "91",
        "ID" => "62",
        "IQ" => "964",
        "IE" => "353",
        "IL" => "972",
        "IT" => "39",
        "JM" => "1876",
        "JP" => "81",
        "JO" => "962",
        "KZ" => "7",
        "KE" => "254",
        "KI" => "686",
        "KR" => "82",
        "KW" => "965",
        "KG" => "996",
        "LA" => "856",
        "LV" => "371",
        "LB" => "961",
        "LS" => "266",
        "LR" => "231",
        "LI" => "423",
        "LT" => "370",
        "LU" => "352",
        "MO" => "853",
        "MK" => "389",
        "MG" => "261",
        "MW" => "265",
        "MY" => "60",
        "MV" => "960",
        "ML" => "223",
        "MT" => "356",
        "MH" => "692",
        "MQ" => "596",
        "MR" => "222",
        "MU" => "230",
        "YT" => "262",
        "MX" => "52",
        "FM" => "691",
        "MD" => "373",
        "MC" => "377",
        "MN" => "976",
        "MS" => "1664",
        "MA" => "212",
        "MZ" => "258",
        "NA" => "264",
        "NR" => "674",
        "NP" => "977",
        "NL" => "31",
        "NC" => "687",
        "NZ" => "64",
        "NI" => "505",
        "NE" => "227",
        "NG" => "234",
        "NU" => "683",
        "NF" => "672",
        "MP" => "1670",
        "NO" => "47",
        "OM" => "968",
        "PK" => "92",
        "PW" => "680",
        "PA" => "507",
        "PG" => "675",
        "PY" => "595",
        "PE" => "51",
        "PH" => "63",
        "PN" => "870",
        "PL" => "48",
        "PT" => "351",
        "PR" => "1",
        "QA" => "974",
        "RE" => "262",
        "RO" => "40",
        "RU" => "7",
        "RW" => "250",
        "SH" => "290",
        "KN" => "1869",
        "LC" => "1758",
        "PM" => "508",
        "VC" => "1784",
        "WS" => "685",
        "SM" => "378",
        "ST" => "239",
        "SA" => "966",
        "SN" => "221",
        "SC" => "248",
        "SL" => "232",
        "SG" => "65",
        "SK" => "421",
        "SI" => "386",
        "SB" => "677",
        "SO" => "252",
        "ZA" => "27",
        "GS" => "500",
        "ES" => "34",
        "LK" => "94",
        "SR" => "597",
        "SJ" => "47",
        "SZ" => "268",
        "SE" => "46",
        "CH" => "41",
        "TW" => "886",
        "TJ" => "992",
        "TZ" => "255",
        "TH" => "66",
        "TG" => "228",
        "TK" => "690",
        "TO" => "676",
        "TT" => "1868",
        "TN" => "216",
        "TR" => "90",
        "TM" => "993",
        "TC" => "1649",
        "TV" => "688",
        "UG" => "256",
        "UA" => "380",
        "AE" => "971",
        "US" => "1",
        "UY" => "598",
        "UZ" => "998",
        "VU" => "678",
        "VE" => "58",
        "VN" => "84",
        "VG" => "1284",
        "VI" => "1340",
        "WF" => "681",
        "YE" => "967",
        "ZM" => "260",
        "IC" => "34" 
    );
    $found = false; 
    foreach ($countries_phone_codes as $key => $country_prefix) {
        $final_phone = preg_replace('/[^0-9]/', '', $phone);
        $pattern = '/^(?:\+|0{0,2}?)(('. $country_prefix .'))( |\.|-)?([\d \-\(\)]*)/';

        preg_match($pattern, $final_phone, $matches);

        if(!empty($matches) && !$found) {
            if(!empty($matches[4])) {
                $found=true;
                $int_phone = $country_prefix . '-' . $matches[4];
             } 
        }
    }

    if(!$found) {
        $country_prefix = getCountryPhoneCode($country);
        $int_phone = $country_prefix . '-' . preg_replace('/[^0-9]/', '', $phone);
    }
    return substr($int_phone, 0, 19);
}

function validateCountryPhoneNumber($phone, $country) {

    $country_prefix = getCountryPhoneCode($country);
    $pattern = '/^(?:\+'. $country_prefix . '|'. $country_prefix . '|0{0,2}'. $country_prefix . '?)( |\.|-)?(\d*)/';
    preg_match($pattern, $phone, $matches);

    if(!empty($matches)) {
        if(!empty($matches[2])) {
           $int_phone = $country_prefix . '-' . preg_replace('/[^0-9]/', '', $matches[2]);
        } else {
            $int_phone =  $country_prefix . '-' . preg_replace('/[^0-9]/', '', $phone);
        }
    } else {
        $int_phone = $country_prefix . '-' . preg_replace('/[^0-9]/', '', $phone);
    }
    return substr($int_phone, 0, 19);

}


function validateStateCode($state, $country) {
    $country_prefix = getCountryPhoneCode($country);
    $pattern = '/(' .$country . '-?)(.*)/';
    preg_match($pattern, $state, $matches);
    $stateCode = $state;

    if(!empty($matches)) {
        if(!empty($matches[2])) {
             $stateCode = $matches[2];
        }
    }
    if(empty($stateCode)){
        //if nothing found for state, assume that is for Attiki
        $stateCode = 'I';
    }
    return $stateCode;
}

function nonLatinChars() {
    return array(
        'À', 'à', 'Á', 'á', 'Â', 'â', 'Ã', 'ã', 'Ä', 'ä', 'Å', 'å', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ǟ', 'ǟ', 'Ǻ', 'ǻ', 'Α', 'α', 'ά', 'Ά',
        'Ḃ', 'ḃ', 'Б', 'б',
        'Ć', 'ć', 'Ç', 'ç', 'Č', 'č', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Ч', 'ч', 'Χ', 'χ',
        'Ḑ', 'ḑ', 'Ď', 'ď', 'Ḋ', 'ḋ', 'Đ', 'đ', 'Ð', 'ð', 'Д', 'д', 'Δ', 'δ',
        'Ǳ',  'ǲ', 'ǳ', 'Ǆ', 'ǅ', 'ǆ', 
        'È', 'è', 'É', 'é', 'Ě', 'ě', 'Ê', 'ê', 'Ë', 'ë', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ę', 'ę', 'Ė', 'ė', 'Ʒ', 'ʒ', 'Ǯ', 'ǯ', 'Е', 'е', 'Э', 'э', 'Ε', 'ε', 'ё', 'є', 'Є', 'έ', 'Έ',
        'Ḟ', 'ḟ', 'ƒ', 'Ф', 'ф', 'Φ', 'φ',
        'ﬁ', 'ﬂ', 
        'Ǵ', 'ǵ', 'Ģ', 'ģ', 'Ǧ', 'ǧ', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ǥ', 'ǥ', 'Г', 'г', 'Γ', 'γ',
        'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ж', 'ж', 'Х', 'х', 'Ή', 'ή',
        'Ì', 'ì', 'Í', 'í', 'Î', 'î', 'Ĩ', 'ĩ', 'Ï', 'ï', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'И', 'и', 'Η', 'η', 'Ι', 'ι', 'і', 'І', 'ї', 'Ї', 'ί', 'ϊ', 'Ί', 'Ϊ','ΐ',
        'Ĳ', 'ĳ', 
        'Ĵ', 'ĵ',
        'Ḱ', 'ḱ', 'Ķ', 'ķ', 'Ǩ', 'ǩ', 'К', 'к', 'Κ', 'κ',
        'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Л', 'л', 'Λ', 'λ',
        'Ǉ', 'ǈ', 'ǉ', 
        'Ṁ', 'ṁ', 'М', 'м', 'Μ', 'μ',
        'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'Ñ', 'ñ', 'ŉ', 'Ŋ', 'ŋ', 'Н', 'н', 'Ν', 'ν',
        'Ǌ', 'ǋ', 'ǌ', 
        'Ò', 'ò', 'Ó', 'ó', 'Ô', 'ô', 'Õ', 'õ', 'Ö', 'ö', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ø', 'ø', 'Ő', 'ő', 'Ǿ', 'ǿ', 'О', 'о', 'Ο', 'ο', 'Ω', 'ω', 'ό', 'ώ', 'Ό','Ώ',
        'Œ', 'œ', 
        'Ṗ', 'ṗ', 'П', 'п', 'Π', 'π','Ψ', 'ψ',
        'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Р', 'р', 'Ρ', 'ρ', 
        'Ś', 'ś', 'Ş', 'ş', 'Š', 'š', 'Ŝ', 'ŝ', 'Ṡ', 'ṡ', 'ſ', 'ß', 'С', 'с', 'Ш', 'ш', 'Щ', 'щ', 'Σ', 'σ', 'ς',
        'Ţ', 'ţ', 'Ť', 'ť', 'Ṫ', 'ṫ', 'Ŧ', 'ŧ', 'Þ', 'þ', 'Т', 'т', 'Ц', 'ц', 'Θ', 'θ', 'Τ', 'τ',
        'Ù', 'ù', 'Ú', 'ú', 'Û', 'û', 'Ũ', 'ũ', 'Ü', 'ü', 'Ů', 'ů', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ų', 'ų', 'Ű', 'ű', 'У', 'у', 
        'В', 'в', 'Β', 'β',
        'Ẁ', 'ẁ', 'Ẃ', 'ẃ', 'Ŵ', 'ŵ', 'Ẅ', 'ẅ',
        'Ξ', 'ξ',
        'Ỳ', 'ỳ', 'Ý', 'ý', 'Ŷ', 'ŷ', 'Ÿ', 'ÿ', 'Й', 'й', 'Ы', 'ы', 'Ю', 'ю', 'Я', 'я', 'Υ', 'υ','ύ', 'ϋ', 'Ύ', 'Ϋ', 'ΰ',
        'Ź', 'ź', 'Ž', 'ž', 'Ż', 'ż', 'З', 'з', 'Ζ', 'ζ',
        'Æ', 'æ', 'Ǽ', 'ǽ', 'а', 'А',
        'ь', 'ъ', 'Ъ', 'Ь',
    );
}

function latinChars() {
    return array(
        'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a','a', 'A',
        'B', 'b', 'B', 'b',
        'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'CH', 'ch', 'CH', 'ch',
        'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd',
        'DZ', 'Dz', 'dz', 'DZ', 'Dz', 'dz',
        'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'e', 'e', 'E','e', 'E',
        'F', 'f', 'f', 'F', 'f', 'F', 'f',
        'fi', 'fl',
        'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
        'H', 'h', 'H', 'h', 'ZH', 'zh', 'H', 'h','H', 'h',
        'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'i', 'I', 'i', 'I','i','i','I', 'I','i',
        'IJ', 'ij',
        'J', 'j',
        'K', 'k', 'K', 'k', 'K', 'k', 'K', 'k', 'K', 'k',
        'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l',
        'LJ', 'Lj', 'lj',
        'M', 'm', 'M', 'm', 'M', 'm',
        'N', 'n', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'N', 'n', 'N', 'n', 'N', 'n',
        'NJ', 'Nj', 'nj',
        'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'o','o','O','O',
        'OE', 'oe',
        'P', 'p', 'P', 'p', 'P', 'p', 'PS', 'ps',
        'R', 'r', 'R', 'r', 'R', 'r', 'R', 'r','R', 'r',
        'S', 's', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 's', 'ss', 'S', 's', 'SH', 'sh', 'SHCH', 'shch', 'S', 's', 's',
        'T', 't', 'T', 't', 'T', 't', 'T', 't', 'T', 't', 'T', 't', 'TS', 'ts', 'TH', 'th', 'T', 't',
        'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 
        'V', 'v', 'V', 'v',
        'W', 'w', 'W', 'w', 'W', 'w', 'W', 'w',
        'X', 'x',
        'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'YU', 'yu', 'YA', 'ya', 'Y', 'y', 'y', 'y', 'Y', 'Y', 'y',
        'Z', 'z', 'Z', 'z', 'Z', 'z', 'Z', 'z', 'Z', 'z',
        'AE', 'ae', 'AE', 'ae', 'a', 'A',
        '', '', '', '',
    );
}

 function convertNonLatinToLatin( $str ) {		

    $converted_name = str_replace( nonLatinChars(), latinChars(), $str );

    // for extra check if any char is not ascii, ignore it.
    $conv_name = iconv('utf-8', 'ASCII//IGNORE', $converted_name);

    //replace any no digit in piraeus accepted chars lantin and /:_().,+-
    $pattern= '/([^a-zA-Z| \/:_().,+-]*?)/';
    $name = preg_replace($pattern, '', $conv_name);

    return $name; 
      
}



    /**
     * Add Settings link to the plugin entry in the plugins menu for WC below 2.1
     * */
    if (version_compare(WOOCOMMERCE_VERSION, "2.1") <= 0) {

        add_filter('plugin_action_links', 'piraeusbank_plugin_action_links', 10, 2);

        function piraeusbank_plugin_action_links($links, $file) {
            static $this_plugin;

            if (!$this_plugin) {
                $this_plugin = plugin_basename(__FILE__);
            }

            if ($file == $this_plugin) {
                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=woocommerce_settings&tab=payment_gateways&section=WC_piraeusbank_Gateway">Settings</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }

    }
    /**
     * Add Settings link to the plugin entry in the plugins menu for WC 2.1 and above
     * */ else {
        add_filter('plugin_action_links', 'piraeusbank_plugin_action_links', 10, 2);

        function piraeusbank_plugin_action_links($links, $file) {
            static $this_plugin;

            if (!$this_plugin) {
                $this_plugin = plugin_basename(__FILE__);
            }

            if ($file == $this_plugin) {
                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=WC_Piraeusbank_Gateway">Settings</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }

    }
}
