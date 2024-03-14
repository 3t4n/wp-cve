<?php

/**
 * Description of OrderFulfillmentController
 *
 * @author Ali2Woo Team
 *
 * @autoload: a2wl_admin_init
 *
 * @ajax: true
 */

namespace AliNext_Lite;;

use WC_Order;
use Automattic\WooCommerce\Utilities\OrderUtil;

class OrderFulfillmentController extends AbstractController
{
    protected static array $shipping_fields = [];
    protected static array $additional_shipping_fields = [];

    public function __construct()
    {
        parent::__construct(A2WL()->plugin_path() . '/view/');
        add_action('admin_init', [$this, 'admin_init']);

        add_filter('a2wl_wcol_bulk_actions_init', array($this, 'bulk_actions'));
        add_action('wp_ajax_a2wl_get_aliexpress_order_data', array($this, 'get_aliexpress_order_data'));

        add_action('wp_ajax_a2wl_load_fulfillment_model', array($this, 'ajax_load_fulfillment_model_html'));
        add_action('wp_ajax_a2wl_load_fulfillment_orders', array($this, 'ajax_load_fulfillment_orders_html'));
        add_action('wp_ajax_a2wl_save_order_shipping_info', array($this, 'ajax_save_order_shipping_info'));

        add_action('wp_ajax_a2wl_fulfillment_place_order', array($this, 'ajax_load_fulfillment_place_order'));

        add_action('wp_ajax_a2wl_update_fulfillment_shipping', array($this, 'ajax_update_fulfillment_shipping'));

        add_action('wp_ajax_a2wl_sync_order_info', array($this, 'ajax_sync_order_info'));

        add_filter('a2wl_fill_additional_shipping_fields', array($this, 'fill_additional_shipping_fields'), 10, 3);
        add_action('a2wl_update_custom_shipping_field', array($this, 'update_custom_shipping_field'), 10, 3);

        add_action( 'init', array($this, 'init') );
    }

    public function admin_init(): void
    {
        if (OrderUtil::custom_orders_table_usage_is_enabled()) {
            $currentPage = $_REQUEST['page'] ?? '';
            if ($currentPage === 'wc-orders') {
                add_action('admin_enqueue_scripts', [$this, 'assets']);
                add_action('admin_footer', [$this, 'place_orders_bulk_popup']);
                add_action('admin_footer', [$this, 'place_shipping_modal']);
            }
        }
        else {
            $post_type = $_GET['post_type'] ?? ($_REQUEST['post_type'] ?? "");
            if (is_admin() && $post_type == "shop_order") {
                add_action('admin_enqueue_scripts', [$this, 'assets']);
                add_action('admin_footer', [$this, 'place_orders_bulk_popup']);
                add_action('admin_footer', [$this, 'place_shipping_modal']);
            }
        }
    }

    public function init() {
        self::$shipping_fields = apply_filters(
            'woocommerce_admin_shipping_fields',
            [
                'first_name' => array(
                    'label' => __( 'First name', 'woocommerce' ),
                    'show'  => false,
                ),
                'last_name'  => array(
                    'label' => __( 'Last name', 'woocommerce' ),
                    'show'  => false,
                ),
                'company'    => array(
                    'label' => __( 'Company', 'woocommerce' ),
                    'show'  => false,
                ),
                'address_1'  => array(
                    'label' => __( 'Address line 1', 'woocommerce' ),
                    'show'  => false,
                ),
                'address_2'  => array(
                    'label' => __( 'Address line 2', 'woocommerce' ),
                    'show'  => false,
                ),
                'city'       => array(
                    'label' => __( 'City', 'woocommerce' ),
                    'show'  => false,
                ),
                'postcode'   => array(
                    'label' => __( 'Postcode / ZIP', 'woocommerce' ),
                    'show'  => false,
                ),
                'country'    => array(
                    'label'   => __( 'Country / Region', 'woocommerce' ),
                    'show'    => false,
                    'type'    => 'select',
                    'class'   => 'js_field-country select short',
                    'options' => array( '' => __( 'Select a country / region&hellip;', 'woocommerce' ) ) + WC()->countries->get_shipping_countries(),
                ),
                'state'      => array(
                    'label' => __( 'State / County', 'woocommerce' ),
                    'class' => 'js_field-state select short',
                    'show'  => false,
                ),
                'phone'      => array(
                    'label' => __( 'Phone', 'woocommerce' ),
                ),
            ],
            false,
            false
        );

        self::$additional_shipping_fields = apply_filters(
            'woocommerce_admin_additional_shipping_fields',
            array(
                'passport_no' => array(
                    'label' => __( 'Passport number', 'ali2woo' ),
                    'show'  => false,
                ),
                'passport_no_date'  => array(
                    'label' => __( 'Passport date', 'ali2woo' ),
                    'show'  => false,
                ),
                'passport_organization'    => array(
                    'label' => __( 'Passport issuing agency', 'ali2woo' ),
                    'show'  => false,
                ),
                'tax_number'  => array(
                    'label' => __( 'Tax number', 'ali2woo' ),
                    'show'  => false,
                ),
                'foreigner_passport_no'  => array(
                    'label' => __( 'Foreign tax number (For Koreans, foreigners must fill in the registration number or passport number)', 'ali2woo' ),
                    'show'  => false,
                ),
                'is_foreigner'       => array(
                    'type'  => 'checkbox',
                    'label' => __( 'Is foreigner?', 'ali2woo' ),
                    'show'  => false,
                ),
                'vat_no'   => array(
                    'label' => __( 'VAT number', 'ali2woo' ),
                    'show'  => false,
                ),
                'tax_company'   => array(
                    'label' => __( 'Company Name', 'ali2woo' ),
                    'show'  => false,
                ),
            )
        );
    }

