<?php
/*
Plugin Name: Payflex Payment Gateway
Description: Use Payflex as a credit card processor for WooCommerce.
Version: 2.5.0
Author: Payflex
*/
if(!function_exists('wp_get_active_network_plugins')){
    function wp_get_active_network_plugins() {
        $active_plugins = (array) get_site_option( 'active_sitewide_plugins', array() );
        if ( empty( $active_plugins ) ) {
            return array();
        }
    
        $plugins        = array();
        $active_plugins = array_keys( $active_plugins );
        sort( $active_plugins );
    
        foreach ( $active_plugins as $plugin ) {
            if ( ! validate_file( $plugin ) // $plugin must validate as file
                && '.php' == substr( $plugin, -4 ) // $plugin must end with '.php'
                && file_exists( WP_PLUGIN_DIR . '/' . $plugin ) // $plugin must exist
                ) {
                $plugins[] = WP_PLUGIN_DIR . '/' . $plugin;
            }
        }
    
        return $plugins;
    }
}

/**
 * Check if WooCommerce is activated
 */

$plugin_path = trailingslashit(WP_PLUGIN_DIR) . 'woocommerce/woocommerce.php';
if (in_array($plugin_path, wp_get_active_and_valid_plugins()) || in_array($plugin_path, wp_get_active_network_plugins()))
{

    add_action('plugins_loaded', 'partpay_gateway', 0);

    function partpay_gateway()
    {

        if (!class_exists('WC_Payment_Gateway')) return;

        class WC_Gateway_PartPay extends WC_Payment_Gateway
        {
            protected array $environments = [];
            protected string $configurationUrl = '';
            protected string $orderurl = '';
            /**
             * @var $_instance WC_Gateway_PartPay The reference to the singleton instance of this class
             */
            private static $_instance = NULL;

            /**
             * @var boolean Whether or not logging is enabled
             */
            public static $log_enabled = false;

            /**
             * @var WC_Logger Logger instance
             */
            public static $log = false;

            /**
             * Main WC_Gateway_PartPay Instance
             *
             * Used for WP-Cron jobs when
             *
             * @since 1.0
             * @return WC_Gateway_PartPay Main instance
             */
            public static function instance()
            {
                if (is_null(self::$_instance))
                {
                    self::$_instance = new self();
                }
                return self::$_instance;
            }

            public function __construct()
            {

                $this->id = 'payflex';
                $this->method_title = __('Payflex', 'woo_partpay');
                $this->method_description = __('Use Payflex as a credit card processor for WooCommerce.', 'woo_partpay');
                $this->icon = WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/Checkout.png';

                $this->supports = array(
                    'products',
                    'refunds'
                );

                // Load the form fields.
                $this->init_environment_config();

                // Load the form fields.
                $this->init_form_fields();

                // Load the settings.
                $this->init_settings();

                // Load the frontend scripts.
                $this->init_scripts_js();
                $this->init_scripts_css();
                $settings = get_option('woocommerce_payflex_settings');
                $api_url = '';
                $this->configurationUrl = '';
                if (false !== $settings)
                {
                    $api_url = $this->environments[$this->settings['testmode']]['api_url'];
                    $this->orderurl = $api_url . '/order';
                    $this->configurationUrl = $api_url . '/configuration';
                }
                else
                {
                    $api_url = '';
                }
                // Define user set variables
                $this->title = '';
                if (isset($this->settings['title']))
                {
                    $this->title = $this->settings['title'];
                }
                $this->description = __('Pay with any Visa or Mastercard.', 'woo_partpay');

                self::$log_enabled = true;

                // Hooks
                add_action('woocommerce_receipt_' . $this->id, array(
                    $this,
                    'receipt_page'
                ));

                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(
                    $this,
                    'process_admin_options'
                ));

                //add_filter( 'woocommerce_thankyou_order_id',array($this,'payment_callback'));
                add_action('woocommerce_order_status_refunded', array(
                    $this,
                    'create_refund'
                ));

                // Don't enable PartPay if the amount limits are not met
                add_filter('woocommerce_available_payment_gateways', array(
                    $this,
                    'check_cart_within_limits'
                ) , 99, 1);

                add_action('woocommerce_settings_start', array(
                    $this,
                    'update_payment_limits'
                ));

                // Payment listener/API hook
                add_action('woocommerce_api_wc_gateway_partpay', array(
                    $this,
                    'payment_callback'
                ));
            }
            public function getOrderUrl(){
                return $this->orderurl;
            }
            /**
             * Initialise Gateway Settings Form Fields
             *
             * @since 1.0.0
             */
            function init_form_fields()
            {

                $env_values = array();
                foreach ($this->environments as $key => $item)
                {
                    $env_values[$key] = $item["name"];
                }

                $this->form_fields = array(
                    'enabled' => array(
                        'title' => __('Enable/Disable', 'woo_partpay') ,
                        'type' => 'checkbox',
                        'label' => __('Enable Payflex', 'woo_partpay') ,
                        'default' => 'yes'
                    ) ,
                    'title' => array(
                        'title' => __('Title', 'woo_partpay') ,
                        'type' => 'text',
                        'description' => __('This controls the payment method title which the user sees during checkout.', 'woo_partpay') ,
                        'default' => __('Payflex', 'woo_partpay')
                    ) ,
                    'testmode' => array(
                        'title' => __('Test mode', 'woo_partpay') ,
                        'label' => __('Enable Test mode', 'woo_partpay') ,
                        'type' => 'select',
                        'options' => $env_values,
                        'description' => __('Process transactions in Test/Sandbox mode. No transactions will actually take place.', 'woo_partpay') ,
                    ) ,
                    'client_id' => array(
                        'title' => __('Client ID', 'woo_partpay') ,
                        'type' => 'text',
                        'description' => __('Payflex Client ID credential', 'woo_partpay') ,
                        'default' => __('', 'woo_partpay')
                    ) ,
                    'client_secret' => array(
                        'title' => __('Client Secret', 'woo_partpay') ,
                        'type' => 'text',
                        'description' => __('Payflex Client Secret credential', 'woo_partpay') ,
                        'default' => __('', 'woo_partpay')
                    ) ,
                    'enable_product_widget' => array(
                        'title' => __('Product Page Widget', 'woo_partpay') ,
                        'type' => 'checkbox',
                        'label' => __('Enable Product Page Widget', 'woo_partpay') ,
                        'default' => 'yes',

                    ) ,
                    'is_using_page_builder' => array(
                        'title' => __('Product Page Widget using any page builder', 'woo_partpay') ,
                        'type' => 'checkbox',
                        'label' => __('Enable Product Page Widget using page builder', 'woo_partpay') ,
                        'default' => 'no',
                        'description' => __('<h3 class="wc-settings-sub-title">Page Builders</h3> If you use a page builder plugin, the above payment info can be placed using a shortcode instead of relying on hooks. Use [payflex_widget] within a product page.', 'woo_partpay')

                    ) ,
                    'enable_checkout_widget' => array(
                        'title' => __('Checkout Page Widget', 'woo_partpay') ,
                        'type' => 'checkbox',
                        'label' => __('Enable Checkout Page Widget', 'woo_partpay') ,
                        'default' => 'yes'
                    ) ,
                    'merchant_widget_reference' => array(
                        'title' => __('Widget Reference', 'woo_partpay') ,
                        'type' => 'text',
                        'label' => __('Widget Reference', 'woo_partpay') ,
                        'default' => __('', 'woo_partpay')
                    ) ,
                    'enable_order_notes' => array(
                        'title' => __('Order Page Notes', 'woo_partpay') ,
                        'type' => 'checkbox',
                        'label' => __('Enable Order Detail Page Notes', 'woo_partpay') ,
                        'default' => 'no'
                    )
                );
            } // End init_form_fields()
            
            /**
             * Init JS Scripts Options
             *
             * @since 1.2.1
             */
            public function init_scripts_js()
            {
                //use WP native jQuery
                wp_enqueue_script("jquery");

            }

            /**
             * Init Scripts Options
             *
             * @since 1.2.1
             */
            public function init_scripts_css()
            {
            }

            /**
             * Init Environment Options
             *
             * @since 1.2.3
             */
            public function init_environment_config()
            {
                if (empty($this->environments))
                {
                    //config separated for ease of editing
                    require (__DIR__.'/config/config.php');
                    $this->environments = $environments;
                }
            }

            /**
             * Admin Panel Options
             *
             * @since 1.0.0
             */
            public function admin_options()
            {
            ?>
				<h3><?php _e('Payflex Gateway', 'woo_partpay'); ?></h3>

				<table class="form-table">
					<?php
                // Generate the HTML For the settings form.
                $this->generate_settings_html();
            ?>
				</table><!--/.form-table-->
				<?php
            } // End admin_options()
            

            
            /**
             * Display payment options on the checkout page
             *
             * @since 1.0.0
             */
            public function payment_fields()
            {

                global $woocommerce;
                $settings = get_option('woocommerce_payflex_settings');
                if (isset($settings['enable_checkout_widget']) && $settings['enable_checkout_widget'] == 'yes')
                {
                    echo '<style>.elementor{max-width:100% !important}';
                    echo 'html {
							-webkit-font-smoothing: antialiased!important;
							-moz-osx-font-smoothing: grayscale!important;
							-ms-font-smoothing: antialiased!important;
						}
						.md-stepper-horizontal {
							display:table;
							width:100%;
							margin:0 auto;
							background-color:transparent;
						}
						.md-stepper-horizontal .md-step {
							display:table-cell;
							position:relative;
							padding:0;
						}
						.md-stepper-horizontal .md-step .md-step-circle {
							width:30px;
							height:30px;
							margin:0 auto;
							border-radius: 50%;
							text-align: center;
							line-height:30px;
							font-size: 16px;
							font-weight: 600;
							color:#FFFFFF;
						}
						.md-stepper-horizontal .md-step .md-step-title {
							margin-top:16px;
							font-size:16px;
							font-weight:600;
						}
						.md-stepper-horizontal .md-step .md-step-title,
						.md-stepper-horizontal .md-step .md-step-optional {
							text-align: center;
							color : #002751;
						}
						.md-stepper-horizontal .md-step .md-step-optional {
							font-size:12px;
						}
						.payflex_description{
							font-size:16px;text-align:center;
							margin-top:17px;
						}
						.fontcolor{
							color : #002751;
						}
						</style>';
                    $ordertotal = $woocommerce
                        ->cart->total;
                    $installment = round(($ordertotal / 4) , 2);
                    echo '<div class="fontcolor" style="font-size:16px;text-align:center">Four interest-free payments totalling R' . $ordertotal . '</div>';
                    echo '<div class="md-stepper-horizontal orange">
							<div class="md-step active">
							<div class="md-step-title">R' . $installment . '</div>
							<div class="md-step-circle"><span><img src ="' . WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/PIE-CHART-01.png' . '"></span></div>
							<div class="md-step-optional">1st instalment</div>
							</div>
							<div class="md-step active">
							<div class="md-step-title">R' . $installment . '</div>
							<div class="md-step-circle"><span><img src ="' . WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/PIE-CHART-02.png' . '"></span></div>
							<div class="md-step-optional">2 weeks later</div>
							</div>
							<div class="md-step active">
							<div class="md-step-title">R' . $installment . '</div>
							<div class="md-step-circle"><span><img src ="' . WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/PIE-CHART-03.png' . '"></span></div>
							<div class="md-step-optional">4 weeks later</div>
							</div>
							<div class="md-step active">
							<div class="md-step-title">R' . $installment . '</div>
							<div class="md-step-circle"><span><img src ="' . WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/PIE-CHART-04.png' . '"></span></div>
							<div class="md-step-optional">6 weeks later</div>
							</div>
						</div>
					<div class="payflex_description fontcolor">You will be redirected to Payflex when you click on place order.</div>
					';
                }
                else
                {
                    if ($this->settings['testmode'] != 'production'): ?><?php _e('TEST MODE ENABLED', 'woo_partpay'); ?><?php
                    endif;
                    $arr = array(
                        'br' => array() ,
                        'p' => array()
                    );
                    if ($this->description)
                    {
                        echo wp_kses('<p>' . $this->description . '</p>', $arr);
                    }
                }

            }
            /**
             * Request an order token from Partpay
             *
             * @return  string or boolean false if no token generated
             * @since 1.0.0
             */
            public function get_partpay_authorization_code()
            {

                $access_token = get_transient('partpay_access_token');
                $this->log('Access token from cache is ' . $access_token);
                if (false !== $access_token && !empty($access_token))
                {
                    $this->log('returning token ' . $access_token);
                    return $access_token;
                }
                if (false === $this->apiKeysAvailable())
                {
                    $this->log('no api keys available');
                    return false;
                }
                $this->log('Getting new token');
                $AuthURL = $this->environments[$this->settings['testmode']]['auth_url'];
                $AuthBody = ['client_id' => $this->settings['client_id'], 'client_secret' => $this->settings['client_secret'], 'audience' => $this->environments[$this->settings['testmode']]['auth_audience'], 'grant_type' => 'client_credentials'];
                $AuthBody = wp_json_encode($AuthBody);
                $headers = array(
                    'Content-Type' => 'application/json'
                );
                $AuthBody = json_decode(json_encode($AuthBody) , true);

                $response = wp_remote_post($AuthURL, array(
                    'body' => $AuthBody,
                    'headers' => $headers
                ));
                $body = json_decode(wp_remote_retrieve_body($response) , true);
                if (!is_wp_error($response) && isset($response['response']['code']) && $response['response']['code'] != '401')
                {
                    //store token in cache
                    $accessToken = isset($body['access_token']) ? $body['access_token'] : '';
                    $expireTime = isset($body['expires_in']) ? $body['expires_in'] : '';
                    $this->log('Storing new token in cache ' . $accessToken . ' which is valid for ' . $expireTime . ' seconds');
                    set_transient('partpay_access_token', $accessToken, ((int)$expireTime - 120));
                    return $accessToken;
                }
                else
                {
                    return false;
                }
            }

            private function apiKeysAvailable()
            {

                if (empty($this->settings['client_id']) || empty($this->settings['client_secret']))
                {
                    $this->log('API keys not available.');
                    return false;
                }

                return true;
            }

            public function update_payment_limits()
            {
                // Get existing limits
                $settings = get_option('woocommerce_payflex_settings');

                if (false === $this->apiKeysAvailable())
                {
                    return false;
                }

                $this->log('Updating payment limits requested');
                if (!empty($this->configurationUrl))
                {
                    $response = wp_remote_get($this->configurationUrl, array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $this->get_partpay_authorization_code()
                        )
                    ));
                    $body = json_decode(wp_remote_retrieve_body($response) , true);

                    $this->log('Updating payment limits response: ' . print_r($body, true));

                    if (!is_wp_error($response) && isset($response['response']['code']) && $response['response']['code'] == 200)
                    {
                        $settings['partpay-amount-minimum'] = isset($body['minimumAmount']) ? $body['minimumAmount'] : 0;
                        $settings['partpay-amount-maximum'] = isset($body['maximumAmount']) ? $body['maximumAmount'] : 0;
                    }

                    update_option('woocommerce_payflex_settings', $settings);
                }
                $this->init_settings();

            }

            /**
             * Process the payment and return the result
             * - redirects the customer to the pay page
             *
             * @param int $order_id
             *
             * @since 1.0.0
             * @return array
             */
            public function process_payment($order_id)
            {

                if (function_exists("wc_get_order"))
                {
                    $order = wc_get_order($order_id);
                }
                else
                {
                    $order = new WC_Order($order_id);
                }

                // Get the authorization token
                $access_token = $this->get_partpay_authorization_code();

                //Process here
                $orderitems = $order->get_items();
                $items = array();
                $i = 0;
                if (count($orderitems))
                {
                    foreach ($orderitems as $item)
                    {

                        $i++;
                        // get SKU
                        if ($item['variation_id'])
                        {

                            if (function_exists("wc_get_product"))
                            {
                                $product = wc_get_product($item['variation_id']);
                            }
                            else
                            {
                                $product = new WC_Product($item['variation_id']);
                            }
                        }
                        else
                        {

                            if (function_exists("wc_get_product"))
                            {
                                $product = wc_get_product($item['product_id']);
                            }
                            else
                            {
                                $product = new WC_Product($item['product_id']);
                            }
                        }

                        if ($i == count($orderitems))
                        {
                            $product = $items[] = array(

                                '{
									"name":"' . esc_html($item['name']) . $i . '",
									"sku":"' . $product->get_sku() . '",
									"quantity":"' . $item['qty'] . '",
									"price":"' . number_format(($item['line_subtotal'] / $item['qty']) , 2, '.', '') . '"
								}'

                            );
                        }
                        else
                        {
                            $product = $items[] = array(

                                '{
									"name":"' . esc_html($item['name']) . $i . '",
									"sku":"' . $product->get_sku() . '",
									"quantity":"' . $item['qty'] . '",
									"price":"' . number_format(($item['line_subtotal'] / $item['qty']) , 2, '.', '') . '"
								}'

                            );
                        }
                    }
                }

                //calculate total shipping amount
                if (method_exists($order, 'get_shipping_total'))
                {
                    //WC 3.0
                    $shipping_total = $order->get_shipping_total();
                }
                else
                {
                    //WC 2.6.x
                    $shipping_total = $order->get_total_shipping();
                }
                $merchantRefe = $order_id;
                $plugin_check = trailingslashit(WP_PLUGIN_DIR) . 'wt-woocommerce-sequential-order-numbers/wt-advanced-order-number.php';
                if (in_array($plugin_check, wp_get_active_and_valid_plugins()) || (is_multisite() && in_array($plugin_check, wp_get_active_network_plugins()))){
                    $merchantRefe = $order->get_order_number();
                }
               

                $OrderBodyObj = new stdClass;
                $OrderBodyObj->amount = number_format($order->get_total() , 2, '.', '');
                $OrderBodyObj->consumer = new stdClass;
                $OrderBodyObj->consumer->phoneNumber = (string)$order->get_billing_phone();
                $OrderBodyObj->consumer->givenNames = (string)$order->get_billing_first_name();
                $OrderBodyObj->consumer->surname = (string)$order->get_billing_last_name();
                $OrderBodyObj->consumer->email = (string)$order->get_billing_email();
                $OrderBodyObj->billing = new stdClass;
                $OrderBodyObj->billing->addressLine1 = (string)$order->get_billing_address_1();
                $OrderBodyObj->billing->addressLine2 = (string)$order->get_billing_address_2();
                $OrderBodyObj->billing->suburb = (string)$order->get_billing_city();
                $OrderBodyObj->billing->postcode = (string)$order->get_billing_postcode();
                $OrderBodyObj->shipping = new stdClass;
                $OrderBodyObj->shipping->addressLine1 = (string)$order->get_shipping_address_1();
                $OrderBodyObj->shipping->addressLine2 = (string)$order->get_shipping_address_2();
                $OrderBodyObj->shipping->suburb =(string) $order->get_shipping_city();
                $OrderBodyObj->shipping->postcode = (string)$order->get_shipping_postcode();
                $OrderBodyObj->description = 'string';
                $OrderBodyObj->items = [];
                $objectItems = [];
                foreach ($items as $item)
                {
                    array_push($objectItems,json_decode($item[0]));
                }
                $OrderBodyObj->items = $objectItems;
                $OrderBodyObj->merchant = new stdClass;
                $OrderBodyObj->merchant->redirectConfirmUrl = (string)$this->get_return_url($order) . '&order_id=' . $order_id . '&status=confirmed&wc-api=WC_Gateway_PartPay';
                $OrderBodyObj->merchant->redirectCancelUrl = (string)$this->get_return_url($order) . '&status=cancelled';
                $OrderBodyObj->merchantReference = (string)$merchantRefe;
                $OrderBodyObj->taxAmount = $order->get_total_tax();
                $OrderBodyObj->shippingAmount = $shipping_total;
                // $OrderBody = json_decode($OrderBodyString);

                $APIURL = $this->orderurl . '/productSelect';
               
                $order_args = array(
                    'method' => 'POST',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $access_token
                    ) ,
                    'body' => json_encode($OrderBodyObj) ,
                    'timeout' => 30
                );

                $this->log('POST Order request: ' . print_r($order_args, true));

                $order_response = wp_remote_post($APIURL, $order_args);

                $order_body = json_decode(wp_remote_retrieve_body($order_response));

                if ($access_token == false)
                {
                    // Couldn't generate token
                    $order->add_order_note(__('Unable to generate the order token. Payment couldn\'t proceed.', 'woo_partpay'));
                    wc_add_notice(__('Sorry, there was a problem preparing your payment.', 'woo_partpay') , 'error');
                    return array(
                        'result' => 'failure',
                        'redirect' => $order->get_checkout_payment_url(true)
                    );

                }
                else
                {
                    $this->log('Created Payflex OrderId: ' . print_r($order_body->orderId, true));
                    // Order token successful, save it so we can confirm it later
                    update_post_meta($order_id, '_partpay_order_token', $order_body->token);
                    update_post_meta($order_id, '_partpay_order_id', $order_body->orderId);
                    update_post_meta($order_id, '_order_redirectURL', $order_body->redirectUrl);
                    $savedId = get_post_meta($order_id, '_partpay_order_id', true);
                    $this->log('Saved ' . $savedId . ' into post meta');
                }

                $redirect = $order->get_checkout_payment_url(true);
                $this->log('Redirect URL ' . json_encode($order->get_payment_method()));
                return array(
                    'result' => 'success',
                    'redirect' => $redirect
                );

            }

            /**
             * Update status after API redirect back to merchant site
             *
             * @since 1.0.0
             */
            public function receipt_page($order_id)
            {

                if (function_exists("wc_get_order"))
                {
                    $order = wc_get_order($order_id);
                }
                else
                {
                    $order = new WC_Order($order_id);
                }

                $redirectURL = get_post_meta($order_id, '_order_redirectURL');

                //Update order status if it isn't already
                $is_pending = false;
                if (function_exists("has_status"))
                {
                    $is_pending = $order->has_status('pending');
                }
                else
                {
                    if ($order->get_status() == 'pending')
                    {
                        $is_pending = true;
                    }
                }

                if (!$is_pending)
                {
                    $order->update_status('pending');
                }
                $this->log('Partpay Checkout URL ' .$redirectURL[0]);
                //Redirect to Partpay checkout
                header('Location: ' . $redirectURL[0]);
            }

            public function get_partpay_order_id($order_id)
            {

                $partpay_order_id = get_post_meta($order_id, '_partpay_order_id', true);

                if (empty($partpay_order_id)) return false;
            }

            /**
             * @param $order_id
             */
            public function payment_callback($order_id)
            {
                $order_id = $_GET['order_id'];
                if (function_exists("wc_get_order"))
                {
                    $order = wc_get_order($order_id);
                }
                else
                {
                    $order = new WC_Order($order_id);
                }

                // //save the order id
                $order_token = get_post_meta($order_id, '_partpay_order_token', true);
                $partpay_order_id = get_post_meta($order_id, '_partpay_order_id', true);

                $this->log(sprintf('Attempting to set order status for %s, payflex orderId: %s, token: %s', $order_id, $partpay_order_id, $order_token));

                if (!empty($partpay_order_id))
                {

                    //Update status for order
                    if (isset($_GET['status']))
                    {

                        $query_string = sanitize_text_field($_GET['status']);

                        if ($query_string != NULL)
                        {
                            if ($query_string == 'confirmed')
                            {

                                $order->add_order_note(sprintf(__('Payment approved. Payflex Order ID: ' . $partpay_order_id . ' ', 'woo_partpay')));
                                $order->payment_complete($partpay_order_id);
                                wc_empty_cart();

                            }
                            elseif ($query_string == 'cancelled')
                            {

                                $order->add_order_note(sprintf(__('Payflex payment is pending approval. Payflex Order ID: ' . $partpay_order_id . ' ', 'woo_partpay')));
                                $order->update_status('cancelled');

                            }
                            elseif ($query_string == 'failure')
                            {

                                $order->add_order_note(sprintf(__('Payflex payment declined. Order ID from Payflex: ' . $partpay_order_id . ' ', 'woo_partpay')));
                                $order->update_status('failed');
                            }

                        }
                        else
                        {
                            $order->update_status('pending');
                        }
                    }

                }

                $payment_page = $this->get_return_url($order) . '&status=confirmed';
                wp_redirect($payment_page);
                exit;

            }

            /**
             * Check whether the cart amount is within payment limits
             *
             * @param  array $gateways Enabled gateways
             * @return  array Enabled gateways, possibly with PartPay removed
             * @since 1.0.0
             */
            public function check_cart_within_limits($gateways)
            {

                global $woocommerce;
                $total = isset($woocommerce
                    ->cart
                    ->total) ? $woocommerce
                    ->cart->total : 0;

                $access_token = $this->get_partpay_authorization_code();
                $config_response_transistent = get_transient('payflex_configuration_response');
                $api_url = $this->configurationUrl;
                if (false !== $config_response_transistent && !empty($config_response_transistent))
                {
                    $order_response = $config_response_transistent;
                    $order_body = json_decode($order_response);
                }
                else
                {

                    $order_args = array(
                        'method' => 'GET',
                        'headers' => array(
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer ' . $access_token
                        ) ,
                        'timeout' => 30
                    );
                    $order_response = wp_remote_post($api_url, $order_args);
                    $order_response = wp_remote_retrieve_body($order_response);
                    $order_body = json_decode($order_response);
                    set_transient('payflex_configuration_response', $order_response, 86400);
                }
                if ($order_response)
                {

                    $pbi = ($total >= $order_body->minimumAmount && $total <= $order_body->maximumAmount);

                    if (!$pbi)
                    {
                        unset($gateways['partpay']);
                    }

                }

                return $gateways;

            }

            /**
             * Can the order be refunded?
             *
             * @param  WC_Order $order
             * @return bool
             */
            public function can_refund_order($order)
            {
                return $order && $order->get_transaction_id();
            }

            /**
             * Process a refund if supported
             *
             * @param  WC_Order $order
             * @param  float $amount
             * @param  string $reason
             * @return  boolean True or false based on success
             */
            public function process_refund($order_id, $amount = null, $reason = '')
            {

                $this->log(sprintf('Attempting refund for order_id: %s', $order_id));

                $partpay_order_id = get_post_meta($order_id, '_partpay_order_id', true);

                $this->log(sprintf('Attempting refund for Payflex OrderId: %s', $partpay_order_id));

                $order = new WC_Order($order_id);

                if (empty($partpay_order_id))
                {
                    $order->add_order_note(sprintf(__('There was an error submitting the refund to Payflex.', 'woo_partpay')));
                    return false;
                }

                $access_token = $this->get_partpay_authorization_code();
                $random_string = wp_generate_password(8, false, false);
                error_log('partpay orderId2' . $partpay_order_id);
                $refund_args = array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $access_token
                    ) ,
                    'body' => json_encode(array(
                        'requestId' => 'Order #' . $order_id . '-' . $random_string,
                        'amount' => $amount,
                        'merchantRefundReference' => 'Order #' . $order_id . '-' . $random_string
                    ))
                );
                error_log('partpay orderId3' . $partpay_order_id);
                $refundOrderUrl = $this->orderurl . '/' . $partpay_order_id . '/refund';

                $refund_response = wp_remote_post($refundOrderUrl, $refund_args);
                $refund_body = json_decode(wp_remote_retrieve_body($refund_response));

                $this->log('Refund body: ' . print_r($refund_body, true));
                error_log('partpay orderId3' . $partpay_order_id);
                $responsecode = isset($refund_response['response']['code']) ? intval($refund_response['response']['code']) : 0;

                if ($responsecode == 201 || $responsecode == 200) {
                    $order->add_order_note(sprintf(__('Refund of $%s successfully sent to PayFlex.', 'woo_partpay') , $amount));
                    return true;
                } else if($responsecode === 400 && $refund_body->errorCode==='MRM007') {
                    $error_message = $refund_body->message;
                    $order->add_order_note(sprintf(__($error_message), 'woo_partpay'));
                    $error = new WP_Error( 'woocommerce_api_create_order_refund_api_failed', $error_message);    
                    return $error;
                } else {
                    if ($responsecode == 404) {
                        $order->add_order_note(sprintf(__('Order not found on Payflex.', 'woo_partpay')));
                    } else {
                        $order->add_order_note(sprintf(__('There was an error submitting the refund to Payflex.', 'woo_partpay')));
                    }
                    return false;
                }

            }

            /**
             * Logging method
             * @param  string $message
             */
            public static function log($message)
            {

                if (self::$log_enabled)
                {
                    if (empty(self::$log))
                    {
                        self::$log = new WC_Logger();
                    }
                    self::$log->add('partpay', $message);
                }
            }

            /**
             * @param $order_id
             */
            public function create_refund($order_id)
            {

                $order = new WC_Order($order_id);
                $order_refunds = $order->get_refunds();

                if (!empty($order_refunds))
                {
                    $refund_amount = $order_refunds[0]->get_refund_amount();
                    if (!empty($refund_amount))
                    {
                        $this->process_refund($order_id, $refund_amount, "Admin Performed Refund");
                    }
                }
            }
            
            /**
             * Check the order status of all orders that didn't return to the thank you page or marked as Pending by PartPay
             *
             * @since 1.0.0
             */
            public function check_pending_abandoned_orders()
            {

                # Get pending orders older than 1 hour
                $pending_orders = wc_get_orders([
                    'status' => [
                        'pending',
                        'failed',
                        'cancelled'
                     ] ,
                    'date_created' => '<' . (time() - 3600)
                ]);

                $this->log('checking ' . count($pending_orders) . " orders");
                foreach ($pending_orders as $pending_order)
                {
                    if (function_exists("wc_get_order"))
                    {
                        $order = wc_get_order($pending_order->get_id());
                    }
                    else
                    {
                        $order = new WC_Order($pending_order->get_id());
                    }

                    //skip all orders that are not PartPay
                    $payment_method = $order->get_payment_method();

                    if ($payment_method != "payflex")
                    {
                        $this->log('ignoring order not from payflex: ' . $payment_method);
                        continue;
                    }

                    $partpay_order_id = get_post_meta($pending_order->get_id(), '_partpay_order_id', true);

                    // Check if there's a stored order token. If not, it's not an PartPay order.
                    if (!$partpay_order_id)
                    {
                        $this->log('No Payflex OrderId for Order ' . $pending_order->get_id());
                        continue;
                    }

                    $this->log('Checking abandoned order for WC Order ID ' . $order->ID . ', Payflex ID ' . $partpay_order_id);

                    $response = wp_remote_get($this->orderurl . '/' . $partpay_order_id, array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $this->get_partpay_authorization_code() ,
                        )
                    ));
                    $body = json_decode(wp_remote_retrieve_body($response));

                    $this->log('Checking abandoned order result: ' . print_r($body, true));

                    $response_code = wp_remote_retrieve_response_code($response);
                    $settings = get_option('woocommerce_payflex_settings');
                    // Check status of order
                    if ($response_code == 200)
                    {
                        $order_note = sprintf(__('Checked payment status with Payflex. Payment %s. Payflex Order ID: %s', 'woo_partpay') , strtolower($body->orderStatus) , $partpay_order_id);
  
                        // Check status of order
                        if($body->orderStatus == "Initiated"){
                            $this->log('Order is Intiated by customer but not logged in with Payflex OrderId :: '. $partpay_order_id);
                        }
                        else if ($body->orderStatus == "Approved")
                        {
                            $order->add_order_note(sprintf(__('Checked payment status with Payflex. Payment approved. Payflex Order ID: %s', 'woo_partpay') , $partpay_order_id));
                            $order->payment_complete($pending_order->orderId);
                        }
                        elseif ($body->orderStatus == "Created")
                        {
                            if ($settings['enable_order_notes'] == 'yes')
                            {
                                $order->add_order_note(__('Checked payment status with Payflex. Still pending approval.', 'woo_partpay'));
                            }
                        }
                        elseif ($body->orderStatus == 'Abandoned')
                        {
                            $isExist = $this->checkOrderNotesExistsByOrderId($pending_order->ID, $order_note);
                            if(!$isExist){
                                $order->add_order_note($order_note);
                            }
                            $order->update_status('cancelled');    
                        }
                        elseif ($body->orderStatus == 'Declined')
                        {
                            $isExist = $this->checkOrderNotesExistsByOrderId($pending_order->ID, $order_note);
                            if(!$isExist){
                                $order->add_order_note($order_note);
                            }
                            $order->update_status('cancelled');    
                        }
                        else
                        {
                            $order->add_order_note($order_note);
                            $order->update_status('failed');
                        }
                    }
                    else
                    {
                        // $order->add_order_note(sprintf(__('Tried to check payment status with Payflex. Unable to access API. Repsonse code is %s Payflex Order ID: %s','woo_partpay'),$response_code,$partpay_order_id));
                        
                    }

                }

            }

            function checkOrderNotesExistsByOrderId($order_id, $content){
                global $wpdb;
                $table_name = $wpdb->prefix . "comments"; 
                $comment_info = $wpdb->get_results("SELECT * 
                FROM $table_name 
                WHERE comment_post_ID = $order_id 
                AND comment_author = 'WooCommerce' 
                AND comment_type = 'order_note'
                AND comment_content = '$content'");
                if(count($comment_info) > 0){
                    return true;
                }
                return false;
            }

        }

        /**
         * Add the Partpay gateway to WooCommerce
         *
         * @param  array $methods Array of Payment Gateways
         * @return  array Array of Payment Gateways
         * @since 1.0.0
         *
         */
        function add_partpay_gateway($methods)
        {
            $methods[] = 'WC_Gateway_PartPay';
            return $methods;
        }
        add_filter('woocommerce_payment_gateways', 'add_partpay_gateway');

        /**
         * Check for the CANCELLED payment status
         * We have to do this before the gateway initialises because WC clears the cart before initialising the gateway
         *
         * @since 1.0.0
         */
        function partpay_check_for_cancelled_payment()
        {
            // Check if the payment was cancelled
            if (isset($_GET['status']) && $_GET['status'] == "cancelled" && isset($_GET['key']) && isset($_GET['token']))
            {

                $gateway = WC_Gateway_PartPay::instance();
                $key = sanitize_text_field($_GET['key']);
                $order_id = wc_get_order_id_by_order_key($key);

                if (function_exists("wc_get_order"))
                {
                    $order = wc_get_order($order_id);
                }
                else
                {
                    $order = new WC_Order($order_id);
                }

                if ($order)
                {

                    $partpay_order_id = get_post_meta($order_id, '_partpay_order_id', true);
                    $obj = new WC_Gateway_PartPay();
                    $ordUrl = $obj->getOrderUrl();
                    $response = wp_remote_get($ordUrl . '/' . $partpay_order_id, array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $obj->get_partpay_authorization_code() ,
                        )
                    ));
                    $body = json_decode(wp_remote_retrieve_body($response));
                    if ($body->orderStatus != "Approved")
                    {
                        $gateway->log('Order ' . $order_id . ' payment cancelled by the customer while on the Payflex checkout pages.');
                        $order->add_order_note(__('Payment cancelled by the customer while on the Payflex checkout pages.', 'woo_partpay'));

                        if (method_exists($order, "get_cancel_order_url_raw"))
                        {
                            wp_redirect($order->get_cancel_order_url_raw());
                        }
                        else
                        {

                            wp_redirect($order->get_cancel_order_url());
                        }
                        exit;
                    }
                    $redirect = $order->get_checkout_payment_url(true);

                    return array(
                        'result' => 'success',
                        'redirect' => $redirect
                    );
                }
            }
        }
        add_action('template_redirect', 'partpay_check_for_cancelled_payment');

        /**
         * Call the cron task related methods in the gateway
         *
         * @since 1.0.0
         *
         */
        function partpay_do_cron_jobs()
        {
            $gateway = WC_Gateway_Partpay::instance();
            $gateway->check_pending_abandoned_orders();
            $gateway->update_payment_limits();
        }
        add_action('partpay_do_cron_jobs', 'partpay_do_cron_jobs');
        add_action('init', function ()
        {
            if (!wp_next_scheduled('partpay_do_cron_jobs'))
            {
                wp_schedule_event(time() , 'twominutes', 'partpay_do_cron_jobs');
            }
        });
    }

    /* WP-Cron activation and schedule setup */

    /**
     * Schedule PartPay WP-Cron job
     *
     * @since 1.0.0
     *
     */
    function partpay_create_wpcronjob()
    {
        $timestamp = wp_next_scheduled('partpay_do_cron_jobs');
        if ($timestamp == false)
        {
            wp_schedule_event(time() , 'twominutes', 'partpay_do_cron_jobs');
        }
    }
    register_activation_hook(__FILE__, 'partpay_create_wpcronjob');

    /**
     * Delete PartPay WP-Cron job
     *
     * @since 1.0.0
     *
     */
    function partpay_delete_wpcronjob()
    {
        wp_clear_scheduled_hook('partpay_do_cron_jobs');
    }
    register_deactivation_hook(__FILE__, 'partpay_delete_wpcronjob');

    /**
     * Add a new WP-Cron job scheduling interval of every 2 minutes
     *
     * @param  array $schedules
     * @return array Array of schedules with 2 minutes added
     * @since 1.0.0
     *
     */
    function partpay_add_two_minute_schedule($schedules)
    {
        $schedules['twominutes'] = array(
            'interval' => 120, // seconds
            'display' => __('Every 2 minutes', 'woo_partpay')
        );
        return $schedules;
    }
    add_filter('cron_schedules', 'partpay_add_two_minute_schedule');
    add_shortcode('payflex_widget', 'widget_shortcode_content');
    global $wp_version;
    if($wp_version >= 6.3){
        add_action('woocommerce_before_add_to_cart_form', 'widget_content', 0);
    }else{
        add_action('woocommerce_single_product_summary', 'widget_content', 12);
    }
    
    
    // FUNCTION - Frontend show on single product page
    function widget_content(){
        $payflex_settings = get_option('woocommerce_payflex_settings');
        if($payflex_settings['enable_product_widget'] == 'yes'){
            echo woo_payflex_frontend_widget();
        }
    }
    function widget_shortcode_content(){
        $payflex_settings = get_option('woocommerce_payflex_settings');
        if($payflex_settings['is_using_page_builder'] == 'yes'){
            return woo_payflex_frontend_widget();
        }
     }
    function woo_payflex_frontend_widget()
    {		
        // Early exit if frontend is disabled in settings:
        $payflex_settings = get_option('woocommerce_payflex_settings');
        $payflex_frontend = $payflex_settings['enable_product_widget'];
        $payflex_frontend_page_builder = $payflex_settings['is_using_page_builder'];
        if ($payflex_frontend == 'no' && $payflex_frontend_page_builder == 'no'){ return; }   
        global $product;
        if(!$product){ return; }
        // Early exit if product is a WooCommerce Subscription type product:
        if (class_exists('WC_Subscriptions_Product') && WC_Subscriptions_Product::is_subscription($product)){
            return;
        }
        // Early exit if product has no price:
        $noprice =  wc_get_price_including_tax($product);
        if (!$noprice){  return; }
        // $payflexprice = wc_get_price_including_tax($product);
        // $payflexnowprice = wc_get_price_including_tax( $product ); 
        //Variable product data saved for updating amount when selection is made
        $variations_data = [];
        if ($product->is_type('variable')) {
            foreach ($product->get_available_variations() as $variation) {
                $varprice = ($variation['display_price'] * 100 / 4) / 100;
                $variations_data[$variation['variation_id']]['amount'] = $variation['display_price'];
                $variations_data[$variation['variation_id']]['installment'] = $varprice;
            }
        }
        ?>
        <script>
            jQuery(function($) {
                var product_type = '<?php echo $product->is_type('variable');?>';
                if(product_type == ''){
                    var installmentValule = getInstallmentAmount(<?php echo $noprice;?>);
                    textBasedOnAmount('<?php echo $noprice;?>',installmentValule); 
                }
                $('.partPayCalculatorWidget1').on('click', function(ev) {
                        ev.preventDefault();
                        var $body = $('body');
                        var $dialog = $('#partPayCalculatorWidgetDialog').show();
                        $body.addClass("partPayWidgetDialogVisible");
                        var $button = $dialog
                            .find("#partPayCalculatorWidgetDialogClose")
                            .on('click', function(e) {
                                e.preventDefault();
                                $dialog.hide();
                                $body.removeClass("partPayWidgetDialogVisible");
                                // Put back on the widget.
                                $('#partPayCalculatorWidgetDialog').append($dialog);
                            });
                        // Move to the body element.
                        $body.append($dialog);
                        $body.animate({ scrollTop: 0 }, 'fast');
                });
                var jsonData = <?php echo json_encode($variations_data); ?> ,
                inputVID = 'input.variation_id';
                $('input.variation_id').change(function() {  
                    if ('' != $(inputVID).val()) {
                        var vid = $(inputVID).val(),
                            installmentPayflex = '',
                            amountPayflex = '';
                        $.each(jsonData, function(index, data) {
                            if (index == vid) {
                                installmentPayflex = data['installment'];
                                amountPayflex = data['amount'];
                            }
                        });
                        
                        textBasedOnAmount(amountPayflex,installmentPayflex);
                    }
                });
                function getInstallmentAmount(value) {  
                    value = + value;
                    if(isNaN(value) || value < 0 ) {
                        return 0;
                    }
                    var result = Math.floor(value * 100 / 4) / 100;
                    return endsWithZeroCents(result) ? result.toFixed(0) : result.toFixed(2);
                }
                function textBasedOnAmount(amount,installmentAmount) {
                    var rangeMin = <?php echo $payflex_settings['partpay-amount-minimum'];?>,
                        rangeMax = <?php echo $payflex_settings['partpay-amount-maximum'];?>;
                    if(rangeMin < 10 || rangeMax < 25 || rangeMax > 2000001 ) {  
                        rangeMin = 50;
                        rangeMax = 20000;
                    } else if (rangeMax < rangeMin) {
                        var x = rangeMax;
                        rangeMax = rangeMin;
                        rangeMin = x;
                    }
                    var installmentAmount = getInstallmentAmount(amount);
                    var html = '';
                    if (amount > 10000) {
                        // if heavy basket
                        html = '';
                        $('.paypercentage').html('');
                        $('#heavybasketnote').html('* Higher first payment may apply on large purchases')
                        $('.heavyBasketText').html("Payflex lets you get what you need now, but pay for it over four interest-free instalments. " +
                            "You pay a larger amount upfront with the remainder spread across three payments over the following six weeks.");
                    }else{
                        $('#heavybasketnote').html('');
                    }
                    if (amount < rangeMin) {
                        html = 'of 25% on orders over <br> R' + rangeMin;
                    } else if (amount > rangeMax) {
                        html = 'of 25% on orders  R' + rangeMin + ' - R' + rangeMax;
                    } else if(amount < 10001) {
                        html = 'of <span>R' + installmentAmount + '</span>';
                    }
                    $('.partPayCalculatorWidgetTextFromCopy').html(html);
                }
                function endsWithZeroCents(value) {
                    value = Number(value); 
                    var fixed = value.toFixed(2);
                    var endsWith = fixed.lastIndexOf(".00") != -1;
                    return endsWith ;
                }
            });   
        </script>
        <?php
        
        $css = '/* Widget */ @font-face { font-family: \'Montserrat\'; font-style: normal; font-weight: 400; src: local(\'Montserrat Regular\'), local(\'Montserrat-Regular\'), url(https://fonts.gstatic.com/s/montserrat/v13/JTUSjIg1_i6t8kCHKm459Wlhzg.ttf) format(\'truetype\'); } @font-face { font-family: \'Montserrat\'; font-style: normal; font-weight: 500; src: local(\'Montserrat Medium\'), local(\'Montserrat-Medium\'), url(https://fonts.gstatic.com/s/montserrat/v13/JTURjIg1_i6t8kCHKm45_ZpC3gnD-w.ttf) format(\'truetype\'); } @font-face { font-family: \'Montserrat\'; font-style: normal; font-weight: 700; src: local(\'Montserrat Bold\'), local(\'Montserrat-Bold\'), url(https://fonts.gstatic.com/s/montserrat/v13/JTURjIg1_i6t8kCHKm45_dJE3gnD-w.ttf) format(\'truetype\'); } body.partPayWidgetDialogVisible { overflow: hidden; } .partPayCalculatorWidgetDialogHeadingLogo {padding:10px} .partPayCalculatorWidget1 { margin: 0; padding: 2px; background-color: #FFFFFF; /*background-image: url(\'Payflex_Widget_BG.png\');*/ color: #002751;; cursor: pointer; text-transform: none; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; position: relative; margin-bottom: 10px} .partPayCalculatorWidgetDialogHeadingLogo img{ background-color : #c8a6fa;padding:10px;border-radius:100px} .partPayCalculatorWidget1 #freetext { font-weight: bold; color: #002751; } .partPayCalculatorWidget1 #partPayCalculatorWidgetLogo {width: 125px; top: 0;bottom: 0; margin: auto 0; right: 0; position: absolute; background-color: transparent; } .partPayCalculatorWidget1 #partPayCalculatorWidgetText { font-size: 15px; width: 60%; position: relative; top: 0; bottom: 0; margin: auto 0px } .partPayCalculatorWidget1 #partPayCalculatorWidgetText .partPayCalculatorWidgetTextFromCopy > span { font-weight: bold; } .partPayCalculatorWidget1 #partPayCalculatorWidgetText #partPayCalculatorWidgetLearn { text-decoration: underline; font-size: 12px; font-style: normal; color: #0086EF; } .partPayCalculatorWidget1 #partPayCalculatorWidgetText #partPayCalculatorWidgetSlogen { font-size: 12px; font-style: normal; } #partPayCalculatorWidgetDialog { box-sizing: border-box; } #partPayCalculatorWidgetDialog *, #partPayCalculatorWidgetDialog *:before, #partPayCalculatorWidgetDialog *:after { box-sizing: inherit; } #partPayCalculatorWidgetDialog { z-index: 999999; font-family: \'Arial\', \'Helvetica\'; font-size: 14px; display: none; color: #002751; position: fixed; bottom: 0; left: 0; right: 0; top: 0; } .partPayCalculatorWidgetDialogOuter { background-color: rgba(0, 0, 0, 0.2); height: 100%; left: 0; position: absolute; text-align: center; top: 0; vertical-align: middle; width: 100%; z-index: 999999; overflow-x: hidden; overflow-y: auto; } .partPayCalculatorWidgetDialogInner { background-color: white; border: solid 1px rgba(0, 0, 0, 0.2); -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); max-width: 900px; margin: auto; position: relative; font-family: "Montserrat", sans-serif; margin: 30px auto; -webkit-border-radius: 30px; -moz-border-radius: 30px; border-radius: 30px; } .partPayCalculatorWidgetDialogInner #partPayCalculatorWidgetDialogClose { position: absolute; margin-right: 8px; margin-top: 8px; cursor: pointer; max-height: 28px; max-width: 28px; right: 0; top: 0; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHeading { padding: 50px; display: flex; /*border-bottom: dotted 1px #CECFD1;*/ padding-bottom: 24px; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHeading .partPayCalculatorWidgetDialogHeadingLogo { /*width: 300px; height: 64px; max-width: 300px; max-height: 64px; padding-top: 10px; flex: 1;background-color:#c8a6fa;border-radius:100px;  */} .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHeading .partPayCalculatorWidgetDialogHeadingLogo img { /*max-width: 300px; max-height: 64px;*/   display: inline-block; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHeading .partPayCalculatorWidgetDialogHeadingTitle { font-size: 32px; text-align: left; flex: 1; margin-left: 2rem; font-style: italic; color: #002751; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHowItWorksTitle { padding: 10px 50px 0 50px; font-size: 28px; padding-top: 20px; text-align: left; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHowItWorksDesc { padding: 10px 50px 0 50px; font-size: 17px; text-align: left; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHowItWorks { display: flex; justify-content: space-between; padding: 30px 50px 0 50px; } @media (max-width: 768px) { .partPayCalculatorWidget1{min-height:100px; } #partPayCalculatorWidgetLogo{float:left !important;top:0 !important;margin-top:8px;padding-bottom:15px;} .partPayCalculatorWidgetDialogHeadingLogo{padding:0}  #partPayCalculatorWidgetText {clear:both} .partPayCalculatorWidget1{width:100%} .partPayCalculatorWidgetDialogHeadingTitle{margin-top:40px;} .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHowItWorks { flex-direction: column; } } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHowItWorks .partPayCalculatorWidgetDialogHowItWorksBody { flex: 1; display: block; padding-top: 20px; /*padding-bottom: 20px;*/ } .nuumberingarea{margin:0 0 10px;padding:0;float:left;width:100%;text-align:center}.nuumberingarea strong{margin:0;padding:15px 0;width:100px;float:none;display:inline-block;border:none;border-radius:100%;font-size:50px;font-weight:700;color:#002751;background-color:#c8a6fa}.partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHowItWorks .partPayCalculatorWidgetDialogHowItWorksBody div img { max-width: 120px; max-height: 120px; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHowItWorks .partPayCalculatorWidgetDialogHowItWorksBody p { display: block; font-size: 15px; padding: 5px 15px; color: #002751; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter { /*background-color: #E5E5E6;*/ margin-top: 20px; border-top: solid 2px #002751; padding-bottom: 25px; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter .partPayCalculatorWidgetDialogFooterTitle { width: 100%; font-size: 15px; font-weight: 500; padding: 10px 0 0 15px; text-align: left; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter .partPayCalculatorWidgetDialogFooterBody { display: block; padding-top: 20px; /* padding-left: 50px; padding-right: 50px;*/ } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter .partPayCalculatorWidgetDialogFooterBody > ul { padding: 0; width: 100%; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter .partPayCalculatorWidgetDialogFooterBody > ul > li { display: inline-block; padding: 0 34px 0 30px; margin-left: 3px; margin-bottom: 13px; text-align: left; list-style: none; background-repeat: no-repeat; background-image: url(https://widgets.payflex.co.za/assets/tick.png); background-position: left center; background-size : 25px } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter .partPayCalculatorWidgetDialogFooterLinks { padding-top: 10px; font-size: 15px; font-style: italic; color: #002751; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter .partPayCalculatorWidgetDialogFooterLinks a { text-decoration: underline; color: #002751; } @media only screen and (max-width: 915px) { .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHeading { display: block; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHeading .partPayCalculatorWidgetDialogHeadingLogo { padding-top: 0; width: 100%; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHeading .partPayCalculatorWidgetDialogHeadingLogo img { max-width: 100%; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogHeading .partPayCalculatorWidgetDialogHeadingTitle { margin-left: 0; font-size: 24px; } } @media only screen and (max-width: 710px) { .partPayCalculatorWidgetDialogInner { max-width: 350px; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter { display: block; width: 100%; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter .partPayCalculatorWidgetDialogFooterBody > ul { width: 100%; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter .partPayCalculatorWidgetDialogFooterBody > ul > li { display: block; } .partPayCalculatorWidgetDialogInner .partPayCalculatorWidgetDialogFooter .partPayCalculatorWidgetDialogFooterLinks { padding-top: 10px; font-size: 10px; } } /* iPhone 5 in portrait & landscape: */ /* iPhone 5 in portrait: */ /* iPhone 5 in landscape: */ /* Explicit */';
        echo '<style type="text/css">' . $css . '</style>';
        return '<div class="partPayCalculatorWidget1"><div id="partPayCalculatorWidgetText">Or split into 4x <span id="freetext">interest-free</span> payments  <span class="partPayCalculatorWidgetTextFromCopy"></span> <span id="partPayCalculatorWidgetLearn">Learn&nbsp;more</span></div><img id="partPayCalculatorWidgetLogo" src="https://widgets.payflex.co.za/assets/partpay_new.png"><div id="partPayCalculatorWidgetDialog" role="dialog"><div class="partPayCalculatorWidgetDialogOuter"><div class="partPayCalculatorWidgetDialogInner"><img id="partPayCalculatorWidgetDialogClose" src="https://widgets.payflex.co.za/assets/cancel-icon.png" alt="Close"><div class="partPayCalculatorWidgetDialogHeading"><div class="partPayCalculatorWidgetDialogHeadingLogo"><img src="https://widgets.payflex.co.za/assets/Payflex_purple.png"></div><div class="partPayCalculatorWidgetDialogHeadingTitle"><div>No interest, no fees,</div><div>4x instalments over 6 weeks</div></div></div><div class="partPayCalculatorWidgetDialogHowItWorksTitle">How it works</div><div class="partPayCalculatorWidgetDialogHowItWorksDesc"><span class="heavyBasketText">Payflex lets you get what you need now, but pay for it over four interest-free instalments. You pay 25% upfront, then three payments of 25% over the following six weeks.</div></span><div class="partPayCalculatorWidgetDialogHowItWorks"><div class="partPayCalculatorWidgetDialogHowItWorksBody"><div><div class="nuumberingarea"><strong>1</strong></div></div><p>Shop Online<br>and fill your cart</p></div><div class="partPayCalculatorWidgetDialogHowItWorksBody"><div><div class="nuumberingarea"><strong>2</strong></div></div><p>Choose Payflex at checkout</p></div><div class="partPayCalculatorWidgetDialogHowItWorksBody"><div><div class="nuumberingarea"><strong>3</strong></div></div><p>Get approved and <br> pay <span class="paypercentage">25% </span>today <br> with your debit <br> or credit card </p><div id="heavybasketnote" style="font-size:13px"></div></div><div class="partPayCalculatorWidgetDialogHowItWorksBody"><div><div class="nuumberingarea"><strong>4</strong></div></div><p>Pay the remainder <br> over 6-weeks.<br> No interest. <br> No fees.</p></div></div><br style="border-bottom: dotted 1px #CECFD1"><div class="partPayCalculatorWidgetDialogFooter"><div class="partPayCalculatorWidgetDialogFooterBody"><ul><li>You must be over<br>18 years old</li><li>You must have a valid<br>South African ID</li><li>You must have a debit or credit card<br>issued by Mastercard, Visa or Amex </li></ul></div><div class="partPayCalculatorWidgetDialogFooterLinks">Still want more information? <a href="http://www.payflex.co.za/#howitworks/" target="_blank">Click here</a></div></div></div></div></div></div>';
    }
   
}

