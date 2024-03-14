<?php

class RecaptureWooCommerce extends RecaptureBasePlatform {

    function get_name() {
        return 'woocommerce';
    }

    public function add_actions() {
        add_action('woocommerce_order_status_changed', [$this, 'order_completed'], 99, 1);
        add_action('woocommerce_cart_updated', [$this, 'update_cart'], 99);
        add_action('woocommerce_checkout_order_processed', [$this, 'save_cart_id_and_generate_new'], 30);
        add_action('wp_login', [$this, 'user_logged_in'], 10, 2);
    }

    public  function remove_actions() {
        remove_action('woocommerce_order_status_changed', [$this, 'order_completed']);
        remove_action('woocommerce_cart_updated', [$this, 'update_cart'], 99);
        remove_action('woocommerce_checkout_order_processed', [$this, 'save_cart_id_and_generate_new'], 30);
        remove_action('wp_login', [$this, 'user_logged_in'], 10, 2);
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

        // Store the cart ID in the user's data
        self::save_cart_id_in_user_session();
    }

    public static function include_sales_tax() {
        return get_option('recapture_woo_include_tax_in_cart') == "1";
    }

    /*
    With WOOCS the cart totals are already in the local currency so we only
    need to provide the currency code so we can calculate the correct exchange
    rate in Recapture
    */

