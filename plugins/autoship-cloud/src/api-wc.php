<?php

use Automattic\WooCommerce\Utilities\OrderUtil;

/**
 * Adjusts the Rest Response Object, formats data prior to sending back to QPilot
 * @param WP_REST_Response $response The response data.
 * @param $object
 * @param $request
 * @return WP_REST_Response
 */
function autoship_woocommerce_rest_prepare_object($response, $object, $request) {
    $response->data = autoship_woocommerce_rest_fix_data($response->data);
    return $response;
}

/**
 * Makes the needed calls to adjust the Rest Order Metadata returned to QPilot's API
 * TODO: legacy code needs review for refactor and see if still needed.
 *
 * @param array $data The Rest Order Data.
 * @return array The filtered Order Rest Data
 */
function autoship_woocommerce_rest_fix_data($data) {
  $fixed_data = $data;
  if (!empty($fixed_data['meta_data'])) {
    $fixed_data['meta_data'] = autoship_woocommerce_rest_fix_meta_data($fixed_data['meta_data']);
  }
  return $fixed_data;
}

/**
 * Converts the metadata array into a stdClass object for Qpilot
 * TODO: legacy code needs review for refactor and see if still needed.
 *
 * @param array $meta_data The Rest Order Data.
 * @return array An array of stdClass objects
 */
function autoship_woocommerce_rest_fix_meta_data($meta_data) {
    $fixed_meta_data = array();
    foreach ($meta_data as $item_key => $item_value) {
      $data = new stdClass();
      $data->id = $item_value->id;
      $data->key = $item_value->key;
      $data->value = $item_value->value;
      $fixed_meta_data[] = $data;
    }
    return $fixed_meta_data;
}


/**
 * Verifies if an order exists before creating it.
 * If the order exists, an error is returned, else, the normal order creation flow occurs.
 *
 * @param int $order          An order to create/update.
 * @param string $request      The request.
 * @param int    $creating      If the order is creating.
 * @return WP_Error if the order exists, or the $order if it does not exist
 */
function autoship_woocommerce_rest_pre_insert_shop_order_object( $order, $request, $creating ) {

    // Check if this is an initial order creation or an update
    // No need to check for duplicates on an update.
    if ( !$creating || is_wp_error( $order ) ) {
      return $order;
    }

    // Check for Scheduled Order Processing ID - Only assigned by QPilot
    $scheduled_order_processing_id = $order->get_meta( '_qpilot_scheduled_order_processing_id' );

    if ( !empty( $scheduled_order_processing_id ) ) {

        // Get and Update the QPilot Site Meta
        $sitemeta = $order->get_meta( '_qpilot_site_meta' );

        // Update the Site Meta
        $sitemeta = autoship_qpilot_get_sitemeta( $sitemeta );

        // Add QPilot Autoship Version to Order Meta as well as any additional tracking.
        $order->update_meta_data( '_qpilot_site_meta', $sitemeta , true );

        // TODO: Once all merchants have migrated to Autoship Cloud v1.2.26 or above the following check for duplicate orders
        // needs to be removed ( line 110 - 130 ).
        if( OrderUtil::custom_orders_table_usage_is_enabled() ) {
          $orders = wc_get_orders( 
            array(
              'meta_query' => array(
                array( 
                  'key' => '_qpilot_scheduled_order_processing_id', 
                  'value' => $scheduled_order_processing_id )
                ),
                )
              );
            } else {
          $orders = wc_get_orders( array( '_qpilot_scheduled_order_processing_id' => $scheduled_order_processing_id ) );
        }

        // Added check to see if there is only one existing order and it has the same ID as the current order
        // Then it means that another plugin probably saved the order before us.
        $valid = !empty( $orders ) && ( count( $orders ) < 2 ) && $order->get_id() && ( $order->get_id() == $orders[0]->get_id() );

        //the order exists, it's a duplicate
        if ( !empty( $orders ) && !$valid ) {

            // Get and Update the QPilot Site Meta
            $sitemeta = $orders[0]->get_meta( '_qpilot_site_meta' );

            // Update the Site Meta
            $sitemeta = autoship_qpilot_get_sitemeta( $sitemeta );

            // Add QPilot Autoship Version to Order Meta as well as any additional tracking.
            $orders[0]->add_meta_data( '_qpilot_site_meta', $sitemeta , true );

            return new WP_Error( 'duplicate_order', __( 'Order has already been created from scheduled order processing.', 'autoship' ),
            array( 'status' => 400,
                    'order' => json_encode( array_values( $orders )[0]->get_data() ) )
          );
        } else {
            return $order;
        }

    } else {
        return $order;
    }

}

/**
 * Adds the Site Meta to orders being created By QPilot.
 * Attaches the environment to the order which eventually gets sent back to the API.
 *
 * @param WC_Data         $order    Object object.
 * @param WP_REST_Request $request  Request object.
 * @param bool            $creating If is creating a new object.
 */
function autoship_woocommerce_rest_insert_shop_object_attach_site_meta( $order, $request, $creating ){

  if ( is_wp_error( $order ) )
  return $order;

  // Check if a QPilot Generated Order and if it's an order being updated.
  if ( !empty( $order->get_meta( '_qpilot_scheduled_order_processing_id' ) ) && $creating ) {

    // Get and Update the QPilot Site Meta
    $sitemeta = $order->get_meta( '_qpilot_site_meta' );

    // Update the Site Meta
    $sitemeta = autoship_qpilot_get_sitemeta( $sitemeta );

    // Add QPilot Autoship Version to Order Meta as well as any additional tracking.
    $order->update_meta_data( '_qpilot_site_meta', $sitemeta , true );

  }

  return $order;

}

/**
 * Adds order notes after order insertion/update.
 *
 * @param int $order          The created/inserted order
 * @param string $request      The request.
 * @param int    $creating      If the order is creating.
 */
function autoship_woocommerce_rest_insert_shop_object_add_note( $order, $request, $creating ) {
  $order_note = $order->get_meta( '_add_order_note' );

  if ( !empty( $order_note ) ) {
    $order->add_order_note( $order_note );
    $order->delete_meta_data( '_add_order_note' );
    $order->save();
  }
}

// ==========================================================
// DEFAULT HOOKED ACTIONS & FILTERS
// ==========================================================

add_filter('woocommerce_rest_prepare_product_object', 'autoship_woocommerce_rest_prepare_object', 100, 3);
add_filter('woocommerce_rest_prepare_product_variation_object', 'autoship_woocommerce_rest_prepare_object', 100, 3);
add_filter('woocommerce_rest_prepare_shop_order_object', 'autoship_woocommerce_rest_prepare_object', 100, 3);
add_filter('woocommerce_order_data_store_cpt_get_orders_query', 'autoship_handle_meta_query_by_scheduled_order_processing_id', 100, 2);
add_filter('woocommerce_rest_pre_insert_shop_order_object', 'autoship_woocommerce_rest_pre_insert_shop_order_object', 100, 3);
add_filter('woocommerce_rest_pre_insert_shop_order_object', 'autoship_woocommerce_rest_insert_shop_object_attach_site_meta', 100, 3 );
add_action('woocommerce_rest_insert_shop_order_object', 'autoship_woocommerce_rest_insert_shop_object_add_note', 10 , 3 );