    public function assets()
    {
        wp_enqueue_style('a2wl-admin-style', A2WL()->plugin_url() . '/assets/css/admin_style.css', array(), A2WL()->version);
        // wp_enqueue_style('a2wl-bootstrap-style', A2WL()->plugin_url() . '/assets/js/bootstrap/css/bootstrap.min.css', array(), A2WL()->version);

        wp_enqueue_script('a2wl-admin-script', A2WL()->plugin_url() . '/assets/js/admin_script.js', array('jquery'),  A2WL()->version);
        wp_enqueue_script('a2wl-ali-orderfulfill-js', A2WL()->plugin_url() . '/assets/js/orderfulfill.js', array('a2wl-admin-script'), A2WL()->version, true);

        wp_enqueue_script('a2wl-sprintf-script', A2WL()->plugin_url() . '/assets/js/sprintf.js', array(), A2WL()->version);

        $lang_data = array(
            'placing_orders_d_of_d' => _x('Placing orders %d/%d...', 'Status', 'ali2woo'),
            'please_wait_data_loads' => _x('Please wait, data loads..', 'Status', 'ali2woo'),
            'process_update_d_of_d_erros_d' => _x('Process update %d of %d. Errors: %d.', 'Status', 'ali2woo'),
            'process_sync_d_of_d_erros_d' => _x('Process sync %d of %d. Errors: %d.', 'Status', 'ali2woo'),
            'complete_result_updated_d_erros_d' => _x('Complete! Result updated: %d; errors: %d.', 'Status', 'ali2woo'),
            'complete_result_sync_d_erros_d' => _x('Complete! Successfully synced: %d; errors: %d.', 'Status', 'ali2woo'),
            'install_chrome_ext' => _x('Please install and connect to your website the Ali2Woo chrome extension to use this feature.', 'Status', 'ali2woo'),
            'please_connect_chrome_extension_check_d' => _x('Please connect the Chrome extension to your store and then continue. Need help? Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'we_found_old_order' => _x('We found an old order fulfillment process and removed it. Press the "Continue" button.', 'Status', 'ali2woo'),
            'login_into_aliexpress_account' => _x('Please switch to AliExpress tab and login into your AliExpress account.', 'Status', 'ali2woo'),
            'detected_old_aliexpress_interface' => _x('Detected old AliExpress interface. Please contact Ali2Woo support.', 'Status', 'ali2woo'),
            'your_customer_address_entered' => _x('Your customer address is entered. Wait...', 'Status', 'ali2woo'),
            'product_is_added_to_cart' => _x('Product (%d) is added to the cart. Wait...', 'Status', 'ali2woo'),
            'all_products_are_added' => _x('All products are added to the cart. Wait...', 'Status', 'ali2woo'),
            'cart_is_cleared' => _x('The previous cart data is cleared. Wait...', 'Status', 'ali2woo'),
            'get_no_responces_from_chrome_ext_d' => _x('Get no responces from the chrome extension for 30s. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'fill_order_note' => _x('Filling order notes...', 'Status', 'ali2woo'),
            'cant_add_product_to_cart_d' => _x('Can`t add this product to the cart. Switch to AliExpress and choose another one or add a similar product from another supplier manually. Then continue. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'please_type_customer_address' => _x('Please switch to AliExpress tab, type the address or skip this order.', 'Status', 'ali2woo'),
            'please_input_captcha' => _x('Please switch to AliExpress and input the Captcha code manually or wait for your Captcha solver to do the job...', 'Status', 'ali2woo'),
            'order_is_placed' => _x('The order is placed. Wait...', 'Status', 'ali2woo'),
            'internal_aliexpress_error' => _x('Internal AliExpress error. Please continue to try again or skip this order.', 'Status', 'ali2woo'),
            'all_orders_are_placed' => _x('All orders are placed! Click "Orders List" to be directed to the orders list on the AliExpress website.', 'Status', 'ali2woo'),
            'cant_process_your_orders' => _x('We can`t process your orders. Check out the "Status Page" for more details.', 'Status', 'ali2woo'),
            'cant_get_order_id' => _x('Can`t get the external order ID, please copy it manually to your WC order. Then continue.', 'Status', 'ali2woo'),
            'payment_is_failed' => _x('The payment is failed, please finish this order manually. Then continue.', 'Status', 'ali2woo'),
            'done_pay_manually' => _x('Please switch to AliExpress and pay for the order.', 'Status', 'ali2woo'),
            'choose_payment_method' => _x('Please switch to AliExpress and choose payment method.', 'Status', 'ali2woo'),

            'please_activate_right_store_apikey_in_chrome' => _x('This website is not connected to the Ali2Woo chrome extension. Please check that you choose right API key.', 'Status', 'ali2woo'),

            'bad_product_id' => _x('Can`t find the WC order with a given ID.', 'Status', 'ali2woo'),
            'no_variable_data' => _x('This order has a variable product but doesn`t contain the variable data for some reason. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'no_product_url' => _x('This order doesn`t contain the `product_url` field for some reason. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'no_ali_products' => _x('No AliExpress products in the current order. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),

            'unknown_error' => _x('Unknown error occured. Please contact support.', 'Status', 'ali2woo'),
            'server_error' => _x('Server error. Continue to try again.', 'Status', 'ali2woo'),
        );

        wp_localize_script('a2wl-ali-orderfulfill-js', 'a2wl_ali_orderfulfill_js', array('lang' => $lang_data));

    }

    public function bulk_actions(array $bulk_actions): array
    {
        $bulk_actions['a2wl_order_place_bulk'] = __("Place on AliExpress", 'ali2woo');
        $bulk_actions['a2wl_order_sync_bulk'] = __("Sync with AliExpress", 'ali2woo');

        return $bulk_actions;
    }

    public function fill_additional_shipping_fields($additional_shipping_fields, $order, $country = false)
    {
        if (!$country) {
            $country = $this->get_order_shipping_to_country($order);
        }

        $rutMetaKey = get_setting('fulfillment_rut_meta_key', '');

        $custom_attributes = [];

        $value = '';

        if (empty($rutMetaKey)) {
            $custom_attributes['disabled'] = 'disabled';
        } else {
            $value = get_post_meta($order->get_id(), $rutMetaKey, true);
        }

        $additional_shipping_fields['rut'] = [
            'id' => 'rut',
            'description' => __('You have to configure RUT meta field in the plugin settings',  'ali2woo'),
            'desc_tip' => true,
            'label' => __( 'RUT', 'ali2woo' ),
            'show'  => false,
            'wrapper_class' => '_shipping_rut '.($country !== 'CL' ? 'hidden' : ''),
            'custom_attributes' => $custom_attributes,
            'value' => $value,
            'custom' => [
                'meta_key' => $rutMetaKey
            ]
        ];

        $cpfMetaKey = get_setting('fulfillment_cpf_meta_key', '');

        $custom_attributes = [];

        $value = '';

        if (empty($cpfMetaKey)) {
            $custom_attributes['disabled'] = 'disabled';
        } else {
            $value = get_post_meta($order->get_id(), $cpfMetaKey, true);
        }

        $additional_shipping_fields['cpf'] = [
            'id' => 'cpf',
            'description' => __('You have to configure CPF meta field in the plugin settings',  'ali2woo'),
            'desc_tip' => true,
            'label' => __( 'CPF', 'ali2woo' ),
            'show'  => false,
            'wrapper_class' => '_shipping_cpf '.($country !== 'BR' ? 'hidden' : ''),
            'custom_attributes' => $custom_attributes,
            'value' => $value,
            'custom' => [
                'meta_key' => $cpfMetaKey
            ]
        ];

        return $additional_shipping_fields;
    }

    public function update_custom_shipping_field($key, $field, $order)
    {
        if (in_array($key, ['rut', 'cpf']) && $field['custom']['meta_key'] && !empty($_POST[$key])) {
            update_post_meta($order->get_id(), $field['custom']['meta_key'], $_POST[$key]);
        }
    }

    public function place_orders_bulk_popup()
    {
        $this->include_view('includes/place_orders_bulk_popup.php');
    }

    public function place_shipping_modal()
    {
        $this->model_put('countries', WC()->countries->get_countries());
        $this->include_view('includes/shipping_modal.php');
    }

    public function get_aliexpress_order_data()
    {

        $result = array("state" => "ok", "data" => "", "action" => "");

        $post_id = $_POST['id'] ?? false;

        if (!$post_id) {
            $result['state'] = 'error';
            $result['error_code'] = -1;
            echo json_encode($result);
            wp_die();
        }

        $order = new WC_Order($post_id);

        $def_prefship = get_setting('fulfillment_prefship');
        $def_customer_note = get_setting('fulfillment_custom_note');
        $def_phone_number = get_setting('fulfillment_phone_number');
        $def_phone_code = get_setting('fulfillment_phone_code');

        $content = array('id' => $post_id,
            'defaultShipping' => $def_prefship,
            'note' => $def_customer_note !== "" ? $def_customer_note : $this->get_customer_note($order),
            'products' => array(),
            'countryRegion' => $this->get_country_region($order),
            'region' => strtolower($this->get_region($order)),
            'city' => $this->get_city($order),
            'contactName' => $this->get_contactName($order),
            'address1' => $this->get_address1($order),
            'address2' => $this->get_address2($order),
            'mobile' => $def_phone_number !== "" ? $def_phone_number : $this->get_phone($order),
            'mobile_code' => $def_phone_code !== "" ? $def_phone_code : '',
            'zip' => $this->get_zip($order),
            'autopay' => false /* todo: rudiment option remove it*/,
            'awaitingpay' => false /* todo: rudiment option remove it*/,
            'cpf' => $this->get_cpf($order),
            'storeurl' => get_site_url(),
            'currency' => $this->get_currency($order),
        );

        $items = $order->get_items();

        $k = 0;
        $total = 0;
        foreach ($items as $item) {

            $normalized_item = new WooCommerceOrderItem($item);
            $product_id = $normalized_item->get_product_id();
            $variation_id = $normalized_item->get_variation_id();
            $quantity = $normalized_item->get_quantity();

            $external_id = get_post_meta($product_id, '_a2w_external_id', true);

            if ($external_id) {

                $skuArray = $this->getSkuArray($normalized_item);

                if (empty($skuArray) && $variation_id && $variation_id > 0) {
                    $result['error_code'] = -2;
                    $result['state'] = 'error';
                    echo json_encode($result);
                    wp_die();
                }

                $original_url = get_post_meta($product_id, '_a2w_product_url', true);

                if (empty($original_url)) {
                    $result['error_code'] = -3;
                    $result['state'] = 'error';
                    echo json_encode($result);
                    wp_die();
                }

                //try to use shipping method that user choose on the product page, cart or checkout
                //if it returns empty, then keep it
                //because chrome extension chosoe default shipping method in this case
                $shipping_service_name = $normalized_item->get_ali_shipping_code();

                //todo: make an ability to change the shipping method
                //before place order on AliExpress

                $content['products'][$k] = array(
                    'url' => $original_url,
                    'productId' => $external_id,
                    'originalId' => $product_id,
                    'qty' => $quantity,
                    'sku' => $skuArray,
                    'shipping' => $shipping_service_name,
                );

                $k++;
            }

            $total++;
        }

        if ($k < 1) {
            $result['error_code'] = -4;
            $result['state'] = 'error';
            echo json_encode($result);
            wp_die();
        }

        if ($k == $total) {
            $result['action'] = 'upd_ord_status';
        }

        $result['data'] = array('content' => $content, 'id' => $post_id);

        echo json_encode($result);
        wp_die();
    }

    private function format_field($str): string
    {
        $str = trim($str);

        if (!empty($str)) {
            $str = ucwords(strtolower($str));
        }

        return $str;
    }

    private function get_currency($order): string
    {
        return strtolower($order->get_currency());
    }

    private function get_cpf($order)
    {
        $b_cpf = $order->get_meta('_billing_cpf');
        $s_cpf = $order->get_meta('_shipping_cpf');

        $cpf = $b_cpf ?: ($s_cpf ?: '');

        return $cpf ? preg_replace("/[^0-9]/", "", $cpf) : '';
    }

    private function get_phone($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->billing_phone ?: $order->shipping_phone;
        } else {
            $result = $order->get_billing_phone();
        }

        return preg_replace('/[^0-9]+/', '', $result);
    }

    private function get_customer_note($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->customer_note;
        } else {
            $result = $order->get_customer_note();
        }

        return $this->translitirate($result);
    }

    private function get_country_region($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_country ? $this->format_field_country($order->shipping_country) : $this->format_field_country($order->billing_country);
        } else {
            $result = $order->get_shipping_country() ? $this->format_field_country($order->get_shipping_country()) : $this->format_field_country($order->get_billing_country());
        }

        return $this->translitirate($result);
    }

    private function get_region($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_state ? $this->format_field_state($order->shipping_country, $order->shipping_state) : $this->format_field_state($order->billing_country, $order->billing_state);
        } else {
            $result = $order->get_shipping_state() ? $this->format_field_state($order->get_shipping_country(), $order->get_shipping_state()) : $this->format_field_state($order->get_billing_country(), $order->get_billing_state());
        }

        return $this->translitirate($result);
    }

    private function get_city($order)
    {

        if (WC()->version < '3.0.0') {
            $result = $order->shipping_city ? $this->format_field($order->shipping_city) : $this->format_field($order->billing_city);
        } else {
            $result = $order->get_shipping_city() ? $this->format_field($order->get_shipping_city()) : $this->format_field($order->get_billing_city());
        }

        return $this->translitirate($result);
    }

    private function get_contactName($order)
    {

        if (WC()->version < '3.0.0') {

            if ($order->shipping_first_name) {
                $result = $order->shipping_first_name . ' ' . $order->shipping_last_name;

                if (isset($this->shipping_third_name)) {
                    $result .= ' ' . $order->shipping_third_name;
                }
            } else {
                $result = $order->billing_first_name . ' ' . $order->billing_last_name;

                if (isset($this->billing_third_name)) {
                    $result .= ' ' . $order->billing_third_name;
                }
            }

        } else {
            $result = $order->get_shipping_first_name() ? $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() . ' ' . $order->get_meta('_shipping_third_name') : $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() . ' ' . $order->get_meta('_billing_third_name');
        }

        return $this->translitirate($result);
    }

    private function get_address_number($order)
    {
        $b_number = $order->get_meta('_billing_number');
        $s_number = $order->get_meta('_shipping_number');

        $number = $b_number ?: ($s_number ?: '');

        return $number ? preg_replace("/[^0-9]/", "", $number) : '';
    }

    private function get_address1($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_address_1 ?: $order->billing_address_1;
        } else {
            $result = $order->get_shipping_address_1() ? $order->get_shipping_address_1() : $order->get_billing_address_1();
        }

        //Add street number if it's available
        $result = $result . " " . $this->get_address_number($order);

        return $this->translitirate($result);
    }

    private function get_address2($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_address_2 ?: $order->billing_address_2;
        } else {
            $result = $order->get_shipping_address_2() ? $order->get_shipping_address_2() : $order->get_billing_address_2();
        }

        return $this->translitirate($result);
    }

    private function get_zip($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_postcode ?: $order->billing_postcode;
        } else {
            $result = $order->get_shipping_postcode() ? $order->get_shipping_postcode() : $order->get_billing_postcode();
        }

        return $result;
    }

    private function format_field_country($str): string
    {
        $str = trim($str);

        if (!empty($str)) {
            $str = strtoupper($str);
        }

        if ($str === "GB") {
            $str = "UK";
        }

        if ($str == "RS") {
            $str = "SRB";
        }

        if ($str == "ME") {
            $str = "MNE";
        }

        return $str;
    }

    private function format_field_state($country_code, $state_code): string
    {
        if (isset(WC()->countries->states[$country_code]) && isset(WC()->countries->states[$country_code][$state_code])) {
            $result = $this->format_field(WC()->countries->states[$country_code][$state_code]);
        } else {
            $result = $state_code;
        }

        //WooCommerce translation file has html entities
        return html_entity_decode($result, ENT_QUOTES, 'UTF-8');
    }

    private function getSkuArray($item): array
    {
        if ($item->get_variation_id() !== 0) {
            $variation_id = $item->get_variation_id();
            $sku = $this->getSkuArrayByVariationID($variation_id);

        } else {
            $product_id = $item->get_product_id();
            $sku = $this->getSkuArrayByVariationID($product_id);

            // if (empty($sku)){
            //     // Backward-compatible code to get sku data for Simple type product
            //     $handle=new \WC_Product_Variable($product_id);
            //     if ($handle){
            //         $variations_ids=$handle->get_children();
            //         if ($variations_ids && count($variations_ids) > 0){
            //             $first_variation_id = $variations_ids[0];
            //             $sku = $this->getSkuArrayByVariationID($first_variation_id);
            //         }
            //     }
            // }
        }
        return $sku;
    }

    private function getSkuArrayByVariationID($variation_id): array
    {
        $sku = array();

        $external_var_data = get_post_meta($variation_id, '_aliexpress_sku_props', true);

        if (empty($external_var_data)) {
            return $sku;
        }

        if ($external_var_data) {
            $items = explode(';', $external_var_data);

            foreach ($items as $item) {
                list(, $sku[]) = explode(':', $item);
            }
        }

        return $sku;
    }

    private function translitirate($result)
    {
        if (get_setting('order_translitirate')) {
            $result = Utils::safeTransliterate($result);
        }

        return $result;
    }

    public function ajax_load_fulfillment_model_html()
    {
        
        
        $purchase_code = 1;
        
        ?>
        <div class="modal-overlay modal-fulfillment">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php _e('Order fulfillment', 'ali2woo');?></h3>
                    <a class="modal-btn-close" href="#"></a>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <?php if($purchase_code):?>
                    <div style="display: inline-block;">
                    <a id="pay-for-orders" target="_blank" class="btn btn-success" href="https://www.aliexpress.com/p/order/index.html" title="<?php _e('You will be redirected to the AlIExpress portal. You must be authorized in your account to make the payment', 'ali2woo');?>"><?php _e('Pay for order(s)', 'ali2woo');?></a>
                    <button id="fulfillment-auto" class="btn btn-success" type="button">
                        <div class="btn-icon-wrap cssload-container"><div class="cssload-speeding-wheel"></div></div>
                        <?php _e('Fulfil orders automatically', 'ali2woo');?>
                    </button>
                    </div>

                    <?php endif; ?>

                    <?php /*
                    <?php if($purchase_code):?>
                    <button id="fulfillment-chrome" class="btn btn-success" type="button">
                        <div class="btn-icon-wrap cssload-container"><div class="cssload-speeding-wheel"></div></div>
                        <?php _e('Fulfil orders via Chrome extension', 'ali2woo');?>
                    </button>
                    <?php endif; ?>
                    */ ?>
                    <button class="btn btn-default modal-close" type="button"><?php _e('Close');?></button>
                </div>
            </div>
        </div>
    <?php wp_die();
    }

    private function get_sign_urls($urls){

        if (a2wl_check_defined('A2WL_DEMO_MODE')){
            return [];
        } 
        
        $payload = [
            "urls" => $urls,
        ];

        $args = [];

        $request_url = RequestHelper::build_request('sign_urls');
        $request = a2wl_remote_post($request_url, $payload, $args);

        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else {
            if (intval($request['response']['code']) == 200) {
                $result = json_decode($request['body'], true);
            } else {
                $result = ResultBuilder::buildError($request['response']['code'] . ' - ' . $request['response']['message']);
            }
        }

        if ($result['state'] == 'error') {
            $result = [];
        } else {
            $result = $result['urls'];
        }

        return $result;
    }

    public function get_order_shipping_to_country($order): string
    {
        $shipping_address = $order->get_address('shipping');
        if (empty($shipping_address['country'])) {
            $shipping_address = $order->get_address('billing');
        }

        return ProductShippingMeta::normalize_country($shipping_address['country']);
    }

    public function ajax_load_fulfillment_orders_html()
    {
        global $thepostid;
        $old_thepostid = $thepostid;

        $ids = array_map('intval', isset($_POST['ids']) ? (is_array($_POST['ids']) ? $_POST['ids'] : array($_POST['ids'])) : array());

        $orders = array();
        if (!empty($ids)) {
            foreach ($ids as $order_id) {
                $orders[] = new WC_Order($order_id);
            }
        }

        $is_wpml = false;
        global $sitepress;
        if (is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
            $default_lang = apply_filters('wpml_default_language', null);
            $current_language = apply_filters('wpml_current_language', null);
            if ($current_language && $current_language !== $default_lang) {
                $is_wpml = true;
            }
        }

        
        $orders_data = array();
        foreach ($orders as $order) {
            // copied from woocommerce/includes/admin/list-tables/class-wc-admin-list-table-orders.php
            $buyer = '';
            if ($order->get_billing_first_name() || $order->get_billing_last_name()) {
                /* translators: 1: first name 2: last name */
                $buyer = trim(sprintf(_x('%1$s %2$s', 'full name', 'woocommerce'), $order->get_billing_first_name(), $order->get_billing_last_name()));
            } elseif ($order->get_billing_company()) {
                $buyer = trim($order->get_billing_company());
            } elseif ($order->get_customer_id()) {
                $user = get_user_by('id', $order->get_customer_id());
                $buyer = ucwords($user->display_name);
            }

            /**
             * Filter buyer name in list table orders.
             *
             * @since 3.7.0
             * @param string   $buyer Buyer name.
             * @param WC_Order $order Order data.
             */
            $order_data['buyer'] = apply_filters('woocommerce_admin_order_buyer_name', $buyer, $order);


            $shipping_address = $order->get_address('shipping');
            if (empty($shipping_address['country'])) {
                $shipping_address = $order->get_address();
            }
            $countries = WC()->countries->get_countries();
            $formatted_address = WC()->countries->get_formatted_address($shipping_address, ', ');

            $shiping_to_country = ProductShippingMeta::normalize_country($shipping_address['country']);

            $order_data = array(
                'order_id' => $order->get_id(),
                'order_number' => $order->get_order_number(),
                'order' => $order,
                'buyer' => $buyer,
                'currency' => $order->get_currency(),
                'shiping_to_country' => $shiping_to_country,
                'shipping_address' => $shipping_address,
                'formatted_address' => $formatted_address,
                'total_cost' => 0,
                'items' => array(),
            );

            foreach ($order->get_items() as $item) {
                /**
                 * todo: here can be a case when product is already deleted in Woocommerce
                 * in this case the order item can be retrieved.
                 */
                $a2wl_order_item = new WooCommerceOrderItem($item);

                if(!$a2wl_order_item->get_external_product_id()){
                    continue;
                }

                $product = $item->get_product();

                $image = $product->get_image();

                $product_id = $item->get_product_id();
                $variation_id = $item->get_variation_id();

                $shipping_meta = new ProductShippingMeta($product_id);

                $shipping_info = Utils::get_product_shipping_info($product, $item->get_quantity(), $shiping_to_country, false);

                $current_shipping_company = '';
                $current_delivery_time = '-';
                $current_shipping_cost = '';
                $shipping_meta_data = $item->get_meta(Shipping::get_order_item_shipping_meta_key());

                if ($shipping_meta_data) {
                    $shipping_meta_data = json_decode($shipping_meta_data, true);
                    $current_shipping_company = $shipping_meta_data['service_name'];
                    $current_delivery_time = $shipping_meta_data['delivery_time'];
                    $current_shipping_cost = $shipping_meta_data['shipping_cost'];
                }
                $current_shipping_company = $current_shipping_company ?: $shipping_info['default_method'];

                $wpml_product_id = $wpml_variation_id = '';
                if ($is_wpml) {
                    $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
                    if ($wpml_object_id != $product_id) {
                        $wpml_product = wc_get_product($wpml_object_id);
                        if ($wpml_product) {
                            $wpml_product_id = $wpml_object_id;
                        }
                    }
                    if ($product_id) {
                        $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
                        if ($wpml_object_id != $product_id) {
                            $wpml_variation = wc_get_product($wpml_object_id);
                            if ($wpml_variation) {
                                $wpml_variation_id = $wpml_object_id;
                            }
                        }
                    }
                }

                if ($wpml_product_id) {
                    $aliexpress_product_id = get_post_meta($wpml_product_id, '_a2w_external_id', true);
                    $item_original_url = get_post_meta($wpml_product_id, '_a2w_original_product_url', true);
                } else {
                    $aliexpress_product_id = get_post_meta($product_id, '_a2w_external_id', true);
                    $item_original_url = get_post_meta($product_id, '_a2w_original_product_url', true);
                }

                $aliexpress_price = $this->get_aliexpress_price($item, $is_wpml);

                $attributes = array();
                if ($meta_data = $item->get_formatted_meta_data('')) {
                    foreach ($meta_data as $meta_id => $meta) {
                        if (substr($meta->key, 0, 3) !== "pa_") {
                            continue;
                        }
                        $attributes[] = force_balance_tags($meta->display_value);
                    }
                }

                $total_cost = $aliexpress_price * $item->get_quantity() + ($current_shipping_cost ? $current_shipping_cost : 0);

                $order_data['items'][] = array(
                    'order_item_id' => $item->get_id(),
                    'product_id' => $item->get_product_id(),
                    'image' => $image,
                    'name' => $item->get_name(),
                    'url' => $item_original_url,
                    'sku' => $product->get_sku(),
                    'attributes' => implode(' / ', $attributes),
                    'cost' => $aliexpress_price,
                    'quantity' => $item->get_quantity(),
                    'shipping_items' => $shipping_info['items'],
                    'current_shipping' => $current_shipping_company,
                    'current_delivery_time' => $current_delivery_time,
                    'current_shipping_cost' => $current_shipping_cost,
                    'total_cost' => $total_cost,
                );

                $order_data['total_cost'] += $total_cost;
            }

            if($order_data['items']) {
                $orders_data[] = $order_data;    
            }
        }
         if (empty($orders_data)) {
            echo '<div class="empty">' . __("Orders not found", 'ali2woo') . '</div>';
        } else {
            foreach ($orders_data as $order_data) {
                $urls = array_column($order_data['items'], 'url');
                $urls = $this->get_sign_urls($urls);
                $urls_to_data = implode(';', $urls);

                echo '<div class="single-order-wrap" data-order_id="' . esc_attr($order_data['order_id']) . 
                '", data-shiping_to_country="' . $order_data['shiping_to_country'] . 
                '", data-urls="' . $urls_to_data . '">';

                echo '<div  class="order-info">';
                echo '<div class="order-name">';
                echo '<strong>' . __('Order', 'ali2woo') . ': </strong>';
                if ($order->get_status() === 'trash') {
                    echo '<strong>#' . esc_attr($order_data['order_number']) . ' ' . esc_html($order_data['buyer']) . '</strong>';
                } else {
                    echo '<a target="_blank" href="' . esc_url(admin_url('post.php?post=' . absint($order_data['order_id'])) . '&action=edit') . '" class="order-view"><strong>#' . esc_attr($order_data['order_number']) . ' ' . esc_html($order_data['buyer']) . '</strong></a>';
                }
                echo '</div>';
                echo '<div class="order-ship-to">';
                echo '<strong>' . __('Ship to', 'ali2woo') . ': </strong><span title="' . esc_attr($order_data['formatted_address']) . '">' . (isset($countries[$order_data['shipping_address']['country']]) ? $countries[$order_data['shipping_address']['country']] : $order_data['formatted_address']) . '</span> <a href="#" class="edit">' . __('Edit') . '</a>';
                echo '</div>';
                echo '<div class="order-total">';
                echo '<strong>' . __('Total cost', 'ali2woo') . ': </strong><span class="total">' . wc_price($order_data['total_cost'], array('currency' => $order_data['currency'])) . '</span>';
                echo '</div>';
                echo '<div class="order-message"></div>';
                echo '</div>';
                echo '<div class="order-edit-address-form">';
                $thepostid = $order_data['order_id'];
                foreach ( self::$shipping_fields as $key => $field ) {
                    if ( ! isset( $field['type'] ) ) {
                        $field['type'] = 'text';
                    }
                    if ( ! isset( $field['id'] ) ) {
                        $field['id'] = '_shipping_' . $key;
                    }

                    $field_name = 'shipping_' . $key;

                    if ( is_callable( array( $order_data['order'], 'get_' . $field_name ) ) ) {
                        $field['value'] = $order_data['order']->{"get_$field_name"}( 'edit' );
                    } else {
                        $field['value'] = $order_data['order']->get_meta( '_' . $field_name );
                    }

                    //we need to set the global post for woocommerce_wp_select and other functions below
                    global $post; 
                    $post = get_post( $thepostid );
                    setup_postdata( $post );

                    switch ( $field['type'] ) {
                        case 'select':
                            woocommerce_wp_select( $field );
                            break;
                        case 'checkbox':
                            woocommerce_wp_checkbox( $field );
                            break;
                        default:
                            woocommerce_wp_text_input( $field );
                            break;
                    }

                    wp_reset_postdata();
                }
                echo '<h4>Additional fields</h4>';

                $additional_shipping_fields = apply_filters('a2wl_fill_additional_shipping_fields', self::$additional_shipping_fields, $order);

                foreach ($additional_shipping_fields as $key => $field ) {
                    if ( ! isset( $field['type'] ) ) {
                        $field['type'] = 'text';
                    }
                    if ( ! isset( $field['id'] ) ) {
                        $field['id'] = '_shipping_' . $key;
                    }

                    $field_name = 'shipping_' . $key;

                    if (empty($field['value'])) {
                        if (is_callable(array($order_data['order'], 'get_' . $field_name))) {
                            $field['value'] = $order_data['order']->{"get_$field_name"}('edit');
                        } else {
                            $field['value'] = $order_data['order']->get_meta('_' . $field_name);
                        }
                    }

                    switch ( $field['type'] ) {
                        case 'select':
                            woocommerce_wp_select( $field );
                            break;
                        case 'checkbox':
                            woocommerce_wp_checkbox( $field );
                            break;
                        default:
                            woocommerce_wp_text_input( $field );
                            break;
                    }
                }
                echo '<button id="save-order-address" class="btn btn-success" type="button">' . __('Save') . '</button>';
                echo '</div>';
                echo '<table class="wp-list-table widefat striped table-view-list fulfillment-order-items">';
                echo '<thead>';
                echo '<tr><th colspan="2" class="name">' . __('Item', 'ali2woo') . '</th><th class="shipping_company">' . __('Shipping Company', 'ali2woo') . '</th><th class="delivery_time">' . __('Delivery Time', 'ali2woo') . '</th><th class="shipping_cost">' . __('Shipping Cost', 'ali2woo') . '</th><th class="cost">' . __('Cost', 'ali2woo') . '</th><th class="total">' . __('Total', 'ali2woo') . '</th><th class="actions"></th></tr>';
                echo '</thead>';
                echo '<body>';
                foreach ($order_data['items'] as $item) {
                    $external_id = get_post_meta($item['product_id'], '_a2w_external_id', true);
                    $shipping_meta = new ProductShippingMeta($item['product_id']);
                    $shipping_cost = $shipping_meta->get_cost();
                    $shipping_country_from = $shipping_meta->get_country_from();
                    $shipping_country_from_list = ProductShippingMeta::get_country_from_list($item['product_id']);
                    $shipping_method = $shipping_meta->get_method();

                    echo '<tr data-order_item_id="' . esc_attr($item['order_item_id']) . '">';

                    echo '<td class="photo">' . Utils::wp_kses_post($item['image']) . '</td>';
                    echo '<td class="name">';
                    echo '<a target="_blank" href="#">' . esc_html($item['name']) . '</a>';

                    if ($attributes) {
                        echo '<div class="info attributes">';
                        echo '<strong>' . __('Attribute', 'ali2woo') . ': </strong><div>' . Utils::wp_kses_post($item['attributes']) . '</div>';
                        echo '</div>';
                    }

                    echo '<div class="info sku">';
                    echo '<strong>' . __('Sku', 'ali2woo') . ': </strong>' . esc_html($item['sku']);
                    echo '</div>';
                    echo '<div class="item-message"></div>';
                    echo '</td>';
                    echo '<td class="shipping_company">';
                    echo '<select class="current-shipping-company">';
                    foreach ($item['shipping_items'] as $si) {
                        echo '<option value="' . $si['serviceName'] . '" ' . ($si['serviceName'] == $item['current_shipping'] ? ' selected="selected"' : '') . '>' . $si['company'] . ' (' . $si['time'] . 'days, ' . $si['freightAmount']['formatedAmount'] . ')</option>';
                    }
                    echo '</select>';
                    echo '<a href="#" class="reload-companies a2wl-shipping-update-global" title="' . esc_attr__('Update Shipping Companies', 'ali2woo') . '" data-country_from_list="' . esc_attr(wp_json_encode($shipping_country_from_list)) . '" data-external_id="' . esc_attr($external_id) . '" data-country_from="' . esc_attr($shipping_country_from) . '" data-country_to="' . esc_attr($shiping_to_country) . '" data-shipping_method="' . esc_attr($shipping_method) . '"></a>';
                    echo '</td>';
                    echo '<td class="delivery_time">';
                    echo esc_html($item['current_delivery_time'] . ' days');
                    echo '</td>';
                    echo '<td class="shipping_cost">';
                    echo $item['current_shipping_cost'] ? wc_price($item['current_shipping_cost'], array('currency' => $order_data['currency'])) : 'Free Shipping';
                    echo '</td>';
                    echo '<td class="cost">';
                    echo wc_price($item['cost'], array('currency' => $order_data['currency'])) . ' x ' . esc_html($item['quantity']) . ' = <strong>' . wc_price($item['cost'] * $item['quantity'], array('currency' => $order_data['currency'])) . '</strong>';
                    echo '</td>';
                    echo '<td class="total_cost">';
                    echo '<strong>' . wc_price($item['total_cost'], array('currency' => $order_data['currency'])) . '</strong>';
                    echo '</td>';
                    echo '<td class="actions">';
                    echo '<a class="remove-item" href="#"></a>';
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</body>';
                echo '</table>';

                echo '</div>';
            }
        }
        
        $thepostid = $old_thepostid;

        wp_die();
    }

    public function ajax_save_order_shipping_info() {
        $shiping_to_country = false;

        if(isset($_POST['_shipping_country'])) {
            $shiping_to_country = ProductShippingMeta::normalize_country($_POST['_shipping_country']);
        }

        if(!isset($_POST['order_id'])) {
            $result=array('state'=>'error', 'message'=>'waiting for order id');
        } else{
            // Get order object.
            $order = wc_get_order( $_POST['order_id'] );
            $props = array();

            $additional_shipping_fields = apply_filters('a2wl_fill_additional_shipping_fields', self::$additional_shipping_fields, $order, $shiping_to_country);
            $shipping_fields = array_merge(self::$shipping_fields, $additional_shipping_fields);

            // Update shipping fields.
            if ( ! empty( $shipping_fields ) ) {
                foreach ( $shipping_fields as $key => $field ) {
                    if ( ! isset( $field['id'] ) ) {
                        $field['id'] = '_shipping_' . $key;
                    }

                    if ( ! isset( $_POST[ $field['id'] ] ) ) {
                        continue;
                    }

                    if (!empty($field['custom'])) {
                        do_action('a2wl_update_custom_shipping_field', $key, $field, $order);
                    } else {
                        if (is_callable(array($order, 'set_shipping_' . $key))) {
                            $props['shipping_' . $key] = wc_clean(wp_unslash($_POST[$field['id']]));
                        } else {
                            $order->update_meta_data($field['id'], wc_clean(wp_unslash($_POST[$field['id']])));
                        }
                    }

                }
            }

            // Save order data.
            $order->set_props( $props );
            $order->save();

            if($shiping_to_country) {
                foreach ($order->get_items() as $item) {
                    $product = $item->get_product();

                    $shipping_info = Utils::get_product_shipping_info($product, $item->get_quantity(), $shiping_to_country, false);

                    $shipping_meta_data = $item->get_meta(Shipping::get_order_item_shipping_meta_key());
                    $shipping_meta_data = $shipping_meta_data ? json_decode($shipping_meta_data, true) : array('company' => '', 'service_name' => '', 'delivery_time' => '', 'shipping_cost' => '', 'quantity' => $item->get_quantity(), 'cost_added' => true);
                    foreach ($shipping_info['items'] as $si) {
                        if ($si['serviceName'] == $shipping_info['default_method']) {
                            $shipping_meta_data['company'] = $si['company'];
                            $shipping_meta_data['service_name'] = $si['serviceName'];
                            $shipping_meta_data['shipping_cost'] = $si['freightAmount']['value'];
                            $shipping_meta_data['delivery_time'] = $si['time'];  
                        }
                    }

                    $item->update_meta_data(Shipping::get_order_item_shipping_meta_key(), json_encode($shipping_meta_data));
                    $item->save_meta_data();
                }
            }

            $result = array('state'=>'ok');
        }

        echo json_encode($result);
        wp_die();
    }

    public function ajax_update_fulfillment_shipping()
    {
        $result = ResultBuilder::buildError('Shipping method not found');

        $is_wpml = false;
        global $sitepress;
        if (is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
            $default_lang = apply_filters('wpml_default_language', null);
            $current_language = apply_filters('wpml_current_language', null);
            if ($current_language && $current_language !== $default_lang) {
                $is_wpml = true;
            }
        }

        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $shiping_to_country = $_POST['shiping_to_country'] ?? false;
        $items = isset($_POST['items']) && is_array($_POST['items']) ? $_POST['items'] : array();

        $order_items = array();
        foreach ($items as $item) {
            $order_items[$item['order_item_id']] = $item['shipping'];
        }

        $total_order_price = 0;
        if ($shiping_to_country && $order_id) {
            $order = new WC_Order($order_id);
            $result_items = array();
            foreach ($order->get_items() as $item) {
                if (isset($order_items[$item->get_id()])) {
                    $shipping = $order_items[$item->get_id()];

                    $aliexpress_price = $this->get_aliexpress_price($item, $is_wpml);

                    $product = $item->get_product();
                    $product_id = $item->get_product_id();

                    $shipping_meta = new ProductShippingMeta($product_id);
                    $shipping_info = Utils::get_product_shipping_info($product, $item->get_quantity(), $shiping_to_country, false);

                    $shipping_meta_data = $item->get_meta(Shipping::get_order_item_shipping_meta_key());
                    $shipping_meta_data = $shipping_meta_data ? json_decode($shipping_meta_data, true) : array('company' => '', 'service_name' => '', 'delivery_time' => '', 'shipping_cost' => '', 'quantity' => $item->get_quantity(), 'cost_added' => true);
                    $current_shipping_cost = 0;
                    foreach ($shipping_info['items'] as $si) {
                        if ($si['serviceName'] == $shipping) {
                            $current_shipping_cost = $si['freightAmount']['value'];

                            $shipping_meta_data['company'] = $si['company'];
                            $shipping_meta_data['service_name'] = $si['serviceName'];
                            $shipping_meta_data['shipping_cost'] = $si['freightAmount']['value'];
                            $shipping_meta_data['delivery_time'] = $si['time'];

                            $result_items[] = array(
                                'order_item_id' => $item->get_id(),
                                'shiping_time' => $si['time'] . ' days',
                                'shiping_price' => wc_price($si['freightAmount']['value'], array('currency' => $order->get_currency())),
                                'total_item_price' => wc_price($aliexpress_price * $item->get_quantity() + $si['freightAmount']['value'], array('currency' => $order->get_currency())),
                            );
                        }
                    }

                    $item->update_meta_data(Shipping::get_order_item_shipping_meta_key(), json_encode($shipping_meta_data));
                    $item->save_meta_data();

                    $total_order_price += $aliexpress_price * $item->get_quantity() + $current_shipping_cost;
                }
            }

            $result = ResultBuilder::buildOk(array('result' => array(
                'order_id' => $order_id,
                'total_order_price' => wc_price($total_order_price, array('currency' => $order->get_currency())),
                'items' => $result_items,
            )));
        } else {
            $result = ResultBuilder::buildError('wrong params');
        }

        echo json_encode($result);
        wp_die();
    }

    public function get_aliexpress_price($order_item, $is_wpml = false)
    {
        $product_id = $order_item->get_product_id();
        $variation_id = $order_item->get_variation_id();

        $wpml_product_id = $wpml_variation_id = '';
        if ($is_wpml) {
            global $sitepress;
            $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
            if ($wpml_object_id != $product_id) {
                $wpml_product = wc_get_product($wpml_object_id);
                if ($wpml_product) {
                    $wpml_product_id = $wpml_object_id;
                }
            }
            if ($product_id) {
                $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
                if ($wpml_object_id != $product_id) {
                    $wpml_variation = wc_get_product($wpml_object_id);
                    if ($wpml_variation) {
                        $wpml_variation_id = $wpml_object_id;
                    }
                }
            }
        }
        if ($wpml_variation_id) {
            $aliexpress_price = get_post_meta($wpml_product_id, '_aliexpress_price', true);
        } else if ($variation_id) {
            $aliexpress_price = get_post_meta($variation_id, '_aliexpress_price', true);
        } else if ($wpml_product_id) {
            $aliexpress_price = get_post_meta($wpml_product_id, '_aliexpress_price', true);
        } else {
            $aliexpress_price = get_post_meta($product_id, '_aliexpress_price', true);
        }

        return $aliexpress_price;
    }


    public function ajax_load_fulfillment_place_order()
    {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $items = isset($_POST['items']) && is_array($_POST['items']) ? array_map('intval', $_POST['items']) : array();

        if ($order_id && $items) {
            $order = new WC_Order($order_id);
            $order_items = array();
            foreach ($order->get_items() as $order_item) {
                if (in_array($order_item->get_id(), $items)) {
                    $order_items[] = $order_item;
                }
            }

            $Aliexpress = new Aliexpress();
            $result = $Aliexpress->place_order(['order' => $order, 'order_items' => $order_items]);
        } else {
            $result = ResultBuilder::buildError('wrong params');
        }

        echo json_encode($result);
        wp_die();
    }

    public function ajax_sync_order_info()
    {
        if(empty($_POST['order_id'])){
            $result = ResultBuilder::buildError('wrong params');
        }else{
            /**
             * @var Woocommerce $wc_api
             */
            $wc_api = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');
            $result = $wc_api->sync_order_with_aliexpress($_POST['order_id']);
        }
        echo json_encode($result);
        wp_die();
    }

}
