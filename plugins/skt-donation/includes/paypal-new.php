 <?php 
if ( esc_attr(get_option('skt_donation_paypalexp_mode_zero_one') == 'true' )){
	    $clientId = esc_attr( get_option('skt_donation_paypalexp_test_api') );
	    $secret = esc_attr( get_option('skt_donation_paypalexp_secretkey') );
	    $sandbox_live = "https://api-m.sandbox.paypal.com";
	}else{
	   $clientId = esc_attr( get_option('skt_donation_paypalexp_live_api') );
	   $secret = esc_attr( get_option('skt_donation_paypalexpIlive_secretkey') );
	   $sandbox_live = "https://api-m.paypal.com";
	}

$page_id = get_queried_object_id();
$get_pageurl = get_the_permalink($page_id);
// Set up PayPal API credentials
$client_id = $clientId;
$client_secret = $secret;

// Set up API endpoints
$api_base = $sandbox_live; // Sandbox environment
$create_order_url = $api_base . '/v2/checkout/orders';

// Set up request data
$data = array(
    'intent' => 'CAPTURE',
    'purchase_units' => array(
        array(
            'amount' => array(
                'currency_code' => $payment_in_currency,
                'value' => $donation_amount // Set your purchase amount here
            )
        )
    ),
    'application_context' => array(
        'brand_name' => 'Donation',
        'landing_page' => 'NO_PREFERENCE', // Set to 'NO_PREFERENCE', 'BILLING', or 'LOGIN'
        'user_action' => 'PAY_NOW', // Set to 'CONTINUE' or 'PAY_NOW'
        'return_url' => $get_pageurl.'?mode=paypalsuccess&first_name='.$first_name.'&last_name='.$last_name.'&email='.$email.'&phone='.$phone.'&donation_amount='.$donation_amount.'&payment_in_currency='.$payment_in_currency, // Redirect URL after payment completion
        'cancel_url' => $get_pageurl.'?mode=paypalfail' // Redirect URL if the user cancels the payment
    )
);

// Convert data to JSON
$json_data = json_encode($data);

// Set up cURL options
$curl = curl_init($create_order_url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode("$client_id:$client_secret")
));

// Execute cURL request
$response = curl_exec($curl);
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Check for errors
if ($http_status !== 201) {
    echo "Error: Failed to create order. HTTP Status Code: $http_status";
} else {
    // Parse the response JSON
    $order = json_decode($response, true);

    // Get the approval URL from the response
    $approval_url = '';
    foreach ($order['links'] as $link) {
        if ($link['rel'] === 'approve') {
            $approval_url = $link['href'];
            break;
        }
    }

    // Redirect the user to PayPal for payment approval
    header("Location: $approval_url");
}
curl_close($curl);
?>