    static function get_local_currency() {
        try {
            global $WOOCS;

            if ($WOOCS) {
                return $WOOCS->current_currency;
            }

            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    // Save the cart id in the user's meta data
    static function save_cart_id_in_user_session() {
        $user_id = get_current_user_id();

        if ($user_id != 0) {
            update_user_meta(
                $user_id,
                'recapture_cart_id_'.get_current_blog_id(),
                RecaptureUtils::get_cart_id()
            );
        }
    }

    function user_logged_in($user_login, $user) {
        // Try to load the cart from the user's meta data
        $cart_id = get_user_meta(
            $user->ID,
            'recapture_cart_id_'.get_current_blog_id(),
            true
        );

        // If we have a stored cart id for this user then save it to the cookie
        // Otherwise, save the current cart id to the user's meta data
        if ($cart_id && strlen($cart_id) > 0) {
            // After the user logs in WooCommerce will merge/update the cart
            // which will trigger a cart update in Recapture updating the user's
            // original cart with the new (merged) cart contents

            $existing_cart_id = RecaptureUtils::get_cart_id();

            // If we have an existing cart id then send a empty product list will delete the cart
            if ($existing_cart_id && strlen($existing_cart_id) > 0) {
                RecaptureUtils::send_cart((object) [
                    'externalId' => $existing_cart_id,
                    'products' => [],
                    'checkoutUrl' => null,
                    'ip' => RecaptureUtils::get_real_ip(),
                    'recaptureVersion' => RECAPTURE_VERSION,
                    'recoveryMeta' => [],
                    'currency' => self::get_local_currency()
                ]);
            }

            RecaptureUtils::set_cart_id($cart_id);
        } else {
            update_user_meta(
                get_current_user_id(),
                'recapture_cart_id_'.get_current_blog_id(),
                RecaptureUtils::get_cart_id()
            );
        }
    }

    function update_cart() {
        $woocommerce_cart = WC()->cart->get_cart();

        RecaptureUtils::set_cart_id_if_missing();
        $cart_id = RecaptureUtils::get_cart_id();
        $ip = RecaptureUtils::get_real_ip();

        $cart = (object) [
            'externalId' => $cart_id,
            'products' => [],
            'checkoutUrl' => null,
            'ip' => $ip,
            'recaptureVersion' => RECAPTURE_VERSION,
            'recoveryMeta' => [
                'cart' => WC()->session->get('cart'),
            ],
            'currency' => self::get_local_currency()
        ];

        // Add details form the logged in user if we have one
        if (get_current_user_id() != 0) {
            $user = wp_get_current_user();

            if (strlen($user->user_email) > 0) {
                $cart->email = $user->user_email;
            }

            if (strlen($user->first_name)) {
                $cart->firstName = $user->first_name;
            }

            if (strlen($user->last_name) > 0) {
                $cart->lastName = $user->last_name;
            }
        }

        $cart_items = [];

        foreach ($woocommerce_cart as $item_key => $item) {
            $product = $item['data'];

            // Force price formatting to include 2 decimal places
            if (isset($item['line_total'])) {
                $total = $item['line_total'];

                if (self::include_sales_tax()) {
                    $total += $item['line_tax'];
                }

                $price = number_format($total / $item['quantity'], 2, '.', '');
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
                'productId' => $product->get_id(),
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
        $cart->checkoutUrl = add_query_arg(
          [
            'racart' => $cart_id,
            'contents' => $cart_contents
          ],
          $cart_url
        );

        // Avoid bots that create empty carts
        if (!isset($_COOKIE['ra_customer_id']) && count($cart->products) == 0) {
            return;
        }

        if (
            !isset($_COOKIE['ra_woo_cart_contents']) ||
            $_COOKIE['ra_woo_cart_contents'] != $cart_contents
        ) {
            RecaptureUtils::send_cart($cart);

            if (!headers_sent()) {
                setcookie('ra_woo_cart_contents', $cart_contents, 0, '/');
            }
        }

        // Store the cart ID in the user's data
        self::save_cart_id_in_user_session();
    }

    static function order_completed($order_id)
    {
        $order = wc_get_order($order_id);

        if (!$order || empty($order)) {
            return;
        }

        $cart_id = RecaptureUtils::get_cart_id_for_post($order_id);

        // Create the cart data based on the order
        $cart = (object) [
            'products' => [],
            'checkoutUrl' => null,
            'externalId' => $cart_id
        ];

        $cart_items = [];

        foreach ($order->get_items() as $item_id => $item) {
            $product = $item->get_product();
            $price = $order->get_item_subtotal($item, self::include_sales_tax(), true);

            $variant_id = $item->get_variation_id();

            $cart->products[] = [
                'name' => $item->get_name(),
                'imageUrl' => wp_get_attachment_url($product->get_image_id()),
                'url' => get_permalink($product->get_id()),
                'price' => strval($price),
                'externalId' => $item->get_product_id(),
                'productId' => $item->get_product_id(),
                'quantity' => $item->get_quantity(),
                'sku' => $product->get_sku(),
                'variantId' => $variant_id
            ];

            $cart_items[] = [
                'id' => $item->get_product_id(),
                'quantity' => $item->get_quantity(),
                'variation_id' => $variant_id,
                'variation' => $item['variation']
            ];
        }

        $cart_url = self::get_cart_url();
        $cart_contents = RecaptureUtils::encode_array($cart_items);
        $cart->checkoutUrl = add_query_arg(
          [
            'racart' => $cart_id,
            'contents' => $cart_contents
          ],
          $cart_url
        );

        $status_map = [
            'pending' => 'placed',
            'processing' => 'placed',
            'on-hold' => 'hold',
            'completed' => 'complete',
            'cancelled' => 'cancelled',
            'refunded' => 'cancelled',
            'failed' => 'cancelled',
        ];

        // Convert a custom WooCommerce order status to a known status that Recapture can understand
        $woo_order_status = apply_filters('recapture_convert_custom_order_status', $order->get_status());

        $status = isset($status_map[$woo_order_status])
            ? $status_map[$woo_order_status]
            : 'placed';

        // Only convert if the order is processing or complete (i.e. payment has been taken)
        // otherwise order statue update
        if (!in_array($woo_order_status, [
            'completed',
            'processing'
        ])) {
            // send order state update
            RecaptureUtils::update_order([
                'external_id' => $order_id,
                'state' => $status
            ]);
            return;
        }

        $data = (object) [
            'externalId' => $cart_id,
            'orderId' => $order_id,
            'shippingCountry' => $order->get_shipping_country(),
            'billingCountry' => $order->get_billing_country(),
            'email' => $order->get_billing_email(),
            'phone' => $order->get_billing_phone(),
            'orderStatusUrl' => $order->get_view_order_url(),
            'state' => $status,
            'cart' => $cart,
            'firstName' => $order->get_billing_first_name(),
            'lastName' => $order->get_billing_last_name(),
            'currency' => self::get_local_currency()
        ];

        // convert the cart
        RecaptureUtils::convert_cart($data);

        // Store the cart ID in the user's data
        self::save_cart_id_in_user_session();
    }

    public function regenerate_cart_from_url($cart, $contents)
    {
        // get the cart contents
        $contents = RecaptureUtils::decode_array($contents, true);

        $meta = RecaptureUtils::get_cart_recovery_meta($cart);

        // remove cart update actions
        $this->remove_actions();

        if ($meta != null && $meta->cart != null) {
            // cart data must be array, JSON encode/decode is a hack to recursively convert object to array
            $woo_cart = json_decode(wp_json_encode($meta->cart), true );
            WC()->session->set('cart', $woo_cart);
        } else {
            // Clear any old cart_contents
            WC()->cart->empty_cart();

            foreach ($contents as $item) {
                try {
                    WC()->cart->add_to_cart(
                        $item['id'],
                        $item['quantity'],
                        $item['variation_id'],
                        $item['variation']);
                } catch (Exception $e) {
                    // ignore this item.
                }
            }
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

    public static function save_reviews($external_id, $author, $email, $reviews) {
        foreach ($reviews as $review) {
            $data = [
                'user_id' => get_current_user_id(), // 0 if not logged in
                'comment_post_ID' => $review->external_id,
                'comment_author' => $author,
                'comment_author_email' => $email,
                'comment_approved' => 0,
                'comment_type' => 'review',
                'comment_content' => $review->detail,
                'comment_agent' => RecaptureUtils::get_server_variable('HTTP_USER_AGENT'),
                'comment_author_IP' => RecaptureUtils::get_real_ip(),
                'comment_meta' => [
                    'rating' => $review->rating,
                    'verified' => 0
                ]
            ];
            wp_insert_comment($data);
        }
    }

    public static function get_product_url($external_id) {
        return get_permalink($external_id);
    }

    public static function get_customer_email_from_order($order_id) {
        $order = wc_get_order($order_id);
        return $order != false
            ? $order->get_billing_email()
            : '';
    }

    public static function get_customer_name_from_order($order_id) {
        $order = wc_get_order($order_id);
        return $order != false
            ? $order->get_formatted_billing_full_name()
            : '';
    }

    public static function get_customer_first_name_from_order($order_id) {
        $order = wc_get_order($order_id);
        return $order != false
            ? $order->get_billing_first_name()
            : '';
    }

    public static function supports_reviews() {
        return wc_review_ratings_enabled();
    }

    public static function create_unique_discount_code($spec) {
        if (!isset($spec->code)
            || !isset($spec->amount)
            || !isset($spec->type)
        ) {
            return null;
        }

        $expires = null;

        if ($spec->expire_period_enabled) {
            switch ($spec->expire_period_type) {
                case 'hours':
                    $expires = date('c', strtotime('+'.$spec->expire_period_value.' hour'));
                    break;

                case 'days':
                    $expires = date('c', strtotime('+'.$spec->expire_period_value.' day'));
                    break;

                default:
                    $expires = null;

            }
        }

        $coupon = [
            'post_title' => sanitize_text_field($spec->code),
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon'
        ];

        $new_coupon_id = wp_insert_post($coupon);

        $type = $spec->type === 'percentage'
            ? 'percent'
            : 'fixed_cart';

        // Add meta
        update_post_meta($new_coupon_id, 'discount_type', $type);
        update_post_meta($new_coupon_id, 'coupon_amount',
            filter_var($spec->amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
        );
        update_post_meta($new_coupon_id, 'individual_use', 'no');
        update_post_meta($new_coupon_id, 'usage_limit', '1');

        if ($expires != null) {
            update_post_meta($new_coupon_id, 'expiry_date', $expires);
        }

        update_post_meta($new_coupon_id, 'exclude_sale_items', 'no');
        update_post_meta($new_coupon_id, 'usage_count', 0);
        update_post_meta($new_coupon_id, 'limit_usage_to_x_items', 0);
        update_post_meta($new_coupon_id, 'usage_limit_per_user', 0);
        update_post_meta($new_coupon_id, 'free_shipping', 'no');

        if (
            isset($spec->min_price)
            && (is_float($spec->min_price) || is_int($spec->min_price))
            && $spec->min_price > 0
        ) {
            update_post_meta(
                $new_coupon_id,
                'minimum_amount',
                filter_var(
                    $spec->min_price,
                    FILTER_SANITIZE_NUMBER_FLOAT,
                    FILTER_FLAG_ALLOW_FRACTION
                )
            );
        }

        return (object) [
            'id' => $new_coupon_id,
            'code' => $spec->code,
            'expires' => empty($expires)
                ? null
                : date('c', strtotime($expires))
        ];
    }

    public static function delete_unique_discount_code($code) {
        $coupon_data = new WC_Coupon($code);
        if (empty($coupon_data->get_id())) {
            return false;
        }
        wp_delete_post($coupon_data->get_id());
        return true;
    }

    public static function find_products($filter) {
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'product',
            'orderby' => 'menu-order',
            'order' => 'asc',
            'fields' => 'ids',
            'posts_per_page' => '50'
        ];

        if (strlen($filter) > 0) {
            $args['s'] = $filter;
        }

        $query = new WP_Query($args);

        $products = array_map(function ($id) {
            $product = wc_get_product($id);

            return (object) [
                'id' => $id,
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'sku' => $product->get_sku(),
                'url' => get_permalink($id),
                'image' => wp_get_attachment_url($product->get_image_id())
            ];
        }, $query->posts);

        return $products;
    }
}
