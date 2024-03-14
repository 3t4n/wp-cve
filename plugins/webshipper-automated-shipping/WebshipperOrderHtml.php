<?php
class WebshipperOrderHtml
{
    protected $webshipper_rates;
    protected $webshipper_api;
    protected $post_type;
    protected $ws_rate_id;
    protected $wooOrder;

    public function __construct(WC_Order $wooOrder)
    {
        try {
            $this->webshipper_api = WebshipperAPI::instance();
            $this->webshipper_rates = $this->webshipper_api->getShippingRates();
            $this->post_type = get_post_type($wooOrder->get_id());
            $this->wooOrder = $wooOrder;

            if (method_exists($wooOrder, 'get_shipping_methods')) {
                $arr = $wooOrder->get_shipping_methods();
                $woo_method_id = "";

                if ($arr) {
                    // Reset hack. Gets the first value of the array
                    // and resets its internal pointer
                    $woo_method = reset($arr);

                    $woo_method_id = $woo_method->get_meta('shipping_rate_id', true);
                    if (!$woo_method_id) {
                        $woo_method_id = $woo_method["method_id"];
                    }
                }
            } else {
                $woo_method_id = $wooOrder->shipping_method->get_meta('shipping_rate_id');
            }

            $this->ws_rate_id = $woo_method_id;
        } catch (Exception $e) {
            echo "<em>Webshipper plugin activated but not configured. Configure it now under WooCommerce > Settings > Shipping > Shipping options</em>";
            return;
        }
    }

    /**
     * Render HTML which allows the shop
     * to change subscription information
     * in WooCommerce
     *
     * (Returns void, but passes data to WC output buffering)
     *
     * @return void
     */
    public function render_html()
    {
        if ($this->post_type == 'shop_subscription') {
            $this->render_table_start();
            $this->render_shipping_change();
            $this->render_table_end();
        }
    }


    /**
     * Pass the start of the table to
     * WC output buffering
     *
     * @return void
     */
    protected function render_table_start()
    {
        echo '<div class="postbox" id="webshipper_backend" style="display:none;">';
        echo '<h3>Webshipper shipping</h3>';
        echo '<div class="inside">';
        echo '<table style="margin-left: 10px; width: 100%; ">';
    }

    /**
     * Render a list of selectable shipping
     * rates to use instead of the current rate
     *
     * @return void
     */
    protected function render_shipping_change()
    {
        $get_droppoints = false;

        echo '
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>Here you can change the shipping method and pickup point for the subscription</p>
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <select name="ws_rate" id="ws_rate">';

        // Hacky URLEncode inside Nowdocs
        $fn = 'urlencode';
        foreach ($this->webshipper_rates as $rate) {
            $attr = '';

            if ($rate['id'] == $this->ws_rate_id) {
                $attr = 'selected';
            }


            $name = $rate["attributes"]["name"];
            $value = $rate['id'] . '::' . urlencode($rate['attributes']['name']);

            echo '<option value="' . esc_attr($value) . '"' . esc_attr($attr) . '>' . esc_html($name) . '</option>';

            if ($this->ws_rate_id == $rate["id"] && $rate["attributes"]["require_drop_point"]) {
                $get_droppoints = true;
            }
        }

        if (!$get_droppoints) {
            // A bit redundant to get post meta just to set a flag
            // if the flag is already set
            $pickup_id = get_post_meta($this->wooOrder->get_id(), 'wspup_pickup_point_id', true);

            if (isset($pickup_id) && strlen($pickup_id) > 0) {
                $get_droppoints = true;
            }
        }

        // Not using HEREDOC nor NOWDOC as they are not allowed by WordPress as codesniffers won't detect lack of escaping in the code
        echo '</select>&nbsp;
            <a class="button button-primary" onClick="webshipper_change_shipping_method()" href="#">Set shipping</a>
            </td></tr>';

        if ($get_droppoints && $this->ws_rate_id) {
            echo '<tr>
                    <td colspan="2">
                        <select name="ws_droppoint" id="ws_droppoint">';

            $droppoints = $this->webshipper_api->searchDropPoint($this->ws_rate_id, $this->wooOrder->shipping_address_1, $this->wooOrder->shipping_postcode, $this->wooOrder->shipping_city, $this->wooOrder->shipping_country);

            foreach ($droppoints as $droppoint) {
                $combined_droppoint_option_value = $droppoint['drop_point_id'] . "::" . $fn($droppoint['address_1']) . "::" .
                    $fn($droppoint['zip']) . "::" . $fn($droppoint['city']) . "::" . $fn($droppoint['name']) . "::" . $droppoint['country_code'];

                echo '<option value="' . esc_attr($combined_droppoint_option_value) . '">' .
                    esc_html($fn($droppoint['name'])) . ' - ' . esc_html($fn($droppoint['address_1'])) . ' - ' .
                    esc_html($fn($droppoint['zip'])) . ' - ' . esc_html($fn($droppoint['city'])) . '
                    </option>';
            }

            echo '
                </select>&nbsp;
                <a class="button button-primary" onClick="webshipper_change_droppoint()" href="#">Set droppoint</a>
                </td></tr>';
        }
    }

    protected function render_table_end()
    {
        echo "
                    </table>
                </div>
            </div>";
    }
}
