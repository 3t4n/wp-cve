<?php

  /**
   * Outputs a create scheduled order link
   *
   * @param  array $attributes Attributes.
   * @return void
   */
  function autoship_shortcode_create_scheduled_order_action( $attributes = array() ) {

  	$attributes = shortcode_atts( array(
  		'customer_id'     => get_current_user_id(), // The user to create the scheduled order for.
  		'wrap_start'      => '',                    // Append html to beginning ( i.e. an open div <div class="wrap">)
  		'wrap_end'        => '',                    // Append html to the end ( i.e. a closed div </div>)
  		'classes'         => '',                    // Classes to attach to the link ( classes should be separated by a space )
      'products'        => '',                    // A list of product ids and associated qty that should be added to the order by default
                                                  // Ids and Qty should be separated by a : (i.e. 244:2 = Product ID 244 Qty 2 ).  Multiple Products should
                                                  // be separated by a comma
      'schedule'        => '',                    // The Frequency & Frequency Type separated by a : ( i.e. Months:2 = Every 2 Months )
      'next'            => '',                    // The Next Occurrence Date & time ( i.e. 2019-12-10 10:00AM )
      'mincycle'        => '',                    // A single min cycle or array of product ids and min cycles to use
                                                  // Ids and Min Cycles should be separated by a : (i.e. 244:2 = Product ID 244 Min Cyle 2 ).  Multiple Products should
                                                  // be separated by a comma
      'maxcycle'        => '',                    // A single max cycle or array of product ids and max cycles to use
                                                  // Ids and Max Cycles should be separated by a : (i.e. 244:2 = Product ID 244 Max Cyle 2 ).  Multiple Products should
                                                  // be separated by a comma
      'atts'            => '',                    // Any additional data attributes to attach to the link.
      'label'           => __( sprintf( 'Create %s', autoship_translate_text( 'Scheduled Order' ) ), 'autoship') // The Button/Link Label
  	), $attributes, 'autoship-create-order-action' );

    $params = array();

    // If a customer id is supplied be sure the current user
    // is belongs to the id or has rights
    if ( $attributes['customer_id'] && ( ( $attributes['customer_id'] == get_current_user_id() ) || autoship_rights_checker( 'autoship_filter_allow_customer_var_on_create_order', array('administrator') ) ) ){

      $params['customer'] = absint( $attributes['customer_id'] );

    }

    // Add Freq and Freqtype
    if ( !empty( $attributes['schedule'] ) && !empty( $attributes['schedule'] )){

      $vals = explode( ':', $attributes['schedule'] );
      if ( count( $vals ) > 1 ){
        $params['freqtype'] = $vals[0];
        $params['freq'] = absint($vals[1]);
      } else {
        if ( is_numeric( $vals[0] ) ){ $params['freq'] = $vals[0]; } else { $params['freqtype'] = $vals[0]; }
      }

    }

    // Add Products and Qty
    if ( !empty( $attributes['products'] ) ){
      $params['products'] = array();
      $vals = explode( ',', $attributes['products'] );
      foreach ($vals as $values) {
        $product = explode( ':', $values );
        $params['products'][absint($product[0])] = isset( $product[1] ) ? absint( $product[1] ) : 1;
      }
    }

    // Add Next Occurrence
    if ( !empty( $attributes['next'] ) ){
      // $vals  = explode( ' ', $attributes['next'] );
      // $date  = $vals[0] . ' ';
      // $time  = isset( $vals[1] ) ? strtotime( $vals[1] ) : false;
      // $date .= false === $time ? date( "g:iA" ) : date( "g:iA", $time );
      $params['nextoccurrence'] = $attributes['next'];
    }

    // Add Min cycles
    if ( !empty( $attributes['mincycle'] ) ){
      $params['mincycle'] = array();
      $vals = explode( ',', $attributes['mincycle'] );

      if ( count( $vals ) > 1 ){
        foreach ($vals as $values) {
          $product = explode( ':', $values );
          $params['mincycle'][absint($product[0])] = isset( $product[1] ) ? absint( $product[1] ) : 0;
        }
      } else {
        $params['mincycle'] = absint( $product[0] );
      }
    }

    // Add Max cycles
    if ( !empty( $attributes['maxcycle'] ) ){
      $params['maxcycle'] = array();
      $vals = explode( ',', $attributes['maxcycle'] );
      foreach ($vals as $values) {
        $product = explode( ':', $values );
        $params['maxcycle'][absint($product[0])] = isset( $product[1] ) ? absint( $product[1] ) : 0;
      }
    }

    $link = apply_filters( 'autoship_shortcode_create_scheduled_order_action_link',
    sprintf( '%s<a href="%s" class="button autoship-action-btn %s" %s >%s</a>%s', $attributes['wrap_start'], autoship_get_scheduled_order_create_url( $params ), $attributes['classes'], $attributes['atts'], $attributes['label'], $attributes['wrap_end'] ), $attributes, $params );

    return $link;

  }
  add_shortcode( 'autoship-create-scheduled-order-action', 'autoship_shortcode_create_scheduled_order_action' );

  /**
   * Outputs the My Account > Scheduled Orders Page html
   * The page could either be the iframe or WP Template
   *
   * @param  array $atts Attributes.
   * @return void
   */
  function autoship_shortcode_scheduled_orders( $attributes = array() ) {

  	$attributes = shortcode_atts( array(
  		'customer_id' => get_current_user_id(), // The user to retrieve the orders for.
  		'template'    => '',                    // Php Template file to use ( allows for custom )
      'version'     => '',                    // Which version should be displayed ( iframe, app, template )
      'page'        => 1,                     // Page to show if paginated
      'paginate'    => false,                 // True paginate else show all.
  	), $attributes, 'autoship_shortcode_scheduled_orders' );

    // If a customer id is supplied be sure the current user
    // is belongs to the id or has rights
    if ( $attributes['customer_id'] && ( $attributes['customer_id'] != get_current_user_id() ) ){

      // If they don't have rights nada!
      // By default only admins can see other users' accounts
      if ( !autoship_rights_checker( 'autoship_customer_accounts_view_scheduled_orders_rights', array( 'administrator') ) )
      return sprintf( __( 'No %s found or insufficient rights.', 'autoship' ), autoship_translate_text( 'scheduled orders' ) );

    }

    $attributes['autoship_customer_id'] = autoship_get_autoship_customer_id( $attributes['customer_id'], 'autoship_shortcode_scheduled_orders' );

    // Get the display version based on the Settings if not supplied
    $attributes['version'] = empty( $attributes['version'] ) ? autoship_get_scheduled_orders_display_version() : $attributes['version'];
    $display_function = 	"autoship_scheduled_orders_{$attributes['version']}_display";

    return function_exists( $display_function ) ?
    $display_function ( $attributes['customer_id'], $attributes['autoship_customer_id'], $attributes['template'], $attributes['page'], $attributes['paginate'] ) :
    sprintf( __( 'No %s found.', 'autoship' ), autoship_translate_text( 'scheduled orders' ) );

  }
  add_shortcode( 'autoship-scheduled-orders', 'autoship_shortcode_scheduled_orders' );

  /**
   * Outputs the My Account > Scheduled Orders > View/Edit Scheduled Order Page html
   *
   * @param  array $atts Attributes.
   * @return void
   */
  function autoship_shortcode_scheduled_order ( $attributes = array() ) {

  	$attributes = shortcode_atts( array(
  		'customer_id'       => get_current_user_id(), // The user to retrieve the orders for.
  		'autoship_order_id' => '',                    // The Autoship Order Number
      'template'          => '',
  	), $attributes, 'autoship_shortcode_scheduled_order' );

    $attributes['autoship_customer_id'] = autoship_get_autoship_customer_id( $attributes['customer_id'], 'autoship_shortcode_scheduled_order'  );

    // If a customer id is supplied be sure the current user
    // is belongs to the id or has rights
    if ( $attributes['customer_id'] && ( $attributes['customer_id'] != get_current_user_id() ) ){

      // If they don't have rights nada!
      // By default only admins can see other users' accounts
      if ( !autoship_rights_checker( 'autoship_customer_accounts_view_scheduled_order_rights', array( 'administrator') ) )
      return sprintf( __( 'No %s found or insufficient rights.', 'autoship' ), autoship_translate_text( 'scheduled orders' ) );

    }

    return autoship_scheduled_order_template_display ( $attributes['customer_id'], $attributes['autoship_customer_id'], $attributes['autoship_order_id'], $attributes['template'] );

  }
  add_shortcode( 'autoship-scheduled-order', 'autoship_shortcode_scheduled_order' );

  /**
   * Outputs the My Account > Scheduled Orders > View Scheduled Order Page html
   *
   * @param  array $atts Attributes.
   * @return void
   */
  function autoship_shortcode_view_scheduled_order ( $attributes = array() ) {

  	$attributes = shortcode_atts( array(
  		'customer_id'       => get_current_user_id(), // The user to retrieve the orders for.
  		'autoship_order_id' => '',                    // The Autoship Order Number
      'template'          => '',
  	), $attributes, 'autoship_shortcode_view_scheduled_order' );

    $attributes['autoship_customer_id'] = autoship_get_autoship_customer_id( $attributes['customer_id'], 'autoship_shortcode_view_scheduled_order' );

    return autoship_view_scheduled_order_template_display ( $attributes['customer_id'], $attributes['autoship_customer_id'], $attributes['autoship_order_id'], $attributes['template'] );

  }
  add_shortcode( 'autoship-view-scheduled-order', 'autoship_shortcode_view_scheduled_order' );

  /**
   * Outputs the Scheduled Orders Cart Template
   *
   * @param  array $atts Attributes.
   * @return void
   */
  function autoship_shortcode_schedule_cart( $attributes = array() ) {
  	$attributes = shortcode_atts( array(), $attributes );

  	if ( ! function_exists( 'WC' ) ) {
  		return;
  	}

  	$cart_items = WC()->cart->get_cart();
  	if ( empty( $cart_items ) ) {
  		return;
  	}

  	$cart_items_grouped_by_frequency = autoship_group_cart_items( $cart_items );

  	return autoship_render_template( 'scheduled-orders-cart/schedule-cart', array( 'cart_items_grouped_by_frequency' => $cart_items_grouped_by_frequency ) );
  }
  add_shortcode( 'autoship-schedule-cart', 'autoship_shortcode_schedule_cart' );

  /**
   * Outputs the Chat Bot Template
   *
   * @param  array $atts Attributes.
   * @return void
   */
  function autoship_shortcode_customer_bot( $attributes = array() ) {
  	$attributes = shortcode_atts( array(), $attributes );

  	$webchat_directline_secret = get_option( 'autoship_webchat_directline_secret' );
  	if ( empty( $webchat_directline_secret ) ) {
  		return '';
  	}

  	$customer_id = get_current_user_id();
  	if ( empty( $customer_id ) ) {
  		return '';
  	}
  	$autoship_customer_id = get_user_meta( $customer_id, '_autoship_customer_id', true );
  	if ( ! empty( $autoship_customer_id ) ) {
  		$client = autoship_get_default_client();
  		try {
  			$scheduled_orders = $client->get_orders( $autoship_customer_id );
  			$scheduled_orders_count = count( $scheduled_orders );
  			if ( $scheduled_orders_count < 1 ) {
  				return '';
  			}

  			$accessToken = $client->generate_customer_access_token( $autoship_customer_id, autoship_get_client_secret() );
  			$user_data = get_userdata( $customer_id );
  			return autoship_render_template( 'webchat/customer-bot', array(
  				'webchat_directline_secret' => $webchat_directline_secret,
  				'scheduled_orders_count' => $scheduled_orders_count,
  				'customer_id' => $customer_id,
  				'customer_name' => $user_data->user_firstname,
  				'autoship_customer_id' => $autoship_customer_id,
  				'token_auth' => $accessToken->TokenAuth
  			) );
  		} catch ( Exception $e ) {
  			return sprintf( __( 'Error %1$s: %2$s', 'autoship' ), strval( $e->getCode() ), $e->getMessage() );
  		}
  	}

  	return '';
  }
  add_shortcode( 'autoship-customer-bot', 'autoship_shortcode_customer_bot' );

  // Enable shortcodes in text widgets
  add_filter( 'widget_text','do_shortcode' );
