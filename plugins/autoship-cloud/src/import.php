<?php

/**
 * Retrieves the total legacy WC Autoship schedules
 * @param array $processed_schedules The processed Scheduled Orders
 * @param int $id The last processed id
 * @param array $data Optional. Default Data to add
 * @return array The stats array.
 */
function autoship_query_legacy_upsert_stats( $processed_schedules = array(), $id = 0, $data = array() ){

  $stats = get_option( 'autoship_upsert_stats', array() );

  // Get current UTC date time
  // Specified date/time in the specified time zone.
  // $date = new DateTime();
  // $date->setTimezone(new DateTimeZone('UTC'));
  // $now = $date->getTimestamp();
  //
  // // Determine the diff of the two UNIX timestamps in minutes
  // $dateDiff  = !empty( $stats ) && isset( $stats['date'] ) ? abs( $now - $stats['date'] ) / 60 : $limit + 1;
  //
  // // Check if it's stale or not set.
  // if ( empty( $stats ) || ( $dateDiff > $limit ) || apply_filters( 'autoship_clear_upsert_stats', false, $stats ) ){

    $total_orders = autoship_query_legacy_orders();

    $stats['count']       = count( $processed_schedules );
    $stats['total']       = count( $total_orders );
    $stats['last_id']     = $id;

    // Find Batch Counts
    foreach ( $total_orders as $key => $order ) {

      if ( !$id || ( $key > $id ) )
      break;

      $stats['count']++;

    }

    $stats['remaining_batch_count'] = $stats['total'] - $stats['count'];
    $stats['complete_percent'] = (int) ( ( $stats['count'] / $stats['total'] ) * 100);
    $stats = array_merge( $stats, $data );

    update_option( 'autoship_upsert_stats', $stats );
    return $stats;

  //
  // };

}

/**
 * Retrieves legacy WC Autoship schedules
 *
 * @param int $id Optional. A Schedule Id to start from
 * @param int $batch_size Optional. The number of scheduled to retrieve.
 * @return array An array of orders.
 */
function autoship_query_legacy_orders( $id = NULL, $batch_size = NULL, $with_payment_method = true ){

  global $wpdb;
  $wp = $wpdb->prefix;

  $params = array();
  $query = "
  SELECT schedules.*, tokens.gateway_id FROM
  {$wp}wc_autoship_schedules as schedules ";

  if ( $with_payment_method )
  $query .= "JOIN {$wp}woocommerce_payment_tokens as tokens
  ON schedules.payment_token_id = tokens.token_id ";

  if ( isset( $id ) && $id ){
  $query .= "WHERE schedules.id > %d ";
  $params[] = $id; }

  $query .= "ORDER BY schedules.id ASC ";

  if ( isset( $batch_size ) ){
  $query .= "LIMIT %d";
  $params[] = $batch_size; }


	// Query for the Autoship legacy schedules
	$orders = $wpdb->get_results( empty( $params ) ? $query : $wpdb->prepare( $query, $params ), ARRAY_A );

  // Re-org for easy use
  $scheduled_orders = array();
  foreach ( $orders as $order )
  $scheduled_orders[$order['id']] = $order;

  return $scheduled_orders;

}

/**
 * Retrieves legacy WC Autoship schedule items
 *
 * @param array $ids The Schedule Ids to retrieve the items for
 * @return array An array of orders.
 */
function autoship_query_legacy_order_items( $ids ){

  // Safety
  $ids = array_map( 'absint', $ids );

  // Create the range for the query
  $range = implode( ',', $ids );

  global $wpdb;
  $wp = $wpdb->prefix;

  $items = $wpdb->get_results( $wpdb->prepare("
    SELECT * FROM
    {$wp}wc_autoship_schedule_items
    WHERE schedule_id IN ( %s )",
    $range
  ), ARRAY_A );

  // Re-org for easy use
  $scheduled_order_items = array();
  foreach ( $items as $item )
  $scheduled_order_items[$item['schedule_id']][$item['id']] = $item;

  return $scheduled_order_items;

}

/**
 * Migrates Scheduled Orders from the Legacy tables to QPilot
 */
