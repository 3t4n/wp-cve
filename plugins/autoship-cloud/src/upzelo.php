<?php
/**
 * Retrieves site integrations from QPilot
 *
 * @return array|WP_Error Array of stdClass integration objects
 */
function autoship_get_site_integrations(){
  // Create QPilot client instance.
  $client = autoship_get_default_client();

  try {

    // Get The Product Group from QPilot.
    $site_integrations = $client->get_site_integrations();
  } catch ( Exception $e ) {
    $notice = autoship_expand_http_code( $e->getCode() );
    $notice = new WP_Error( 'Site Integrations Retrieval Failed', __( $notice['desc'], "autoship" ) );
    autoship_log_entry( __( 'Autoship Site Integrations', 'autoship' ), sprintf( 'Retrieving site integrations failed. Additional Details: Error Code %s - %s', $e->getCode(), $e->getMessage() ) );
    return $notice;
  }

  return $site_integrations;
}

if( !function_exists( 'autoship_get_upzelo_integration' ) ) {

/**
 * Looks for Upzelo integration for the site in use
 *
 * @return stdClass|boolean stdClass of integration object
 */
  function autoship_get_upzelo_integration() {
    // Check if data exists in the cache before making API call
    $has_data = autoship_get_upzelo_integration_cache();
    if(false !== $has_data) {
      return $has_data;
    }
    $site_integrations = autoship_get_site_integrations();
    $site_id = autoship_get_site_id();
    $upzelo_integration = false;

    if( $site_integrations ) {
      foreach ( $site_integrations as $integration ) {
        if ( $site_id === $integration->siteId && $integration->integration->name === 'Upzelo' ) {
          $upzelo_integration = $integration;
          autoship_save_upzelo_integration_cache( $upzelo_integration );
          break;
        }
      }
    }
    return $upzelo_integration;
  }
}

if ( !function_exists( 'autoship_get_upzelo_integration_cache' ) ){

  /**
   * Retrieves the Transient option
   * Can be overwritten to use other caching, session, transient.
   * @see get_transient()
   */
  function autoship_get_upzelo_integration_cache(){
    return get_transient( apply_filters( 'autoship_upzelo_integration_cache_object_name' , 'autoship_upzelo_integration_cache' ) );
  }

}

if ( !function_exists( 'autoship_save_upzelo_integration_cache' ) ){

  /**
   * Saves the Transient option
   * Can be overwritten to use other caching, session, transient.
   * @see set_transient()
   */
  function autoship_save_upzelo_integration_cache( $data ){
    $bool_response = set_transient(
      apply_filters( 'autoship_upzelo_integration_cache_object_name' , 'autoship_upzelo_integration_cache' ),
      $data,
      apply_filters( 'autoship_upzelo_integration_cache_max_life', 300 ) );
  }

}


/**
 * Load Upzelo script on accuont pages
 */
function autoship_upzelo_load_script(){
  // Bail if not account pages
  if ( !is_account_page() ) {
    return;
  }
  $upzelo_integration = autoship_get_upzelo_integration();
  // Check if Upzelo integration exists and if enabled
  if ( !$upzelo_integration || $upzelo_integration->enabled == false ) {
    return;
  }
  wp_print_script_tag([
    "id" => apply_filters( 'autoship_upzelo_script_id', 'upzpdl' ),
    "src" => esc_url( apply_filters(' autoship_upzelo_script_url', 'https://assets.upzelo.com/upzelo.min.js' ) ),
    "appId" => esc_attr( $upzelo_integration->outboundApiKey1 ),
    "defer" => apply_filters( 'autoship_upzelo_script_defer', true ),
  ]);
}
add_action( 'wp_head', 'autoship_upzelo_load_script', 10 );


/**
 * Add Upzelo script to the end of the the single Scheduled Order page
 *
 * @param array  $autoship_order The autoship order array.
 * @param int    $customer_id The woocommerce customer id.
 * @param int    $autoship_customer_id The autoship customer id.
 * @param int    $autoship_order_id The autoship order id.
 *
 * @return string The html/script output
 */
