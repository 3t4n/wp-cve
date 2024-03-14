<?php

add_action( 'admin_notices', [new AdminNotice(), 'displayAdminNotice']);

add_filter( 'woocommerce_payment_gateways', 'add_ecentric_gateway_class' );

function add_ecentric_gateway_class( $gateways ) {
    $gateways[] = 'WC_Card_Gateway';
    return $gateways;
}

add_action( 'plugins_loaded', 'init_ecentric_gateway_class' );

function prefix_register_ecentric_gateway_rest_routes() {
    $controller = new WC_Card_Gateway();
    $controller->register_routes();
    $controller->register_cron();
}

add_action( 'rest_api_init', 'prefix_register_ecentric_gateway_rest_routes');

function init_ecentric_gateway_class()
{
    class WC_Card_Gateway extends WC_Payment_Gateway{
        const IMG_SNAPSCAN_LOGO = '';
        const PERFORM_HEALTH_CHECK_ON_CHECKOUT = false;
        const PLUGIN_VERSION = '1.5.16';
        const PLUGIN_ID = 'card';
        const PLUGIN_TITLE = 'Card Payments - powered by SnapScan';
        const PLUGIN_DESCRIPTION = 'Accept card payments through entering card details. (Powered by SnapScan)';
        const DEFAULT_API_ERROR = 'We could not confirm the status of your payment. Please contact help@snapscan.co.za for help.';

        public function __construct()
        {
            $this->version = self::PLUGIN_VERSION;
            $this->id = self::PLUGIN_ID;
            $this->icon = self::IMG_SNAPSCAN_LOGO;
            // URL of the icon that will be displayed on checkout page near your gateway name
            $this->method_title = self::PLUGIN_TITLE;
            $this->method_description = self::PLUGIN_DESCRIPTION;

            $this->host = getenv('POS_URL') === false ? 'https://pos.snapscan.io' : getenv('POS_URL');
            $this->snapscan_api = $this->host.'/merchant/api/v1/payments';
            $this->merchant_portal_url = getenv('MERCHANT_PORTAL_URL') === false ? 'https://merchant.getsnapscan.com/plugin_settings' :  getenv('MERCHANT_PORTAL_URL').'/plugin_settings';

            $this->supports = array('products');

            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('head');
            $this->description = $this->get_option('description');
            $this->enabled = $this->get_option('enabled');
            $this->merchant = $this->get_option('merchant_id');
            $this->logging = $this->get_option('card_logging');
            $this->snap_api = $this->get_option('snap');

            // try to extract settings from merchant portal
            $mp_settings = isset($_GET["settings"]) ? htmlspecialchars($_GET["settings"], ENT_QUOTES, 'UTF-8') : null;

            if (isset($mp_settings)) {
                $json_settings = base64_decode($mp_settings);
                $settings = json_decode($json_settings, true);
                $this->update_option('snap', $settings['api_key']);                
                $this->update_option('snap_api', $settings['api_key']);                
                $newURL = preg_replace('/&settings=.*/', '', $_SERVER['REQUEST_URI']);
                header('location:' . $newURL);
                return;
            }

            add_action('snap_payment_hook', 'check_payments') ;

            add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
                $this,
                'process_admin_options'
            ) );

            add_action('woocommerce_receipt_' . $this->id, array($this, 'payment'));

            add_filter( 'woocommerce_available_payment_gateways', array($this, 'filter_gateways'));

            add_action( 'woocommerce_settings_saved', array($this, 'perform_health_check'));
        }

        function plugin_url()
        {
            if (isset($this->plugin_url)) {
                return $this->plugin_url;
            }

            return $this->plugin_url = plugins_url("", dirname(__FILE__));
        }

        public function register_cron(){
            if ( ! wp_next_scheduled( 'snap_payment_hook' ) ) {
                wp_schedule_event( time(), 'hourly', 'snap_payment_hook' );
            }
        }

        function handle_payment_api_response($response, $source)
        {
            $response_code = wp_remote_retrieve_response_code( $response );
            $error_message = $this->convert_response_code_to_error_message($response_code);

            if (!empty($error_message)) {
                if ($this->settings['card_logging'] == 'yes') {
                    SnapLogger::log('[Card] Error handling payment response. Status code ' . $response_code . ' with error ' . $error_message . ' calling ' . $source . ' with headers ' . wp_remote_retrieve_headers($response));
                }
                AdminNotice::displayError(__('SnapScan Card Payment Error: '.$error_message));
                exit;
            }

            $body = wp_remote_retrieve_body( $response );
            $body = json_decode($body)[0];

            $filteredBody = [];
            $filteredBody['merchantReference'] = sanitize_key($body->merchantReference);
            $filteredBody['status'] = sanitize_text_field($body->status);

            if(isset($filteredBody['merchantReference'])) {
                $order = wc_get_order($body->merchantReference);
            }else{
                $order = wc_get_order($reference);
            }

            if(!empty($order)) {
                if(isset($filteredBody['status'])) {
                    if ($filteredBody['status'] == "completed") {
                        $order->payment_complete();
                    }
                }
            }

            return $order;
        }

        function get_payment_status($reference)
        {
            $check_order_url = $this->snapscan_api . '?merchantReference=' . $reference;
            $header = 'Basic '. base64_encode($this->settings['snap'] . ':');

            $response = wp_remote_get($check_order_url, array(
                'headers' => array(
                    'Authorization' => $header
                )
            ));

            return $this->handle_payment_api_response($response, $check_order_url);
        }

        public function check_payments(){
            $customer_orders = wc_get_orders( array(
                'limit'    => -1,
                'status'   => 'pending'
            ) );
            
            foreach ( $customer_orders as $order ) {
                $this->get_payment_status($order->get_id());
            }
        }


        // from https://woocommerce.com/document/implementing-wc-integration/
        /**
         * Generate Button HTML.
         *
         * @access public
         * @param mixed $key
         * @param mixed $data
         * @since 1.0.0
         * @return string
         */
        public function generate_button_html( $key, $data ) {
            $field    = $this->plugin_id . $this->id . '_' . $key;
            $defaults = array(
                'class'             => 'button-secondary',
                'css'               => '',
                'custom_attributes' => array(),
                'desc_tip'          => false,
                'description'       => '',
                'title'             => '',
            );

            $data = wp_parse_args( $data, $defaults );

            ob_start();
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
                    <?php echo $this->get_tooltip_html( $data ); ?>
                </th>
                <td class="forminp">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
                        <button class="<?php echo esc_attr( $data['class'] ); ?>" type="button" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php echo $this->get_custom_attribute_html( $data ); ?>><?php echo wp_kses_post( $data['title'] ); ?></button>
                        <?php echo $this->get_description_html( $data ); ?>
                    </fieldset>
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }

        public function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => 'Enable/Disable',
                    'label' => 'Enable Card Payment',
                    'type' => 'checkbox',
                    'description' => '',
                    'default' => 'no'
                ),
                'card_logging' => array(
                    'title' => 'Enable/Disable Error Logging',
                    'label' => 'Enable Error Logging',
                    'type' => 'checkbox',
                    'description' => '',
                    'default' => 'no'
                ),
                'head' => array(
                    'title' => 'Title',
                    'type' => 'text',
                    'default' => 'Pay with Card'
                ),
                'description' => array(
                    'title' => 'Description',
                    'type' => 'textarea',
                    'description' => 'This controls the description which the user sees during checkout.',
                    'default' => 'Pay using Visa, Mastercard or Diners Club.',
                ),
                'merchant_id' => array(
                    'title' => 'Merchant ID',
                    'type' => 'text'
                ),
                'fetch' => array(
                    'title' => 'Fetch API settings',
                    'type' => 'button',
                    'description' => 'This fetches your API settings from your merchant portal.',
                    'custom_attributes' => array(
                        'onclick' => "location.href='" . $this->merchant_portal_url . "?next=" . "' + encodeURIComponent(location.href)",
                    ),
                    'desc_type' => true
                ),
                'snap' => array(
                    'title' => 'API Key',
                    'type' => 'text'
                ),
            );
        }

        public function register_routes()
        {
            register_rest_route('snap', '/s', array(
                'methods' => 'GET',
                'callback' => array($this, 'order_redirect'),
                'permission_callback' => '__return_true',
            ));
        }

        function convert_response_code_to_error_message($response_code)
        {
            $error_message = '';
            switch ($response_code){
                case 400:
                case 401:
                case 401:
                    $error_message = "The SnapScan API details you’ve entered are incorrect. Please contact help@snapscan.co.za to confirm your details.";
                case 404:
                    $error_message = "An invalid request was sent to the SnapScan API. Please contact help@snapscan.co.za for help.";
                case (500 <= $response_code):
                    $error_message = "There was an error communicating with the SnapScan API. Please contact help@snapscan.co.za for help.";
                default:
                    break;
            }

            return $error_message;
        }

        function perform_api_health_check()
        {
            $enabled = $this->settings['enabled'];
            if ($enabled == 'no') return true;
            $api = $this->settings['snap'];
            if (empty($api)) return true;

            $check_order_url = $this->snapscan_api . '?merchantReference=test';
            $header = 'Basic '. base64_encode($api . ':');

            $response = wp_remote_get($check_order_url, array(
                'headers' => array(
                    'Authorization' => $header
                )
            ));

            $response_code = wp_remote_retrieve_response_code( $response );
            $error_message = $this->convert_response_code_to_error_message($response_code);

            if ($response_code != 200) {
                if ($this->settings['card_logging'] == 'yes') {
                    SnapLogger::log('[Card] Error performing health check. Status code ' . $response_code . ' with error ' . $error_message . ' calling ' . $check_order_url . ' with API key [' . $api .']');
                }
                AdminNotice::displayError(__('SnapScan Card health check failed - Please check your API Key'));
                return false;
            } else {
                return true;
            }
        }

        function perform_health_check()
        {
            return $this->perform_api_health_check();
        }

        function filter_gateways($gateways) {
            global $woocommerce;

            if (self::PERFORM_HEALTH_CHECK_ON_CHECKOUT && $this->perform_health_check() == false) {
                unset($gateways[self::PLUGIN_ID]);
            }

            return $gateways;
        }

        function fetch_status_and_redirect($reference, $attempts) {
            $order = $this->get_payment_status($reference);
            $status = $order->get_status();

            if ($status == 'pending') {
                if ($attempts > 9) {
                    // we've waited 10s - tell the customer we can't process
                    $back = $order->get_checkout_payment_url(false);
                    $url = $this->plugin_url() . "/assets/html/payment_error.html?reference=".$reference."&back=".$back;
                } else {
                    // still waiting on PSP to finalize payment
                    sleep(1);
                    return $this->fetch_status_and_redirect($reference, $attempts + 1);
                }
            } else if ($status == 'failed') {
                // we've landed on the failed page on SnapScan and need to go back to checkout
                $url = $order->get_checkout_payment_url(false);
            } else {
                // completed
                $url = $order->get_checkout_order_received_url();
            }

            return $url;
        }

        public function order_redirect() {
            $reference = sanitize_key($_GET['merchant_reference']);
            $url = $this->fetch_status_and_redirect($reference, 0);
            wp_redirect( $url );
            exit;
        }

        public function payment($order_id)
        {
            $order = new WC_Order($order_id);

            $order_redirect_url = get_site_url(null, '/wp-json/snap/s?merchant_reference='.$order_id, 'https');

            $user = wp_get_current_user();

            $email = $user->user_email;
            $formEmail = WC()->customer->get_billing_email();

            if(isset($formEmail) && $formEmail != "" && $formEmail != $email){
                $email = $formEmail;
            }

            $total_in_cents = round($order->get_total() * 100);
            $snapcode = $this->settings['merchant_id'];

            print '
		<div class="card-wrapper">
			<style type="text/css">
				#card-widget {
				  width: 160px;
				  padding: 30px 20px;
				  background-color: #ffffff;
				  box-sizing: content-box;
				}
				}
			</style>
			<div id="card-widget" style="margin:0 auto;text-align: center; border:none">
			    Processing payment
			</div>
		</div>';

            $lightbox_script = '
            <script type="text/javascript" src="'.$this->host.'/gateway/lightbox"></script>
            <script type="text/javascript">
                function paySuccess(data) {
                    window.location = "'.$order_redirect_url .'";
                }
                function payFailure(data) {                
                    window.location = "'.$order->get_checkout_payment_url(false).'";
                }
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "'.$this->host.'/gateway/pay");
                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xhr.send(JSON.stringify({
                    code: "'.$snapcode.'",
                    reference: "'.$order_id.'",
                    amount: '.$total_in_cents.',
                    email: "'.$email.'",
                    r_url: "'.$order_redirect_url.'",
                    plugin: "woo",
                    plugin_version: "'.$this->version.'"
                }));
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        var response = JSON.parse(xhr.response);
                        window.hpp.payment(response, paySuccess, payFailure);
                    } else {
                        window.location = "'.$order->get_checkout_payment_url(false).'";
                    }
                };
            </script>';
            print $lightbox_script;
        }

        function process_payment($order_id)
        {

            $order = new WC_Order($order_id);

            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url(true)
            );
        }

    }
}
