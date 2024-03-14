<?php   
function skt_donation_add_paypalsubscription_function(){
    if ( !isset( $_POST['add_paypal_nonce'] ) || !wp_verify_nonce($_POST['add_paypal_nonce'], 'paypal_subscriptionnormal' ) ){    
}else{
        $page_id = get_queried_object_id();
        $site_url = esc_url(get_site_url().'/?page_id='.$page_id);
        include_once( SKT_DONATIONS_DIR .'/payment-method/paypal-subscription/stdpaypal.php');
        // For Normal PayPal Integration 
        if ( esc_attr(get_option('skt_donation_paypal_mode_zero_one') == 'true' )){
            $env_production_sandbox = 'sandbox';
            $paypal_test_api = esc_attr( get_option('skt_donation_paypal_test_api') );
        }else{
            $env_production_sandbox = 'production';
            $paypal_live_api = esc_attr( get_option('skt_donation_paypal_live_api') );
        }
        // For Subscription PayPal Integration 
        $config = "";
        $mode = esc_attr( get_option('skt_donation_paypal_mode_zero_one') );
        $skt_donation_test_paypal_business_email = esc_attr( get_option('skt_donation_test_paypal_business_email') );
        $skt_donation_live_paypal_business_email = esc_attr( get_option('skt_donation_live_paypal_business_email') );
        if($mode=="true"){
            $config = array(
                'paypal_use_sandbox' => true,
                'paypal_business_email' => $skt_donation_test_paypal_business_email
            );
        }else{
            $config = array(
                'paypal_use_sandbox' => false,
                'paypal_business_email' => $skt_donation_live_paypal_business_email
            );
        }
        $paypal = new StdPayPal($config);
        $paypal_mode_subscription = isset($_POST['paypal_mode_subscription']) ? $_POST['paypal_mode_subscription'] : '';
         $site_website_name = get_bloginfo( 'name' );
        if ($paypal_mode_subscription=='paypal_mode') {
            $page_id = sanitize_text_field($_POST['page_id']);
            $first_name = sanitize_text_field($_POST['first_name']);
            $last_name = sanitize_text_field($_POST['last_name']);
            $email = sanitize_email($_POST['email']);
            if($email==""){
              $errorMessages ="Email is incorrect";
              $path_name = get_site_url().'/?page_id='.$page_id.'&payment_fail='.$errorMessages.'&payment_gatway=payment_fail&payment_gatway_result=result';
              echo '<script>window.location = "'.$path_name.'";</script>';
              exit();
            }
            $phone = sanitize_text_field($_POST['phone']);
            $donation_amount = sanitize_text_field($_POST['donation_amount']);
            $total_cycle = "";
            $product_name = $site_website_name;
            $product_currency =  sanitize_text_field($_POST['payment_in_currency']);
            if ($_POST['paypal_recurring'] == 'Daily') {
                $cycle = 'D';
                $duration_of_subscription ="daily";
            } else if ($_POST['paypal_recurring'] == 'Weekly') {
                $cycle = 'W';
                $duration_of_subscription ="week";
            } else if ($_POST['paypal_recurring'] == 'Month') {
                $cycle = 'M';
                $duration_of_subscription ="month";
            } else if ($_POST['paypal_recurring'] == 'Yearly') {
                $cycle = 'Y';
                $duration_of_subscription ="year";
            }
            $site_url_fail = get_site_url().'/?page_id='.$page_id.'&paypal_subscription=subscription_fail';
            $site_url_success = get_site_url().'/?page_id='.$page_id.'&paypal_subscription=subscription_success&first_name='.$first_name.'&last_name='.$last_name.'&email='.$email.'&phone='.$phone.'&donation_amount='.$donation_amount.'&duration_of_subscription='.$duration_of_subscription;
            $params = array(
                'item_name' => $product_name,
                'sra' => 1,  //reattempt failed recurring payments before canceling
                'src' => 1,  //subscription payments recur 
                'srt' => $total_cycle,  //Recurring times. Number of times that subscription payments recur.
                'a3' => $donation_amount,  //Regular subscription price. 
                'p3' => 1,  //Subscription duration.
                't3' => $cycle,  //Regular subscription units of duration. 
                't1' => 'D', // // trial peroid unit
                'custom' => 'STK Donation',
                'invoice' => uniqid(),
                'currency_code' => $product_currency,
                'cancel_return' => $site_url_fail,
                'return' => $site_url_success,
                'image_url' => '',   // set wesite logo to display checkout page,
                'first_name' => '',
                'last_name' => '',
                'address1' => '',
                'address2' => '',
                'city' => '',
                'country' => '',
                'zip' => '',
                'country' => 'UK',
                'email' => ''
            );
            $sub_paypal_detail =  $paypal->subscribe($params);
        }
    }
}
?>