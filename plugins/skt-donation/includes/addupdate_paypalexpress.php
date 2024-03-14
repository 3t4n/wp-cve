<?php   
function skt_donation_add_paypalexpresssubscription_function(){
    if ( !isset( $_POST['add_paypalexpress_nonce'] ) || !wp_verify_nonce($_POST['add_paypalexpress_nonce'], 'paypalexpress_subscriptionnormal' ) ){    
    }else{
    global $wpdb;
    global $post;
    $page_id = $post->ID;
    $donation_amount = sanitize_text_field($_POST['donation_amount']);
    $customer_firstname = sanitize_text_field($_POST['first_name']);
    $customer_lastname = sanitize_text_field($_POST['last_name']);
    $customer_email = sanitize_text_field($_POST['email']);
    $customer_phone = sanitize_text_field($_POST['phone']);
    $paypalexpsubscription_id = sanitize_text_field($_POST['paypalexpsubscription_id']);
    $mode="paypalexpress";
    $status = "paid";
    $current_date = date('d-m-Y');
    $table_name = $wpdb->prefix ."skt_donation_amount"; 
    $data_donation_amt = array(
      'customer_firstname' => $customer_firstname,
      'customer_lastname' => $customer_lastname,
      'customer_email' => $customer_email,
      'customer_phone' => $customer_phone,
      'paypalrxpress_subscriptions_id' => $paypalexpsubscription_id,
      'mode' => "paypalexpress",
      'status' => 'paid',
      'donation_amount' => $donation_amount,
      'payment_date' => $current_date,
    );
    $insert_data = $wpdb->insert( $table_name, $data_donation_amt );
    if($insert_data){
      /*********Email functiion start here*****/
      $admin_email_address = esc_attr( get_option('skt_donation_skt_email_address') );
      $email_subject = esc_attr( get_option('skt_donation_skt_email_subject') );
      $email_message = esc_attr( get_option('skt_donation_skt_email_message') );
      $to = $customer_email;
        // subject
        $subject = $email_subject;
        // compose message
        $message = "
        <html>
          <head>
            <title></title>
          </head>
          <body>
            <p>".$email_message."</p>
          </body>
        </html>
        ";
        // To send HTML mail, the Content-type header must be set
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        $headers .= 'From: <'.$admin_email_address.'>' . "\r\n";
        // send email
        mail($to, $subject, $message, $headers);
        /*********Email functiion end here*******/
      $paypal_success = "Payment Sucessfully Completed and Transaction ID : ".$paypalexpsubscription_id;
      $path_name = get_site_url().'/?page_id='.$page_id.'&payment_result_success='.$paypal_success.'&payment_gatway=payment_success&payment_gatway_result=result';
      echo '<script>window.location = "'.$path_name.'";</script>';
      exit();
    }else{
      $paypal_success = "Payment Sucessfully Completed But Data Not Save In Our System and Transaction ID : ".$paypalexpsubscription_id;
      $path_name = get_site_url().'/?page_id='.$page_id.'&payment_result_success='.$paypal_success.'&payment_gatway=payment_success&payment_gatway_result=result';
        echo '<script>window.location = "'.$path_name.'";</script>';
      exit();
    }
    }
}
?>