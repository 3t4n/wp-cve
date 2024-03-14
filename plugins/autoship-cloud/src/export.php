<?php

/**
* Retrieves the Legacy WC Autoship Schedule Records.
* @return array The WC Autoship Records.
*/
function autoship_legacy_export_query_ids(){

  global $wpdb;
  $wp = $wpdb->prefix;

  // Set to false to exclude Scheduled Orders without Payment Method or Line Items
  $join = apply_filters( 'autoship_legacy_export_query_ids_include_all', true ) ? 'LEFT JOIN': 'JOIN';

  $query = "
  SELECT schedules.*, tokens.gateway_id,schedule_items.*  FROM
  {$wp}wc_autoship_schedules as schedules
  {$join} {$wp}woocommerce_payment_tokens as tokens
  ON schedules.payment_token_id = tokens.token_id
  {$join} {$wp}wc_autoship_schedule_items as schedule_items ON schedules.id = schedule_items.schedule_id
  ORDER BY schedules.id ASC
  ";

  $wc_autoship_schedules = $wpdb->get_results($query, ARRAY_A);

  return $wc_autoship_schedules;

}

/**
* Returns the Export Path
*/
function autoship_export_filepath_url(){
  return apply_filters('autoship_export_filepath_url', WP_CONTENT_DIR . '/uploads/schedule-data/' );
}

/**
* Retrieves the Download Link When A file Exists.
* @return string The url or empty string if none exists.
*/
function autoship_get_export_download_link(){

  $file = get_option('autoship_last_export');
  $path = trailingslashit( autoship_export_filepath_url() );

  if ( empty( $file ) )
  return '';

  if ( !file_exists( $path . $file ) ){

    update_option( 'autoship_last_export', '' );
    return '';

  }
  return get_site_url() . '/wp-admin/admin.php?page=migrations&autoship_download_csv';
}

/**
 * Exports the WC Autoship Scheduled Orders to a CSV file.
 *
 * @param int The payment token id
 * @return bool|WC_Payment_Token_CC False on failure else WC_Payment_Token_CC object
 */
function autoship_export_retrieve_payment_token( $token_id ){

  try {

    $payment_method = new WC_Payment_Token_CC($token_id);

  } catch (Exception $e) {

    $payment_method = false;

  }

  return $payment_method;

}

