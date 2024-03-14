<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 *
 * Plugin Name: Webshipper - Automated Shipping
 * Description: Automated shipping for WooCommerce (Webshipper v2 only)
 * Author: Webshipper
 * Author URI: https://www.webshipper.com
 * Version: 1.5.7
 */
if (!function_exists('is_plugin_active_for_network')) {
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || is_plugin_active_for_network('woocommerce/woocommerce.php')) {
    // Loads dependencies e.g. Guzzle
    require(dirname(__FILE__) . '/vendor/autoload.php');


    // REST API Adapter
    require_once(dirname(__FILE__) . '/WebshipperAPI.php');
    require_once(dirname(__FILE__) . '/WebshipperHelpers.php');
    require_once(dirname(__FILE__) . '/WebshipperOrderHtml.php');

    /**
     * Wait for WC_Shipping_Method class to be loaded
     */
    add_action('woocommerce_shipping_init', function () {
        if (class_exists('WebshipperShippingRates')) {
            return;
        }

        require_once(dirname(__FILE__) . '/WebshipperShippingRates.php');
    });


    /**
     * Initialise shipping rates
     */
    add_filter('woocommerce_shipping_methods', function ($methods) {
        $methods['WS'] = 'WebshipperShippingRates';
        return $methods;
    });


    /**
     * Hook into "Your order" summary
     *
     * Add a row for drop point selector of chosen shipping rate requires it
     */
    add_action('woocommerce_review_order_before_order_total', 'webshipper_drop_point_selector_location');

    function webshipper_drop_point_selector_location()
    {
        if (!WC()->cart->needs_shipping()) {
            return;
        }

        try {
            WebshipperAPI::instance()->printDropPointSelector();
        } catch (Exception $e) {
            echo "<em>Webshipper plugin activated but not configured. Configure it now under WooCommerce > Settings > Shipping > Shipping options</em>";
            return;
        }
    }


    /**
     *  Validate "ws_drop_point_blob" POST field on checkout
     */
    add_action('woocommerce_checkout_process', function () {
        if (!WC()->cart->needs_shipping()) {
            return;
        }

        try {
            $api = WebshipperAPI::instance();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return;
        }

        $rate = $api->getSelectedRate();

        if (!$rate) {
            wc_add_notice(__('No shipping rate chosen'), 'error');
            return;
        }

        if (is_string($rate)) {
            if (preg_match("/WS/", $rate)) {
                $method_arr = explode("_", $rate);
                if ($method_arr[0] != '1') {
                    return;
                }
            }
        } else {
            if ($rate->get_meta_data() && (!array_key_exists('requires_drop_point', $rate->get_meta_data()) || !$rate->get_meta_data()['requires_drop_point'])) {
                return;
            }
        }

        if (strlen(get_option('webshipper_google_maps_api_key', '')) > 1) {
            // Validate that the POST attribute is set
            if (!isset($_POST['ws_drop_point_blob'])) {
                $translation = get_option('webshipper_drop_point_required', false);
                if (!$translation) {
                    $translation = esc_html_e('Please choose a drop point');
                }
                wc_add_notice($translation, 'error');

                return;
            }

            # stripslashes remove all backslashes that cannot properly be decoded
            $ws_drop_point_blob = json_decode(sanitize_text_field(urldecode(stripslashes($_POST['ws_drop_point_blob']))), true);

            // Validate again, post-parse
            if (!$ws_drop_point_blob) {
                $translation = get_option('webshipper_drop_point_required', false);
                if (!$translation) {
                    $translation = esc_html_e('Please choose a drop point');
                }
                wc_add_notice($translation, 'error');
                return;
            }
        }
    });


    /**
     * Set drop point as delivery address
     * if rate needs drop-point
     *
     */
    add_action('woocommerce_checkout_update_order_meta', function ($order_id) {
        $order = new WC_Order($order_id);
        $orderData = $order->get_data();

        if (!isset($_POST['ws_drop_point_blob'])) {
            return;
        }

        # stripslashes remove all backslashes that cannot properly be decoded
        $ws_drop_point_blob = json_decode(sanitize_text_field(urldecode(stripslashes($_POST['ws_drop_point_blob']))), true);

        if (get_option('webshipper_save_droppoint_as_address', false) == 'yes') {
            $zip = $ws_drop_point_blob['zip'];
            if (isset($ws_drop_point_blob['routing_code'])) {
                $zip = $zip . ':' . $ws_drop_point_blob['routing_code'];
            }

            update_post_meta($order_id, '_shipping_first_name', sanitize_text_field($orderData['billing']['first_name']));
            update_post_meta($order_id, '_shipping_last_name', sanitize_text_field($orderData['billing']['last_name']));
            update_post_meta($order_id, '_shipping_address_1', sanitize_text_field($ws_drop_point_blob['address_1']));
            update_post_meta($order_id, '_shipping_address_2', sanitize_text_field('Drop point:' . $ws_drop_point_blob['drop_point_id']));
            update_post_meta($order_id, '_shipping_company', sanitize_text_field($ws_drop_point_blob['name']));
            update_post_meta($order_id, '_shipping_city', sanitize_text_field($ws_drop_point_blob['city']));
            update_post_meta($order_id, '_shipping_postcode', sanitize_text_field($zip));
        }

        // Use customer name, but drop point address
        update_post_meta($order_id, '_drop_point_id', sanitize_text_field($ws_drop_point_blob['drop_point_id']));
        update_post_meta($order_id, '_drop_point_company', sanitize_text_field($ws_drop_point_blob['name']));
        update_post_meta($order_id, '_drop_point_address_1', sanitize_text_field($ws_drop_point_blob['address_1']));
        update_post_meta($order_id, '_drop_point_city', sanitize_text_field($ws_drop_point_blob['city']));
        update_post_meta($order_id, '_drop_point_zip', sanitize_text_field($ws_drop_point_blob['zip']));
        update_post_meta($order_id, '_drop_point_country', sanitize_text_field($ws_drop_point_blob['country_code']));
        update_post_meta($order_id, '_drop_point_routing_code', sanitize_text_field($ws_drop_point_blob['routing_code']));
    });

    add_action('woocommerce_order_status_changed', 'ws_action_woocommerce_order_status_changed', 10, 3);

    function ws_action_woocommerce_order_status_changed($order_id, $oldStatus, $newStatus)
    {
        // Expedite orders
        $statuses = get_option('webshipper_expedite_order_statuses', []);
        $async = get_option('webshipper_expedite_order_async') == 'yes';

        // For some reason Woo sends me the
        // status without the wc- prefix
        $newStatus = 'wc-' . $newStatus;

        // Validate that the ids haven't run as a bulk action
        $ids = get_transient('webshipper_bulk_order_action');
        if (!$ids) {
            $ids = [];
        }

        if (in_array($newStatus, $statuses) && !in_array($order_id, $ids)) {
            $api = WebshipperAPI::instance();
            $api->expediteOrder([$order_id], $async);
        }
    }

    add_filter('handle_bulk_actions-edit-shop_order', 'filter_bulk_woocommerce_order_status_changed', 10, 3);
    function filter_bulk_woocommerce_order_status_changed($url, $action, $ids)
    {
        try {
            $action = explode('_', $action)[1];
        } catch (\Exception $e) {
            // Unsupported Action, return

            return;
        }

        $async = get_option('webshipper_expedite_order_async') == 'yes';

        // Statuses to expedite
        $statuses = get_option('webshipper_expedite_order_statuses', []);

        // For some reason Woo sends us the
        // status without the wc- prefix
        $newStatus = 'wc-' . $action;

        if (in_array($newStatus, $statuses)) {
            set_transient('webshipper_bulk_order_action', $ids, 60 * 5);

            $api = WebshipperAPI::instance();
            $api->expediteOrder($ids, $async);
        }
    }

    /**
     * Allow the user to change the shipping method
     * directly from the order page
     */
    add_action('woocommerce_admin_order_data_after_order_details', 'show_on_order');

    function show_on_order()
    {
        try {
            if (!current_user_can('manage_woocommerce')) {
                return;
            }

            $wooOrder = new WC_Order($_GET["post"]);
            if (isset($_GET["webshipper_change_droppoint"]) && $_GET["webshipper_change_droppoint"] == 'true') {
                change_droppoint(
                    $wooOrder,
                    sanitize_text_field($_GET["dp_id"]),
                    sanitize_text_field(urldecode($_GET["dp_street"])),
                    sanitize_text_field($_GET["dp_zip"]),
                    sanitize_text_field(urldecode($_GET["dp_city"])),
                    sanitize_text_field(urldecode($_GET["dp_name"])),
                    sanitize_text_field($_GET["dp_country"])
                );
            }

            if (isset($_GET["webshipper_change_shipping_method"]) && $_GET["webshipper_change_shipping_method"] == 'true') {
                change_shipping_method(
                    $wooOrder,
                    sanitize_text_field($_GET["ws_rate"]),
                    sanitize_text_field(urldecode($_GET["name"]))
                );
            }

            if (isset($_GET['webshipper_change_shipping_method']) || isset($_GET['webshipper_change_droppoint'])) {
                unset($_GET['webshipper_change_shipping_method']);
                unset($_GET['webshipper_change_droppoint']);
                unset($_GET['dp_id']);
                unset($_GET['dp_street']);
                unset($_GET['dp_zip']);
                unset($_GET['dp_city']);
                unset($_GET['dp_name']);
                unset($_GET['dp_country']);
                unset($_GET['ws_rate']);
                unset($_GET['name']);
                $qs = http_build_query($_GET);
                $base = sanitize_url($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0]);
                $base .= '?' . $qs;
                header('Location: ' . esc_url($base));
            }

            $order_html = new WebshipperOrderHtml($wooOrder);
            $order_html->render_html();
        } catch (Exception $e) {
            error_log($e->message);
            return;
        }
    }

    /**
     * Change the shipping method for an order
     *
     * @param WC_Order $woo_order
     * @param int $rate_id
     * @param string $rate_name
     * @return void
     */
    function change_shipping_method($woo_order, $rate_id, $rate_name)
    {
        if (method_exists($woo_order, 'get_shipping_method') && $woo_order->get_shipping_method()) {
            foreach ($woo_order->get_shipping_methods() as $shipping_method) {
                $shipping_method->set_method_title($rate_name);
                $shipping_method->update_meta_data('shipping_rate_id', $rate_id);
                $woo_order->save();
            }
        } else {
            $shipping_method = new WC_Shipping_Rate($rate_id, $rate_name, 0, array());
            $shipping_method->add_meta_data('shipping_rate_id', $rate_id);
            $woo_order->add_shipping($shipping_method);
        }
    }

    /**
     * Change the drop-point to deliver to
     * for an order
     * expects sanitized params
     *
     * @param WC_Order $woo_order
     * @param int $dp_id
     * @param string $dp_street
     * @param string $dp_zip
     * @param string $dp_city
     * @param string $dp_name
     * @param string $dp_country
     * @return void
     */
    function change_droppoint($woo_order, $dp_id, $dp_street, $dp_zip, $dp_city, $dp_name, $dp_country)
    {
        update_post_meta($woo_order->id, '_drop_point_id', $dp_id);
        update_post_meta($woo_order->id, '_drop_point_company', $dp_name);
        update_post_meta($woo_order->id, '_drop_point_address_1', $dp_street);
        update_post_meta($woo_order->id, '_drop_point_city', $dp_city);
        update_post_meta($woo_order->id, '_drop_point_zip', $dp_zip);
        update_post_meta($woo_order->id, '_drop_point_country', $dp_country);

        $legacy_dp = get_post_meta($woo_order->id, 'wspup_pickup_point_id');

        if ($legacy_dp) {
            update_post_meta($woo_order->id, 'wspup_pickup_point_id', $dp_id);
            $woo_order->set_shipping_company($dp_name);
            $woo_order->set_shipping_address_1($dp_street);
            $woo_order->set_shipping_address_2('Drop point:' . $dp_id);
            $woo_order->set_shipping_city($dp_city);
            $woo_order->set_shipping_postcode($dp_zip);
            $woo_order->set_shipping_country($dp_country);
            $woo_order->save();
        }
    }


    /**
     * Adds Webshipper configuration settings to WooCommerce > Settings > Shipping > Shipping options
     */
    add_filter('woocommerce_get_settings_shipping', function ($settings, $current_section) {
        $settings[] = [
            'name' => 'Webshipper Settings',
            'type' => 'title',
            'desc' => 'The Webhipper plugin needs to be configured to function. See https://help.webshipper.com',
            'id' => 'webshipper'
        ];

        $settings[] = [
            'name' => 'Configuration string',
            'type' => 'text',
            'id' => 'webshipper_access_str'
        ];

        $settings[] = [
            'name' => 'Drop point title',
            'type' => 'text',
            'id' => 'webshipper_drop_point_title'
        ];

        $settings[] = [
            'name' => 'Drop point dropdown title',
            'type' => 'text',
            'id' => 'webshipper_drop_point_dropdown_title'
        ];

        $settings[] = [
            'name' => 'Drop point required translation',
            'type' => 'text',
            'id' => 'webshipper_drop_point_required'
        ];

        $settings[] = [
            'name' => 'Filter rates by basket currency',
            'default' => 'no',
            'type' => 'checkbox',
            'id' => 'webshipper_filter_basket_by_currency'
        ];

        $settings[] = [
            'name' => 'Google Maps API Key',
            'default' => '',
            'type' => 'text',
            'id' => 'webshipper_google_maps_api_key'
        ];

        $settings[] = [
            'name' => 'Order statuses to expedite import',
            'default' => '',
            'type' => 'multiselect',
            'css' => 'height: 150px',
            'id' => 'webshipper_expedite_order_statuses',
            'options' => wc_get_order_statuses()
        ];

        $settings[] = [
            'name' => 'Run imports async',
            'default' => 'no',
            'type' => 'checkbox',
            'id' => 'webshipper_expedite_order_async'
        ];

        $settings[] = [
            'name' => 'Save selected droppoint in delivery address',
            'default' => 'no',
            'type' => 'checkbox',
            'id' => 'webshipper_save_droppoint_as_address'
        ];

        $settings[] = [
            'name' => 'Stop cart recalculations during rate quote. Try enabling this, if you experience issues with coupons',
            'default' => 'no',
            'type' => 'checkbox',
            'id' => 'webshipper_remove_cart_recalculation'
        ];

        $settings[] = [
            'type' => 'sectionend',
            'id' => 'webshipper'
        ];

        return $settings;
    }, 10, 2);



    // Load Google Maps dependencies only if
    // the user is using out Google Maps map
    add_action('wp_enqueue_scripts', 'enqueue_frontend');

    // Enqueue Backend scripts
    add_action('admin_enqueue_scripts', 'enqueue_backend');

    /**
     * Enqueue frontend scripts
     * Only used for displaying the incredible
     * drop-point selector
     *
     * @return void
     */
    function register_frontend()
    {
        wp_register_style("webshipper_css", plugins_url("css/webshipper.min.css", __FILE__), array(), WebshipperAPI::VERSION, false);

        if (get_option('webshipper_google_maps_api_key', false)) {
            wp_register_script("webshipper_maps", "https://maps.googleapis.com/maps/api/js?key=" . get_option('webshipper_google_maps_api_key'), array(), WebshipperAPI::VERSION, false);
        }

        wp_register_script("webshipper_drop_point", plugins_url("js/drop_point.js", __FILE__), array('jquery'), WebshipperAPI::VERSION, false);
    }

    // Add the frontend scripts to the dom
    function enqueue_frontend()
    {
        // Only load assets if woocommerce
        if (!function_exists('is_woocommerce')) {
            return;
        }

        // Only load assets if either checkout or cart
        if (!is_cart() && !is_checkout()) {
            return;
        }

        // CSS
        wp_register_style("webshipper_css", plugins_url("css/webshipper.min.css", __FILE__), array(), WebshipperAPI::VERSION, false);
        wp_enqueue_style('webshipper_css');

        // JS
        if (get_option('webshipper_google_maps_api_key', false)) {
            wp_register_script("webshipper_maps", "https://maps.googleapis.com/maps/api/js?key=" . get_option('webshipper_google_maps_api_key'), array(), WebshipperAPI::VERSION, false);
            wp_enqueue_script('webshipper_maps');
        }

        wp_register_script("webshipper_drop_point", plugins_url("js/drop_point.js", __FILE__), array('jquery'), WebshipperAPI::VERSION, false);
        wp_enqueue_script("webshipper_drop_point");

        $webshipper_nonce = wp_create_nonce("webshipper_nonce");
        wp_localize_script("webshipper_drop_point", "webshipper_ajax_object", array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'webshipper_nonce' => $webshipper_nonce
        ));
    }

    // Add backend scripts to the dom
    function enqueue_backend()
    {
        wp_register_script("webshipper_backend", plugins_url("js/backend.min.js", __FILE__), array('jquery'), WebshipperAPI::VERSION, true);
        wp_enqueue_script('webshipper_backend');
    }

    // Bind ajax calls to our methods
    add_action('wp_ajax_get_shops', 'locate_drop_points');
    add_action('wp_ajax_nopriv_get_shops', 'locate_drop_points');

    /**
     * Locate drop_points for the shipping_rate
     *
     * @return void
     */
    function locate_drop_points()
    {
        // Webshipper API Instance
        $api = WebshipperAPI::instance();
        $rate = $api->getSelectedRate();

        $require_dp = false;
        $rate_id = null;
        if ($rate) {
            if (is_string($rate) && preg_match("/WS/", $rate)) {
                $method_arr = explode("_", $rate);
                if ($method_arr[0] == '1') {
                    $require_dp = true;
                    $rate_id = end($method_arr);
                }
            } else {
                if ($rate->get_meta_data() && $rate->get_meta_data()['requires_drop_point'] && $rate->get_meta_data()['shipping_rate_id']) {
                    $require_dp = true;
                    $rate_id = $rate->get_meta_data()['shipping_rate_id'];
                }
            }
        }

        if ($require_dp) {
            try {
                $country_code = WC()->checkout->get_value('shipping_country');

                $result = $api->searchDropPoint($rate_id, $_POST['address'], $_POST['zip'], $_POST['city'], $country_code);

                wp_send_json_success(['drop_points' => $result]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    };
}
