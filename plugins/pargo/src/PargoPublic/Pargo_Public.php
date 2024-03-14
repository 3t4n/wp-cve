<?php

    namespace PargoWp\PargoPublic;

    use PargoWp\Includes\Analytics;
    use PargoWp\PargoAdmin\Pargo_Admin_API;
    use WC;
    use WP_REST_Response;
    use PargoWp\Includes\Pargo_Orders;
    use PargoWp\Includes\Pargo_Shipping_Process;
    use PargoWp\Includes\Pargo_Wp_Shipping_Method;

    /**
     * The public-facing functionality of the plugin.
     *
     * @link       pargo.co.za
     * @since      1.0.0
     *
     * @package    Pargo
     * @subpackage Pargo/public
     */

    /**
     * The public-facing functionality of the plugin.
     *
     * Defines the plugin name, version, and two examples hooks for how to
     * enqueue the public-facing stylesheet and JavaScript.
     *
     * @package    Pargo
     * @subpackage Pargo/public
     * @author     Pargo <support@pargo.co.za>
     */
    class Pargo_Public
    {

        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_name The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The version of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $version The current version of this plugin.
         */
        private $version;

        /**
         * Initialize the class and set its properties.
         *
         * @param string $plugin_name The name of the plugin.
         * @param string $version     The version of this plugin.
         *
         * @since    1.0.0
         */
        public function __construct($plugin_name, $version)
        {

            $this->plugin_name = $plugin_name;
            $this->version = $version;

        }

        /**
         * Register the stylesheets for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function enqueue_styles()
        {

            /**
             * Only add this CSS on the checkout page
             */
            if (is_cart() || is_checkout()) {
	            $css_file_path = plugin_dir_path( __FILE__ ) . "../../assets/css/pargo_wp.css";
                if (!file_exists($css_file_path)) {
	                $pargo_shipping_method = new Pargo_Wp_Shipping_Method();
	                $styling = $pargo_shipping_method->get_option('pargo_custom_styling');
	                file_put_contents( $css_file_path, sanitize_text_field( $styling ) );
                }
                wp_enqueue_style($this->plugin_name . '-front', PARGO_PLUGIN_PATH . 'assets/vue/pargo_front.css', [], $this->version, 'all');
                wp_enqueue_style($this->plugin_name . '-styling', PARGO_PLUGIN_PATH . 'assets/css/pargo_wp.css', [], $this->version, 'all');
            }

        }

        public function add_module_script($tag, $handle, $src)
        {
            // if not your script, do nothing and return original $tag
            if ($this->plugin_name . '-button' !== $handle) {
                return $tag;
            }
            // change the script tag by adding type="module" and return it.
            $tag = '<script src="' . esc_url($src) . '"></script>';
            return $tag;
        }

        /**
         * Register the JavaScript for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts()
        {

            /**
             * Only add these scripts on the checkout page
             */
            if (is_cart() || is_checkout()) {
                wp_enqueue_script($this->plugin_name . '-button', PARGO_PLUGIN_PATH . 'assets/vue/pargo_button.umd.js', ['jquery'], $this->version, true);
                wp_localize_script(
                    $this->plugin_name . '-button',
                    'OBJ',
                    [
                        'asset_url' => PARGO_PLUGIN_PATH . 'assets',
                        'api_url' => esc_url_raw(rest_url()),
                        'ajax_url' => esc_url_raw(admin_url('admin-ajax.php')),
                        'nonce' => wp_create_nonce('wp_rest'),
                    ]
                );
            }
        }

        /**
         * Update the shipping label
         *
         * @param string           $label
         * @param WC_Shipping_Rate $method
         */
        function pargo_label_change($label, $method)
        {
            $shippingProcesses = new Pargo_Shipping_Process($this->plugin_name, $this->version);
            return $shippingProcesses->wcPargoLabelChange($label, $method);
        }

        /**
         * Add the additional required fields for Pargo Shipping
         *
         * @param array $fields
         *
         * @return array
         */
        public function pargo_checkout_fields($fields)
        {
            global $woocommerce;
            $fields['billing']['billing_suburb'] = [
                'label' => __('Suburb', 'woocommerce'),
                'placeholder' => __('Suburb', 'placeholder', 'woocommerce'),
                'required' => true,
                'class' => ['form-row-wide', 'address-field'],
                'autocomplete' => 'address-level2',
                'priority' => 60,
            ];

            if (!isset(WC()->session)) {
                return;
            }
            $chosen_methods = WC()->session->get('chosen_shipping_methods');
            $chosen_shipping = $chosen_methods[0];
            if ($chosen_shipping == 'wp_pargo') {
                $fields['shipping'] = [];
                return $fields;
            }

            $fields['shipping']['shipping_phone'] = [
                'label' => __('Phone', 'woocommerce'),
                'placeholder' => __('Alternate Phone Number', 'placeholder', 'woocommerce'),
                'required' => false,
                'class' => ['form-row-wide', 'address-field'],
                'autocomplete' => 'off',
                'priority' => 20,
            ];
            $fields['shipping']['shipping_suburb'] = [
                'label' => __('Suburb', 'woocommerce'),
                'placeholder' => __('Suburb', 'placeholder', 'woocommerce'),
                'required' => true,
                'class' => ['form-row-wide', 'address-field'],
                'autocomplete' => 'address-level2',
                'priority' => 60,
            ];

            return $fields;
        }

        public function pargo_default_address_fields ($fields) {

            $fields['suburb'] = [
                'label' => __('Suburb', 'woocommerce'),
                'placeholder' => __('Suburb', 'placeholder', 'woocommerce'),
                'required' => true,
                'class' => ['form-row-wide', 'address-field'],
                'autocomplete' => 'address-level2',
                'priority' => 60,
            ];
            return $fields;
        }

	    public function pargo_account_suburb_address_field($fields, $customer_id, $type) {
            if ($type === "billing") {
                $fields['suburb'] = get_user_meta($customer_id, 'billing_suburb', true);
            }
            if ($type === "shipping") {
                $fields['suburb'] = get_user_meta($customer_id, 'shipping_suburb', true);
            }
		    return $fields;
        }

        public function pargo_order_suburb_billing_address_field($fields, $order) {
            $fields['suburb'] = get_post_meta($order->get_id(), '_billing_suburb', true);
            return $fields;
        }

        public function pargo_order_suburb_shipping_address_field($fields, $order) {
            $fields['suburb'] = get_post_meta($order->get_id(), '_shipping_suburb', true);
            return $fields;
        }

        public function pargo_formatted_address_replacements($address, $args) {
            $address['{suburb}'] = '';
            if (isset($args['suburb'])) {
	            $address['{suburb}'] = $args['suburb'];
            }
            return $address;
        }

        public function pargo_localisation_address_formats($formats) {
            $formats['default'] = "{name}\n{company}\n{address_1}\n{address_2}\n{suburb}\n{city}\n{state}\n{postcode}\n{country}";
            return $formats;
        }

        /**
         * Additional validation on the suburb fields before checkout is processed
         */
        public function validate_pargo_fields()
        {
            if (empty($_POST['billing_suburb'])) {
                wc_add_notice('Please add a billing suburb.', 'error');
            }
            if (isset($_POST['ship_to_different_address'])) {
                if (empty($_POST['shipping_suburb'])) {
                    wc_add_notice('Please add a shipping suburb.', 'error');
                }
            }
            // Check if W2P shipping method is selected and has a value
            $chosen_methods = WC()->session->get('chosen_shipping_methods');
            $chosen_shipping = $chosen_methods[0];
            if ($chosen_shipping == 'wp_pargo') {
                $pargoshipping = WC()->session->get('pargo_shipping_address');
                if (!$pargoshipping) {
                    wc_add_notice('Please select a Pargo Pickup Point.', 'error');
                }
            }
        }

        /**
         * We have to tell WC that this should not be handled as a REST request.
         * Otherwise we can't use the product loop template contents properly.
         * Since WooCommerce 3.6
         *
         * @param bool $is_rest_api_request
         *
         * @return bool
         */
        public function simulate_as_not_rest($is_rest_api_request)
        {
            if (empty($_SERVER['REQUEST_URI'])) {
                return $is_rest_api_request;
            }

            // Bail early if this is not our request.
            if (false === strpos($_SERVER['REQUEST_URI'], $this->plugin_name)) {
                return $is_rest_api_request;
            }

            return false;
        }

        /**
         * Register the API routes
         *
         */
        public function register_public_routes()
        {
            register_rest_route($this->plugin_name . '/v1', '/get-pargo-settings', [
                'methods' => 'GET',
                'callback' => [$this, 'get_pargo_settings'],
                'permission_callback' => '__return_true',
            ]);

            register_rest_route($this->plugin_name . '/v1', '/set-pargo-pickup-point', [
                'methods' => 'POST',
                'callback' => [$this, 'set_pargo_pickup_point'],
                'permission_callback' => '__return_true', // Change this to check if there is a cart object
            ]);
        }

        /**
         * API route to get eh Pargo Settings for the cart / checkout pages
         *
         */
        public function get_pargo_settings()
        {
            $pargo_shipping_method = new Pargo_Wp_Shipping_Method();
            $pargo_url_endpoint = $pargo_shipping_method->get_option('pargo_url_endpoint');
            $pargo_map_token = $pargo_shipping_method->get_option('pargo_map_token');
            if (empty($pargo_url_endpoint) || empty($pargo_map_token)) {
	            $admin_api = new Pargo_Admin_API($this->plugin_name, $this->version);
                if (empty($pargo_url_endpoint)) {
                    $pargo_url_endpoint = $admin_api::PARGO_API_ENDPOINTS['staging'];
                }
                if (empty($pargo_map_token)) {
                    if (strpos($pargo_url_endpoint, 'staging') !== false) { // using strpos here until php 8 is more widely used
	                    $pargo_map_token = $admin_api::PARGO_DEFAULT_TOKENS['staging'];
                    } else {
	                    $pargo_map_token = $admin_api::PARGO_DEFAULT_TOKENS['production'];
                    }
                }
            }

            return new WP_REST_Response(['code' => 'success', 'data' => [
                'pargo_url_endpoint' => $pargo_url_endpoint,
                'pargo_map_token' => $pargo_map_token,
            ]], 200);
        }

        /**
         * API route to update the cart session with the selected Pargo Point
         *
         */
        public function set_pargo_pickup_point()
        {
            if (!empty($_POST['pargoshipping'])) {
                $sanitized_post = sanitize_post($_POST);
                if (isset(WC()->session)) {
                    if (empty(WC()->session->get('delivery_type'))) {
                        WC()->session->set('delivery_type', Pargo_Orders::PROCESS_TYPE_W2P);
                    }
                    WC()->session->set('pargo_shipping_address', $sanitized_post['pargoshipping']);
                    $this->set_shipping_zip();
	                if (is_checkout())
	                {
		                Analytics::submit('customer', 'click', 'select_pup_checkout');
	                }
	                if (is_cart())
	                {
		                Analytics::submit('customer', 'click', 'select_pup_cart');
	                }

                    wp_send_json(['code' => 'success', 'message' => 'Pargo Pickup Point set']);
                }
                wp_send_json(['code' => 'error', 'message' => 'Session Error']);
            }
            wp_die();
        }

        /**
         * Update the shipping address with the selected Pargo Point
         * Was previously being set in the action: woocommerce_before_checkout_form
         * Was previously being set in the filter: woocommerce_checkout_get_value
         *
         */
        public function set_shipping_zip()
        {
            global $woocommerce;
            $state = null;
            if (WC()->session->get('pargo_shipping_address')) {
                $pargo_shipping = json_decode(stripslashes(WC()->session->get('pargo_shipping_address')));
                if (isset($pargo_shipping->province)) {
                    switch ($pargo_shipping->province) {
                        case 'Western Cape':
                            $state = 'WC';
                            break;
                        case 'Northern Cape':
                            $state = 'NC';
                            break;
                        case 'Eastern Cape':
                            $state = 'EC';
                            break;
                        case 'Gauteng':
                            $state = 'GP';
                            break;
                        case 'North West':
                            $state = 'NW';
                            break;
                        case 'Mpumalanga':
                            $state = 'MP';
                            break;
                        case 'Free State':
                            $state = 'FS';
                            break;
                        case 'Limpopo':
                            $state = 'LP';
                            break;
                        case 'KwaZulu-Natal':
                            $state = 'KZN';
                            break;

                        default:
                            $state = null;
                            break;
                    }
                }

                //set it
                if (isset($pargo_shipping->address1)) {
                    $woocommerce->customer->set_shipping_address($pargo_shipping->address1);
                }
                if (isset($pargo_shipping->address2)) {
                    $woocommerce->customer->set_shipping_address_2($pargo_shipping->address2);
                }
                if (isset($pargo_shipping->city)) {
                    $woocommerce->customer->set_shipping_city($pargo_shipping->city);
                }
                if (isset($pargo_shipping->province)) {
                    $woocommerce->customer->set_shipping_state($pargo_shipping->province);
                }
                if (!is_null($state)) {
                    $woocommerce->customer->set_shipping_state($state);
                }
                if (isset($pargo_shipping->storeName)) {
                    $woocommerce->customer->set_shipping_company($pargo_shipping->storeName . ' (' . $pargo_shipping->pargoPointCode . ')');
                }
                if (isset($pargo_shipping->postalcode)) {
                    $woocommerce->customer->set_shipping_postcode($pargo_shipping->postalcode);
                }
            }
        }

        /**
         * Draws an element for the selected pickup point
         *
         */
        public function display_selected_pickup_point()
        {
            $html = "<div id=\"pargo-after-cart\"></div>"; // Renders the element for the Vue container to populate
            if (WC()->session->chosen_shipping_methods) {
                $pargoshipping = null;
	            if ( WC()->session->chosen_shipping_methods[0] == 'wp_pargo' ) {
		            $pargoshipping = WC()->session->get( 'pargo_shipping_address' );
		            if ($pargoshipping) {
			            $pargoshipping = json_decode( stripslashes( $pargoshipping ) );
		            }
	            }
	            if (isset($pargoshipping)) {
		            $html = '<div id="pargo-after-cart">
                            <p class="pargo_style_title">Selected Pickup Point: ' . $pargoshipping->storeName . '</p>
                            <img class="pargo_style_image" src="' . $pargoshipping->photo . '" alt="' . $pargoshipping->storeName . '"  />
                            <p class="pargo_style_desc">' . $pargoshipping->addressSms . '</p>
                         </div>';
	            }
            }
            echo $html;
            return;
        }

        /**
         * Place the order to Pargo
         *
         * @param int $order_id
         */
        public function place_pargo_order($order_id)
        {
            $order = wc_get_order($order_id);
            if (!$order) return;
            // only post order if a waybill does not already exist
            if (get_post_meta($order_id, 'pargo_waybill', true)) {
                return;
            }
            // Test the order to see if they are using Pargo
	        $pargoOrders = new Pargo_Orders($this->plugin_name, $this->version);
            if ('processing' === $order->get_status() && $order->has_shipping_method('wp_pargo_home')) {
                $pargoOrders->placeOrder($order, Pargo_Orders::PROCESS_TYPE_W2D);
                return;
            }

            if ('processing' === $order->get_status() && $order->has_shipping_method('wp_pargo')) {
                $pargoOrders->placeOrder($order, Pargo_Orders::PROCESS_TYPE_W2P);
            }
        }

        /**
         * Runs after checkout validation to add meta values to the order that display on Admin
         *
         */
        public function pargo_after_checkout_validation($data, $errors = null)
        {
            $chosen_methods = WC()->session->get('chosen_shipping_methods');
            $chosen_shipping = $chosen_methods[0];
            if ($chosen_shipping != 'wp_pargo') {
                return;
            }

            if (!empty(WC()->session->get('pargo_shipping_address'))) {
                add_action('woocommerce_checkout_update_order_meta', function ($order_id, $data) {
	                $order = wc_get_order($order_id);
                    $pargo_shipping_address = json_decode(stripslashes(WC()->session->get('pargo_shipping_address')), true);
                    $post = $pargo_shipping_address['pargoPointCode'];
                    unset($pargo_shipping_address['pargoPointCode']);
                    unset($pargo_shipping_address['photo']);
                    foreach($pargo_shipping_address as $address_field_name => $address_field_detail) {
                        if (empty($address_field_detail)) {
                            continue;
                        }
	                    $order->update_meta_data('pargo_' . $address_field_name, $address_field_detail);
                    }
                    $pargo_delivery_address = implode(', ', $pargo_shipping_address);
                    $order->update_meta_data('pargo_pc', $post);
                    $order->update_meta_data('pargo_delivery_address', $pargo_delivery_address);
                    $order->save();
                }, 10, 2);
            }
        }

        /**
         * After order completed clean up the sessions
         *
         */
        public function pargo_order_status_completed()
        {
	        if (isset(WC()->session)) {
	            if ( ! empty( WC()->session->get( 'delivery_type' ) ) ) {
		            WC()->session->__unset( 'delivery_type' );
	            }
	            if ( ! empty( WC()->session->get( 'pargo_shipping_address' ) ) ) {
		            WC()->session->__unset( 'pargo_shipping_address' );
	            }
            }
        }

        public function pargo_hide_shipping_based_on_order_weight($rates, $package)
        {
            $max_weight = 15;
            $weight = WC()->cart->get_cart_contents_weight();
            if ($weight > $max_weight) {
                unset($rates['wp_pargo']);
                unset($rates['wp_pargo_home']);
            }

            return $rates;
        }

        /**
         * Get the Pick Up Point address and display it on the order details
         */
        public function account_order_details($order)
        {
            if (get_post_meta($order->get_id(), 'pargo_delivery_address')):
                ?>
                <section
                        class="woocommerce-columns woocommerce-columns--1 woocommerce-columns--addresses col1-set addresses"
                >
                <div class="woocommerce-column woocommerce-column--1 woocommerce-column--shipping-address col-1">
                    <h2 class="woocommerce-column__title">
                        <?php esc_html_e('Pargo Pick Up Point address', 'woocommerce'); ?>
                    </h2>
                    <address>
                        <?php
                            $pup_address = get_post_meta($order->get_id(), 'pargo_delivery_address', true);
                            $pup_address = explode(", ", $pup_address);
                            echo implode(",<br />", $pup_address);
                        ?>
                    </address>
                </div>
            <?php
            endif;
        }

	    /**
         * Checks if the shipping method on the cart page is one of Pargo's and sends analytics event
	     *
	     * @return void
	     */
        public function event_shipping_method_selected_cart( )
        {
            if (isset($_REQUEST['shipping_method'])) {
		        $shipping_method = $_REQUEST['shipping_method'][0];
	            if ( $shipping_method == 'wp_pargo_home' ) {
                    Analytics::submit( 'customer', 'click', 'select_w2d_cart' );
	            }
	            if ( $shipping_method == 'wp_pargo' ) {
                    Analytics::submit( 'customer', 'click', 'select_pup_cart' );
	            }
            }
        }

        /**
         * Checks if the shipping method on the checkout page is one of Pargo's and sends analytics event
	     *
	     * @return void
	     */
        public function event_shipping_method_selected_checkout(  )
        {
            if (isset($_REQUEST['shipping_method'])) {
		        $shipping_method = $_REQUEST['shipping_method'][0];
	            if ( $shipping_method == 'wp_pargo_home' && WC()->session->get( 'chosen_shipping_methods' )[0] !== 'wp_pargo_home' ) {
                    Analytics::submit( 'customer', 'click', 'select_w2d_checkout' );
	            }
	            if ( $shipping_method == 'wp_pargo' && WC()->session->get( 'chosen_shipping_methods' )[0] !== 'wp_pargo' ) {
                    Analytics::submit( 'customer', 'click', 'select_pup_checkout' );
	            }
            }
        }
    }