function autoship_ajax_import_schedule_data( $last_id = 0, $ajax = true ) {

	if ( ! current_user_can( 'manage_woocommerce' ) )
	autoship_ajax_result( 403 );

	// Get last exported order id
	$last_id = isset( $_POST['last_id'] ) ? $_POST['last_id'] : $last_id;

  // Retrieve the legacy orders.
  $wc_autoship_schedules = autoship_query_legacy_orders( $last_id, 2 );

	// Get items for each schedule
  $wc_autoship_scheduled_items = autoship_query_legacy_order_items( array_keys( $wc_autoship_schedules ) );

	$failed = array();
	foreach ( $wc_autoship_schedules as $schedule_id => $schedule ) {

		// Create customers in QPilot
		//$qpilot_customer_id = autoship_update_autoship_customer( $schedule['customer_id'] );

    // Check for Scheduled Order Items
    $schedule['schedule_items'] = isset( $wc_autoship_scheduled_items[$schedule_id] ) ? $wc_autoship_scheduled_items[$schedule_id] : array();

    // if ( is_wp_error( $qpilot_customer_id ) )
		// array_push( $failed, array( $schedule_id, $qpilot_customer_id ) );
		// continue;

    // Retrieve the Payment Method Data
		if ( isset( $schedule["gateway_id"] ) ) {
			switch ( $schedule['gateway_id'] ) {
				case 'stripe':
					$schedule['payment_method'] = wc_autoship_import_get_stripe_method( $schedule['payment_token_id'] );
					break;
				case 'wc_autoship_authorize_net':
					$schedule['payment_method'] = wc_autoship_import_get_authorize_method( $schedule['payment_token_id'] );
					break;
				case 'wc_autoship_braintree':
					$schedule['payment_method'] = wc_autoship_import_get_braintree_method( $schedule['payment_token_id'] );
					break;
				case 'wc_autoship_cyber_source':
					$schedule['payment_method'] = wc_autoship_import_get_cyber_source_method( $schedule['payment_token_id'] );
					break;
				case 'wc_autoship_paypal':
					$schedule['payment_method'] = wc_autoship_import_get_paypal_method( $schedule['payment_token_id'] );
					break;
				default:
					$schedule['payment_method'] = null;
			}
		}

		$result =	autoship_import_create_scheduled_order( $schedule );

    if ( is_wp_error( $result ) )
		array_push( $failed, array( $schedule['id'], $result->get_error_message(), json_encode( $schedule ) ) );

	}

  // Update the Last ID
  end( $wc_autoship_schedules );
  $last_id = key( $wc_autoship_schedules );
  reset( $wc_autoship_schedules );

  // Set/Update the Stats
  $stats = autoship_query_legacy_upsert_stats( $wc_autoship_schedules, $last_id, array( 'failed' => $failed, 'result' => 1 ) );

  // Gather Return Data
  $result = array(
    'total'   => $stats['total'],
    'count'   => $stats['count'],
    'failed'  => $stats['failed'],
    'last_id' => $stats['last_id'],
    'result'  => $stats['result'],
  );

	$type = "auto_schedule";
  autoship_ajax_result( 200, $stats, false );

  $status = ( $stats['complete_percent'] == 100 ) ? 1 : 0;

  $value_array = array(
    'schedule_type' => $type,
    'response_array' => json_encode( $result ),
    'complete_percent' => $complete_percent,
    'status' => $status
  );

  wc_autoship_update_db_import_schedules( $result, $value_array);
  die();

}
add_action( 'wp_ajax_autoship_import_schedule_data', 'autoship_ajax_import_schedule_data' );

/**
 * Migrates Legacy Options to new Autoship Product Options
 */
