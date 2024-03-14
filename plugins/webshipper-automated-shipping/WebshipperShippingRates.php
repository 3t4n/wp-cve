<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class WebshipperShippingRates
 *
 *
 * See https://docs.woocommerce.com/document/shipping-method-api/#section-5
 */
class WebshipperShippingRates extends WC_Shipping_Method
{

    public static $quoteCounter = 0;

    /**
     * WebshipperShippingRates constructor.
     */
    public function __construct()
    {
        $this->id = 'WS';
        $this->method_title = "Webshipper Rate Provider";
        $this->method_description = "Webshipper calculates shipping rates autmatically and live directly from your webshipper.com account.<br/>
                    If you experience any issues, please contact support@webshipper.com.<br/><br/>
                    If you want to disable the webshipper shipping, please disable the plugin, under the plugins menu.";
        $this->title = "Webshipper Rate Provider";
        $this->enabled = "yes";

        // Dont show tab in settings
        $this->supports = [];

        $this->init();
    }

    /**
     * Required to be here by Woo
     *
     * @return void
     */
    protected function init()
    {
      //
    }


    /**
     * Framework method. Must call $this->add_rate($rate) to add rates
     *
     * @param array $package
     */
    public function calculate_shipping($package = [])
    {
        self::$quoteCounter++;

        if (self::$quoteCounter >= 20) {
          return;
        }

        try {
            $api = WebshipperAPI::instance();
        } catch (Exception $e) {
            error_log('Webshipper plugin not configured');
            return;
        }

        $total = 0;
        $coupon_free_shipping = false;
        $items = [];
        $weight_uom = get_option('woocommerce_weight_unit');
        $weight_multiplier = $weight_uom == 'kg' ? 1000 : 1;
        $weight_total = 0;

        // Calculate cart total incl. taxes
        if (count($package["contents"]) > 0) {
            foreach ($package["contents"] as $content) {
                // Calculate total
                $total += $content["line_total"] + $content["line_tax"];

                // Get apropriate product info for the quote
                if ((int) $content["variation_id"] > 0) {
                  try {
                    $product = new WC_Product_Variation($content["variation_id"]); // Variation inherits Product
                  } catch (Exception $e) { // They might have removed the variation.
                    $product = new WC_Product($content["product_id"]);
                  }
                } else {
                  $product = new WC_Product($content["product_id"]);
                }
                // Get apropriate weight
                $weight = (double) $product->get_weight() * (double) $content["quantity"] * $weight_multiplier;
                $weight_total += $weight;

                if ((float) $content["line_subtotal"] > 0 && (int) $content["quantity"] > 0) {
                  $price = (float) $content["line_subtotal"] / (float) $content["quantity"];
                } else {
                  $price = 0;
                }

                $content_data = isset($content['data']) ? $content['data'] : false;

                $items[] = [
                  'quantity' => $content['quantity'],
                  'sku' => $content_data ? $content_data->get_sku() : '',
                  'description' => $content_data ? $content_data->get_name() : '',
                  'unit_price' => $price,
                  'weight' => $weight
                ];
            }
        }

        // Check if any coupon codes are applied
        if (count($package['applied_coupons']) > 0) {
            foreach ($package['applied_coupons'] as $coupon) {
                $obj = new WC_Coupon($coupon);
                //$total = $total - $obj->amount;

                // check if coupon grants free shipping
                if ($obj->get_free_shipping($coupon) == true) {
                  $coupon_free_shipping = true;
                }
            }
        }

        $delivery_address = array(
            'address_1' => $package['destination']['address'],
            'address_2' => $package['destination']['address_2'],
            'city' => $package['destination']['city'],
            'zip' => $package['destination']['postcode'],
            'country_code' => $package['destination']['country'],
            'state' => $package['destination']['state']
        );


        if (isset($_POST) && isset($_POST['post_data'])) {
          wp_parse_str(sanitize_text_field($_POST['post_data']), $postData);
  
          if (isset($postData)) {
            $companyName = null;
            $shipping_company = sanitize_text_field($postData['shipping_company']);
            $billing_company = sanitize_text_field($postData['billing_company']);
  
            if (isset($shipping_company) && strlen($shipping_company) > 0) {
              $companyName = $shipping_company;
            } elseif (isset($billing_company) && strlen($billing_company) > 0) {
              $companyName = $billing_company;
            }
  
            if ($companyName) {
                $delivery_address['company_name'] = $companyName;
            }
          }
        }

        $res = $api->quoteRates($delivery_address, $items, $total, $weight_total);

        foreach ($res as $rate) {
            $rate_id = $this->id . '_' . preg_replace('/[,.:;]/' , '' , $rate['shipping_rate']['carrier_service_code']) . '_' . $rate['shipping_rate']['id'];

            if ($rate['shipping_rate']['require_drop_point']) {
              $rate_id = '1_' . $rate_id;
            };

            $rate_params = [
                'id' => $rate_id,
                'label' => $rate['shipping_rate']['name'],
                'cost' => ($coupon_free_shipping ? 0 : $rate['price']),
                'calc_tax' => 'per_order',
                'taxes' => (isset($rate['tax_percent']) && $rate['tax_percent'] > 0 ? '' : false ),
                'meta_data' => [
                    'requires_drop_point' => $rate['shipping_rate']['require_drop_point'],
                    'shipping_rate_id' => $rate['shipping_rate']['id'],
                    'carrier_service_code' => $rate['shipping_rate']['carrier_service_code']
                ]
            ];

            if (class_exists('WCML_Multi_Currency') && get_option('webshipper_filter_basket_by_currency') === 'yes') {
              $wcml_options = get_option('_wcml_settings');
              if ($wcml_options['enable_multi_currency']) {
                $rate_params['cost'] = $this->prepare_for_exchange_rates($rate, $rate_params);
                $rate_params['price_decimals'] = 8;
              }
            }

            $rate_params = apply_filters('webshipper_add_shipping_rate', $rate_params, $rate);

            $this->add_rate($rate_params);
        }
    }

    /**
     * Prepare value  for an exchange between two currencies
     * Only used if WCML is in use
     *
     * @param array $rate
     * @param array $params
     * @return void
     */
    public function prepare_for_exchange_rates($rate, $params)
    {
      if ($params['cost'] == 0) {
        return 0;
      }

      if ($rate['currency'] == get_option('woocommerce_currency')) {
        return $params['cost'];
      }

      // Unfuck the forced currency conversion even though we return the requested currency
      $multiCurrency = new WCML_Multi_Currency;
      return $multiCurrency->prices->unconvert_price_amount($rate['price'], $rate['currency']);
    }
}
