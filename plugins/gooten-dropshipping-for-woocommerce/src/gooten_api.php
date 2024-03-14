<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (! class_exists( 'Gooten_Api')) {
    class Gooten_Api {

        private $gooten_gateway_url = "https://api.print.io/api/v/5/source/api/skushipping?";

        private $partnerRecipeId = "b4651f58-3370-4bf2-bb7a-f10dafdfe679";

        private $passphrase = "NmYwNTZkNTA2OGQ1ZTE4MjAwMjc4NzE4ZDIyODQyMmU=";

        public function __construct() {

        }

        public function items($cart) {
            $items = array();
            if(isset($cart)) {
                $skus = array();
                foreach($cart as $cart_item_key => $cart_item) {
                    if(isset($cart_item['data']) && method_exists( $cart_item['data'], 'get_sku')) {
                        if (!in_array($cart_item['data']->get_sku(), $skus)) {
                            $skus[] = $cart_item['data']->get_sku();
                            $product = $cart_item['data'];
                            $items[] = array(
                                "Quantity" => $cart_item['quantity'],
                                "SKU"      => $cart_item['data']->get_sku()
                            );
                        }
                    }
                }
            }
            return !empty($items)? $this->get_payload($items) : NULL;
        }

        public function get_payload ($items) {
            $currency_name = get_woocommerce_currency();
            return array(
                "CurrencyCode"  => $currency_name,
                "LanguageCode"  => "en",
                "ShipToCountry" => WC()->customer->get_shipping_country(),
                "ShipToState"   => WC()->customer->get_shipping_state(),
                "Items"         => $items
            );
        }

        public function shipping_prices_api ($body) {
            $args = array(
                'method'      => 'POST',
                'httpversion' => '1.0',
                'timeout'     => 45,
                'redirection' => '5',
                'blocking'    => true,
                'sslverify'   => false,
                'headers'     => array(
                    'Content-Type'  => 'application/json; charset=utf-8',
                ),
                'body'        => json_encode($body),
            );

            $data = array(
                'recipeId' => $this->partnerRecipeId,
                'passPhrase' => $this->passphrase
            );

            $query = http_build_query($data, '', '&', PHP_QUERY_RFC3986);

            $request = wp_remote_post( $this->gooten_gateway_url . $query, $args );

            $result = $request['body'];

            return json_decode($result, true);
        }
    }
}
