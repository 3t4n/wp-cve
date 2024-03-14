<?php

/*
 * Easy-Digital-Downloads calls empty_cart() before orders are processed so
 * we have to be careful about how we send carts to Recapture. So we Reset
 * the cart ID on the success page so the empty cart is a new cart id
 *
 * Another issue is how carts are converted. The edd_complete_purchase action
 * is often not called in the contact of the user request, it might be called
 * from a webhook, in which case we don't have the cart ID available. So we
 * send the email address and use that to match the cart
 *
 */

class RecaptureEDD extends RecaptureBasePlatform {
    protected $disable_conversion_tracking = false;

    function get_name() {
        return 'edd';
    }

    public function add_actions() {
        add_action('edd_post_add_to_cart', [$this, 'update_cart']);
        add_action('edd_post_remove_from_cart', [$this, 'update_cart']);
        add_action('edd_after_set_cart_item_quantity', [$this, 'update_cart']);
        add_action('edd_cart_discounts_updated', [$this, 'update_cart']);
        add_action('edd_complete_purchase', [$this, 'order_completed']);
        add_action('edd_after_checkout_cart', [$this, 'update_cart']);
        add_action('wp', [$this, 'site_loaded']);
        add_action('edd_insert_payment', [$this, 'insert_payment'], 10, 2);

        // Don't track payments made with EDD Free Downlodas
        add_action('edd_free_downloads_pre_complete_payment', [$this, 'disable_conversion_tracking']);
        add_action('edd_free_downloads_post_complete_payment', [$this, 'enable_conversion_tracking']);

        add_action('edd_straight_to_gateway_purchase_data', [$this, 'straight_to_gateway']);
    }

    public function remove_actions() {
        remove_action('edd_post_add_to_cart', [$this, 'update_cart']);
        remove_action('edd_post_remove_from_cart', [$this, 'update_cart']);
        remove_action('edd_complete_purchase', [$this, 'order_completed']);
        remove_action('edd_cart_discounts_updated', [$this, 'update_cart']);
        remove_action('edd_after_checkout_cart', [$this, 'update_cart']);
        remove_action('wp', [$this, 'site_loaded']);
        remove_action('edd_insert_payment', [$this, 'insert_payment']);
    }

    public function site_loaded() {
        // set a new checkout id on the thank you page the payment
        // success will be handled by email address only

        /*
        Disabling this because with 2Checkout order_complete notifications can arrive
        much later than the thank you page which can send a duplicate cart to Recapture

        Recapture already resets the cart ID internally so we should not need this.
        if (edd_is_success_page()) {
            RecaptureUtils::set_new_cart_id();
        }
        */
    }

    public function disable_conversion_tracking() {
        $this->disable_conversion_tracking = true;
    }

    public function enable_conversion_tracking() {
        $this->disable_conversion_tracking = false;
    }

