<?php

add_action( 'admin_notices', [new AdminNotice(), 'displayAdminNotice']);

add_filter( 'woocommerce_payment_gateways', 'add_snapscan_class' );

function add_snapscan_class( $gateways ) {
    $gateways[] = 'WC_Ecentric_Snapscan';
    return $gateways;
}

add_action( 'plugins_loaded', 'init_snapscan_class' );

function prefix_register_snapscan_rest_routes() {
    $controller = new WC_Ecentric_Snapscan();
    $controller->register_routes();
}

add_action( 'rest_api_init', 'prefix_register_snapscan_rest_routes');

function init_snapscan_class()
{
    class WC_Ecentric_Snapscan extends WC_Payment_Gateway
    {
        const IMG_SNAPSCAN_LOGO = '/assets/snapscan_images/SnapScan_logo_blue_v1.svg';
        const PERFORM_HEALTH_CHECK_ON_CHECKOUT = false;
        const PLUGIN_VERSION = '1.5.16';
        const PLUGIN_ID = 'snapscan';
        const PLUGIN_TITLE = 'SnapScan App Payments';
        const PLUGIN_DESCRIPTION = 'Accept payments from the SnapScan app though Scan-to-Pay on desktop or Pay Links on mobile sites.';
        const DEFAULT_API_ERROR = 'We could not confirm the status of your payment. Please contact help@snapscan.co.za for help.';

        public function __construct()
        {
            $this->version = self::PLUGIN_VERSION;
            $this->id = self::PLUGIN_ID;
            $this->icon = $this->plugin_url() . self::IMG_SNAPSCAN_LOGO;
            // URL of the icon that will be displayed on checkout page near your gateway name
            $this->method_title = self::PLUGIN_TITLE;
            $this->method_description = self::PLUGIN_DESCRIPTION;
            $this->pos_host = getenv('POS_URL') === false ? 'https://pos.snapscan.io' : getenv('POS_URL');
            $this->snapscan_api = $this->pos_host . '/merchant/api/v1/payments';
            $this->merchant_portal_url = getenv('MERCHANT_PORTAL_URL') === false ? 'https://merchant.getsnapscan.com/plugin_settings' :  getenv('MERCHANT_PORTAL_URL').'/plugin_settings';

            $this->supports = array('products');

            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('header');
            $this->description = $this->get_option('desc');
            $this->enabled = $this->get_option('on');
            $this->merchant = $this->get_option('merchant');
            $this->snap_api = $this->get_option('snap_api');
            $this->logging = $this->get_option('logging');
            $this->webhook_auth = $this->get_option('webhook_auth');

            // try to extract settings from merchant portal
            $mp_settings = isset($_GET["settings"]) ? htmlspecialchars($_GET["settings"], ENT_QUOTES, 'UTF-8') : null;

            if (isset($mp_settings)) {
                $json_settings = base64_decode($mp_settings);
                $settings = json_decode($json_settings, true);
                $this->update_option('webhook_auth', $settings['webhook_auth_key']);
                $this->update_option('snap_api', $settings['api_key']);                
                $this->update_option('snap', $settings['api_key']);                
                $newURL = preg_replace('/&settings=.*/', '', $_SERVER['REQUEST_URI']);
                header('location:' . $newURL);
                return;
            }

            add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );

            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
                $this,
                'process_admin_options'
            ) );

            add_action('woocommerce_receipt_' . $this->id, array($this, 'checkout'));

            add_filter( 'woocommerce_available_payment_gateways', array($this, 'filter_gateways'));

            add_action( 'woocommerce_settings_saved', array($this, 'perform_health_check'));
        }

        public function register_routes()
        {
            register_rest_route('snap', '/payment-complete', array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_snapscan_webhook_notification'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('snap', '/verify-payment', array(
                'methods' => 'GET',
                'callback' => array($this, 'handle_snapscan_redirect'),
                'permission_callback' => '__return_true',
            ));
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
                'on' => array(
                    'title' => 'Enable/Disable',
                    'label' => 'Enable SnapScan',
                    'type' => 'checkbox',
                    'description' => '',
                    'default' => 'no'
                ),
                'logging' => array(
                    'title' => 'Enable/Disable Error Logging',
                    'label' => 'Enable Error Logging',
                    'type' => 'checkbox',
                    'description' => '',
                    'default' => 'no'
                ),
                'header' => array(
                    'title' => 'Title',
                    'type' => 'text',
                    'default' => 'SnapScan'
                ),
                'desc' => array(
                    'title' => 'Description',
                    'type' => 'textarea',
                    'description' => 'This controls the description which the user sees during checkout.',
                    'default' => 'Pay using the SnapScan app.',
                ),
                'merchant' => array(
                    'title' => 'SnapCode',
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
                'snap_api' => array(
                    'title' => 'API Key',
                    'type' => 'text'
                ),
                'webhook_auth' => array(
                    'title' => 'Webhook Auth Key',
                    'type' => 'text'
                ),
            );
        }

        function plugin_url()
        {
            if (isset($this->plugin_url)) {
                return $this->plugin_url;
            }

            return $this->plugin_url = plugins_url("", dirname(__FILE__));
        }

        function perform_api_health_check()
        {
            $on = $this->settings['on'];
            if ($on == 'no') return true;
            $api = $this->settings['snap_api'];
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
                if ($this->settings['logging'] == 'yes') {
                    SnapLogger::log('[App] Error performing health check. Status code ' . $response_code . ' with error ' . $error_message . ' calling ' . $check_order_url . ' with API key [' . $api . ']');
                }
                AdminNotice::displayError(__('SnapScan App health check failed - Please check your API Key'));
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

        function handle_webhook_notification($filteredParams)
        {
            $on = $this->settings['on'];
            if ($on == 'no') return array('success');

            if(isset($filteredParams['merchantReference'])){
                $order = wc_get_order($filteredParams['merchantReference']);
            }else{
                if ($this->settings['logging'] == 'yes') {
                    SnapLogger::log('[App] Merchant reference is missing from webhook');
                }
                return array('error' => 'Merchant reference is missing.');
            }

            if(empty($order)) {
                if ($this->settings['logging'] == 'yes') {
                    SnapLogger::log('[App] Webhook could not find order ' . $filteredParams['merchantReference']);
                }
                return array('error' => 'Order not found.');
            }

            $status = $filteredParams['status'];
            $total = $filteredParams['totalAmount'];
            $required = $filteredParams['requiredAmount'];

            if(!isset($status) || !isset($total) || !isset($required) || !isset($order)){
                if ($this->settings['logging'] == 'yes') {
                    SnapLogger::log('[App] Webhook could not resolve one of: [' . $filteredParams . ']');
                }
                return array('error' => 'Parameter validation failed.');
            }

            if ($required > $total) {
                if ($this->settings['logging'] == 'yes') {
                    SnapLogger::log('[App] Webhook could not complete. Required amount ZAR' . $required . ' is greater than total amount ZAR' . $total);
                }
                return array('error' => 'Amount paid must be more than required amount.');
            }

            if ($status == "completed") {
                $order->payment_complete();
            }else if($status === "pending"){
                // this is an edge case since the webhook notification
                // should never send us a pending payment status
                return array('error' => 'Payment is still pending.');
            }
            return array('success');
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

        // see: https://www.popmartian.com/tipsntricks/2015/07/14/howto-use-php-getallheaders-under-fastcgi-php-fpm-nginx-etc/
        function get_all_headers() {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }

        function handle_snapscan_webhook_notification()
        {
            // the SnapScan platform has sent us a webhook notification in the background
            $entityBody = file_get_contents('php://input');
            $authHeader = null;
            foreach ($this->get_all_headers() as $name => $value) {
                if ($name == 'Authorization') {
                    $authHeader = $value;
                }
            }

            $params = json_decode(stripslashes(stripslashes($_POST["payload"])), true);

            $filteredParams = [];
            $filteredParams['merchantReference'] = sanitize_key($params['merchantReference']);
            $filteredParams['status'] = sanitize_text_field($params['status']);
            $filteredParams['totalAmount'] = intval($params['totalAmount']);
            $filteredParams['requiredAmount'] = intval($params['requiredAmount']);

            $signature = hash_hmac('sha256', $entityBody, $this->settings['webhook_auth']);
            $auth = "SnapScan signature=$signature";

            if (hash_equals($authHeader, $auth)) {
                return $this->handle_webhook_notification($filteredParams);
            }else {
                if ($this->settings['logging'] == 'yes') {
                    SnapLogger::log('[App] Auth header on payment does not match plugin settings: [' . $this->settings['webhook_auth'] . ']');
                }
                return ['error' => "Auth header on payment does not match plugin settings."];
            }
        }

        function handle_payment_api_response($response, $source)
        {
            $response_code = wp_remote_retrieve_response_code( $response );
            $error_message = $this->convert_response_code_to_error_message($response_code);

            if (!empty($error_message)) {
                wc_add_notice( self::DEFAULT_API_ERROR, 'error' );
                if ($this->settings['logging'] == 'yes') {
                    SnapLogger::log('[App] Error handling payment response. Status code ' . $response_code . ' with error ' . $error_message . ' calling ' . $source . ' with headers ' . wp_remote_retrieve_headers($response));
                }
                AdminNotice::displayError(__('SnapScan App Payment Error: '.$error_message));
                $url = $order->get_checkout_payment_url(false);
                wp_redirect($url);
            }

            $body = wp_remote_retrieve_body( $response );
            $body = json_decode($body)[0];

            $filteredBody = [];
            $filteredBody['merchantReference'] = sanitize_key($body->merchantReference);
            $filteredBody['status'] = sanitize_text_field($body->status);

            if(isset($filteredBody['merchantReference'])) {
                $order = new WC_Order((int)$body->merchantReference);
            }else{
                $order = new WC_Order((int)$reference);
            }

            if(isset($filteredBody['status'])) {
                if ($filteredBody['status'] == "completed") {
                    $order->payment_complete();
                    $url = $order->get_checkout_order_received_url();
                    wp_redirect($url);
                    exit;
                } else {
                    // no update - wait for payment confirmation
                }
            }

            return array('success');
        }

        function get_payment_status($reference)
        {
            $check_order_url = $this->snapscan_api . '?merchantReference=' . $reference;
            $header = 'Basic '. base64_encode($this->settings['snap_api'] . ':');

            $response = wp_remote_get($check_order_url, array(
                'headers' => array(
                    'Authorization' => $header
                )
            ));

            return $this->handle_payment_api_response($response, $check_order_url);
        }

        function handle_snapscan_redirect()
        {
            // the SnapScan gateway has redirected us back to our WooCommerce page
            $reference = sanitize_key($_GET["merchantReference"]);
            return $this->get_payment_status($reference);
        }

        function checkout($order_id)
        {
            $order = new WC_Order($order_id);

            $order_received_url = $order->get_checkout_order_received_url();
            $order_checkout_payment_url = $order->get_checkout_payment_url(true);


            $total_in_cents = round($order->get_total() * 100);
            $snapcode = $this->settings['merchant'];

            $qr_url =  $this->pos_host . '/qr/' . $snapcode . '?id=' . $order_id . '&strict=true&amount=' . $total_in_cents . '&plugin=woo&plugin_v=' . $this->version;
            $qr_image_url = $this->pos_host . '/qr/' . $snapcode . '.png?id=' . $order_id . '&strict=true&amount=' . $total_in_cents . '&snap_code_size=180&plugin=woo&plugin_v=' . $this->version;

            print '
		<div class="snapscan-wrapper">
			<style type="text/css">
				#snapscan-widget {
				  width: 208px;
				  padding: 30px 20px;
				  background-color: #ffffff;
				  box-sizing: content-box;
				}
				#snapscan-widget div, #snapscan-widget img, #snapscan-widget a {
				  line-height: 1em;
				}
				#snapscan-widget .snap-code-contaner {
				  background-color: #ffffff;
				  padding: 24px 24px 12px;
				  box-sizing: content-box;
				  border: 1px solid #4A90E2;
				  border-radius: 24px 24px 0 0;
				}
				#snapscan-widget .download-links {
				  display: inline-flex;
				  margin-top: 16px;
				}
			    #snapscan-widget .snap-code-contaner .scan-header {
				  width: 160px;
				  margin-top: 12px;
				  margin-bottom: 12px;
				  text-align: center;
				  font-size: 16px;
				  color: #263943;
				}
				#snapscan-widget .scan-footer {
				  margin-bottom: 16px;
				  padding: 12px 24px;
				  box-sizing: content-box;
				  background-color: #4A90E2;
				  border-radius: 0 0 24px 24px;
				}
				#snapscan-widget b{
				    text-align: center;
				}
				#snapscan-widget .download-text{
				    font-size: 12px;
				    color: #263943;
				    padding: 0;
				    margin: 0;
				}
				#snapscan-widget .pay-link {
				  text-decoration:none;
				  border:none;
				  display: none;
				  box-sizing: content-box;
				  margin: 0;
				  padding: 0;
				}
				#snapscan-widget .download-links a{
				    max-width: 33%;
				 }
				 #snapscan-widget .download-links .download-link{
				    margin-right: 14px;
				 }
				#snapscan-widget img {
				  border:none;
				  margin-bottom: 4px;
				  padding: 0;
				  background:transparent;
				  box-sizing: content-box;
				  box-shadow: none;
				  display:block;
				}
				#snapscan-widget .pay-link .tap-to-pay{
				    display: inline-flex;
				    background-color: #4A90E2;
				    border: 1px solid #4A90E2;
				    border-radius: 24px;
				    margin-bottom: 10px;
				    margin-top: 8px;
				    padding: 4px;
				}
				#snapscan-widget .pay-link .tap-to-pay .snap-logo{
				    margin-right: 8px;
				}
				#snapscan-widget .pay-link .tap-to-pay .tap-text{
				    font-size: 17px;
				    font-weight: bold;
				    color: #ffffff;
				    margin-right: 8px;
				    margin-top: 8px;
				    margin-bottom: 8px;
				}
				#snapscan-widget .scan-footer .logo{
				    margin: auto;
				}
				@media screen and (max-device-width: 667px){
				  #snapscan-widget .snap-code-contaner  {
					display: none;
				  }
				  #snapscan-widget .scan-text {
					display: none;
				  }
				  .card-link {
				    display: none;
				  }
				  #snapscan-widget .pay-link {
				  text-decoration:none;
				  border:none;
				  display: block;
				  box-sizing: content-box;
				  margin: 0;
				  padding: 0;
				}
				#snapscan-widget .scan-header, .scan-footer {
				  display: none;
				}
				#snapscan-widget .download-links {
				  display: inline-flex;
				  margin-top: 10px;
				}
				#snapscan-widget img {
				  margin-bottom: 0;
				}
				}
			</style>
			<div id="snapscan-widget" style="margin:0 auto;text-align: center; border:none">
				<div class="snap-code-contaner">
				  <img class="snapscan-snap-code" src="' . $qr_image_url . '" width="160" height="160" style="padding:0px; background-color:white; border:none; background:transparent">
			      <b class="scan-header">Scan here to pay</b>
				</div>
                <div class="scan-footer">
                    <img class="logo" src="' . $this->plugin_url() . "/assets/snapscan_images/SnapScan_logo_v1.svg" . '">
                </div>
				<a class="pay-link" href="' . $qr_url . '" target="_blank">
				    <div class="tap-to-pay">
				        <img src="' . $this->plugin_url() . "/assets/snapscan_images/SnapScan_Icon_v1.svg" . '" class="snap-logo">
				        <p class="tap-text">Tap here to pay</p>
                    </div>
                </a>
				<div class="text-box">
				    <p class="download-text">Download the app:</p>
				</div>
				<div class="download-links">
					<a class="download-link" href="'.$qr_url .'"><img src="' . $this->plugin_url() . "/assets/snapscan_images/apple_icon.svg" . '"></a>
				    <a class="download-link" href="'.$qr_url .'"><img src="' . $this->plugin_url() . "/assets/snapscan_images/play_icon.svg" . '"></a>
				    <a class="download-link" href="'.$qr_url .'"><img src="' . $this->plugin_url() . "/assets/snapscan_images/huawei_icon.svg" . '"></a>
                </div>
			</div>
		
		</div>';

            $check_order_url = $this->snapscan_api;
            $order_redirect_url = get_site_url(null, '/wp-json/snap/verify-payment?merchantReference='. $order_id, 'https');

            if (strrpos($check_order_url, '?') === false) {
                $check_order_url .= '?merchantReference=' . $order_id . '&status=completed';
            } else {
                $check_order_url .= '&merchantReference=' . $order_id . '&status=completed';
            }

            $polling_script = '';
            if (in_array($order->get_status(), array('pending', 'failed'))) {
                $polling_script = '
			<script type="text/javascript">
                function pollSnapScanPayment() {
                    const xhr = new XMLHttpRequest();
                    xhr.open("GET", "' . $check_order_url . '");
                    xhr.setRequestHeader("Authorization", "Basic ' . base64_encode($this->settings['snap_api'] . ':' . '') . '");
                    xhr.send();
                    xhr.onload = function() {
                        if (xhr.status == 200) {                          
                            const response = JSON.parse(xhr.response);
                            let payments = response.length;
                            if (payments == 0) {
                                setTimeout(pollSnapScanPayment, 1000);
                            } else {
                                var isCompleted = false;
                                response.forEach((r) => {
                                    if (r.status === "completed") {
                                        isCompleted = true;
                                    }
                                });
                                if (isCompleted) {
                                    window.location.replace("' . $order_redirect_url . '");
                                } else {
                                    setTimeout(pollSnapScanPayment, 1000);
                                }
                            }
                        } else {
					        setTimeout(pollSnapScanPayment, 3000);
                        }
                    };
                }
				pollSnapScanPayment();
			</script>';
            }
            print $polling_script;
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