function autoship_ajax_import_product_settings() {

  if ( ! current_user_can( 'manage_woocommerce' ) )
	autoship_ajax_result( 403 );

	global $wpdb;
	$wp = $wpdb->prefix;
	$type = "product_setting";

	// Batch size
  $all_products_count = $wpdb->get_var("
  SELECT COUNT(*)
  FROM {$wp}posts
  WHERE post_type = 'product' AND post_status = 'publish'" );

	// Get last exported product id
	$last_id = isset( $_POST['last_id'] ) ? $_POST['last_id'] : 0;

	// Get the products
	$product_ids = $wpdb->get_col(
		$wpdb->prepare("
      SELECT ID
      FROM {$wp}posts
      WHERE ID > %d AND post_type = 'product' AND post_status = 'publish'
      ORDER BY ID ASC",
			$last_id
		)
	);

	// Loop through the products
	$failed = array();
	foreach ( $product_ids as $id ) {

		$product = wc_get_product( $id );
		if ( null == $product ) {
			array_push( $failed, array( $id, "Product with id " . $id . " does not exist" ) );
			continue;
		}

    $type = $product->get_type();

    // Check for unsupported types.
    if ( 'simple' != $type && 'variable' != $type )
    continue;

		// Check whether autoship is enabled
		$autoship_enabled = get_post_meta( $id, '_wc_autoship_enable_autoship', true );

		if ( 'yes' != $autoship_enabled ) {
			// Autoship is not enabled for this product
			// Disable autoship for this product
			autoship_set_product_autoship_enabled ( $id, 'no' );
			continue;
		}

		// Enable autoship for this product
		autoship_set_product_autoship_enabled ( $id );

		if ( 'simple' == $product->get_type() ) {

      // Handle the simple product
			autoship_import_product_settings_price( $id );

		} else {

			// Handle the variable product
			// Loop through the child variations
			$variations = $product->get_available_variations();
			foreach ( $variations as $variation ) {
				autoship_import_product_settings_price( $variation['variation_id'] );
			}

		}

	}

	// Count the current batch
	$batch_count = count( $product_ids );

	// Get the previous count
	$previous_count = $wpdb->get_var( $wpdb->prepare("
    SELECT COUNT(*)
    FROM {$wp}posts
    WHERE ID <= %d AND post_type = 'product' AND post_status = 'publish'",
		$last_id
	) );

	$count          = $previous_count + $batch_count;

	// Get the new last id
	$new_last_id = ( $batch_count > 0 ) ? $product_ids[ $batch_count - 1 ] : $last_id;

  $complete_percent = (int) ( ( $count / $all_products_count ) * 100);

	$result = array(
		'total'             => (int) $all_products_count,
		'count'             => (int) $count,
		'failed'            => $failed,
		'last_id'           => $new_last_id,
    'complete_percent'  => $complete_percent
	);

	autoship_ajax_result( 200, $result, false );

  $value_array = array(
      'schedule_type' => $type,
      'response_array' => json_encode($result),
      'complete_percent' => $complete_percent,
      'status' => 1
  );
  wc_autoship_update_db_import_schedules($result, $value_array);
  die();

}
add_action( 'wp_ajax_autoship_import_product_settings', 'autoship_ajax_import_product_settings' );

/**
 * Migrates Scheduled Orders from the Legacy tables to QPilot
 * @param array The raw legacy Scheduled Order Data
 * @return stdClass|WP_Error The QPilot generated Scheduled Order Object or WP Error on failure.
 */
function autoship_import_create_scheduled_order( $schedule ) {

	// Get customers shipping info
	$wc_customer_id = $schedule['customer_id'];
	$wc_customer    = new WC_Customer( $wc_customer_id );

	$payment_method = null;
  $invalids = array();
	if ( isset( $schedule['payment_method'] ) ) {

		if ( $schedule['payment_method'] != null ) {

			$payment_method = new WC_Payment_Token_CC( $schedule['payment_token_id'] );
			$description    = $payment_method->get_display_name();
			$type           = null;

			switch ( $schedule['payment_method']['gateway_id'] ) {
				case 'stripe':
					$type = 'Stripe';
					break;
				case 'wc_autoship_authorize_net':
					$type = 'AuthorizeNet';
					break;
				case 'wc_autoship_braintree':
					$type = 'Braintree';
					break;
				case 'wc_autoship_cyber_source':
					$type = 'CyberSource';
					break;
				case 'wc_autoship_paypal':
					$type = 'PayPal';
					break;
				default:
					$schedule['payment_method'] = null;
			}

      $payment_method = autoship_get_general_payment_method_customer_data( $schedule['customer_id'], array(
        'type'              => $type,
        //'expiration'        => $expiration,
        'lastFourDigits'    => $payment_method->get_last4(),
				"gatewayCustomerId" => $schedule['payment_method']['customer_id'],
				"gatewayPaymentId"  => $schedule['payment_method']['token'],
				"description"       => $description,
      ) );

		}

  }

  $scheduled_order_data = autoship_generate_customer_shipping_data ( $wc_customer, array(
		"customerId"          => $wc_customer->get_id(),
    "customer"            => autoship_generate_customer_upsert_data( $wc_customer->get_id() ),
		"originalExternalId"  => "wc-import-" . $schedule['id'],
		"status"              => $schedule['autoship_status'] == '1' ? 'Active' : 'Paused',
		"nextOccurrenceUtc"   => $schedule['next_order_date'],
    "utcOffset"           => autoship_get_local_timezone_offset(),
		"lastOccurrenceUtc"   => $schedule['last_order_date'],
		"frequencyType"       => 'Days',
		"frequency"           => $schedule['autoship_frequency'],
		"currencyIso"         => get_woocommerce_currency(),
		"scheduledOrderItems" => array(),
		"paymentMethod"       => $payment_method
	) );

	// Create scheduled order items
	foreach ( $schedule['schedule_items'] as $item ) {

		$external_id = ! empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];
		$product     = wc_get_product( $external_id );

		if( $product ) {

      // For checking for autoship price
			$autoship_price     = get_post_meta( $item['product_id'], '_wc_autoship_price', true );
			$quantity           = (int) $item['qty'];
			$item_subtotal      = floatval( $product->get_price() );
			$item_total         = floatval( $item_subtotal * $quantity );
			$product_price      = floatval( $product->get_price() );
			$product_sale_price = null;

			if ( ! empty( $autoship_price ) ) {

				$product_sale_price = $autoship_price;

      } else {

      	$wc_sale_price = $product->get_sale_price();
				if ( ! empty( $wc_sale_price ) ) {
					$product_sale_price = floatval( $wc_sale_price );
				}

      }

			// In case they are too long
			$sku = substr( $product->get_sku(), 0, 32 );

			$length = strval( round( floatval( $product->get_length() ), 2 ) ) . get_option( 'woocommerce_dimension_unit' );
			$width  = strval( round( floatval( $product->get_width() ), 2 ) ) . get_option( 'woocommerce_dimension_unit' );
			$height = strval( round( floatval( $product->get_height() ), 2 ) ) . get_option( 'woocommerce_dimension_unit' );

			// Concat them to 10 chars
			$length = substr( $length, 0, 10 );
			$width  = substr( $width, 0, 10 );
			$height = substr( $height, 0, 10 );

			$scheduled_order_item_data = array(
        "productId" => $external_id,
				"product"   => autoship_generate_product_upsert_data ( $product ),
				"quantity"  => $quantity,
				"price"     => $product_price,
				"salePrice" => $product_sale_price
			);

			$scheduled_order_data["scheduledOrderItems"][] = $scheduled_order_item_data;

		} else {

      $invalids[] = $external_id;

    }

	}

  // Finally Upsert the order
  $upsert = autoship_upsert_scheduled_order( $scheduled_order_data["customerId"], $scheduled_order_data );

	return !is_wp_error( $upsert ) && !empty( $invalids ) ? new WP_Error( 'Create Scheduled Order Item Error', sprintf( "Product(s) (%s) associated with this schedule no longer exist: Scheduled Order for ( %s ) was created without these products." ), implode(',', $invalids ),  $wc_customer->get_email() ) : $upsert;

}