/**
* Exports the WC Autoship Scheduled Orders to a CSV file.
*
* @param bool $ajax True if the call is an ajax call else false.
*                   Default True.
* @return int The total number of Scheduled Orders exported
*/
function autoship_export_ajax_scheduled_orders( $ajax = true, $email = true ) {

  $wc_autoship_schedules = autoship_legacy_export_query_ids();

  try {

    // Check if there's anything to even export
    if ( empty( $wc_autoship_schedules ) )
    throw new Exception( 'no-orders-to-export', 500 );

    $current_server_time  = date("Y_m_d_g_i_s_a");
    $filename             = 'autoship_schedules_export_' . $current_server_time . '.csv';
    $logfilename          = 'autoship_schedules_export_' . $current_server_time . '_activity.log';
    $path                 = trailingslashit( autoship_export_filepath_url() );

    // Check for the Export Directory and Make it if it doesn't exist.
    $dirname = dirname( $path . $filename );
    if ( !is_dir($dirname) ){
      $dir = mkdir($dirname, 0755, true);

      if (!$dir)
      throw new Exception( 'directory-creation-failed', 500 );
    }

    // Open / Create the Log File and Export File.
    $log_file             = fopen( $path . $logfilename, 'a');
    $file                 = fopen( $path . $filename, 'w+');

    if (!$file)
    throw new Exception( 'file-creation-failed', 500 );

    $message = "File {$filename} created successfully.";
    fwrite($log_file, "\n" . $current_server_time . " :: " . $message);

    // Add the Column Headers to the Export file.
    $fields = array(
      'Customer_externalid',
      'Customer_Email',
      'Customer_FirstName',
      'Customer_LastName',
      'Customer_Created',
      'Customer_ShippingFirstName',
      'Customer_ShippingLastName',
      'Customer_ShippingStreet1',
      'Customer_ShippingStreet2',
      'Customer_ShippingCity',
      'Customer_ShippingState',
      'Customer_ShippingPostcode',
      'Customer_ShippingCountry',
      'Customer_BillingFirstName',
      'Customer_BillingLastName',
      'Customer_BillingStreet1',
      'Customer_BillingStreet2',
      'Customer_BillingCity',
      'Customer_BillingState',
      'Customer_BillingPostcode',
      'Customer_BillingCountry',
      'PaymentMethod_PaymentMethodType',
      'PaymentMethod_GatewayCustomerId',
      'PaymentMethod_GatewayPaymentId',
      'PaymentMethod_Description',
      'ScheduledOrder_ShippingFirstName',
      'ScheduledOrder_ShippingLastName',
      'ScheduledOrder_ShippingStreet1',
      'ScheduledOrder_ShippingStreet2',
      'ScheduledOrder_ShippingCity',
      'ScheduledOrder_ShippingState',
      'ScheduledOrder_ShippingPostcode',
      'ScheduledOrder_ShippingCountry',
      'ScheduledOrder_LastOccurrence',
      'ScheduledOrder_NextOccurrence',
      'ScheduledOrder_Status',
      'ScheduledOrder_Frequency',
      'ScheduledOrder_FrequencyType',
      'ScheduledOrder_Cycles',
      'Product_ExternalId',
      'Product_Title',
      'Product_Qty',
      'Product_Price',
      'Product_SalePrice'
    );
    $success = fputcsv( $file, $fields);

    if ( !$success )
    throw new Exception( 'column-header-creation-failed', 500 );

    $message = "Header created in CSV file & inserting data in CSV";
    fwrite($log_file, "\n" . $current_server_time . " :: " . $message);

    // Go through each scheduled order and make a line for each with
    // a valid customer in the Export file.
    $customers = array();
    $invalids  = $invalid_orders = $valid_orders = 0;
    foreach ( $wc_autoship_schedules as $key => $single_schedule) {

      if (isset($single_schedule["gateway_id"])) {
          switch ($single_schedule['gateway_id']) {
              case 'stripe':
                  $single_schedule['payment_method']      = wc_autoship_import_get_stripe_method($single_schedule['payment_token_id']);
                  $single_schedule['paymentmethod_type']  = 'Stripe';
                  break;
              case 'wc_autoship_authorize_net':
                  $single_schedule['payment_method']      = wc_autoship_import_get_authorize_method($single_schedule['payment_token_id']);
                  $single_schedule['paymentmethod_type']  = 'AuthorizeNet';
                  break;
              case 'wc_autoship_braintree':
                  $single_schedule['payment_method']      = wc_autoship_import_get_braintree_method($single_schedule['payment_token_id']);
                  $single_schedule['paymentmethod_type']  = 'Braintree';
                  break;
              case 'wc_autoship_cyber_source':
                  $single_schedule['payment_method']      = wc_autoship_import_get_cyber_source_method($single_schedule['payment_token_id']);
                  $single_schedule['paymentmethod_type']  = 'CyberSource';
                  break;
              case 'wc_autoship_paypal':
                  $single_schedule['payment_method']      = wc_autoship_import_get_paypal_method($single_schedule['payment_token_id']);
                  $single_schedule['paymentmethod_type']  = 'Braintree';
                  break;
              default:
                  $single_schedule['payment_method']      = null;
                  $single_schedule['paymentmethod_type']  = null;
          }
      }

      $wc_customer_id = $single_schedule['customer_id'];

      if ( !isset( $customers[$wc_customer_id]) ){

        // Check if the customer is invalid already.
        $user_exists = !isset( $invalid_customers[$wc_customer_id] );
        $user_exists = $user_exists ? get_userdata( $wc_customer_id ): $user_exists;

        if ( false === $user_exists ){
          $invalid_orders++;
          $invalid_customers[$wc_customer_id] = $user_exists;
          $message = sprintf( __( 'Customer %d could not be found or is invalid. This scheduled order #%d will not be included.', 'autoship' ), $wc_customer_id, $single_schedule['id'] );
          fwrite($log_file, "\n" . $current_server_time . " :: " . $message);
          continue;
        }

        // No issues so get the customer.
        $customers[$wc_customer_id] = new WC_Customer($wc_customer_id);

      }

      $wc_customer = $customers[$wc_customer_id];

      $payment_method = $description = null;
      if ( isset( $single_schedule['payment_token_id'] ) ){

        $payment_method = autoship_export_retrieve_payment_token( $single_schedule['payment_token_id'] );

        // Check if payment method retrieval failed
        $description = $payment_method ?
        $payment_method->get_display_name() : sprintf( __( 'Payment Method with Token ID %d could not be found or is invalid.', 'autoship' ), $single_schedule['payment_token_id'] );

      }

      $external_id = !empty($single_schedule['variation_id']) ? $single_schedule['variation_id'] : $single_schedule['product_id'];

      if ( !isset( $external_id ) ){
        $product = false;
      } else {
        $product     = wc_get_product($external_id);

        // Check for invalid or missing products
        if ( !$product ){
          $invalids++;
          $message = sprintf( __( 'Product %d could not be found or is invalid. This product will not be included.', 'autoship' ), $external_id );
          fwrite($log_file, "\n" . $current_server_time . " :: " . $message);
        }
      }

      $sku                  = !$product ? '' : $product->get_sku();
      $product_price        = !$product ? '' : floatval($product->get_price());
      $title                = !$product ? '' : $product->get_name();
      $product_description  = !$product ? '' : $product->get_description();
      $length               = !$product ? '' : $product->get_length();
      $width                = !$product ? '' : $product->get_width();
      $height               = !$product ? '' : $product->get_height();
      $weight               = !$product ? '' : $product->get_weight();
      $Product_ImageUrl     = !$product ? '' : autoship_get_wc_product_image_url( $external_id );
      $product_tax_class    = !$product ? '' : $product->get_tax_class();
      $product_thumb_url    = !$product ? '' : get_permalink( $single_schedule['product_id']);
      $autoship_price       = !$product ? '' : get_post_meta( $single_schedule['product_id'], '_wc_autoship_price', true);

      $data['Customer_externalid']              = $wc_customer_id;
      $data['Customer_Email']                   = get_user_meta($single_schedule['customer_id'], 'billing_email', true);
      $data['Customer_FirstName']               = get_user_meta($single_schedule['customer_id'], 'billing_first_name', true);
      $data['Customer_LastName']                = get_user_meta($single_schedule['customer_id'], 'billing_last_name', true);
      $data['Customer_Created']                 = '';
      $data['Customer_ShippingFirstName']       = substr($wc_customer->get_shipping_first_name(), 0, 40);
      $data['Customer_ShippingLastName']        = substr($wc_customer->get_shipping_last_name(), 0, 40);
      $data['Customer_ShippingStreet1']         = $wc_customer->get_shipping_address_1();
      $data['Customer_ShippingStreet2']         = $wc_customer->get_shipping_address_2();
      $data['Customer_ShippingCity']            = substr($wc_customer->get_shipping_city(), 0, 30);
      $data['Customer_ShippingState']           = $wc_customer->get_shipping_state();
      $data['Customer_ShippingPostcode']        = $wc_customer->get_shipping_postcode();
      $data['Customer_ShippingCountry']         = $wc_customer->get_shipping_country();
      $data['Customer_BillingFirstName']        = get_user_meta($single_schedule['customer_id'], 'billing_first_name', true);
      $data['Customer_BillingLastName']         = get_user_meta($single_schedule['customer_id'], 'billing_last_name', true);
      $data['Customer_BillingStreet1']          = get_user_meta($single_schedule['customer_id'], 'billing_address_1', true);
      $data['Customer_BillingStreet2']          = get_user_meta($single_schedule['customer_id'], 'billing_address_2', true);
      $data['Customer_BillingCity']             = get_user_meta($single_schedule['customer_id'], 'billing_city', true);
      $data['Customer_BillingState']            = get_user_meta($single_schedule['customer_id'], 'billing_state', true);
      $data['Customer_BillingPostcode']         = get_user_meta($single_schedule['customer_id'], 'billing_postcode', true);
      $data['Customer_BillingCountry']          = get_user_meta($single_schedule['customer_id'], 'billing_country', true);
      $data['PaymentMethod_PaymentMethodType']  = isset( $single_schedule['paymentmethod_type'] ) ?  $single_schedule['paymentmethod_type'] : NULL;
      $data['PaymentMethod_GatewayCustomerId']  = isset( $single_schedule['payment_method'] )     ? $single_schedule['payment_method']['customer_id'] : NULL;
      $data['PaymentMethod_GatewayPaymentId']   = isset( $single_schedule['payment_method'] )     ? $single_schedule['payment_method']['token'] : NULL;
      $data['PaymentMethod_Description']        = $description;
      $data['ScheduledOrder_ShippingFirstName'] = substr($wc_customer->get_shipping_first_name(), 0, 20);
      $data['ScheduledOrder_ShippingLastName']  = substr($wc_customer->get_shipping_last_name(), 0, 20);
      $data['ScheduledOrder_ShippingStreet1']   = $wc_customer->get_shipping_address_1();
      $data['ScheduledOrder_ShippingStreet2']   = $wc_customer->get_shipping_address_2();
      $data['ScheduledOrder_ShippingCity']      = substr($wc_customer->get_shipping_city(), 0, 30);
      $data['ScheduledOrder_ShippingState']     = $wc_customer->get_shipping_state();
      $data['ScheduledOrder_ShippingPostcode']  = $wc_customer->get_shipping_postcode();
      $data['ScheduledOrder_ShippingCountry']   = $wc_customer->get_shipping_country();
      $data['ScheduledOrder_LastOccurrence']    = $single_schedule['last_order_date'];
      $data['ScheduledOrder_NextOccurrence']    = $single_schedule['next_order_date'];
      $data['ScheduledOrder_Status']            = $single_schedule['autoship_status'] == '1' ? 'Active' : 'Paused';
      $data['ScheduledOrder_Frequency']         = $single_schedule['autoship_frequency'];
      $data['ScheduledOrder_FrequencyType']     = 'Days';
      $data['ScheduledOrder_Cycles']            = NULL;
      $data['Product_ExternalId']               = $external_id;
      $data['Product_Title']                    = $title;
      $data['Product_Qty']                      = $single_schedule['qty'];
      $data['Product_Price']                    = $product_price;
      $data['Product_SalePrice']                = $autoship_price;

      $written = fputcsv($file, $data);

      if ( $written ){
      $valid_orders++;
      $message = $wc_customer_id.' Record inserted in CSV';
      fwrite($log_file, "\n" . $current_server_time . " :: " . $message); }
      else {
      $message = $wc_customer_id.' Record insertion into CSV Failed!';
      fwrite($log_file, "\n" . $current_server_time . " :: " . $message); }

    }

    $message = "File created and populated with {$valid_orders} records successfully";
    fwrite( $log_file, "\n" . $current_server_time . " :: " . $message);

    $file = fclose($file);
    fclose($log_file);

    update_option( 'autoship_last_export', $filename );

    if ( $email )
    autoship_export_email_notification();

  } catch (Exception $e) {

    $result = array();

    if ( 'directory-creation-failed' == $e->getMessage() )
    $result = array( 'code' => 500, 'success' => false, 'notice' => printf( __('The Directory at %s can\'t be created! Please check permissions.', 'autoship'), $path ) );

    if ( 'column-header-creation-failed' == $e->getMessage() )
    $result = array( 'code' => 500, 'success' => false, 'notice' => __('Writing Column Headers to CSV File failed.', 'autoship') );

    if ( 'file-creation-failed' == $e->getMessage() )
    $result = array( 'code' => 500, 'success' => false, 'notice' => printf( __('CSV File %s can\'t be opened and/or created! Please check rights for %s.', 'autoship'), $filename, $path ) );

    if ( 'no-orders-to-export' == $e->getMessage() )
    $result = array( 'code' => 200, 'success' => true, 'notice' => __('No Scheduled Orders to Export', 'autoship') );

    if ( empty( $result ) )
    $result = array( 'code' => $e->getCode(), 'success' => false, 'notice' => $e->getMessage() );

    if ( $ajax )
    autoship_ajax_result( $result['code'], $result );

    return $result;

  }

  $result = array(
    'success' => true,
    'code'    => 200,
    'notice'  => sprintf( __('%d Scheduled Orders successfully exported to %s.', 'autoship'), count( $wc_autoship_schedules ), $filename ),
    'filename'=> $filename,
    'path'    => $path
  );

  if ( $invalid_orders == count( $wc_autoship_schedules ) ){
    $result['notice'] = sprintf( __('None of the %d Scheduled Orders could be exported. See the log for additional details in %s', 'autoship'), count( $wc_autoship_schedules ), $log_path );
  } else if ( $invalid_orders ){
    $result['notice'] = sprintf( __('%d Scheduled Orders successfully exported to %s.  %d Scheduled Orders were missing or invalid and could not be exported.', 'autoship'), count( $wc_autoship_schedules ), $filename, count( $invalid_orders ) );
  }

  if ( $ajax )
  autoship_ajax_result( $result['code'], $result );

  return $result;

}