function upzelo_autoship_after_scheduled_order_edit( $autoship_order, $customer_id, $autoship_customer_id, $autoship_order_id ){

  $upzelo_integration = autoship_get_upzelo_integration();

  // Check if Upzelo integration exists and if enabled
  if (!$upzelo_integration || $upzelo_integration->enabled == false) {
    return;
  }
  $retention_api_key = $upzelo_integration->outboundApiKey2;
  $customer_id_conf = isset( $autoship_order['lastProcessingCycle']['scheduledOrder']['customerId'] ) ? $autoship_order['lastProcessingCycle']['scheduledOrder']['customerId']  : $autoship_customer_id;

  $hash = hash_hmac( 'sha256', $customer_id_conf, $retention_api_key );
  $button_selector_selector = '.autoship-action-btn.deleted';
  $button_selector = apply_filters( 'autoship_uzpelo_config_single_so_delete_button_selector', $button_selector_selector, $autoship_order );

  $customer_id_conf = apply_filters( 'autoship_uzpelo_config_single_so_customer_id', $customer_id_conf, $customer_id, $autoship_customer_id, $autoship_order );
  $type = apply_filters( 'autoship_uzpelo_config_single_so_type', 'full', $autoship_order );
  $mode = 'live';
  if( isset( $upzelo_integration->testMode ) && $upzelo_integration->testMode ) {
    $mode = 'test';
  }
  $mode = apply_filters( 'autoship_uzpelo_config_single_so_mode', $mode, $autoship_order );
?>
  <script>
    const cancelButton = document.querySelector('<?php echo $button_selector ?>');
    if (cancelButton) {
      cancelButton.addEventListener('click', () => {
        // Upzelo Config object.
        const config = {
          // The customer's ID from the subscription platform
          customerId: '<?php echo esc_attr( $customer_id_conf ); ?>',
          // The customer's subscription ID from the subscription platform
          subscriptionId: '<?php echo esc_attr( $autoship_order_id ); ?>',
          // The HMAC hash generated in step 2
          hash: '<?php echo esc_attr( $hash ); ?>',
          // The type of flow to serve
          type: '<?php echo esc_attr( $type ); ?>',
          // The mode that we are working with.
          mode: '<?php echo esc_attr( $mode ); ?>',
        };
        window.upzelo.open(config);
      });
    }
  </script>
<?php
  // Hide Autoship confirmation dialog
  if( ( $button_selector === $button_selector_selector ) && apply_filters( 'autoship_uzpelo_config_single_so_hide_delete_confirm_msg', true ) ) : 
?>
  <style>.confirm-delete { display: none; }</style>
<?php
  endif;
}
add_action( 'autoship_after_scheduled_order_edit', 'upzelo_autoship_after_scheduled_order_edit', 30, 4 );


/**
 * Add Upzelo script after Scheduled Orders template
 *
 * @param array  $autoship_order The autoship order array.
 * @param int    $customer_id The woocommerce customer id.
 * @param array  $autoship_orders Array of Autoship scheduled orders objects
 *
 * @return string The html/script output
 */
function upzelo_autoship_after_autoship_scheduled_orders_template( $customer_id, $autoship_customer_id, $autoship_orders ){
  $upzelo_integration = autoship_get_upzelo_integration();

  // Check if Upzelo integration exists and if enabled
  if ( !$upzelo_integration || $upzelo_integration->enabled == false ) {
    return;
  }
  $retention_api_key = $upzelo_integration->outboundApiKey2;
  $mode = 'live';
  if( isset( $upzelo_integration->testMode ) && $upzelo_integration->testMode ) {
    $mode = 'test';
  }
  $type = apply_filters( 'autoship_uzpelo_config_list_so_type', 'full' );
  $mode = apply_filters( 'autoship_uzpelo_config_list_so_mode', $mode );
  $customer_id_conf = $autoship_customer_id;
  $configs = [];

  foreach( $autoship_orders as $autoship_order ) {
    $button_selector_selector = sprintf('.autoship-button.deleted[data-autoship-order="%d"]', $autoship_order->id);
    $button_selector = apply_filters( 'autoship_uzpelo_config_list_so_delete_button_selector', $button_selector_selector, $autoship_order);
    $customer_id_conf = isset( $autoship_order->lastProcessingCycle->scheduledOrder->customerId ) ? $autoship_order->lastProcessingCycle->scheduledOrder->customerId : $autoship_customer_id;
    $customer_id_conf = apply_filters( 'autoship_uzpelo_config_list_so_customer_id', $customer_id_conf, $customer_id, $autoship_customer_id );
    $configs[$autoship_order->id] = $button_selector;
  }

  $hash = hash_hmac( 'sha256', $customer_id_conf, $retention_api_key );

  if( $configs ) :
    ?>
  <script>
    const configs = <?php echo json_encode( $configs ); ?>;
    if(configs) {
      for (const key in configs) {
        if (configs.hasOwnProperty(key)) {
          const value = configs[key];
          let cancelButton = document.querySelector(value);
          if (cancelButton) {
            cancelButton.addEventListener('click', () => {
              // Upzelo Config object.
              const config = {
                // The customer's ID from the subscription platform
                customerId: '<?php echo esc_attr( $customer_id_conf ); ?>',
                // The customer's subscription ID from the subscription platform
                subscriptionId: key,
                // The HMAC hash generated in step 2
                hash: '<?php echo esc_attr( $hash ); ?>',
                // The type of flow to serve
                type: '<?php echo esc_attr( $type ); ?>',
                // The mode that we are working with.
                mode: '<?php echo esc_attr( $mode ); ?>',
              };
              window.upzelo.open(config);
            });
          }
        }
      }
    }
  </script>
<?php
  // Hide Autoship confirmation dialog
    if( ( str_starts_with( $button_selector, '.autoship-button.deleted' ) ) && apply_filters( 'autoship_uzpelo_config_list_so_hide_delete_confirm_msg', true, $autoship_order ) ) : 
      ?>
        <style>table.shop_table_responsive tr td.confirm-delete { display: none; }</style>
      <?php
      endif;
  endif;
}
add_action( 'autoship_after_autoship_scheduled_orders_template', 'upzelo_autoship_after_autoship_scheduled_orders_template', 30, 3 ); 
