<?php

/**
 * Updates the Checkout Price for Products.
 *
 * @return Ajax Response
 */
function autoship_batch_update_products(){

  // Default Batch Actions
  $actions = apply_filters(
    'autoship_batch_update_products_actions', array(
    'autoship_bulk_update_checkout_price'       => 'autoship_batch_update_product_checkout',
    'autoship_bulk_update_recurring_price'      => 'autoship_batch_update_product_recurring',
    'autoship_bulk_update_enable_autoship'      => 'autoship_batch_update_product_enable_autoship',
    'autoship_bulk_update_enable_availability'  => 'autoship_batch_update_product_enable_availability',
    'autoship_bulk_update_active_sync'          => 'autoship_batch_update_product_active_sync',
    'autoship_bulk_update_reset_active_sync'    => 'autoship_batch_reset_product_active_sync',
    'autoship_bulk_update_customer_metrics'     => 'autoship_batch_update_customer_metrics',
  ));

  // Retrieve the posted action.
  $args = array();
  $args['batch_action'] = !isset( $_POST['batch_action'] ) || empty( $_POST['batch_action'] ) ?
  '' : $_POST['batch_action'];

  // Validate the Action.
  if ( !isset( $actions[$args['batch_action']] ) || !function_exists( $actions[$args['batch_action']] ) ){
    autoship_ajax_result( 400, array(
      'success'       => false,
      'notice'        => __( 'Invalid autoship batch action.', 'autoship' )
     ));
  }

  // Retrieve the next set of options.
  $args['batch_function'] = $actions[$args['batch_action']];

  $args['batch_size'] = !isset( $_POST['batch_size'] ) || empty( $_POST['batch_size'] ) ?
  10 : absint( $_POST['batch_size'] );

  $args['current_count'] = !isset( $_POST['current_count'] ) || empty( $_POST['current_count'] ) ?
  0 : absint( $_POST['current_count'] );

  $args['current_page'] = !isset( $_POST['current_page'] ) || empty( $_POST['current_page'] ) ?
  0 : absint( $_POST['current_page'] );

  $args['total_count'] = !isset( $_POST['total_count'] ) || empty( $_POST['total_count'] ) ?
  0 : absint( $_POST['total_count'] );

  // Allow devs to extend the gathered options.
  $args = apply_filters( "autoship_batch_update_products_{$args['batch_action']}_args", $args );

  // Run the function
  $function = $args['batch_function'];
  $results = $function( $args );

  do_action( $args['batch_action'] . '_complete', $results, $args );

  autoship_ajax_result( 200, $results );
 	die();

}
add_action( 'wp_ajax_autoship_batch_update_products', 'autoship_batch_update_products' );

/**
 * Gathers the Additional Posted Vals for the Batch Update Active
 * @param array $args
 */
function autoship_batch_update_product_active_sync_added_args( $args ){

  $args['include_availability'] = !isset( $_POST['autoship_include_availability_on_sync'] ) || empty( $_POST['autoship_include_availability_on_sync'] ) || ( 'yes' != $_POST['autoship_include_availability_on_sync'] ) ?
  false : true;

  return $args;

}
add_filter( 'autoship_batch_update_products_autoship_bulk_update_active_sync_args', 'autoship_batch_update_product_active_sync_added_args', 10, 1 );

/**
 * Gathers the Additional Posted Vals for the Batch Update Checkout
 * @param array $args
 */
function autoship_batch_update_product_checkout_added_args( $args ){

  $args['checkout_pct'] = !isset( $_POST['checkout_pct'] ) || empty( $_POST['checkout_pct'] ) ?
  "" : round( floatval( $_POST['checkout_pct'] ) , 2 );

  $args['base_price'] = !isset( $_POST['base_price'] ) || empty( $_POST['base_price'] ) ?
  'regular' : $_POST['base_price'];

  return $args;

}
add_filter( 'autoship_batch_update_products_autoship_bulk_update_checkout_price_args', 'autoship_batch_update_product_checkout_added_args', 10, 1 );

/**
 * Gathers the Additional Posted Vals for the Batch Update Recurring
 * @param array $args
 */
function autoship_batch_update_product_recurring_added_args( $args ){

  $args['recurring_pct'] = !isset( $_POST['recurring_pct'] ) || empty( $_POST['recurring_pct'] ) ?
  "" : round( floatval( $_POST['recurring_pct'] ) , 2 );

  $args['base_price'] = !isset( $_POST['base_recurring_price'] ) || empty( $_POST['base_recurring_price'] ) ?
  'regular' : $_POST['base_recurring_price'];

  return $args;

}
add_filter( 'autoship_batch_update_products_autoship_bulk_update_recurring_price_args', 'autoship_batch_update_product_recurring_added_args', 10, 1 );

/**
 * Sets the Enable Option for the autoship_bulk_update_enable_autoship action
 * @param array $args
 */
