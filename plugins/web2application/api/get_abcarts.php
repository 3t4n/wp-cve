<?php




global $wpdb;

// 1. Retrieve session data
$results = $wpdb->get_results("
    SELECT session_key, session_value 
    FROM {$wpdb->prefix}woocommerce_sessions
    WHERE session_expiry > UNIX_TIMESTAMP()
");

$abandoned_carts = [];
$existing_orders = [];

// Get list of all orders' cart session keys
$orders = $wpdb->get_col("
    SELECT meta_value
    FROM {$wpdb->postmeta}
    WHERE meta_key = '_customer_user'
");
if ($orders) {
    $existing_orders = array_map('intval', $orders);
}

foreach ($results as $result) {
    $session_data = maybe_unserialize($result->session_value);

    // 2. Extract cart details
    if (isset($session_data['cart'])) {
        $cart = maybe_unserialize($session_data['cart']);
        $user_id = intval($result->session_key);

        // 3. Check if cart is truly abandoned
        if (!empty($cart) && !in_array($user_id, $existing_orders)) {
            $data = [
                'cart' => $cart
            ];
            
            // 4. Fetch contact details if available
            if (isset($session_data['billing_email'])) {
                $data['billing_email'] = $session_data['billing_email'];
            }
            if (isset($session_data['billing_phone'])) {
                $data['billing_phone'] = $session_data['billing_phone'];
            }

            $abandoned_carts[$user_id] = $data;
        }
    }
}

print_r($abandoned_carts);



?>