/**
 * Converts the legacy WC Autoship Price value to the new Autoship Fields
 * @param int The WC Product ID.
 */
function autoship_import_product_settings_price( $product_id ) {

  $autoship_price = get_post_meta( $product_id, '_wc_autoship_price', true );

  if ( empty( $autoship_price ) )
	return;

	autoship_set_product_checkout_price( $product_id, $autoship_price );
	autoship_set_product_recurring_price( $product_id, $autoship_price );

}

function autoship_import_payment_integrations() {
	// TODO: Make sure they are allowed to do this

	$site_id_str = autoship_get_site_id();
	if ( empty( $site_id_str ) ) {
		// TODO: Add error message
		autoship_add_message( 'Site Id not found on WP.' );
		wp_redirect( admin_url( 'admin.php?page=migrations' ) );
		die();
	}

	$site_id = intval( $site_id_str );
	$client  = autoship_get_default_client();

	// Get existing integrations
	try {
		$payment_integrations = $client->get_payment_integrations();
	} catch ( Exception $e ) {
		autoship_add_message( $e->getMessage() );
		wp_redirect( admin_url( 'admin.php?page=migrations' ) );
		die();
	}

	$integration_types = array();
	foreach ( $payment_integrations as $integration ) {
		$integration_types[ $integration->paymentMethodType ] = true;
	}


	// Stripe
	if ( empty( $integration_types['Stripe'] ) ) {
		$stripe_settings = get_option( 'woocommerce_wc_autoship_stripe_settings', true );
		if ( ! empty( $stripe_settings ) ) {
			$stripe_test_mode        = ! empty( $stripe_settings['authorize_only'] ) && $stripe_settings['authorize_only'] == 'yes';
			$stripe_integration_data = array(
				'apiKey1'           => $stripe_settings['publishable_key'],
				'apiKey2'           => $stripe_settings['secret_key'],
				'testMode'          => $stripe_test_mode,
				'paymentMethodType' => 'Stripe'
			);


			try {
				$stripe_result = $client->create_payment_integration( $stripe_integration_data );
			} catch ( Exception $e ) {
				autoship_add_message( $e->getMessage() );
				wp_redirect( admin_url( 'admin.php?page=migrations' ) );
				die();
			}
		}
	}


	// TODO: Paypal
	if ( empty( $integration_types['PayPal'] ) ) {
		$paypal_settings = get_option( 'woocommerce_wc_autoship_paypal_settings', true );
		if ( ! empty( $paypal_settings ) ) {
			$paypal_sandbox = ! empty( $paypal_settings['sandbox_mode'] ) && $paypal_settings['sandbox_mode'] == 'yes';
			$paypal_data    = array(
				'apiAccount'        => $paypal_settings['user'],
				'apiKey1'           => $paypal_settings['password'],
				'apiKey2'           => $paypal_settings['signature'],
				'testMode'          => $paypal_sandbox,
				'paymentMethodType' => 'PayPal'
			);

			try {
				$client->create_payment_integration( $paypal_data );
			} catch ( Exception $e ) {
				autoship_add_message( $e->getMessage() );
				wp_redirect( admin_url( 'admin.php?page=migrations' ) );
				die();
			}
		}
	}


	// TODO: Braintree
	if ( empty( $integration_types['Braintree'] ) ) {
		$bt_settings = get_option( 'woocommerce_wc_autoship_braintree_settings', true );
		if ( ! empty( $bt_settings ) ) {
			$bt_sandbox = ! empty( $bt_settings['sandbox_mode'] ) && $bt_settings['sandbox_mode'] == 'yes';
			$bt_data    = array(
				'paymentMethodType' => 'Braintree',
				'apiAccount'        => $bt_settings['merchant_id'],
				'apiKey1'           => $bt_settings['public_key'],
				'apiKey2'           => $bt_settings['private_key'],
				'testMode'          => $bt_sandbox
			);

			try {
				$client->create_payment_integration( $bt_data );
			} catch ( Exception $e ) {
				autoship_add_message( $e->getMessage() );
				wp_redirect( admin_url( 'admin.php?page=migrations' ) );
				die();
			}
		}

	}


	// TODO: Authorize.net
	if ( empty( $integration_types['AuthorizeNet'] ) ) {
		$anet_settings = get_option( 'woocommerce_wc_autoship_authorize_net_settings', true );
		if ( ! empty( $anet_settings ) ) {
			$anet_sandbox = ! empty( $anet_settings['sandbox_mode'] ) && $anet_settings['sandbox_mode'] == 'yes';
			$anet_data    = array(
				'paymentMethodType' => 'AuthorizeNet',
				'apiAccount'        => $anet_settings['api_login_id'],
				'apiKey1'           => $anet_settings['transaction_key'],
				'testMode'          => $anet_sandbox
			);
		}

		try {
			$client->create_payment_integration( $anet_data );
		} catch ( Exception $e ) {
			autoship_add_message( $e->getMessage() );
			wp_redirect( admin_url( 'admin.php?page=migrations' ) );
			die();
		}
	}

	// TODO: CyberSource
	if ( empty( $integration_types['CyberSource'] ) ) {
		$anet_settings = get_option( 'woocommerce_wc_autoship_cyber_source_settings', true );
		if ( ! empty( $anet_settings ) ) {
			$anet_sandbox = ! empty( $anet_settings['sandbox_mode'] ) && $anet_settings['sandbox_mode'] == 'yes';
			$anet_data    = array(
				'paymentMethodType' => 'CyberSource',
				'apiAccount'        => $anet_settings['merchant_id'],
				'apiKey1'           => $anet_settings['transaction_key'],
				'testMode'          => $anet_sandbox
			);
		}

		try {
			$client->create_payment_integration( $anet_data );
		} catch ( Exception $e ) {
			autoship_add_message( $e->getMessage() );
			wp_redirect( admin_url( 'admin.php?page=migrations' ) );
			die();
		}
	}


	autoship_add_message( 'Success!' );
	wp_redirect( admin_url( 'admin.php?page=migrations' ) );
	die();
}
add_action( 'wp_ajax_autoship_import_payment_integrations', 'autoship_import_payment_integrations' );