function autoship_batch_update_enable_autoship_added_args( $args ){

  $args['enable_option'] = !isset( $_POST['enable_autoship_option'] ) || empty( $_POST['enable_autoship_option'] ) || 'no' != $_POST['enable_autoship_option'] ?
  'yes' : 'no';

  return $args;

}
add_filter( 'autoship_batch_update_products_autoship_bulk_update_enable_autoship_args', 'autoship_batch_update_enable_autoship_added_args', 10, 1 );

/**
 * Sets the Enable Option for the autoship_bulk_update_enable_availability action
 * @param array $args
 */
function autoship_batch_update_enable_availability_added_args( $args ){

  $args['enable_option'] = !isset( $_POST['enable_availability_option'] ) || empty( $_POST['enable_availability_option'] ) || 'no' != $_POST['enable_availability_option'] ?
  'yes' : 'no';

  return $args;

}
add_filter( 'autoship_batch_update_products_autoship_bulk_update_enable_availability_args', 'autoship_batch_update_enable_availability_added_args', 10, 1 );

/**
 * Sets the Enable Option for the autoship_bulk_update_active_sync action
 * @param array $args
 */
function autoship_batch_update_active_sync_added_args( $args ){

  $args['enable_option'] = !isset( $_POST['enable_active_sync_option'] ) || empty( $_POST['enable_active_sync_option'] ) || 'no' != $_POST['enable_active_sync_option'] ?
  'yes' : 'no';

  return $args;

}
add_filter( 'autoship_batch_update_products_autoship_bulk_update_active_sync_args', 'autoship_batch_update_active_sync_added_args', 10, 1 );

/**
 * Sets the Enable Option for the autoship_bulk_update_reset_active_sync action
 * @param array $args
 */
function autoship_batch_update_active_reset_sync_added_args( $args ){

  $args['enable_option'] = !isset( $_POST['enable_reset_active_sync_option'] ) || empty( $_POST['enable_reset_active_sync_option'] ) || 'no' != $_POST['enable_reset_active_sync_option'] ?
  'yes' : 'no';

  return $args;

}
add_filter( 'autoship_batch_update_products_autoship_bulk_update_reset_active_sync_args', 'autoship_batch_update_active_reset_sync_added_args', 10, 1 );

/**
 * Retrieves Product IDs where the Enable Schedule Options is checked or
 * _autoship_schedule_process_enabled is enabled or _autoship_schedule_order_enabled is
 * enabled
 * @param string $type The post types to retrieve
 * @return array of IDs
 */
function autoship_batch_query_maybe_active_product_ids ( $type = 'all' ){

  global $wpdb;
  $wp = $wpdb->prefix;

  $query = "
  SELECT parent.ID
  FROM {$wp}posts as parent ";

  if ( 'simple' == $type ){

    $query .= "
    INNER JOIN {$wp}postmeta as meta on parent.ID = meta.post_id
    WHERE parent.ID NOT IN (
      SELECT child.post_parent
      FROM {$wp}posts as child WHERE child.post_parent > 0 AND child.post_type = 'product_variation' )
      AND parent.post_type = 'product' AND
      ( meta.meta_key LIKE '_autoship_schedule_process_enabled' OR meta.meta_key LIKE '_autoship_schedule_order_enabled' OR meta.meta_key LIKE '_autoship_schedule_options_enabled' ) AND meta.meta_value = 'yes' ";

  } else if ( 'variable' == $type ){

    $query .= "
    INNER JOIN {$wp}postmeta as meta on parent.ID = meta.post_id
    WHERE parent.ID IN (
        SELECT child.post_parent
        FROM {$wp}posts as child WHERE child.post_parent > 0 AND child.post_type = 'product_variation' )
    AND parent.post_type = 'product' AND
    ( meta.meta_key LIKE '_autoship_schedule_process_enabled' OR meta.meta_key LIKE '_autoship_schedule_order_enabled' OR meta.meta_key LIKE '_autoship_schedule_options_enabled' ) AND meta.meta_value = 'yes' ";

  } else if ( 'variation' == $type ){

    $query .= "
    WHERE parent.post_parent > 0 AND parent.post_type = 'product_variation'
    AND parent.post_parent IN (
        SELECT child.ID
        FROM {$wp}posts as child
        INNER JOIN {$wp}postmeta as childmeta on child.ID = childmeta.post_id
        WHERE ( childmeta.meta_key LIKE '_autoship_schedule_process_enabled' OR childmeta.meta_key LIKE '_autoship_schedule_order_enabled' OR childmeta.meta_key LIKE '_autoship_schedule_options_enabled' ) AND childmeta.meta_value = 'yes' )";

  }

  $query .= "
  GROUP BY parent.ID
  ORDER BY ID ASC";

  $products = $wpdb->get_col( $query );
  return $products;

}

/**
 * Retrieves Product IDs where the Active flag is checked.
 * @param string $type The post types to retrieve
 * @return array of IDs
 */
