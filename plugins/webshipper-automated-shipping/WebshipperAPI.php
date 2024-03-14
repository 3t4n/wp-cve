<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class WebshipperAPI
 */
class WebshipperAPI
{
    const VERSION = '1.4.8';

    // Singleton w/o Dependency Injection
    protected static $instance;

    // Which environemnt to connect to
    protected $apiKey;
    protected $tenant;
    protected $endpoint;

    protected $quotes = [];
    protected $drop_point_translation;

    public function __construct()
    {
        $config = json_decode(base64_decode(get_option('webshipper_access_str')));
        if ($config->token) {
            $this->apiKey = $config->token;
            $this->orderChannelId = (int) $config->order_channel_id;
            $this->tenant = $config->tenant_name;
            $this->endpoint = $config->endpoint . '/v2';
        } else {
            throw new Exception('Configuration webshipper_access_str not set');
        }
    }

    /**
     * @return WebshipperAPI
     * @throws Exception
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new WebshipperAPI();
        }
        return self::$instance;
    }

    /**
     * Fetches all shipping rates for
     * the order channel
     *
     * @return array
     */
    public function getShippingRates()
    {
        try {
            $res = $this->get($this->endpoint . '/shipping_rates?filter[order_channel_id]=' . $this->orderChannelId);
            $body = $res['data'];
        } catch (Exception $e) {
            error_log('Get shipping rates failed: ' . $e->getMessage());
            $body = [];
        }

        return $body;
    }

    /**
     * Perform a rate quote lookup
     * and return any available rates
     * for the shipping route
     *
     * @return array An array of rates
     */
    public function quoteRates($delivery_address, $items, $price, $weight, $weight_unit = 'g') // Default weight unit to gram
    {
        $data = [
            'data' => [
                'type' => 'rate_quotes',
                'attributes' => [
                    'price' => $price,
                    'weight' => $weight,
                    'weight_unit' => $weight_unit,
                    'order_channel_id' => $this->orderChannelId,
                    'items' => $items,
                    'delivery_address' => $delivery_address
                ]
            ]
        ];

        if (get_woocommerce_currency() && get_woocommerce_currency() !== '' && class_exists('WCML_Multi_Currency') && get_option('webshipper_filter_basket_by_currency') === 'yes') {
            $data['data']['attributes']['filter_by_currency'] = get_woocommerce_currency();
        }

        $data = apply_filters('webshipper_quote_rates_data', $data);

        $key = 'webshipper:' . md5($this->tenant . $this->apiKey . json_encode($data));

        if ($cache = get_transient($key)) {
            return $cache;
        }

        try {
            $res = $this->post($this->endpoint . '/rate_quotes', $data);
            $body = $res['data']['attributes']['quotes'];

            // Cache it for up to a day
            set_transient($key, $body, 60 * 60 * 24);
        } catch (Exception $e) {
            error_log('Rate quote failed: ' . $e->getMessage());
            $body = [];
        }

        return $body;
    }

    /**
     * Ping Webshipper with order IDs
     * in order to expedite their import
     *
     * @param array $orderIds
     * @return void
     */
    public function expediteOrder($orderIds, $async = false)
    {
        $data = [
            'data' => [
                'type' => 'bulk_import_orders',
                'attributes' => [
                    'ids' => $orderIds,
                    'order_channel_id' => $this->orderChannelId,
                    'async' => $async
                ]
            ]
        ];

        @$this->post($this->endpoint . '/bulk_import_orders', $data);
    }

    /**
     * Find drop-doints close to the entered
     * address
     *
     * @param int $shipping_rate_id
     * @param string $address
     * @param string|int $zip
     * @param string $city
     * @param string $country_code
     *
     * @return array array of drop-points
     */
    public function searchDropPoint($shipping_rate_id, $address, $zip, $city, $country_code)
    {
        $data = [
            'data' => [
                'type' => 'drop_point_locators',
                'attributes' => [
                    'shipping_rate_id' => (int) $shipping_rate_id,
                    'delivery_address' => [
                        'address_1' => sanitize_text_field($address),
                        'zip' => sanitize_text_field($zip),
                        'city' => sanitize_text_field($city),
                        'country_code' => sanitize_text_field($country_code)
                    ],
                ]

            ],
        ];

        try {
            $res = $this->post($this->endpoint . '/drop_point_locators', $data);
            $body = $res['data']['attributes']['drop_points'];
        } catch (Exception $e) {
            error_log('Unable to GET drop point: ' . $e->getMessage());
            return [];
        }

        return $body;
    }


    /**
     * Overruling a built in WooCommerce method
     * Get the rate currently selected in checkout
     *
     * @return WC_Shipping_Rate
     */
    public function getSelectedRate()
    {
        if (get_option('webshipper_remove_cart_recalculation') != 'yes') {
            WC()->cart->calculate_totals();
        }

        $chosen_rates = WC()->session->chosen_shipping_methods;

        foreach (WC()->shipping->get_packages() as $package) {
            /** @var WC_Shipping_Rate $rate */
            foreach ($package['rates'] as $rate) {
                if (in_array($rate->get_id(), $chosen_rates)) {
                    return $rate;
                }
            }
        }

        if (is_array($chosen_rates)) {
            return reset($chosen_rates);
        }


        if ($_POST && isset($_POST["shipping_method"])) {
            if (is_array($_POST["shipping_method"])) {
                return reset($_POST["shipping_method"]);
            }
            if (is_string($_POST["shipping_method"])) {
                return $_POST["shipping_method"];
            }
        }

        return null;
    }