/**
* Sets the Email Type to HTML
*/
function autoship_set_html_content_type() {
	return 'text/html';
}

/**
* Autoship Export Email Notification
* @param string $file The file name and path to the export.
*/
function autoship_export_email_notification(){

  $email         = get_bloginfo('admin_email');
  $headers       = 'From: Autoship System Exports <'. $email .'>' . "\r\n";
  $subject       = "Completed Autoship Schedules Export";
  $url           = autoship_get_export_download_link();

  if ( empty( $url ) )
  return;

  $body = 'Below is the URL to download the Autoship CSV export file.<br/>';
  $body .= '<a href="' . $url . '" target="_blank">Download Export File</a>';

  add_filter( 'wp_mail_content_type', 'autoship_set_html_content_type' );

  $status = wp_mail( $email, $subject, $body, $headers );

  // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
  remove_filter( 'wp_mail_content_type', 'autoship_set_html_content_type' );

}

/**
* Checks for Download Init and echo's the screen.
*/
function autoship_download_csv() {

  $file = get_option('autoship_last_export');

  if ( empty( $file ) )
  return;

  $path = trailingslashit( autoship_export_filepath_url() );


  if ( !file_exists( $path . $file ) ){

    update_option( 'autoship_last_export', '' );

  } else if ( current_user_can( 'export' ) && isset( $_GET['autoship_download_csv'] ) ) {

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="'. $file .'"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $outstream = fopen("php://output",'w');
    $contents = file_get_contents ( $path . $file );
    echo $contents;
    fclose($outstream);
    exit();
  }

}
add_action( 'init', 'autoship_download_csv' );

/**
* Autoship Export Ajax Trigger
*/
function autoship_ajax_initiate_schedule_export() {
    autoship_export_ajax_scheduled_orders();
    exit(1);
}
add_action('wp_ajax_autoship_initiate_schedule_export', 'autoship_ajax_initiate_schedule_export');