// =================================
// HELPERS
// =================================

/**
 * Queries the Payment Tokens from the Database.
 * @param string $payment_token_id
 */
function autoship_import_get_payment_row( $payment_token_id ) {
	global $wpdb;
	$wp = $wpdb->prefix;

	return $wpdb->get_row( $wpdb->prepare("
      SELECT token.*, last_four.meta_value last_four, card_type.meta_value card_type, exp_month.meta_value exp_month, exp_year.meta_value exp_year FROM
      {$wp}woocommerce_payment_tokens as token
      LEFT JOIN {$wp}woocommerce_payment_tokenmeta as last_four
      ON last_four.payment_token_id = token.token_id
      AND last_four.meta_key = 'last4'
      LEFT JOIN {$wp}woocommerce_payment_tokenmeta as card_type
      ON card_type.payment_token_id = token.token_id
      AND card_type.meta_key = 'card_type'
      LEFT JOIN {$wp}woocommerce_payment_tokenmeta as exp_month
      ON exp_month.payment_token_id = token.token_id
      AND exp_month.meta_key = 'expiry_month'
      LEFT JOIN {$wp}woocommerce_payment_tokenmeta as exp_year
      ON exp_year.payment_token_id = token.token_id
      AND exp_year.meta_key = 'expiry_year'
      WHERE token_id = %s", $payment_token_id ), ARRAY_A );
}

function wc_autoship_import_get_stripe_method( $payment_token_id ) {
	$token_data                = autoship_import_get_payment_row( $payment_token_id );
  $token_data['customer_id'] = get_user_option( '_stripe_customer_id', $token_data['user_id'] );

	return $token_data;
}

function wc_autoship_import_get_authorize_method( $payment_token_id ) {
	$token_data                = autoship_import_get_payment_row( $payment_token_id );
	$token_data['customer_id'] = get_user_meta( $token_data['user_id'], 'wc_autoship_authorize_net_id', true );

	return $token_data;
}

function wc_autoship_import_get_braintree_method( $payment_token_id ) {
	$token_data                = autoship_import_get_payment_row( $payment_token_id );
	$token_data['customer_id'] = get_user_meta( $token_data['user_id'], 'wc_autoship_braintree_id', true );

	return $token_data;
}

function wc_autoship_import_get_paypal_method( $payment_token_id ) {
	$token_data                = autoship_import_get_payment_row( $payment_token_id );
	$token_data['customer_id'] = null;

	return $token_data;
}

function wc_autoship_import_get_cyber_source_method( $payment_token_id ) {
	$token_data                = autoship_import_get_payment_row( $payment_token_id );
	$token_data['customer_id'] = get_user_meta( $token_data['user_id'], 'wc_autoship_cyber_source_id', true );
}

function wc_autoship_update_db_import_schedules($result, $value_array) {
    global $wpdb;
    $table = $wpdb->prefix . "wc_autoship_import";
    try{
        $delete_type_record=$wpdb->query("delete from ".$table." where schedule_type='".$value_array['schedule_type']."'");
        $update_table = $wpdb->query("insert into " . $table . "(schedule_type,response_array,complete_percent,status) values('" . $value_array['schedule_type'] . "','" . $value_array['response_array'] . "','" . $value_array['complete_percent'] . "','1')");
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
    return true;
}

function wc_autoship_update_test_connection($result, $value_array) {
    global $wpdb;
    $table = $wpdb->prefix . "wc_autoship_import";
    try{
        $update_table = $wpdb->query("insert into " . $table . "(schedule_type,response_array,complete_percent,status) values('" . $value_array['schedule_type'] . "','" . $value_array['response_array'] . "','" . $value_array['complete_percent'] . "','1')");
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
    return true;
}

function get_import_values($schedule_type) {
    global $wpdb;
    $table = $wpdb->prefix . "wc_autoship_import";
    try{
        $get_data = $wpdb->get_results("select * from " . $table . " where schedule_type='" . $schedule_type . "' order by ID desc LIMIT 0,1");
    }
    catch(Exception $e){
        echo $e->getMessage();
    }

    return $get_data;
}

function format_date($date) {
    $new_date = date('F d, Y', strtotime($date));
    return $new_date;
}

function format_time($date) {
    $new_time = date('H:i', strtotime($date));
    return $new_time;
}

function check_table_exist(){
    global $wpdb;
    $table = $wpdb->prefix . "wc_autoship_import";
    if($wpdb->get_var("SHOW TABLES LIKE '".$table."'") != $table) {
        return 0;
    }else{
        return 1;
    }
}