function autoship_batch_query_active_product_ids ( $type = 'all' ){

  global $wpdb;
  $wp = $wpdb->prefix;

  $query = "
  SELECT parent.ID
  FROM {$wp}posts as parent ";

  if ( 'simple' == $type ){

    $query .= "
    INNER JOIN {$wp}postmeta as meta on parent.ID = meta.post_id
    WHERE parent.ID IN (
      SELECT object_id FROM {$wp}term_relationships as term_index WHERE term_index.term_taxonomy_id IN ( SELECT terms.term_id FROM {$wp}terms as terms WHERE terms.name = 'simple' ) )
      AND parent.post_type = 'product' AND meta.meta_key = '_autoship_sync_active_enabled' AND meta.meta_value = 'yes'";

  } else if ( 'variable' == $type ){

    $query .= "
    INNER JOIN {$wp}postmeta as meta on parent.ID = meta.post_id
    WHERE parent.ID IN (
        SELECT object_id FROM {$wp}term_relationships as term_index WHERE term_index.term_taxonomy_id IN ( SELECT terms.term_id FROM {$wp}terms as terms WHERE terms.name = 'variable' ) )
    AND parent.post_type = 'product' AND meta.meta_key = '_autoship_sync_active_enabled' AND meta.meta_value = 'yes'";

  } else if ( 'variation' == $type ){

    $query .= "
    WHERE parent.post_parent > 0 AND parent.post_type = 'product_variation'
    AND parent.post_parent IN (
        SELECT child.ID
        FROM {$wp}posts as child
        INNER JOIN {$wp}postmeta as childmeta on child.ID = childmeta.post_id
        WHERE child.ID IN (
            SELECT object_id FROM {$wp}term_relationships as term_index WHERE term_index.term_taxonomy_id IN ( SELECT terms.term_id FROM {$wp}terms as terms WHERE terms.name = 'variable' ) )
        AND childmeta.meta_key = '_autoship_sync_active_enabled' AND childmeta.meta_value = 'yes' )";

  } else if ( 'product' == $type ){

    $query .= "
    INNER JOIN {$wp}postmeta as meta on parent.ID = meta.post_id
    WHERE parent.post_type = 'product'
    AND meta.meta_key = '_autoship_sync_active_enabled' AND meta.meta_value = 'yes'";

  } else {

    $query .= "
    INNER JOIN {$wp}postmeta as meta on parent.ID = meta.post_id
    WHERE parent.post_type IN ('product','product_variation')
    AND meta.meta_key = '_autoship_sync_active_enabled' AND meta.meta_value = 'yes'";

  }

  $query .= " ORDER BY ID ASC";

  $products = $wpdb->get_col( $query );
  return $products;

}

/**
 * Retrieves Product IDs
 * @param string $type The post types to retrieve
 * @return array of IDs
 */
function autoship_batch_query_product_ids ( $type = 'all' ){

  global $wpdb;
  $wp = $wpdb->prefix;

  $query = "
  SELECT parent.ID
  FROM {$wp}posts as parent";

  if ( 'simple' == $type ){

    $query .= "
    WHERE parent.ID NOT IN (
      SELECT child.post_parent
      FROM {$wp}posts as child WHERE child.post_parent > 0 AND child.post_type = 'product_variation' )
      AND parent.post_type = 'product'";

  } else if ( 'variable' == $type ){

    $query .= "
    WHERE parent.ID IN (
        SELECT child.post_parent
        FROM {$wp}posts as child WHERE child.post_parent > 0 AND child.post_type = 'product_variation' )
    AND parent.post_type = 'product'";

  } else if ( 'variation' == $type ){

    $query .= "
    WHERE parent.post_parent > 0 AND parent.post_type = 'product_variation'";

  } else if ( 'product' == $type ){

    $query .= "
    WHERE parent.post_type = 'product'";

  } else {

    $query .= "
    WHERE parent.post_type IN ('product','product_variation')";

  }

  $query .= " ORDER BY ID ASC";

  $products = $wpdb->get_col( $query );

  return $products;

}

/**
 * Batch Updates the Autoship Checkout Price
 * @param array $args
 */
