<?php

class RecaptureWooCommerce extends RecaptureBasePlatform {

    function get_name() {
        return 'woocommerce';
    }

    public function add_actions() {
        add_action('woocommerce_order_status_changed', [$this, 'order_completed'], 99, 1);
        // add_action('woocommerce_cart_updated', [$this, 'update_cart'], 99);
        add_action('woocommerce_checkout_order_processed', [$this, 'save_cart_id_and_generate_new'], 30);
    }

    public  function remove_actions() {
        remove_action('woocommerce_order_status_changed', [$this, 'order_completed']);
        // remove_action('woocommerce_cart_updated', [$this, 'update_cart'], 99);
        remove_action('woocommerce_checkout_order_processed', [$this, 'save_cart_id_and_generate_new'], 30);
    }

    protected static function get_cart_url() {
        global $woocommerce;

        if ($woocommerce->version < '2.3') {
            return $woocommerce->cart->get_cart_url();
        }

        return wc_get_cart_url();
    }

    public function save_cart_id_and_generate_new($order_id) {
        // store the cart id
        RecaptureUtils::set_cart_id_for_post($order_id);

        // set a new cart id
        RecaptureUtils::set_new_cart_id();
    }

    function update_cart() {
        $woocommerce_cart = WC()->cart->get_cart();

        RecaptureUtils::set_cart_id_if_missing();
        $cart_id = RecaptureUtils::get_cart_id();

        $cart = (object) [
            'externalId' => $cart_id,
            'products' => [],
            'checkoutUrl' => null
        ];

        $cart_items = [];

        foreach ($woocommerce_cart as $item_key => $item) {
            $product = $item['data'];

            // Force price formatting to include 2 decimal places
            if (isset($item['line_total'])) {
                $price = number_format($item['line_total'] / $item['quantity'], 2, '.', '');
            } else {
                $price = 0;
            }

            $variant_id = $item['variation_id'];

            $cart->products[] = [
                'name' => $product->get_name(),
                'imageUrl' => wp_get_attachment_url($product->get_image_id()),
                'url' => get_permalink($product->get_id()),
                'price' => strval($price),
                'externalId' => $product->get_id(),
                'quantity' => $item['quantity'],
                'sku' => $product->get_sku(),
                'variantId' => $variant_id
            ];

            $cart_items[] = [
                'id' => $product->get_id(),
                'quantity' => $item['quantity'],
                'variation_id' => $variant_id,
                'variation' => $item['variation']
            ];
        }

        $cart_url = self::get_cart_url();
        $cart_contents = RecaptureUtils::encode_array($cart_items);
        $cart->checkoutUrl = $cart_url.'?racart='.$cart_id.'&contents='.$cart_contents;

        RecaptureUtils::send_cart($cart);
    }

    static function order_completed($order_id)
    {
        $order = wc_get_order($order_id);

        if (!$order || empty($order)) {
            return;
        }

        $cart_id = RecaptureUtils::get_cart_id_for_post($order_id);

        $data = (object) [
            'externalId' => $cart_id,
            'orderId' => $order_id,
            'shippingCountry' => $order->get_shipping_country(),
            'billingCountry' => $order->get_billing_country(),
            'email' => $order->get_billing_email()
        ];

        // convert the cart
        RecaptureUtils::convert_cart($data);

        // set a new cart id
        RecaptureUtils::set_new_cart_id();
    }

    public function regenerate_cart_from_url($cart, $contents)
    {
        // get the cart contents
        $contents = RecaptureUtils::decode_array($contents);

        // remove cart update actions
        $this->remove_actions();

        // Clear any old cart_contents
        WC()->cart->empty_cart();

        foreach ($contents as $item) {
            WC()->cart->add_to_cart(
                $item->id,
                $item->quantity,
                $item->variation_id,
                $item->variation);
        }

        // Ignoring wpecs warning because we receive this URL from Recapture
        // so we can't add/check a nonce
        // phpcs:ignore
        if (isset($_GET['discount'])) {
            // phpcs:ignore
            $discount = sanitize_text_field(wp_unslash($_GET['discount']));
            WC()->cart->add_discount($discount); // apply the coupon discount
            WC()->session->__unset('discount'); // remove coupon code from session
            return;
        }

        $this->add_actions();
        wp_safe_redirect(self::get_cart_url());
        die();
    }

    function is_product_page() {
        return is_product();
    }

    public static function is_ready() {
        return class_exists('WooCommerce') && function_exists('WC');
    }

    public function enqueue_scripts() {
    }
}
