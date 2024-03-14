<?php
function skt_donation_paypal_process_function(){
  $mode_of_paypal = isset($_GET['mode_of_paypal']) ? $_GET['mode_of_paypal'] : '';
  global $wpdb;
  if($mode_of_paypal=="simple_paypal"){
    global $post;
    $page_id = $post->ID;
    $paypal_payment_id = sanitize_text_field($_GET['paymentID']);
    $paypal_payer_id = sanitize_text_field($_GET['payerID']);
    $paypal_token = sanitize_text_field($_GET['token']);
    $donation_amount = sanitize_text_field($_GET['donation_amount']);
    $customer_firstname = sanitize_text_field($_GET['first_name']);
    $customer_lastname = sanitize_text_field($_GET['last_name']);
    $customer_email = sanitize_text_field($_GET['email']);
    $customer_phone = sanitize_text_field($_GET['phone']);
    $mode="paypal";
    $status = "paid";
    $current_date = date('d-m-Y');
    $table_name = $wpdb->prefix ."skt_donation_amount"; 
    $data_donation_amt = array(
      'customer_firstname' => $customer_firstname,
      'customer_lastname' => $customer_lastname,
      'customer_email' => $customer_email,
      'customer_phone' => $customer_phone,
      'paypal_payment_id' => $paypal_payment_id,
      'paypal_payer_id' => $paypal_payer_id,
      'paypal_token' => $paypal_token,
      'mode' => "paypal",
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
      $paypal_success = "Payment Sucessfully Completed and Transaction ID : ".$paypal_payment_id;
      $path_name = get_site_url().'/?page_id='.$page_id.'&payment_result_success='.$paypal_success.'&payment_gatway=payment_success&payment_gatway_result=result';
      echo '<script>window.location = "'.$path_name.'";</script>';
      exit();
    }else{
      $paypal_success = "Payment Sucessfully Completed But Data Not Save In Our System and Transaction ID : ".$paypal_payment_id;
      $path_name = get_site_url().'/?page_id='.$page_id.'&payment_result_success='.$paypal_success.'&payment_gatway=payment_success&payment_gatway_result=result';
        echo '<script>window.location = "'.$path_name.'";</script>';
      exit();
    }
  }
}
?>