function autoship_batch_update_product_checkout( $args ){

  $args = wp_parse_args( $args,
  array(
    'current_page'  => -1,
    'checkout_pct'  => 0,
    'current_count' => 0,
    'total_count'   => 0,
    'current_page'  => 1,
    'base_price'    => 'regular'
  ) );

  // Retrieve the IDs
  // We only update simple and variations.
  // Depending on Global get active or all are active
  $query_function = autoship_global_sync_active_enabled() ?
  'autoship_batch_query_product_ids' : 'autoship_batch_query_active_product_ids';

  $query_ids  = array_merge(  $query_function ( 'simple' ),
                              $query_function ( 'variation' )
                            );

  // Don't paginate if full run is expected
  // Otherwise get pages
  $page = array();
  if ( $args['current_page'] > 0 ){

    $pages = array_chunk ( $query_ids, $args['batch_size'] );
    $page = isset( $pages[$args['current_page'] - 1] ) ? $pages[$args['current_page'] -1 ] : array();

  }

  $count = $last = 0;
  foreach ( $page as $product_id ) {

    // Grab the Product
    $product = wc_get_product( $product_id );

    // Add Check for missing or invalid product
    if ( !$product || empty( $product ) )
    continue;

    // Grab the current checkout price
    $args['current_checkout_price'] = autoship_get_product_checkout_price( $product_id );

    if ( "" != $args['checkout_pct'] ){

      // Grab the base price so we can calculate the checkout.
      if ( 'regular' == $args['base_price'] ){
        $args['base_price_val'] = floatval( $product->get_regular_price() );
      } else if ( 'sale' == $args['base_price'] ){
        $args['base_price_val'] = floatval( $product->get_price() );
      } else {
        $args['base_price_val'] = apply_filters( "autoship_batch_update_product_checkout_base_{$args['base_price']}price", floatval( $product->get_regular_price() ), $args, $product );
      }

      $checkout_price = apply_filters( "autoship_batch_update_product_checkout_price_calculation", round( floatval( $args['base_price_val'] - ( $args['base_price_val'] * $args['checkout_pct'] ) ) , 2 ), $args, $product );

    } else {

      // Clear the Checkout Price
      $checkout_price = $args['checkout_pct'];

    }

    // Set the checkout price
    $updated = autoship_set_product_checkout_price( $product->get_id(), $checkout_price );

    if ( false == $updated )
    autoship_log_entry( __( 'Autoship Bulk Update Utility', 'autoship' ), "" == $args['checkout_pct'] ? sprintf( __('Batch Clear Checkout Price for Product %d from %f to nothing Failed or was Not Needed.', 'autoship' ), $product->get_id(), $args['current_checkout_price'] ) : sprintf( __('Batch Update Checkout Price for Product %d from %f to %f Failed or was Not Needed.', 'autoship' ), $product->get_id(), $args['current_checkout_price'], $checkout_price ) );

    // Adjust the counters
    if ( $updated )
    $ids[$product->get_id()] = $product->get_id();

    $last = $product->get_id();
    $count++;

  }

  // Finally calculate the percentage completed and update for next round.
  $pct = $count && $args['total_count'] ? round( 100 * ( ( $count + $args['current_count'] ) / $args['total_count'] ) , 2 ) : 0;
  $pct = !empty( $page ) ? $pct : 100;
  $pct = $pct >= 100 ? 100: $pct;

  return !$count ? array(
    'success'       => false,
    'total_pct'     => 0,
    'current_count' => $args['total_count'],
    'notice'        => __( 'A Problem was encountered processing the products.  Please try again.', 'autoship' )
  ) : array(
    'success'       => true,
    'page'          => $args['current_page']++,
    'last_record'   => isset( $last ) ? $last : 0,
    'updated_record'=> isset( $ids ) ? $ids : array(),
    'count'         => $count,
    'current_count' => $pct == 100 ? $args['total_count'] : $count + $args['current_count'],
    'total_pct'     => $pct < 5 ? 5 : $pct,
    'notice'        => sprintf( __( '%s%% of the %d Products and Product Variations have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] ),
  );

}

/**
 * Batch Updates the Autoship Recurring Price
 * @param array $args
 */
function autoship_batch_update_product_recurring( $args ){

  $args = wp_parse_args( $args,
  array(
    'current_page'  => -1,
    'recurring_pct' => 0,
    'current_count' => 0,
    'total_count'   => 0,
    'current_page'  => 1,
    'base_price'    => 'regular'
  ) );

  // Retrieve the IDs
  // We only update simple and variations.
  // Depending on Global get active or all are active
  $query_function = autoship_global_sync_active_enabled() ?
  'autoship_batch_query_product_ids' : 'autoship_batch_query_active_product_ids';

  $query_ids  = array_merge(  $query_function ( 'simple' ),
                              $query_function ( 'variation' )
                            );

  // Don't paginate if full run is expected
  // Otherwise get pages
  $page = array();
  if ( $args['current_page'] > 0 ){

    $pages = array_chunk ( $query_ids, $args['batch_size'] );
    $page = isset( $pages[$args['current_page'] - 1] ) ? $pages[$args['current_page'] -1 ] : array();

  }

  $ids = array();
  $count = $last = 0;
  foreach ( $page as $product_id ) {

    // Get the Product
    $product = wc_get_product( $product_id );

    // Add Check for missing or invalid product
    if ( !$product || empty( $product ) )
    continue;

    // Grab the current checkout & recurring prices
    $args['current_checkout_price'] = autoship_get_product_checkout_price( $product_id );
    $args['current_recurring_price'] = autoship_get_product_recurring_price( $product_id );

    if ( "" != $args['recurring_pct'] ){

      // Grab the base price so we can calculate the recurring
      if ( 'regular' == $args['base_price'] ){
        $args['base_price_val'] = floatval( $product->get_regular_price() );
      } else if ( 'sale' == $args['base_price'] ){
        $args['base_price_val'] = floatval( $product->get_price() );
      } else if ( 'checkout'  == $args['base_price'] ){
        $args['base_price_val'] = $args['current_checkout_price'];
      } else {
        $args['base_price_val'] = apply_filters( "autoship_batch_update_product_recurring_base_{$args['base_price']}price", floatval( $product->get_regular_price() ), $args, $product );
      }

      // Calculate the recurring price.
      $recurring_price = $checkout_price = apply_filters( "autoship_batch_update_product_recurring_price_calculation", round( floatval( $args['base_price_val'] - ( $args['base_price_val'] * $args['recurring_pct'] ) ) , 2 ), $args, $product );

    } else {

      // Clear the recurring Price
      $recurring_price = $args['recurring_pct'];

    }

    // Update the Recurring price.
    $updated = autoship_set_product_recurring_price( $product->get_id(), $recurring_price );

    if ( false == $updated )
    autoship_log_entry( __( 'Autoship Bulk Update Utility', 'autoship' ), "" == $args['recurring_pct'] ? sprintf( __('Batch Clear Recurring Price for Product %d from %f to nothing Failed or was Not Needed.', 'autoship' ), $product->get_id(), $args['current_recurring_price'] ) : sprintf( __('Batch Update Recurring Price for Product %d from %f to %f Failed or was Not Needed.', 'autoship' ), $product->get_id(), $args['current_recurring_price'], $recurring_price ) );

    // Do not Upsert any orphaned Variations - i.e. Variations with no parent ids.
    $valid_product =  'variation' == $product->get_type() ? $product->get_parent_id() : $product->get_id();

    // Upsert the Simple and Variations to QPilot.
    if ( apply_filters( 'autoship_upsert_on_batch_update_products', true && !empty( $valid_product ) , 'autoship_bulk_update_recurring_price', $product ) )
    autoship_push_product ( $product->get_id() );

    // Adjust all counters.

    if ( $updated )
    $ids[$product->get_id()] = $product->get_id();

    $last = $product->get_id();
    $count++;

  }

  // Finally calculate the percentage completed and update for next round.
  $pct = $count && $args['total_count'] ? round( 100 * ( ( $count + $args['current_count'] ) / $args['total_count'] ) , 2 ) : 0;
  $pct = !empty( $page ) ? $pct : 100;
  $pct = $pct >= 100 ? 100: $pct;

  return !$count ? array(
    'success'       => false,
    'total_pct'     => 0,
    'current_count' => $args['total_count'],
    'notice'        => __( 'A Problem was encountered processing the products.  Please try again.', 'autoship' )
  ) : array(
    'success'       => true,
    'page'          => $args['current_page']++,
    'last_record'   => isset( $last ) ? $last : 0,
    'updated_record'=> isset( $ids ) ? $ids : array(),
    'count'         => $count,
    'current_count' => $pct == 100 ? $args['total_count'] : $count + $args['current_count'],
    'total_pct'     => $pct < 5 ? 5 : $pct,
    'notice'        => sprintf( __( '%s%% of the %d Products and Product Variations have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] ),
  );

}

/**
 * Batch Updates the Enable Autoship option
 * @param array $args
 */
function autoship_batch_update_product_enable_autoship( $args ){

  $args = wp_parse_args( $args,
  array(
    'current_page'  => -1,
    'current_count' => 0,
    'enable_option' => 'yes',
    'batch_size'    => 10,
    'total_count'   => 0,
  ) );

  // Retrieve the IDs
  // If we're enabling the option we need Simple, Variable, and Variations
  // Else just Simple and Variable
  $query_ids = array();

  if ( 'yes' == $args['enable_option'] ){

    $query_ids      = array_merge(  autoship_batch_query_product_ids ( 'simple' ),
                                    autoship_batch_query_product_ids ( 'variation' ),
                                    autoship_batch_query_product_ids ( 'variable' )
                                  );

  } else {

    $query_ids      = array_merge(  autoship_batch_query_product_ids ( 'simple' ),
                                    autoship_batch_query_product_ids ( 'variable' )
                                  );

  }

  // As long as we haven't processed the full set
  if ( $args['current_count'] < $args['total_count'] ){


    // Don't paginate if full run is expected
    // Otherwise get pages
    $page = array();
    if ( $args['current_page'] > 0 ){

      $pages = array_chunk ( $query_ids, $args['batch_size'] );
      $page = isset( $pages[$args['current_page'] - 1] ) ? $pages[$args['current_page'] -1 ] : array();

    }

    $ids = array();
    $count = $last = 0;
    foreach ( $page as $product_id ) {

      // Get the Product & type
      $product = wc_get_product( $product_id );

      // Add Check for missing or invalid product
      if ( !$product || empty( $product ) )
      continue;

      $type = $product->get_type();

      $updated = true;

      // Only update the parent level for variations and simple products.
      $id = $type == 'variation' ? $product->get_parent_id() : $product->get_id();
     
      $updated = autoship_set_product_autoship_enabled ( $id, $args['enable_option'] );
  
      // If we're enabling the option we update Simple, Variable and we uncheck the disable option for variations
      if ( ( 'yes' == $args['enable_option'] ) && ( $type == 'variation' ) )
      $updated = autoship_set_product_variation_autoship_disabled ( $product->get_id() );

      if ( false == $updated )
      autoship_log_entry( __( 'Autoship Bulk Update Utility', 'autoship' ), sprintf( __('Batch Update Enable Autoship for Product %d Failed or was Not Needed.', 'autoship' ), $product->get_id() ) );

      // Adjust all counters.
      if ( $updated )
      $ids[$product->get_id()] = $product->get_id();

      $last = $product->get_id();
      $count++;

    }

    $pct = $count && $args['total_count'] ? round( 100 * ( ( $count + $args['current_count'] ) / $args['total_count'] ) , 2 ) : 0;

  } else {

    $pct = 100;

  }

  // Finally calculate the percentage completed and update for next round.
  $pct = !empty( $page ) ? $pct : 100;
  $pct = $pct >= 100 ? 100: $pct;


  return !$count ? array(
    'success'       => false,
    'total_pct'     => 0,
    'current_count' => $args['total_count'],
    'notice'        => __( 'A Problem was encountered processing the orders.  Please try again.', 'autoship' )
  ) : array(
    'success'       => true,
    'page'          => $args['current_page']++,
    'last_record'   => isset( $last ) ? $last : 0,
    'updated_record'=> isset( $ids ) ? $ids : array(),
    'count'         => $count,
    'current_count' => $pct == 100 ? $args['total_count'] : $count + $args['current_count'],
    'total_pct'     => $pct < 5 ? 5 : $pct,
    'notice'        => 'yes' == $args['enable_option'] ?
    sprintf( __( '%s%% of the %d Simple Products, Variable Products, and Product Variations have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] ) :
    sprintf( __( '%s%% of the %d Simple Products and Variable Products have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] ),

  );

}

/**
 * Batch Updates the Active Autoship Sync option
 * @param array $args
 */
function autoship_batch_update_product_active_sync( $args ){

  $args = wp_parse_args( $args,
  array(
    'current_page'          => -1,
    'current_count'         => 0,
    'enable_option'         => 'yes',
    'batch_size'            => 10,
    'total_count'           => 0,
    'include_availability'  => true,
  ) );

  // Retrieve the IDs
  $query_ids      = array_merge(  autoship_batch_query_product_ids ( 'simple' ),
                                  autoship_batch_query_product_ids ( 'variation' ),
                                  autoship_batch_query_product_ids ( 'variable' )
                                );

  $count = 0;
  // As long as we haven't processed the full set
  if ( $args['current_count'] < $args['total_count'] ){


    // Don't paginate if full run is expected
    // Otherwise get pages
    $page = array();
    if ( $args['current_page'] > 0 ){

      $pages = array_chunk ( $query_ids, $args['batch_size'] );
      $page = isset( $pages[$args['current_page'] - 1] ) ? $pages[$args['current_page'] -1 ] : array();

    }

    $overrides = ( 'yes' == $args['enable_option'] ) && $args['include_availability'] ?
    array( 'addToScheduledOrder' => true, 'processScheduledOrder' => true ) : array();

    $count = 0;
    foreach ( $page as $product_id ) {

      // Get the Product & type
      $product = wc_get_product( $product_id );

      // Add Check for missing or invalid product
      if ( !$product || empty( $product ) )
      continue;

      $type = $product->get_type();

      $updated = true;

      // Enable or Disable the option based on submit
      $updated = autoship_set_product_sync_active_enabled ( $product->get_id() , $args['enable_option'] );

      // Update the availability flags if needed.
      if ( ( 'yes' == $args['enable_option'] ) && $args['include_availability'] && ( 'variable' != $product->get_type() ) ){

        $updated = autoship_set_product_add_to_scheduled_order ( $product->get_id(), 'yes' );
        $updated = autoship_set_product_process_on_scheduled_order ( $product->get_id(), 'yes' );

      }

      // Update the Simple, Variable or Variation in QPilot.
      autoship_push_product ( $product->get_id(), $overrides );

      if ( false == $updated )
      autoship_log_entry( __( 'Autoship Bulk Update Utility', 'autoship' ), sprintf( __('Batch Update Active Sync for Product %d Failed or was Not Needed.', 'autoship' ), $product->get_id() ) );

      // Adjust all counters.
      if ( $updated )
      $ids[$product->get_id()] = $product->get_id();

      $last = $product->get_id();
      $count++;

    }

    $pct = $count && $args['total_count'] ? round( 100 * ( ( $count + $args['current_count'] ) / $args['total_count'] ) , 2 ) : 0;

  } else {

    $pct = 100;

  }

  // Finally calculate the percentage completed and update for next round.
  $pct = !empty( $page ) ? $pct : 100;
  $pct = $pct >= 100 ? 100: $pct;

  return !$count ? array(
    'success'       => false,
    'total_pct'     => 0,
    'current_count' => $args['total_count'],
    'notice'        => __( 'A Problem was encountered processing the Products.  Please try again.', 'autoship' )
  ) : array(
    'success'       => true,
    'page'          => $args['current_page']++,
    'last_record'   => isset( $last ) ? $last : 0,
    'updated_record'=> isset( $ids ) ? $ids : array(),
    'count'         => $count,
    'current_count' => $pct == 100 ? $args['total_count'] : $count + $args['current_count'],
    'total_pct'     => $pct < 5 ? 5 : $pct,
    'notice'        => 'yes' == $args['enable_option'] ?
    sprintf( __( '%s%% of the %d Simple Products, Variable Products, and Product Variations have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] ) :
    sprintf( __( '%s%% of the %d Simple Products and Variable Products have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] ),

  );

}

/**
 * Batch Updates the Autoship Add To Scheduled Order and Process on Scheduled Orders Options
 * NOTE: Due to the way these flags are stored both are either enabled or disabled via the api.
 * @param array $args
 */
function autoship_batch_update_product_enable_availability( $args ){

  $args = wp_parse_args( $args,
  array(
    'current_page'  => -1,
    'current_count' => 0,
    'enable_option' => 'yes',
    'batch_size'    => 10,
    'total_count'   => 0,
  ) );

  // Retrieve the IDs
  // Depending on Global get active or all are active
  $query_function = autoship_global_sync_active_enabled() ?
  'autoship_batch_query_product_ids' : 'autoship_batch_query_active_product_ids';

  $query_ids  = array_merge(  $query_function ( 'simple' ),
                              $query_function ( 'variation' )
                            );

  $page = array();
  $count = $last = 0;
  // As long as we haven't processed the full set
  if ( $args['current_count'] < $args['total_count'] ){


    // Don't paginate if full run is expected
    // Otherwise get pages
    if ( $args['current_page'] > 0 ){

      $pages = array_chunk ( $query_ids, $args['batch_size'] );
      $page = isset( $pages[$args['current_page'] - 1] ) ? $pages[$args['current_page'] -1 ] : array();

    }

    $ids = array();
    foreach ( $page as $product_id ) {

      $updated = true;

      // Get the Product & type
      $product = wc_get_product( $product_id );

      // Add Check for missing or invalid product
      if ( !$product || empty( $product ) )
      continue;

      // Update the Options in WC
      $updated = autoship_set_product_add_to_scheduled_order ( $product_id, $args['enable_option'] );
      $updated = $updated && autoship_set_product_process_on_scheduled_order ( $product_id, $args['enable_option'] );

      if ( false == $updated )
      autoship_log_entry( __( 'Autoship Bulk Update Utility', 'autoship' ), sprintf( __('Batch Update Availability for Product %d Failed or was Not Needed.', 'autoship' ), $product_id ) );

      // Update the Simple or Variation in QPilot.
      autoship_update_product_availability ( $product_id , 'yes' == $args['enable_option'] ? 'AddToScheduledOrder,ProcessScheduledOrder' : 'none' );

      // Adjust all counters.
      if ( $updated )
      $ids[$product_id] = $product_id;

      $last = $product_id;
      $count++;

    }

    $pct = $count && $args['total_count'] ? round( 100 * ( ( $count + $args['current_count'] ) / $args['total_count'] ) , 2 ) : 0;

  } else {

    $pct = 100;

  }

  // Finally calculate the percentage completed and update for next round.
  $pct = !empty( $page ) ? $pct : 100;
  $pct = $pct >= 100 ? 100: $pct;

  return !$count ? array(
    'success'       => false,
    'total_pct'     => 0,
    'current_count' => $args['total_count'],
    'notice'        => __( 'A Problem was encountered processing the orders.  Please try again.', 'autoship' )
  ) : array(
    'success'       => true,
    'page'          => $args['current_page']++,
    'last_record'   => $last,
    'updated_record'=> isset( $ids ) ? $ids : array(),
    'count'         => $count,
    'current_count' => $pct == 100 ? $args['total_count'] : $count + $args['current_count'],
    'total_pct'     => $pct < 5 ? 5 : $pct,
    'notice'        => sprintf( __( '%s%% of the %d Simple Products and Product Variations have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] )
  );

}

/**
 * Batch Resets the Active Autoship Sync option
 * @param array $args
 */
function autoship_batch_reset_product_active_sync( $args ){

  $args = wp_parse_args( $args,
  array(
    'current_page'  => -1,
    'current_count' => 0,
    'enable_option' => 'yes',
    'batch_size'    => 10,
    'total_count'   => 0,
  ) );

  // Check if the global option is set and if so initialize fresh
  if ( autoship_global_sync_active_enabled() ){

    // Call the client to flip the flag.
    autoship_reset_all_products_activate ( 'no' );

    // Flip the global flag
    autoship_set_global_sync_active_enabled( 'no' );

  }

  if ( 'no' == $args['enable_option'] ){

    // Retrieve the IDs
    $query_ids      = array_merge(  autoship_batch_query_maybe_active_product_ids ( 'simple' ),
                                    autoship_batch_query_maybe_active_product_ids ( 'variation' ),
                                    autoship_batch_query_maybe_active_product_ids ( 'variable' )
                                  );

    $activate_val = 'yes';
  } else {

    // Retrieve the IDs
    $query_ids      = array_merge(  autoship_batch_query_product_ids ( 'simple' ),
                                    autoship_batch_query_product_ids ( 'variation' ),
                                    autoship_batch_query_product_ids ( 'variable' )
                                  );
    $activate_val = 'no';
  }

  // As long as we haven't processed the full set
  if ( $args['current_count'] < $args['total_count'] ){


    // Don't paginate if full run is expected
    // Otherwise get pages
    $page = array();
    if ( $args['current_page'] > 0 ){

      $pages = array_chunk ( $query_ids, $args['batch_size'] );
      $page = isset( $pages[$args['current_page'] - 1] ) ? $pages[$args['current_page'] -1 ] : array();

    }

    $ids = array();
    $count = 0;
    foreach ( $page as $product_id ) {

      // Get the Product & type
      $product = wc_get_product( $product_id );

      // Add Check for missing or invalid product
      if ( !$product || empty( $product ) )
      continue;

      $type = $product->get_type();

      $updated = true;

      // Enable or Disable the option based on submit
      $updated = autoship_set_product_sync_active_enabled ( $product->get_id() , $activate_val );

      // Update the Simple, Variable or Variation in QPilot.
      autoship_push_product ( $product->get_id() );

      if ( false == $updated )
      autoship_log_entry( __( 'Autoship Bulk Update Utility', 'autoship' ), sprintf( __('Batch Update Active Sync for Product %d Failed or was Not Needed.', 'autoship' ), $product->get_id() ) );

      // Adjust all counters.
      if ( $updated )
      $ids[$product->get_id()] = $product->get_id();

      $last = $product->get_id();
      $count++;

    }

    $pct = $count && $args['total_count'] ? round( 100 * ( ( $count + $args['current_count'] ) / $args['total_count'] ) , 2 ) : 0;

  } else {

    $pct = 100;

  }

  // Finally calculate the percentage completed and update for next round.
  $pct = !empty( $page ) ? $pct : 100;
  $pct = $pct >= 100 ? 100: $pct;


  return !$count ? array(
    'success'       => false,
    'total_pct'     => 0,
    'current_count' => $args['total_count'],
    'notice'        => __( 'A Problem was encountered processing the Products.  Please try again.', 'autoship' )
  ) : array(
    'success'       => true,
    'page'          => $args['current_page']++,
    'last_record'   => isset( $last ) ? $last : 0,
    'updated_record'=> isset( $ids ) ? $ids : array(),
    'count'         => $count,
    'current_count' => $pct == 100 ? $args['total_count'] : $count + $args['current_count'],
    'total_pct'     => $pct < 5 ? 5 : $pct,
    'notice'        => 'yes' == $args['enable_option'] ?
    sprintf( __( '%s%% of the %d Simple Products, Variable Products, and Product Variations have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] ) :
    sprintf( __( '%s%% of the %d Simple Products and Variable Products have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] ),

  );

}

/**
 * Batch Updates the Customer Metrics Data
 * @param array $args
 */
function autoship_batch_update_customer_metrics( $args ){

  $args = wp_parse_args( $args,
  array(
    'current_page'  => -1,
    'current_count' => 0,
    'batch_size'    => 10,
    'total_count'   => 0,
  ) );

  // As long as we haven't processed the full set
  if ( $args['current_count'] < $args['total_count'] ){

    $results = autoship_available_customer_metrics( array( 'page' => $args['current_page'], 'pageSize' =>  $args['batch_size'] ) );

    if ( empty( $results->data ) ){

      $pct   = 100;
      $count = $args['total_count'];

    } else {

      foreach ( $results->data as $metrics_data )
      autoship_save_customer_metrics_data( $metrics_data->customerId, $metrics_data, true );

      $count = count( $results->data );
      $pct = $count && $args['total_count'] ? round( 100 * ( ( $count + $args['current_count'] ) / $args['total_count'] ) , 2 ) : 0;

    }

  } else {

    $pct = 100;

  }

  // Finally calculate the percentage completed and update for next round.
  $pct = $count ? $pct : 100;
  $pct = $pct >= 100 ? 100: $pct;

  return !$count ? array(
    'success'       => false,
    'total_pct'     => 0,
    'current_count' => $args['total_count'],
    'notice'        => __( 'A Problem was encountered processing the Customers.  Please try again.', 'autoship' )
  ) : array(
    'success'       => true,
    'page'          => $args['current_page']++,
    'last_record'   => isset( $last ) ? $last : 0,
    'updated_record'=> isset( $ids ) ? $ids : array(),
    'count'         => $count,
    'current_count' => $pct == 100 ? $args['total_count'] : $count + $args['current_count'],
    'total_pct'     => $pct < 5 ? 5 : $pct,
    'notice'        => sprintf( __( '%s%% of the %d Customers have been processed.', 'autoship' ), $pct < 5 ? 5 : $pct,  $args['total_count'] ),
  );

}