    protected function get($endpoint)
    {
        return $this->request($endpoint, 'get');
    }

    protected function post($endpoint, $data)
    {
        return $this->request($endpoint, 'post', $data);
    }

    protected function request($endpoint, $method, $data = null)
    {
        $headers = [
            'X-TenantName' =>  $this->tenant,
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/vnd.api+json',
            'Accept' => 'application/vnd.api+json'
        ];

        $response = null;

        try {
            if ($method == 'post') {
                $payload = json_encode($data);

                $response = wp_remote_retrieve_body(wp_remote_post($endpoint, [
                    'body' => $payload,
                    'headers' => $headers
                ]));
            } else {
                $response = wp_remote_retrieve_body(wp_remote_get($endpoint, ['headers' => $headers]));
            }
        } catch (Exception $e) {
            error_log("Failed to make request to Webshipper api: " . $e->getMessage());
            return false;
        }

        $json = json_decode($response, true);

        return $json;
    }

    /**
     * Hacky hack.
     * echo <tr></tr> for choosing a drop-point
     * caught in WooCommerces ob_start
     * and returned in the response of the request
     *
     * @return void
     */
    public function printDropPointSelector()
    {
        $rate = $this->getSelectedRate();
        $require_dp = false;
        $rate_id = false;

        if ($rate) {
            if (is_string($rate)) {
                if (preg_match("/WS/", $rate)) {
                    $method_arr = explode("_", $rate);
                    if ($method_arr[0] == '1') {
                        $require_dp = true;
                        $rate_id = end($method_arr);
                    }
                }
            } else {
                if ($rate->get_meta_data() && array_key_exists('requires_drop_point', $rate->get_meta_data()) && $rate->get_meta_data()['requires_drop_point']) {
                    $require_dp = true;
                }
            }
        }

        if (!$require_dp) {
            // Rate does't require a drop-point, so we just display an empty table-header
            echo "<tr><th></th><td>";
        } else {
            // Rate requires a drop-point

            // We display a gorgeous drop-point selection map
            // if the customer have a google maps api key
            if (strlen(get_option('webshipper_google_maps_api_key', '')) > 1) {
                $this->modalWithMap($rate);
            } else {
                $this->genericRadioButtons($rate, $rate_id);
            }
        }
    }

    protected function modalWithMap($rate)
    {
        wc_get_template('templates/drop_point_modal.php', array(), '', plugin_dir_path(__FILE__));
    }

    protected function genericRadioButtons($rate, $rate_id = false)
    {
        // Display a boring table for selecting the drop-point
        $result = array();

        // Default state; No drop-point selected
        $drop_points = [];

        // Grab shipping_rate_id from meta_data if, rate is not set
        $rate_id = $rate_id ? $rate_id : $rate->get_meta_data()['shipping_rate_id'];

        // Find an appropriate address
        $address = WC()->checkout->get_value('shipping_address_1') ? WC()->checkout->get_value('shipping_address_1') : WC()->checkout->get_value('billing_address_1');
        $zip = WC()->checkout->get_value('shipping_postcode') ? WC()->checkout->get_value('shipping_postcode') : WC()->checkout->get_value('billing_postcode');
        $city =  WC()->checkout->get_value('shipping_city') ? WC()->checkout->get_value('shipping_city') : WC()->checkout->get_value('billing_city');
        $country_code = WC()->checkout->get_value('shipping_country') ? WC()->checkout->get_value('shipping_country') : WC()->checkout->get_value('billing_country');

        // Sanitize above variables
        $address = sanitize_text_field($address);
        $zip = sanitize_text_field($zip);
        $city = sanitize_text_field($city);
        $country_code = sanitize_text_field($country_code);

        // Don't search droppints when there is no zip code
        if (!isset($zip) || (isset($zip) && ($zip == '0' || strlen($zip) < 1))) {
            return;
        }

        // Find all available drop points
        // and format them prettily
        // Ignore errors and just continue
        // with the next DP
        try {
            $result = $this->searchDropPoint($rate_id, $address, $zip, $city, $country_code);
            foreach ($result as $dp) {
                $key = urlencode(json_encode(['drop_point_id' => $dp['drop_point_id'], 'address_1' => $dp['address_1'], 'zip' => $dp['zip'], 'city' => $dp['city'], 'country_code' => $dp['country_code'], 'name' => $dp['name'], 'routing_code' => $dp['routing_code']]));
                $drop_points[$key] = "{$dp['name']}, {$dp['address_1']} {$dp['zip']} {$dp['city']}";
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $drop_points = ['null' => ws_get_option('webshipper_drop_point_dropdown_title', '---')];
        }

        // Start output buffering
        // (Allows us to capture echo statements)
        ob_start();

        echo "<tr><th>" . esc_html(get_option('webshipper_drop_point_title')) . "</th><td>";
        if (count($drop_points) <= 0) {
            echo ___("Unable to find droppoint. Please check your address");
        } else {
            woocommerce_form_field('ws_drop_point_blob', [
                'type' => 'select',
                'label' => null,
                'required' => false,
                'options' => $drop_points
            ], '0');
        }

        echo "</td></tr>";

        // Stop this level of output buffering
        // and get the clean HTML
        $html = ob_get_clean();

        // Pass the full html to WooCommerces output buffering
        echo apply_filters('webshipper_drop_point_selector_html', $html, $drop_points, $result);
    }
}