    protected function set_recapture_cart($items) {
        // Set the cart token if needed
        RecaptureUtils::set_cart_id_if_missing();

        $discounts = edd_get_cart_discounts();

        $cart_id = RecaptureUtils::get_cart_id();
        $ip = RecaptureUtils::get_real_ip();
        $cart = (object) [
            'externalId' => $cart_id,
            'products' => [],
            'checkoutUrl' => null,
            'ip' => $ip,
            'recaptureVersion' => RECAPTURE_VERSION,
            'discountCodes' => $discounts,
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

        foreach ($items as $item_key => $item) {
            // Compatibility with Aelia Currency Switcher
            // https://aelia.co/shop/currency-switcher-for-easy-digital-downloads/
            if (has_filter('edd_aelia_cs_convert')) {
                $base_currency = 'USD';
                $edd_settings = edd_get_settings();

                if (isset($edd_settings['currency'])) {
                    $base_currency = $edd_settings['currency'];
                }

                if (edd_get_currency() !== $base_currency) {
                    $item['price'] = apply_filters(
                        'edd_aelia_cs_convert',
                        $item['price'],
                        edd_get_currency(),
                        $base_currency
                    );
                }
            } else {
              // pass the currency to Recapture
              $cart->currency = edd_get_currency();
            }

            $quantity = max($item['quantity'], 1);
            $price_per_item = $item['price'] / $quantity;
            $price = number_format($price_per_item , 2, '.', '');
            $image_url = wp_get_attachment_url(get_post_thumbnail_id($item['id']));

            $variant_id = isset($item['item_number']['options']['price_id'])
                ? $item['item_number']['options']['price_id']
                : null;

            $cart->products[] = [
                'name' => edd_get_cart_item_name($item),
                'imageUrl' => $image_url ? $image_url : '',
                'url' => get_permalink($item['id']),
                'price' => strval($price),
                'externalId' => $item['id'],
                'quantity' => $quantity,
                'variantId' => $variant_id
            ];

            $cart_items[] = $item['id'].'|'.$variant_id.'|'.$item['quantity'];
        }

        $cart_contents = RecaptureUtils::encode_array($cart_items);

        $checkout_params = [
            'racart' => $cart_id,
            'contents' => $cart_contents
        ];

        // set the first discount code
        if (count($discounts) > 0) {
            $checkout_params['discount'] = $discounts[0];
        }
        
        $cart->checkoutUrl = add_query_arg(
          $checkout_params,
          edd_get_checkout_uri()
        );

        RecaptureUtils::send_cart($cart);
    }

    public function straight_to_gateway($purchase_data) {
        $this->set_recapture_cart($purchase_data['cart_details']);
        return $purchase_data;
    }

    public function update_cart() {
        $items = edd_get_cart_content_details();
        $this->set_recapture_cart($items);
    }

    public function insert_payment($payment_id, $payment_data) {
        $cart_id = edd_get_payment_meta($payment_id, '_edd_recapture_cart_id', true);

        // If we don't have a cart ID stored record the cart ID with the meta data and set a new ID
        if ($cart_id == false) {
            edd_update_payment_meta($payment_id, '_edd_recapture_cart_id', RecaptureUtils::get_cart_id());
            RecaptureUtils::set_new_cart_id();
        }
    }

    public function order_completed($payment_id)
    {
        if ($this->disable_conversion_tracking) {
            return;
        }

        $user_info = edd_get_payment_meta_user_info($payment_id);
        $country = '';
        if (!empty($user_info['address']) && ! empty($user_info['address']['country'])) {
            $country = $user_info['address']['country'];
        }

        $name = self::get_customer_first_and_last_from_order($payment_id);

        // Get the existing ID
        $external_id = edd_get_payment_meta($payment_id, '_edd_recapture_cart_id', true);

        $data = (object) [
            'externalId' => $external_id != false ? $external_id : null,
            'orderId' => $payment_id,
            'shippingCountry' => $country,
            'billingCountry' => $country,
            'firstName' => $name != null
                ? $name->first_name
                : null,
            'lastName' => $name != null
                ? $name->last_name
                : null,
            'email' => edd_get_payment_user_email($payment_id)
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

        // empty the current cart - if any
        $this->remove_actions();

        // Clear any old cart_contents
        edd_empty_cart();

        foreach ($contents as $item) {
            $parts = explode('|', $item);

            $download_id = $parts[0];
            $options = isset($parts[1])
                ? [
                    'price_id' => $parts[1],
                    'quantity' =>$parts[2]
                ]
                : ['quantity' => $parts[2]];

            edd_add_to_cart($download_id, $options);
        }

        // Apply the discount discount code if we have been passed one
        if (isset($_GET['discount'])) {
            $code = sanitize_text_field($_GET['discount']);
            if (edd_is_discount_valid($code, '', false)) {
                edd_set_cart_discount($code);
            }
        }

        // send the cart to Recapture
        $items = edd_get_cart_content_details();
        $this->set_recapture_cart($items);

        $this->add_actions();
    }

    function is_product_page() {
        return is_singular('download');
    }

    public static function is_ready() {
        return class_exists('Easy_Digital_Downloads');
    }

    public function enqueue_scripts() {
    }

    public static function create_unique_discount_code($spec) {
        if (!isset($spec->code)
            || !isset($spec->name)
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

        $details = [
            'code' => $spec->code,
            'name' => $spec->name,
            'amount' => $spec->amount,
            'type' => $spec->type,
            'expiration' => $expires,
            'min_price' => $spec->min_price,
            'status' => 'active',
            'use_once' => 1,
            'max' => 1,
            'uses' => 0
        ];

        $id = edd_store_discount($details, null);

        if (!is_numeric($id)) {
            return null;
        }

        //get discount code
        $discount = edd_get_discount($id);

        return (object) [
            'id' => $id,
            'code' => $discount->get_code(),
            'expires' => $expires
        ];
    }

    public static function save_reviews($external_id, $author, $email, $reviews) {
        if (!function_exists('edd_reviews')) {
            return;
        }

        $comment_author_ip = $_SERVER['REMOTE_ADDR'];
        $comment_author_ip = preg_replace('/[^0-9a-fA-F:., ]/', '', $comment_author_ip);

        $user = get_user_by('email', $email);

        if ($user == false) {
            $user = wp_get_current_user();
        }

        // set to 1 to auto-approve
        $approved = 0;

        foreach ($reviews as $review) {
            // Find the user by email
            // save the reviews
            $args = apply_filters(
                'edd_reviews_insert_review_args',
                array(
                    'comment_post_ID'      => $review->external_id,
                    'comment_author'       => $author,
                    'comment_author_email' => $email,
                    'comment_author_url'   => '',
                    'comment_content'      => $review->detail,
                    'comment_type'         => 'edd_review',
                    'comment_parent'       => '',
                    'comment_author_IP'    => $comment_author_ip,
                    'comment_agent'        => isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 254) : '',
                    'user_id'              => $user->ID,
                    'comment_date'         => current_time('mysql'),
                    'comment_date_gmt'     => current_time('mysql', 1),
                    'comment_approved'     => 1,
               )
            );

            $args = apply_filters('preprocess_comment', $args);

            $review_id = wp_insert_comment(wp_filter_comment($args));

            add_comment_meta($review_id, 'edd_rating', $review->rating);
            add_comment_meta($review_id, 'edd_review_title', $review->title);
            add_comment_meta($review_id, 'edd_review_approved', $approved);

            // Add review metadata to the $args so it can be passed to the notification email
            $args['id']              = $review_id;
            $args['rating']          = $review->rating;
            $args['review_title']    = $review->title;
            $args['review_approved'] = $approved;

            if (!edd_get_option('edd_reviews_settings_emails_disable_notifications', false)) {
                if (
                    (!edd_get_option('edd_reviews_settings_emails_toggle', false) && $approved !== 1) ||
                    edd_get_option('edd_reviews_settings_emails_toggle', false)
                ) {
                    edd_reviews()->email_admin_notification($review_id, $args);
                }
            }

            update_post_meta($review->external_id, 'edd_reviews_average_rating', edd_reviews()->average_rating(false, $review->external_id));

            if ($approved == 1) {
                $this->create_reviewer_discount($review_id, $args);
            }
        }
    }

    public static function get_customer_email_from_order($order_id) {
        $payment = new EDD_Payment($order_id);

        if ($payment == false) {
            return '';
        }

        return $payment->email;
    }

    public static function get_customer_name_from_order($order_id) {
        $payment = new EDD_Payment($order_id);

        if ($payment == false) {
            return '';
        }

        $customer = new EDD_Customer($payment->customer_id);

        if ($customer == false) {
            return '';
        }

        return $customer->name;
    }

    public static function get_customer_first_and_last_from_order($order_id) {
        $name = self::get_customer_name_from_order($order_id);

        if ($name == false) {
            return null;
        }

        $parts = explode(' ', $name);

        return (object) [
            'first_name' => count($parts) > 0
                ? $parts[0]
                : '',
            'last_name' => count($parts) > 1
                ? $parts[1]
                : ''
        ];
    }

    public static function get_customer_first_name_from_order($order_id) {
        $name = self::get_customer_first_and_last_from_order($order_id);

        return $name == null
            ? ''
            : $name->first_name;
    }
}
