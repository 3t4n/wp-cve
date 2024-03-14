<?php


// ==========================================================
// WC Query Adjustments
// ==========================================================

/**
 * Adjusts the WC Query by adding the '_autoship_schedule_order_enabled' as an
 * additional variable that can be used in the wc_get_products() function
 *
 * @param array $wp_query_args The current query arguments
 * @param array $query_vars Query vars from a WC_Product_Query.
 * @param WC_Product_Data_Store_CPT The current Data Store Object
 *
 * @return array The filtered query arguments
 */
function autoship_handling_meta_query_keys( $wp_query_args, $query_vars, $data_store_cpt ) {
    $meta_key = '_autoship_schedule_order_enabled'; // The custom meta_key

    if ( ! empty( $query_vars[$meta_key] ) ) {
        $wp_query_args['meta_query'][] = array(
            'key'     => $meta_key,
            'value'   => esc_attr( $query_vars[$meta_key] ),
            'compare' => '=',
        );
    }
    return $wp_query_args;
}

// ==========================================================
// Get & Utility Functions
// ==========================================================

/**
 * Retrieves the Display name for the Product or Variation
 *
 * @param WC_Product|int $product The WC Product or WC Product ID to retrieve the name for.
 * @param int $frequency The frequency
 * @return string The display name.
 */
function autoship_get_product_display_name( $product ) {

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

	$display_name = '';
	if ( null == $product ) {

		$display_name = '';

	} elseif ( method_exists( $product, 'get_name' ) ) {

		$display_name = $product->get_name();

	} else {

		$display_name = $product->get_title();

	}

	if ( $product->is_type( 'variation' ) ) {

		if ( version_compare( WC()->version, '3.0', '<' ) ) {
			// WC 2.6
			$display_name .= ' (' . trim( strip_tags( str_replace( '><', ' > <', str_replace( '</dd><dt>', ', ', $product->get_formatted_variation_attributes() ) ) ) ) . ')';
		} else {
			// WC 3.0
			$display_name .= ' (' . wc_get_formatted_variation( $product, true, true ) . ')';
		}
	}


	return apply_filters( 'autoship_product_display_name', $display_name, $product->get_id() );
}

/**
 * retrieves the img html using the supplied url.
 *
 * @param string $url        the image url.
 * @return string            The img html output
 */
function autoship_get_product_thumbnail_html ( $url ){

  return apply_filters( 'autoship_get_product_thumbnail_html', '<img width="100%" height="100%" src="'. $url .'" />', $url );

}

/**
 * Queries products by the Sync Active metadata value.
 * @param string $active The Autoship Sync Active flag value to query for.
 * @param array $params Additional Query Params to narrow down search.
 * @return array results.
 */
function autoship_query_products_by_sync_active ( $active, $params = array() ){
  return wc_get_products( array_merge( $params, array( '_autoship_sync_active_enabled' => $active ) ) );
}

/**
 * Add the '_autoship_sync_active_enabled' metadata field to handle custom query.
 * @param array $query - Args for WP_Query.
 * @param array $query_vars - Query vars from WC_Product_Query.
 * @return array modified $query
 */
function autoship_handle_custom_sync_active_meta_query( $wp_query_args, $query_vars, $data_store_cpt ) {
    $meta_key = '_autoship_sync_active_enabled'; // The custom meta_key

    if ( ! empty( $query_vars[$meta_key] ) ) {
        $wp_query_args['meta_query'][] = array(
            'key'     => $meta_key,
            'value'   => esc_attr( $query_vars[$meta_key] ),
        );
    }
    return $wp_query_args;
}

/**
 * Gets the Autoship index for the associated WC Weight Unit of Measurement
 * @param string $wc_unit The WooCommerce Unit of Measurement for weight
 * @return int|NULL The Autoship corresponding number. Null if not found
 */
function autoship_get_mapped_weight_unit( $wc_unit ){

  /*
  Current Weight Values
  Pound = 0,
  Ounce = 1,
  Kilogram = 2,
  Gram = 3
  */
  $wc_options  = array (
    'kg'  => 'Kilogram',
    'g'   => 'Gram',
    'lbs' => 'Pound',
    'oz'  => 'Ounce',
  );

  return isset( $wc_options[$wc_unit] )? $wc_options[$wc_unit] : NULL;

}

/**
 * Gets the Autoship index for the associated WC Length Unit of Measurement
 * @param string $wc_unit The WooCommerce Unit of Measurement for length
 * @return int|NULL The Autoship corresponding number. Null if not found
 */
function autoship_get_mapped_length_unit( $wc_unit ){

  /*
  Current Length Values
  Inch = 0,
  Foot = 1,
  Milimeter = 2,
  Centimeter = 3,
  Meter = 4
  */
  $wc_options = array (
    'm'  => 'Meter',
    'cm' => 'Centimeter',
    'mm' => 'Milimeter',
    'in' => 'Inch',
    'ft' => 'Foot',
    'yd' => 'Yard',
  );

  return isset( $wc_options[$wc_unit] )? $wc_options[$wc_unit] : NULL;

}

/**
 * Retrieves the QPilot Equivalent for the supplied WC Stock Status
 * @param string $stock_status The WC Stock Status
 * @return string The converted status.
 */
function autoship_get_mapped_stocklevel( $stock_status ){

  $translation = apply_filters( 'autoship_get_mapped_stocklevel', array(
    'outofstock'  => 'OutOfStock',
    'onbackorder' => 'InStock',
    'instock'     => 'InStock',
  ) );

  return isset( $translation[$stock_status] ) ? $translation[$stock_status] : '';

}

/**
 * Retrieves a list of valid product statuses for Sync Action
 * @return array of valid statuses
 */
function autoship_valid_product_sync_statuses(){

  return apply_filters( 'autoship_valid_product_statuses', array(
	'publish'
  ));

}

/**
 * Retrieves a list of valid post types for the sync action
 * @return array of valid types
 */
function autoship_valid_post_sync_types(){

  return apply_filters( 'autoship_valid_post_types', array(
  'product', 'product_variation'
  ));

}

/**
 * Retrieves a list of valid product types for the sync action
 * @return array of valid sync types
 */
function autoship_valid_product_sync_types(){

  return apply_filters( 'autoship_valid_product_types', array(
  'simple', 'variable', 'variation'
  ));

}

/**
 * Validates a Products Type for Upsert.
 * @param WC_Product|int $product The WC Product to check
 * @return bool True if valid else false.
 */
function autoship_is_valid_sync_type_product( $product ){

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  if ( !$product )
  return false;

  // Get the Valid Sync Types
  $valid_sync_types = autoship_valid_product_sync_types();

  // Get this products status and type
  $product_type = $product->get_type();

  return in_array( $product_type, $valid_sync_types);
}

/**
 * Validates a Products Type and Status for Upsert.
 * @param WC_Product|int $product The WC Product to check
 * @return bool True if valid else false.
 */
function autoship_is_valid_sync_status_product( $product ){

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  if ( !$product )
  return false;

  // Get the Valid Sync Statuses
  $valid_sync_statuses = autoship_valid_product_sync_statuses();

  // Get this products status and type
  $product_status = $product->get_status();

  return in_array( $product_status, $valid_sync_statuses );
}

/**
 * Validates a Products Type and Status for Upsert.
 * @param WC_Product|int $product The WC Product to check
 * @return bool True if valid else false.
 */
function autoship_is_valid_sync_post_type( $product ){

  if ( !( $product instanceof WC_Product ) && !( $product instanceof WC_Product_Variation ) )
  $product = wc_get_product( absint( $product ) );

  if ( !$product )
  return false;

  $valid_sync_post_types = autoship_valid_post_sync_types();

  return in_array( get_post_type( $product->get_id() ), $valid_sync_post_types );

}

/**
 * Validates a Products Type and Status for Upsert.
 * @param WC_Product|int $product The WC Product to check
 * @return bool True if valid else false.
 */
function autoship_is_valid_sync_product( $product ){

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  if ( !$product )
  return false;

  $valid_sync_post_types = autoship_valid_post_sync_types();

  if ( !in_array( get_post_type( $product->get_id() ), $valid_sync_post_types ) )
  return false;

  // Get the Valid Sync Statuses and Types
  $valid_sync_statuses = autoship_valid_product_sync_statuses();
  $valid_sync_types = autoship_valid_product_sync_types();

  // Get this products status and type
  $product_type = $product->get_type();
  $product_status = $product->get_status();

  return ( in_array( $product_type, $valid_sync_types ) && in_array( $product_status, $valid_sync_statuses ) );
}

/**
 * Checks if a Variation is Available.
 * Uses same rules as @see WC_Product_Variable::get_available_variations()
 *
 * @param WC_Product_Variation|int $product The WC Product Variation to check
 * @return bool True if valid else false.
 */
function autoship_is_available_variation( $variation ){

  if ( is_numeric( $variation ) ){
    $variation = wc_get_product( $variation );
  }

  if ( ! $variation || ! $variation->exists() )
  return false;

  return apply_filters( 'autoship_is_available_variation', true, $variation );

}

/**
 * Gets the Available variations for a product.
 * @see autoship_is_available_variation()
 *
 * @param WC_Product_Variable|int $product The WC Product
 * @param bool $include_invalids When true an array is returned
 *                               with both valid and invalid ids.
 * @return array The variations ids.
 */
function autoship_get_available_variations( $product, $include_invalids = false ){

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  // Check if the product is a variable product
  // If variable & has children we should loop through children.
  $variations = $invalids = array();
  if ( ( 'variable' == $product->get_type() ) && $product->has_child() ) {

    // Update the child variations
    foreach ( $product->get_children() as $variation_id ) {

      if ( autoship_is_available_variation( $variation_id ) ){
        $variations[] = $variation_id;
      } else if ( $include_invalids ){
        $invalids[] = $variation_id;
      }

    }

  }

  return $include_invalids ? array(
    'valids' => $variations,
    'invalids' => $invalids
  ) : $variations;

}


/**
 * Retrieves the custom Autoship Product Data for a product or variation.
 *
 * @param int $product_id. The product or variation id.
 * @return array of custom frequency options. Empty array if no custom exist.
 */
function autoship_get_product_custom_data_values( $product ){

  if ( is_numeric( $product ) )
	$product = wc_get_product( $product );

  $data = array(
    'title'       => autoship_get_product_display_name( $product ),
    'weightunit'  => NULL,
    'lengthunit'  => NULL,
  );

  if ( !$product )
  return $data;

	$post_id = $product->get_id();
	if ( "yes" == autoship_override_product_data_enabled( $product->get_id() ) ) {

    $custom_title = autoship_get_product_title_override( $product->get_id() );

    if ( !empty( $custom_title ) )
    $data['title'] = $custom_title;

    $custom_weightunit = autoship_get_product_weightunit_override( $product->get_id() );

    if ( !empty( $custom_weightunit ) )
    $data['weightunit'] = $custom_weightunit;

    $custom_lengthunit = autoship_get_product_lengthunit_override( $product->get_id() );

    if ( !empty( $custom_lengthunit ) )
    $data['lengthunit'] = $custom_lengthunit;

	}

	return apply_filters( 'autoship-product-custom-data-values', $data, $product->get_id() );

}

/**
 * Checks if the supplied schedule is valid for the product
 *
 * @param int $product_id The product or variation id.
 * @param int $frequency The frequency to check
 * @param string $frequency_type The frequency type to check
 *
 * @return bool True if valid else false.
 */
function autoship_is_valid_product_frequency_option( $product_id, $frequency, $frequency_type ){

  if ( !empty( $options = autoship_product_frequency_options( $product_id ) ) ) {

    // Check if assigned freq and type exists in array
    foreach ( $options as $key => $values ) {

      if ( $frequency_type == $values['frequency_type'] && $frequency == $values['frequency'] )
      return true;

    }

  }

  return false;

}

/**
 * Retrieves the custom Autoship frequency options for a product or variation.
 *
 * @param int $product_id. The product or variation id.
 * @return array of custom frequency options. Empty array if no custom exist.
 */
function autoship_get_product_custom_frequency_options( $product ){

  $options = array();

  if ( is_numeric( $product ) )
	$product = wc_get_product( $product );

  if ( !$product )
  return $options;

	$post_id = $product->get_id();
	if ( "yes" == autoship_override_frequency_options_enabled( $product->get_id() ) ) {

    // Get the Global Max allowed custom frequencies
    $autoship_frequency_options = autoship_get_frequency_options_count();

    // Iterate through the total possible frequencies and check for values.
		for ( $i = 0; $i < $autoship_frequency_options; $i++ ) {

      $frequency_type  = get_post_meta( $post_id, "_autoship_frequency_type_{$i}", true );
			$frequency       = get_post_meta( $post_id, "_autoship_frequency_{$i}", true );
			$display_name    = get_post_meta( $post_id, "_autoship_frequency_display_name_{$i}", true );

			if ( empty( $frequency_type ) || empty( $frequency ) )
			continue;

      // If a custom display name wasn't entered then get the default.
			if ( empty( $display_name ) )
			$display_name = autoship_get_frequency_display_name( $frequency_type, $frequency );

      $options[$frequency_type.'-'.$frequency] = array(
				'frequency_type' => $frequency_type,
				'frequency'      => $frequency,
				'display_name'   => $display_name
			);

    }

	}

	return apply_filters( 'autoship-product-frequency-options', $options, $product->get_id() );

}

/**
 * Retrieves the Autoship frequency options for a product or variation.
 * NOTE: This value can be modified via the autoship-product-frequency-options filter.
 *
 * @param int $product_id. The product or variation id.
 * @return array of options.
 */
function autoship_product_frequency_options( $product_id ) {

	$product = wc_get_product( $product_id );

	if ( !$product )
	return array();

  // Check for and retrieve any custom frequencies for this product
  $options = autoship_get_product_custom_frequency_options( $product );

	// Fall back to parent product for variations
	if ( empty( $options ) && $product->is_type( 'variation' ) )
  return autoship_product_frequency_options( $product->get_parent_id() );

  // If no custom frequencies exist return defaults
  return !empty( $options ) ?
  $options : autoship_default_frequency_options();

}

/**
 * Outputs the default Autoship frequency options.
 * @return array of options.
 */
function autoship_default_frequency_options() {
	$options = array();
  $autoship_frequency_options = apply_filters( 'autoship_default_frequency_option_count' , Autoship_Options_Count );
	for ( $i = 0, $f = 1; $i < $autoship_frequency_options; $i++, $f++ ) {
		$options['Months'.'-'.$f] = array(
			'frequency_type' => 'Months',
			'frequency' => $f,
			'display_name' => autoship_get_frequency_display_name('Months', $f )
		);
	}
	return apply_filters( 'autoship-default-frequency-options', $options );
}

/**
 * Retrieves the default frequency override number of field groups
 * NOTE  This count can be overridden by developers using the
 *       autoship_custom_frequency_override_count filter
 * @return int the total number of frequency override fields.
 */
function autoship_get_frequency_options_count (){

  return apply_filters( 'autoship_custom_frequency_override_count' , Autoship_Options_Count );

}

/**
 * Retrieves the image url for a product or variation.
 *
 * @param int $product_id. The product or variation id.
 * @return string|NULL The image url is it exists or NULL if not.
 */
function autoship_get_wc_product_image_url( $wc_product_id ) {

	$wc_product = wc_get_product( $wc_product_id );
	if ( null == $wc_product ) {
		return null;
	}
	$image_id = 0;
	if ( $wc_product->is_type( 'variation' ) ) {
		if ( property_exists( $wc_product, 'variation_id' ) ) {
			// WC 2.6
			$image_id = get_post_thumbnail_id( $wc_product->variation_id );
		}
		if ( empty( $image_id ) ) {
			$image_id = get_post_thumbnail_id( $wc_product->get_id() );
			if ( empty( $image_id ) ) {
				if ( method_exists( $wc_product, 'get_parent_id' ) ) {
					// WC 3.0
					$image_id = get_post_thumbnail_id( $wc_product->get_parent_id() );
				}
			}
		}
	} else {
		$image_id = get_post_thumbnail_id( $wc_product->get_id() );
	}
	if ( $image_id ) {
		$image_src = wp_get_attachment_image_src( $image_id, 'full' );
		if ( ! empty( $image_src[0] ) ) {
			return $image_src[0];
		}
	}
	return null;
}

/**
 * Retrieves all of a products prices with and without tax ( Autoship & WC ).
 * NOTE: This value can be modified via the autoship_all_prices_array filter.
 *
 * @param int $product_id. The product or variation id.
 * @return array The Autoship and WC prices
 */
function autoship_get_product_prices( $product_id ){

  // Get the Product
  $product = wc_get_product( $product_id );

  $prices = array();

  // The Products Current Price.
  $prices['price']                              = $product->get_price();

  // The Products Current Price including or excluding tax, based on the 'woocommerce_tax_display_shop' setting.
  $prices['display_price']                      = wc_get_price_to_display( $product, array( 'price' => $prices['price'] ) );

  // The Products Regular Price if/when it's not on sale.
  $prices['regular_price']                      = $product->get_regular_price();

  // The Products Regular Price if/when it's not on sale including or excluding tax, based on the 'woocommerce_tax_display_shop' setting.
  $prices['regular_display_price']              = wc_get_price_to_display( $product, array( 'price' => $prices['regular_price'] ) );

  // The Products Sale Price
  $prices['sale_price']                         = $product->get_sale_price();

  // The Products Regular Price if/when it's not on sale including or excluding tax, based on the 'woocommerce_tax_display_shop' setting.
  $prices['sale_display_price']                 = wc_get_price_to_display( $product, array( 'price' => $prices['sale_price'] ) );

  // The Custom Autoship Checkout Price
  $prices['autoship_checkout_price']            = autoship_get_product_checkout_price( $product_id );

  // The Custom Autoship Checkout Price including or excluding tax, based on the 'woocommerce_tax_display_shop' setting.
  $prices['autoship_checkout_display_price']    = wc_get_price_to_display( $product, array( 'price' => $prices['autoship_checkout_price'] ) );

  // The final Checkout Price ( either Autoship or WC )
  $prices['checkout_price']                     = autoship_checkout_price( $product, array( 'price' => $prices['price'], 'discount' => $prices['autoship_checkout_price'] )  );

  // Record if the price is WC or Autoship
  $prices['checkout_price_is_autoship']         = $prices['checkout_price'] != $prices['price'];

  // The final Checkout Price ( either Autoship or WC ) including or excluding tax, based on the 'woocommerce_tax_display_shop' setting.
  $prices['checkout_display_price']             = wc_get_price_to_display( $product, array( 'price' => $prices['checkout_price'] ) );

  // The Custom Autoship Price for Recurring Orders
  $prices['autoship_recurring_price']           = autoship_get_product_recurring_price( $product_id );

  // The Custom Autoship Price for Recurring Orders including or excluding tax, based on the 'woocommerce_tax_display_shop' setting.
  $prices['autoship_recurring_display_price']   = wc_get_price_to_display( $product, array( 'price' => $prices['autoship_recurring_price'] ) );

  // Get the calculated Percent Discount for the Checkout Price
  $prices['autoship_percent_discount']          = autoship_percent_discount( $product, array( 'price' => $prices['regular_price'], 'discount' => $prices['autoship_checkout_price'] ) );

  // Get the calculated Percent Discount for the Recurring Price
  $prices['autoship_percent_recurring_discount']= autoship_percent_recurring_discount( $product, array( 'price' => $prices['regular_price'], 'discount' => $prices['autoship_recurring_price'] ) );

  return apply_filters('autoship_all_prices_array', $prices, $product_id, $product );
}

/**
 * Retrieves the Autoship checkout price for a product or variation.
 * NOTE: This value can be modified via the autoship_checkout_price filter.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @param string $frequency_type.  Optional. The autoship frequency type ( i.e. month, day )
 * @param int    $frequency.       Optional. The autoship frequency duration
 * @return string The price
 */
function autoship_get_product_checkout_price( $product_id, $frequency_type = '', $frequency = 0 ) {
	$checkout_price = get_post_meta( $product_id, '_autoship_checkout_price', true );
	return apply_filters( 'autoship_checkout_price', $checkout_price, $product_id, $frequency_type, $frequency );
}

/**
 * Retrieves the Recurring price for a product.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @param string $frequency_type.  Optional. The autoship frequency type ( i.e. month, day )
 * @param int    $frequency.       Optional. The autoship frequency duration
 * @return string  The recurring price.
 */
function autoship_get_product_recurring_price( $product_id, $frequency_type = '', $frequency = 0 ) {

	$recurring_price = get_post_meta( $product_id, '_autoship_recurring_price', true );

  // HACK: Check for empty string specifically and return null if empty else value.
  $recurring_price = '' == $recurring_price ? NULL : $recurring_price;

	return apply_filters( 'autoship_recurring_price', $recurring_price, $product_id, $frequency_type, $frequency );

}

/**
 * Retrieves the Autoship Product Title Override for a product or variation.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @return string The custom title
 */
function autoship_get_product_title_override( $product_id ) {
	return get_post_meta( $product_id, '_autoship_title_override', true );
}

/**
 * Retrieves the Autoship Product Type Override for a product or variation.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @return string The custom title
 */
function autoship_get_product_type_override( $product_id ) {
	return  get_post_meta( $product_id, '_autoship_type_override', true );
}

/**
 * Retrieves the Autoship Product Unit of Weight Override for a product or variation.
 *
 * @param int    $product_id.      The WC Product or variation id.
 *
 * @return string The custom weight
 */
function autoship_get_product_weightunit_override( $product_id ) {
	return get_post_meta( $product_id, '_autoship_weightunit_override', true );
}

/**
 * Retrieves the Autoship Product Unit of Weight Override for a product or variation.
 *
 * @param int    $product_id.      The WC Product or variation id.
 *
 * @return string The custom weight
 */
function autoship_get_product_lengthunit_override( $product_id ) {
	return get_post_meta( $product_id, '_autoship_lengthunit_override', true );
}




/**
 * Gets the Enable Schedule Options option for Variable and Simple Products.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @return string The autoship enabled value
 */
function autoship_get_product_autoship_enabled ( $product_id ) {
  $option = get_post_meta( $product_id, '_autoship_schedule_options_enabled', true );
	return $option;
}

/**
 * Gets the Disable Schedule Options option for a Variation.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @return string The autoship enabled value
 */
function autoship_get_product_variation_autoship_enabled ( $product_id ) {
  $option = get_post_meta( $product_id, '_autoship_dissable_schedule_order_options', true );
	return $option;
}

/**
 * Ajax Function for retrieving the autoship data for a product or variation
 * Uses {@see autoship_product_discount_data} for data compilation.
 *
 * @return array The product pricing and label info.
 */
function autoship_ajax_product_discount() {
	$product_id = $_POST['product_id'];
	$product = wc_get_product( $product_id );

	if ( empty( $product ) || !$product ) {
		autoship_ajax_result( 404 );
		die();
	}

  $data = autoship_product_discount_data( $product );

	autoship_ajax_result( 200, $data );
	die();
}

/**
 * Retrieves Autoship Checkout Percent Discount
 *
 * @param WC_Product|int $product WC_Product, WC_Product_Variation or Id
 * @param array $prices Optional. The Current Checkout Price and Autoship Checkout Price.
 * @return float  The percent discount at checkout.
 */
function autoship_percent_discount( $product, $prices = array() ) {

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  $prices = wp_parse_args(
    $prices,
    array(
      'price'    => !isset( $prices['price'] ) ? $product->get_regular_price() : $prices['price'],
      'discount' => !isset( $prices['discount'] ) ? autoship_get_product_checkout_price( $product->get_id() ) : $prices['discount']
    )
  );

  if ( empty( $prices['discount'] ) )
	return 0;

	$percent_discount = round( 100 * (1 - $prices['discount'] / $prices['price'] ) );

  return apply_filters( 'autoship_checkout_percent_discount', $percent_discount, $product, $prices );
}

/**
 * Retrieves Checkout Price - Either Autoship or Product price
 *
 * @param WC_Product|int $product WC_Product, WC_Product_Variation or Id
 * @param array $prices Optional. The Current Checkout Price and Autoship Checkout Price.
 * @return float The checkout price.
 */
function autoship_checkout_price( $product, $prices = array() ) {

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  $prices = wp_parse_args(
    $prices,
    array(
      'price'    => !isset( $prices['price'] ) ? $product->get_price() : $prices['price'],
      'discount' => !isset( $prices['discount'] ) ? autoship_get_product_checkout_price( $product->get_id() ) : $prices['discount']
    )
  );

  $autoship_price = empty( $prices['discount'] ) ?
	$prices['price'] : $prices['discount'];

  return apply_filters( 'autoship_discounted_price', $prices['discount'], $product, $prices );
}

/**
 * Gets formatted price for display.
 *
 * @uses wc_price(), wc_format_sale_price()
 *
 * @param float  $price The Price being formatted.
 * @param  array $args  Arguments to format a price {
 *     Array of arguments.
 *     Defaults to empty array.
 *
 *     @type float $original            Original Price to compare to.
 *                                      Defaults to NULL
 *     @type string $suffix             Suffix to append to Price.
 *                                      Defaults to empty string.
 *
 *     @type bool   $ex_tax_label       Adds exclude tax label.
 *                                      Defaults to false.
 *     @type string $currency           Currency code.
 *                                      Defaults to empty string (Use the result from get_woocommerce_currency()).
 *     @type string $decimal_separator  Decimal separator.
 *                                      Defaults the result of wc_get_price_decimal_separator().
 *     @type string $thousand_separator Thousand separator.
 *                                      Defaults the result of wc_get_price_thousand_separator().
 *     @type string $decimals           Number of decimals.
 *                                      Defaults the result of wc_get_price_decimals().
 *     @type string $price_format       Price format depending on the currency position.
 *                                      Defaults the result of get_woocommerce_price_format().
 * }
 * }
 *
 * @return string
 */
function autoship_get_formatted_price( $price, $args = array() ){

  // Parse the args.
  $args = wp_parse_args( $args, array( 'original' => NULL, 'suffix'   => '' ) );

  // Run the display price through the WC Sale Price format and add the suffix if needed
  $formatted_price  = !empty( $args['original'] ) && ( $args['original'] != $price )?
  wc_format_sale_price( $args['original'], $price ) . $args['suffix'] :
  wc_price( $price, $args ) . $args['suffix'];

  return apply_filters( 'autoship_get_formatted_price', $formatted_price, $price, $args );

}

/**
 * Retrieves Autoship Recurring Percent Discount
 *
 * @param WC_Product|int $product WC_Product, WC_Product_Variation or Id
 * @param array $prices Optional. The Current Checkout Price and Autoship Checkout Price.
 * @return float  The percent Recurring discount.
 */
function autoship_percent_recurring_discount( $product, $prices = array() ) {

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  $prices = wp_parse_args(
    $prices,
    array(
      'price'    => !isset( $prices['price'] ) ? $product->get_regular_price() : $prices['price'],
      'discount' => !isset( $prices['discount'] ) ? autoship_get_product_recurring_price( $product->get_id() ) : $prices['discount']
    )
  );

  if ( empty( $prices['discount'] ) )
	return 0;

	$percent_discount = round( 100 * (1 - $prices['discount'] / $prices['price'] ) );

  return apply_filters( 'autoship_recurring_percent_discount', $percent_discount, $product, $prices );
}

/**
 * Retrieves the values for all autoship fields for the supplied variation id
 * @param int           WC_Product_Variation id to retrieve the values for.
 * @param string|array  Optional.  A specific field or fields to retrieve.
 *                      Defaults to all fields.  Takes a comma separated string or array.
 *
 * @return array        An array of fields and corresponding values.
 */
function autoship_get_variable_product_custom_field_values( $variation_id, $fields = array() ){

  // Get the variation object so we can grab the parent id later.
  $variation = wc_get_product($variation_id);

  // If a field or fields are supplied only retrieve those.
  if ( !empty( $fields ) ){
    $autoship_field_names = is_array( $fields ) ? $fields : explode( ',' , $fields );
  } else {
    $autoship_field_names = autoship_get_custom_metafield_names();
  }
  $variation_data = array();
  foreach ( $autoship_field_names as $name => $settings ) {
    // If value inherited get from parent else grab it's value from variation.
    $variation_data[$name] = $settings['inherit'] ?
    get_post_meta( $variation->get_parent_id(), $name, true) : get_post_meta( $variation_id, $name, true);
  }

  return $variation_data;

}

/**
 * Returns a list of Autoship custom field for products and variations.
 * NOTE  Values can be modified by developers using the autoship_custom_core_metafields
 *       and autoship_custom_frequency_override_metafields filters.
 * @param string $set. Optional. The set of fields to return.
 *                     'core' returns the Autoship core set of custom fields only.
 *                     'frequency' returns the Autoship frequency override set of custom fields only.
 *                     When no $set value is supplied or an invalid value all fields are returned.
 *                     Default empty string.
 * @return array       of field names, their default values,
 *                     and if they should inherit the value from parent when
 *                     they don't exist.
 */
function autoship_get_custom_metafield_names( $set = '' ){

  // Default value is used when metadata field is first added to a product or variation
  // The inherit setting is used only with variations and overrides the default setting. If set to true
  // the value will be inherited from the parent.
  $core_fields = apply_filters( 'autoship_custom_core_metafields' , array(
    '_autoship_checkout_price'                  => array( 'default' => '', 'inherit' => false ),
    '_autoship_recurring_price'                 => array( 'default' => '', 'inherit' => false ),
    '_autoship_sync_active_enabled'             => array( 'default' => '', 'inherit' => true ),
    '_autoship_schedule_order_enabled'          => array( 'default' => '', 'inherit' => false ),
    '_autoship_schedule_process_enabled'        => array( 'default' => '', 'inherit' => false ),
    '_autoship_group_ids'                       => array( 'default' => '', 'inherit' => false ),
    '_autoship_override_product_data'           => array( 'default' => '', 'inherit' => false ),
    '_autoship_title_override'                  => array( 'default' => '', 'inherit' => false ),
    '_autoship_weightunit_override'             => array( 'default' => '', 'inherit' => false ),
    '_autoship_lengthunit_override'             => array( 'default' => '', 'inherit' => false ),
    '_autoship_schedule_options_enabled'        => array( 'default' => '', 'inherit' => true ),
    '_autoship_override_frequency_options'      => array( 'default' => '', 'inherit' => false ),
    '_autoship_dissable_schedule_order_options' => array( 'default' => '', 'inherit' => false ),
    '_autoship_relative_next_occurrence_enabled'=> array( 'default' => '', 'inherit' => false ),
    '_autoship_relative_next_occurrence_type'   => array( 'default' => '', 'inherit' => false ),
    '_autoship_relative_next_occurrence'        => array( 'default' => '', 'inherit' => false )
  ) );

  $frequency_overrides = array();
  $autoship_frequency_options = autoship_get_frequency_options_count();
  for ( $i = 0; $i < $autoship_frequency_options; $i++ ) {

    $frequency_overrides["_autoship_frequency_type_{$i}"]         = array( 'default' => '', 'inherit' => false );
    $frequency_overrides["_autoship_frequency_{$i}"]              = array( 'default' => '', 'inherit' => false );
    $frequency_overrides["_autoship_frequency_display_name_{$i}"] = array( 'default' => '', 'inherit' => false );

  }
  $frequency_overrides = apply_filters( 'autoship_custom_frequency_override_metafields' , $frequency_overrides );


  if ( 'core' == $set ){

    return $core_fields;

  } elseif ( 'frequency' == $set ){

    return $frequency_overrides;

  } else {

    return $core_fields + $frequency_overrides;

  }

}

/**
 * Gets the Pricing and Discount data for a products variations.
 * @param WC_Product|int $product WC_Product or Product Id
 * @return array         The product pricing and label info.
 */
function autoship_product_variations_discount_data( $product ){

  if ( is_numeric( $product ) )
  $product = wc_get_product( $product );

  if ( !$product )
  return array();

  return autoship_get_all_variation_cart_options( $product );

}

/**
 * Gets the Pricing and Discount data for a product or variation.
 * @param WC_Product|int $product WC_Product or Product Id
 * @param array $prices  Optional. The Base Prices to use in the calcs.
 * @return array         The product pricing and label info.
 */
function autoship_product_discount_data( $product, $prices = array(), $is_cart = false ){

  if ( is_numeric( $product ) )
  $product = wc_get_product( $product );

  if ( !$product )
  return array();

  // Retrieve all the base autoship * non-autoship prices if not supplied.
  if ( empty( $prices ) )
  $prices                      = autoship_get_product_prices( $product->get_id() );

  // Get the Checkout Discount Percentage if there is one.
	$percent_discount            = $prices['autoship_percent_discount'];

  // Get/Set the Recurring Discount Percentage if there is one.
	$recurring_percent_discount  = $prices['autoship_percent_recurring_discount'];

  // Get/Set the checkout price ( either Autoship or WC )
	$discounted_price            = $prices['checkout_price'];

  // Get/Set the checkout display price ( either Autoship or WC )
	$discounted_display_price    = $prices['checkout_display_price'];

  // Check if show variation price is enabled or not.
  $display_price_target_type = $product->get_type();

  // Check if this is the cart page or non-cart
  $cart_variation = $is_cart ? false : 'variation' == $display_price_target_type;

  // Get the Discount String
	$custom_percent_discount_str = autoship_checkout_recurring_discount_string( $product->get_id(), $cart_variation, $prices );
	$frequency_options           = autoship_product_frequency_options( $product->get_id() );

  // Depending on if this is a variation or a simple product.
  // Get the next few settings from the parent
  if ( 'variation' == $display_price_target_type ){

    $parent_product = wc_get_product( $product->get_parent_id() );

    $schedule_options_enabled        = autoship_schedule_options_enabled( $parent_product );
    $dissable_schedule_order_options = autoship_disable_schedule_order_options( $product );

  // Simple product
  } else {

    $schedule_options_enabled        = autoship_schedule_options_enabled( $product );
    $dissable_schedule_order_options = 'yes' == $schedule_options_enabled ? 'no' : 'yes';

  }

	$recurring_price                 = $prices['autoship_recurring_price'];
  $schedule_order_enable           = autoship_schedule_order_enabled( $product );
  $schedule_process_enabled        = autoship_schedule_process_enabled( $product );

  // Run the display price through the WC Sale Price format and add the suffix if needed
  $display_price  = autoship_get_formatted_price( $discounted_display_price, array(
    'original'  => $prices['checkout_price_is_autoship'] ? $prices['price'] : NULL,
    'suffix'    => $product->get_price_suffix(),
  ) );

  // Get the Price HTML to show in place of the current price.
  $display_price = apply_filters( "autoship_{$display_price_target_type}_product_discount_price_html" , $display_price, $discounted_display_price, $product );

  // Get the Price HTML to show in place of the current price.
  $selector = 'simple' == $display_price_target_type ? 'p.price' : '.woocommerce-variation-price .price';
  $display_price_target = apply_filters( "autoship_{$display_price_target_type}_product_discount_price_html_selector" , $selector, $product );

  // filter to disable dynamically displaying the discount price
  $display_price_enable = 0 !== $percent_discount;
  $display_price_enable = apply_filters( "autoship_{$display_price_target_type}_product_discount_price_html_enabled" , $display_price_enable, $product );

  // Filter the default autoship option
  $default_autoship_option = apply_filters( 'autoship_default_product_schedule_options_choice_value' , 'no', $product );

	$data = array(

    // The Product ID
		'product_id'                      => $product->get_id(),

    // The Percent Discount for Checkout
		'percent_discount'                => $percent_discount,

    // The Percent HTML Discount for Checkout
		'percent_discount_html'           => $percent_discount . '%',

    // The Autoship Checkout Price
		'discounted_price'                => $discounted_price,

    // The Autoship Checkout Price HTML
		'discounted_price_html'           => wc_price( $discounted_display_price ),

    // The Frequency Options for the product
		'frequency_options'               => $frequency_options,

    // The Autoship Option discount String
		'custom_percent_discount_str'     => $custom_percent_discount_str,

    // Checkout Price and Selectors
    'discount_display_price'          => $display_price,

    // The HTML Element that Contains the Price
    'discount_display_price_selector' => $display_price_target,

    // Is it a Simple or Variation Price Selector
    'discount_display_price_type'     => $display_price_target_type,

    // Should the Custom Autoship Price be displayed.
    'discount_display_price_enable'   => $display_price_enable,

    // Fallback Autoship Display Selector
    'original_display_price_selector' => '.autoship-price-display',

    // The Custom Price for Recurring Orders
    'recurring_price'                 => $recurring_price,

    // The Percent Discount for Recurring Orders
    'recurring_percent_discount'      => $recurring_percent_discount,

    // Toggles the Discount String on the Product Pages
    'show_discount_str'               => $percent_discount || $recurring_percent_discount,

    // If Add to Scheduled Order is endabled for the product
    'schedule_order_enable'           => $schedule_order_enable,

    // If Process on Scheduled Order is endabled for the product
    'schedule_process_enabled'        => $schedule_process_enabled,

    // If Show Autoship Scheduled Options is enabled for the Product
    'schedule_options_enabled'        => $schedule_options_enabled,

    // If Disable Autoship Scheduled Options is checked for the Variation
    'dissable_schedule_order_options' => $dissable_schedule_order_options,

    // If Autoship Scheduled Option should be defaulted to yes
    'default_autoship_option'         => $default_autoship_option,

    // Attach all the base prices
    'prices'                          => $prices,

	);

  return apply_filters( 'autoship_product_discount_data', $data, $product );

}

/**
 * Gets the value for the Global 'autoship_sync_all_products_enabled' field
 * for the supplied product.
 *
 * @return bool True if enabled else False
 */
function autoship_global_sync_active_enabled() {
  // Delete the cache for this option first.
  wp_cache_delete( '_autoship_sync_all_products_enabled', 'options' );
  return ( 'yes' == get_option('_autoship_sync_all_products_enabled') );
}

/**
 * Gets the value for the autoship_sync_active_enabled field
 * for the supplied product.
 *
 * NOTE Since the Active flag is always at the Parent level if the supplied
 * product id belongs to a variation get the associated Variable product's flag.
 *
 * @param WC_Product|int $product WC_Product or Product Id
 * @return string    no or yes value.
 */
function autoship_sync_active_enabled( $product ) {
  if ( apply_filters('autoship_sync_active_enabled', autoship_global_sync_active_enabled(), $product ) )
  return 'yes';

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  if ( !$product )
  return 'no';

  // Get the parents id for variations.
  $id = 'variation' == $product->get_type() ? $product->get_parent_id() : $product->get_id();

	$val = get_post_meta( $id, '_autoship_sync_active_enabled', true );

	return empty( $val ) ? 'no' : $val;
}

/**
 * Gets the value for the autoship_schedule_order_enabled field
 * for the supplied product.
 *
 * @param WC_Product|int $product WC_Product or Product Id
 * @return string    no or yes value.
 */
function autoship_schedule_order_enabled( $product ) {

  $id = is_numeric( $product ) ? $product : $product->get_id();

  $val = apply_filters( 'autoship_override_schedule_order_enabled_default',
  get_post_meta( $id, '_autoship_schedule_order_enabled', true ), $id );

	return empty( $val ) ? 'no' : $val;
}

/**
 * Gets the value for the autoship_schedule_process_enabled field
 * for the supplied product.
 *
 * @param WC_Product|int $product WC_Product or Product Id
 * @return string    no or yes value.
 */
function autoship_schedule_process_enabled( $product ) {

  $id = is_numeric( $product ) ? $product : $product->get_id();

  $val = apply_filters( 'autoship_override_schedule_process_enabled_default',
  get_post_meta( $id, '_autoship_schedule_process_enabled', true ), $id );

	return empty( $val ) ? 'no' : $val;

}

/**
 * Gets the value for the autoship_schedule_options_enabled field
 * for the supplied product.
 *
 * filtered via @see autoship_override_schedule_options_enabled_default
 *
 * @param WC_Product|int $product WC_Product or Product Id
 * @return string no or yes value.
 */
function autoship_schedule_options_enabled( $product ) {

  $id = is_numeric( $product ) ? $product : $product->get_id();

	$val = apply_filters( 'autoship_override_schedule_options_enabled_default',
  get_post_meta( $id, '_autoship_schedule_options_enabled', true ), $id );

	return empty( $val ) ? 'no' : $val;

}

/**
 * Gets the value for the _autoship_override_frequency_options field
 * for the supplied product.
 *
 * @param WC_Product|int $product WC_Product or Product Id
 * @return string no or yes value.
 */
function autoship_override_frequency_options_enabled( $product ) {

  $id = is_numeric( $product ) ? $product : $product->get_id();

  // Show or hide Frequency Overrides based on checkbox value.
  $val = apply_filters( 'autoship_override_simple_frequency_options_default',
  get_post_meta( $id, '_autoship_override_frequency_options', true ), $id );

	return empty( $val ) ? 'no' : $val;

}

/**
 * Gets the assigned group ids.
 *
 * @param WC_Product|int $product WC_Product or Product Id
 * @param bool $raw True return the raw value else convert to array
 * @return array|string  An array of ids or the raw string
 */
function autoship_get_assigned_group_ids( $product, $raw = false ) {

  $id = is_numeric( $product ) ? $product : $product->get_id();

  $val = get_post_meta( $id, '_autoship_group_ids', true );

  if ( empty( $val ) )
  return "";

  return $raw ? $val :  array_map('intval', explode(',', $val ) );

}

/**
 * Gets the value for the _autoship_override_product_data field
 * for the supplied product.
 *
 * @param WC_Product|int $product WC_Product or Product Id
 * @return string no or yes value.
 */
function autoship_override_product_data_enabled( $product ) {

  $id = is_numeric( $product ) ? $product : $product->get_id();

  // Show or hide Frequency Overrides based on checkbox value.
  $val = get_post_meta( $id, '_autoship_override_product_data', true );

	return empty( $val ) ? 'no' : $val;

}

/**
 * Gets the value for the _autoship_dissable_schedule_order_options field
 * for the supplied product.
 *
 * @param WC_Product|int $product WC_Product or Product Id
 * @return string no or yes value.
 */
function autoship_disable_schedule_order_options( $product ) {

  $id = is_numeric( $product ) ? $product : $product->get_id();

  // Show or hide Frequency Overrides based on checkbox value.
  $val = apply_filters( 'autoship_override_dissable_schedule_order_options_default',
  get_post_meta( $id, '_autoship_dissable_schedule_order_options', true ), $id );

	return empty( $val ) ? 'no' : $val;

}

/**
 * Checks if the Product should be available on the front for
 * Scheduled Orders via checkout
 * @param WC_Product|int $product WC_Product or Product Id
 * @return bool True is yes else false.
 */
function autoship_is_visible_active_shop_product( $product ){

  if ( is_numeric( $product ) ){
    $product = wc_get_product( $product );
  }

  // Only Active and Enabled Show.
	return apply_filters( 'autoship_is_visible_active_shop_product',
  'yes' == autoship_schedule_options_enabled( $product ) &&
  'yes' == autoship_sync_active_enabled( $product ), $product );

}

/**
 * Retrieves the autoship product message
 *
 * @param int    $product_id.      The WC Product or variation id.
 */
function autoship_product_message_string( $product_id ){

  return apply_filters(
  'autoship_product_message_string', ' ' .
  get_option( 'autoship_product_message' ),
  $product_id );

}

/**
 * Gets the value for the autoship_relative_next_occurrence_enabled field
 * for the supplied product.
 *
 * @param WC_Product|int $product WC_Product or Product Id
 * @return string no or yes value.
 */
function autoship_relative_next_occurrence_enabled( $product ) {

  $id = is_numeric( $product ) ? $product : $product->get_id();

	$val = get_post_meta( $id, '_autoship_relative_next_occurrence_enabled', true );

	return empty( $val ) ? 'no' : $val;

}

/**
 * Gets the Autoship Relative Next Occurrence Date for a product.
 *
 * @param int $product_id. The WC Product or variation id.
 * @param string $frequency_type Optional. The Scheduled Frequency Type. Default Empty String
 * @param int $frequency Optional. The Scheduled Frequency. Default 0
 *
 * @return string|NULL The next occurrence string or NULL if doesn't exist.
 */
function autoship_get_product_default_relative_next_occurrence( $product_id, $frequency_type = '', $frequency = 0 ){

  $date = NULL;
  if ( 'yes' === autoship_relative_next_occurrence_enabled( $product_id ) ){

    // Get the Type and Value from the Parent Product
    $relative_nod      = autoship_get_product_relative_next_occurrence( $product_id );
    $relative_nod_type = autoship_get_product_relative_next_occurrence_type( $product_id );

    // Calculate the Relative Next Occurrence based on current date
    $date = autoship_calculate_relative_next_occurrence_date ( $relative_nod, $relative_nod_type,
    apply_filters( 'autoship_calculate_relative_next_occurrence_date_from_basedate', new DateTime() , $relative_nod, $relative_nod_type, $product_id, $frequency_type, $frequency ) );

  }

  return apply_filters( 'autoship_calculate_relative_next_occurrence_date', $date, $product_id, $frequency_type, $frequency );

}

/**
  * Retrieves the new Next Occurrence value
  * @param int $product_id The WC Product ID
  * @return int The autoship relative next occurrence value
  */
function autoship_get_product_relative_next_occurrence( $product_id ){
  return get_post_meta( $product_id, '_autoship_relative_next_occurrence', true );
}
/**
  * Retrieves the new Next Occurrence type
  * @param int $product_id The WC Product ID
  * @return string The autoship relative next occurrence type
  */
function autoship_get_product_relative_next_occurrence_type( $product_id ){
  return get_post_meta( $product_id, '_autoship_relative_next_occurrence_type', true );
}

/**
 * Queries variation products that have sync and add to SO enabled
 * @param array $params Additional Query Params to narrow down search.
 * @return array variation products.
 */
function autoship_get_available_schedulable_variations ( $args = array() ){

  $products_args = apply_filters('autoship_filter_schedulable_variation_products_query_args', array(
    'status' => 'publish',
    'limit' => '-1',
    'type' => [
      'variable',
    ]
  ) );

  $args = wp_parse_args( $args, $products_args );

  $products = wc_get_products( $args );
  $all_products = [];
  if ( ! empty( $products ) ) {
    foreach ( $products as $product ) {
      if ( $product->is_type( 'variable' ) ) {
        $variation_ids = $product->get_children();
        foreach ( $variation_ids as $variation_id ) {
          if ( autoship_is_available_variation( $variation_id ) ){ 
            $variation = wc_get_product( $variation_id );
            $autoship_schedule_order   = ( 'yes' == get_post_meta( $variation_id, '_autoship_schedule_order_enabled', true ) );
            $active = ( 'yes' == autoship_sync_active_enabled( $variation_id ) );

            if( !$autoship_schedule_order || !$active || !$variation->is_in_stock() ) {
              continue;
            }

            $all_products[] = $variation;
          }
        }
      }
    }
  }
  return $all_products;
}


// ==========================================================
// API Get & Utility Functions
// ==========================================================

/**
 * Retrieves the available products from QPilot
 *
 * @param array $params {
 *     Optional. An array of search parameters.
 *
 *     @type int     $page                 The search results page to return. Default 1
 *     @type int     $pageSize             The default page size.  Default 100
 *     @type string  $orderBy              A product property to sort the results by
 *     @type string  $order                The Sort Direction the results should be returned ( DESC vs ASC )
 *     @type string  $availability         The Stock Status ( Undefined, InStock, OutOfStock, PreOrder )
 *     @type float   $minPrice	            Minimum Price Range
 *     @type float   $maxPrice	            Maximum Price Range
 *     @type bool    $addToScheduledOrder	The Add to Scheduled Order Setting ( true for enabled )
 *     @type bool    $processScheduledOrderThe Process Scheduled Order Setting ( true for enabled )
 *     @type string  $search               A query string to search for.
 *     @type int     $groupId              A group id to search for.
 *     @type array   $productIds           An array of product ids to search for.
 *     @type array   $metadataKey          An array of metadata keys to search for.
 *     @type array   $metadataValue        An array of metadata values to search for.
 *     @type bool    $active               True for Active products
 *     @type bool    $valid                True for valid products
 * }
 * @return array An array of stdClass Product Objects.
 */
function autoship_search_available_products( $params = array(), $index = 1 ){

  $params = wp_parse_args( $params, array( 'pageSize' => 100, 'page' => $index ) );

  // Create QPilot client instance.
	$client = autoship_get_default_client();

	try {

    // Retrieve the page of products from QPilot.
		$products = $client->get_products( $params );

  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );
    $notice = new WP_Error( 'Product Search Failed', __( $notice['desc'], "autoship" ) );
    autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'Searching available products failed. Additional Details: Error Code %s - %s', $e->getCode(), $e->getMessage() ) );

  }

  if ( is_wp_error( $products ) )
  return $products;

  if ( $products->totalPages > $index ){

    $index++;
    $params['page'] = $index;
    $new_products = autoship_search_available_products( $params, $index );
    return !is_wp_error( $new_products ) ? array_merge( $products->items, $new_products ) : $new_products;

  }

  return $products->items;

}

/**
  * Retrieves a set of Products from QPilot by ID
  *
  * @param array $product_ids       An array of WC Product IDs.
  * @param int   $batch_size        Optional. The number of products to retrieve each call.
  *                                 NOTE: This is important due to the call is a GET request
  *                                 And there is a limit to URL length.
  * @param array $args              Optional. An array of optional search criteria.
  * @return array|WP_Error          Array of Scheduled order objects
  *                                 WP_Error on failure.
  */
function autoship_get_products_by_ids( $product_ids, $batch_size = NULL, $args = array() ){

  // Since batch size is dependent on id length allow to be filtered.
  $batch_size =  !isset( $batch_size ) || !$batch_size ?
  apply_filters( 'autoship_get_products_by_ids_default_batch_size', 5 ) : $batch_size;

  if ( count( $product_ids ) > $batch_size ){

    $all_products = array();

    // Group the ids into batches.
    $batches = array_chunk( $product_ids , $batch_size );

    // Recursively retrieve all the products
    foreach ( $batches as $batch ){

      $products = autoship_get_products_by_ids( $batch, count( $batch ), $args );

      // Bail on Errors
      if ( is_wp_error( $products ) )
      return $products;

      $all_products = array_merge( $all_products, $products );

    }

    return $all_products;

  } else {

    // Create QPilot client instance.
  	$client = autoship_get_default_client();

  	try {

      // Create the order in QPilot.
  		$products = $client->get_products( $args + array( 'productIds' => $product_ids ) );

    } catch ( Exception $e ) {

      $notice = autoship_expand_http_code( $e->getCode() );
      $notice = new WP_Error( 'Product Search by ID Failed', __( $notice['desc'], "autoship" ) );
      autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'Searching products by ID failed. Additional Details: Error Code %s - %s', $e->getCode(), $e->getMessage() ) );
      return $notice;

    }

    return $products->items;

  }

}

/**
 * Retrieves a product from QPilot
 *
 * @param int    $product_id The WC Product ID.
 * @return stdClass The QPilot Product Objects.
 */
function autoship_get_available_product( $product_id ){

  // Create QPilot client instance.
	$client = autoship_get_default_client();

	try {

    // Get The Product from QPilot.
		$product = $client->get_product( $product_id );

  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );
    $notice = new WP_Error( 'Product Retrieval Failed', __( $notice['desc'], "autoship" ) );
    autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'Retrieving the product failed. Additional Details: Error Code %s - %s', $e->getCode(), $e->getMessage() ) );
    return $notice;

  }

  return $product;

}

/**
 * Retrieves a product(s) from QPilot
 *
 * @param array|int $product_ids     An wc product id or array of ids.
 * @return stdClass|array|WP_Error   A Product object or array of Products.
 *                                   WP_Error on failure.
 */
function autoship_get_remote_products( $product_ids = array() ){

  $search_params = apply_filters( 'autoship_get_remote_products_search_params', array( 'availability' => 1, 'active' => 'true' ) );

  // Check if this is a specific order to retrieve or
  // all orders for a customer.
  if ( empty( $product_ids ) ){

    $all_products = autoship_search_available_products( $search_params );

  } else if ( is_array( $product_ids ) ){

    $all_products = autoship_get_products_by_ids( $product_ids, NULL, $search_params );

  } else {

    $all_products = autoship_get_available_product( $product_ids );

  }

  return $all_products;

}

/**
 * Retrieves a product from QPilot
 *
 * @param int $product_id            An wc product id.
 * @return stdClass|WP_Error   A Product object.
 *                                   WP_Error on failure.
 */
function autoship_get_remote_product( $product_id ){

  $client = autoship_get_default_client();

  try {

    $product = $client->$client->get_product( $product_id );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'The supplied product can not be found in QPilot for Site ID %d. Additional Details: %s', $client->get_site_id(), $e->getMessage() ) );
      return new WP_Error( 'Product Not Found', sprintf( __( "The supplied product could not be found in your connected QPilot Site ( Site ID: %d )", "autoship" ), $client->get_site_id() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );
  		autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'Product Retrieval Failed. Additional Details: %s', $e->getMessage() ) );
      return new WP_Error( 'Product Retrieval Failed', __( $notice['desc'], "autoship" ) );
    }

  }

  return $product;

}


// ==========================================================
// Core API Data Setup Functions
// ==========================================================

/**
 * Retrieve the WC Product Object & Type
 *
 * Currently if a product does not have a status of publish and / or
 * is not of type 'simple', 'variable', 'variation' it's
 * not ready for QPilot & false is returned.
 * @see autoship_is_valid_sync_product()
 *
 * @param mixed $product WC Product ID or the WC_Product object.
 * @return object|bool The WC Product object or
 *                     false if product doesn't exist or wrong type.
 */
function autoship_productize_and_validate( $product ){

  // Get the WC Product
  if ( is_numeric( $product ) )
  $product = wc_get_product( $product );

  if ( !$product )
  return false;

  $valid_variation = 'variation' === $product->get_type() ?
  autoship_is_available_variation( $product ) : true;

  if ( apply_filters( 'autoship_productize_and_validate', empty( $product ) || !autoship_is_valid_sync_post_type( $product ) || !autoship_is_valid_sync_status_product( $product ) || !$valid_variation, $product ) )
  return false;

  // Check whether this is an allowed product type & status
  return $product;

}

/**
 * Initialize Autoship fields for a Variation
 *
 * Checks if each Autoship field exists for the supplied variation.
 * if not if pulls the value from the parent and adds to variation.
 * @see metadata_exists
 *
 * @param mixed $the_variation Post object or post ID of the product.
 * @return object Post object
 */
function autoship_init_variation_metafields( $the_variation ){

  // Get the variation object
  if ( false === ( $variation = autoship_productize_and_validate( $the_variation ) ) )
  return;

  // Get an array of current autoship metadata field names
  $autoship_fields = autoship_get_custom_metafield_names();

  // Get the Parent Product
  $parent = $variation->get_parent_id();

  // Gather the parents current settings as defaults
  // for the variations and
  // Gather the variations settings
  // If the setting doesn't exist add it and use parents or default.
  $parent_data = array();
  $variation_data = array();
  foreach ( $autoship_fields as $field => $settings ) {

    // Get the Parents Val
    $parent_data[$field] = get_post_meta( $parent, $field, true);

    // Check if the metadata field actually exists
    // Can't trust get_post_meta since non-existent and empty are same.
    if ( !metadata_exists('post', $variation->get_id(), $field ) ){

      // Since the field doesn't exist update with parents or use default value.
      $variation_data[$field] = $settings['inherit'] ? $parent_data[$field] : $settings['default'];
      update_post_meta( $variation->get_id() , $field, $variation_data[$field] );
    } else {

      // It exists so grab it's value.
      $variation_data[$field] = get_post_meta( $variation->get_id(), $field, true);
    }

  }

  return wc_get_product( $variation );

}

/**
 * Gathers the Product Data to send to QPilot.
 *
 * @param int|WC_Product $product WC Product Object or Product ID.
 * @param array $product_data Optional Overrides
 * @return array The mapped Upsert Data
 */
function autoship_generate_product_upsert_data ( $product, $product_data = array() ){

  $client = autoship_get_default_client();

  // Get the product ( if invalid or wrong type returns false )
  if ( ( $wc_product = autoship_productize_and_validate( $product ) ) === false )
  return array();

  $id   = $wc_product->get_id();
  $type = $wc_product->get_type();

  // Get the Overrides in case there are some
  // If no overrides the standard data is returned.
  $overrides = autoship_get_product_custom_data_values( $id );

  $autoship_schedule_order   = ( 'yes' == get_post_meta($id, '_autoship_schedule_order_enabled', true ) );
  $autoship_schedule_process = ( 'yes' == get_post_meta($id, '_autoship_schedule_process_enabled', true ) );
  $active = ( 'yes' == autoship_sync_active_enabled( $id ) ) ? 'true' : 'false';

  // Get the autoship recurring price
  $autoship_recurring_price = get_post_meta( $id, '_autoship_recurring_price', true );

  // Set the autoship recurring price as the sale price if it exists
  $sale_price = ! empty( $autoship_recurring_price ) ? $autoship_recurring_price : null;

  // Grab initial Values from Product & Client
  $siteid         = $client->get_site_id();
  $price          = $wc_product->get_regular_price();
  $stock          = $wc_product->get_stock_quantity();
  $stocklevel     = autoship_get_mapped_stocklevel( $wc_product->get_stock_status() );
  $title          = $overrides['title'];
  $sku            = $wc_product->get_sku();
  $length         = $wc_product->get_length();
  $width          = $wc_product->get_width();
  $height         = $wc_product->get_height();
  $weight         = $wc_product->get_weight();
  $shippingclass  = $wc_product->get_shipping_class();
  $taxclass       = $wc_product->get_tax_class();
  $imageurl       = autoship_get_wc_product_image_url( $id );
  $thumburl       = autoship_get_wc_product_image_url( $id );
  $url            = $wc_product->get_permalink();
  $lengthunit     = apply_filters( 'autoship_get_mapped_product_length_unit', $overrides['lengthunit'], $wc_product );
  $weightunit     = apply_filters( 'autoship_get_mapped_product_weight_unit', $overrides['weightunit'], $wc_product );
  $frequencies    = autoship_get_product_custom_frequency_options( $wc_product );
  $groupIds       = autoship_get_assigned_group_ids( $wc_product );

  // Gather the metadata for the product.
  $metadata       = array(
    'type'       => substr( $type, 0, 20 ),
    'url'        => empty($url)                ? null : substr( $url, 0, 2000 ),
    'isFeatured' => $wc_product->is_featured() ? true : false
  );

  if ( !empty( $imageurl ) )
  $metadata['imageUrl'] = substr( $imageurl, 0, 2000 );

  if ( !empty( $thumburl ) )
  $metadata['imageThumbUrl'] = substr( $thumburl, 0, 2000 );

  // Since metadata can be used for so many customizations include a metadata specific filter.
  $metadata = apply_filters( 'autoship_product_upsert_metadata', $metadata, $wc_product );

  // Setup the array
  $product_data = array_merge( array(
    "id"                    => $id,
    "parentProductId"       => 'variation' == $type       ? $wc_product->get_parent_id() : null,
    "title"                 => empty( $title )            ? null : substr( $title, 0, 200 ),
    "price"                 => empty( $price)             ? 0    : floatval( $price ),
    "sku"                   => empty($sku)                ? null : substr( $sku, 0, 50 ),
    "salePrice"             => empty( $sale_price )       ? null : floatval( $sale_price ),
    "stock"                 => $stock,
    "length"                => empty($length)             ? null : substr( $length, 0, 10 ),
    "width"                 => empty($width)              ? null : substr( $width, 0, 10 ),
    "height"                => empty($height)             ? null : substr( $height, 0, 10 ),
    "weight"                => empty($weight)             ? null : substr( $weight, 0, 10 ),
    "lengthUnitType"        => '' == $lengthunit          ? null : $lengthunit,
    "weightUnitType"        => '' == $weightunit          ? null : $weightunit,
    "shippingClass"         => empty($shippingclass)      ? null : substr( $shippingclass, 0, 100 ),
    "taxClass"              => empty($taxclass)           ? null : substr( $taxclass, 0, 100 ),
    "availability"          => $stocklevel,
    "active"                => $active,
    "addToScheduledOrder"   => $autoship_schedule_order,
    "processScheduledOrder" => $autoship_schedule_process,
    "allowedFrequencies"    => autoship_get_api_formatted_frequencies_data ( $frequencies ),
    "productGroupIds"       => $groupIds,
    "metadata"              => empty( $metadata ) || !array( $metadata ) ? NULL : $metadata
  ), $product_data );

  return apply_filters( 'autoship_product_upsert_data', $product_data );

}

// ==========================================================
// Save Functions
// ==========================================================

/**
 * Saves the Autoship Custom Fields for a Products and Variations
 * @see woocommerce_process_product_meta
 * @see woocommerce_save_product_variation
 *
 * Saves / updates the Custom Fields returned from {@see autoship_get_custom_metafield_names}
 *
 * @param int $id The variation id.
 * @param int #index. Optional The current variation index. Default NULL
 */
function autoship_save_product_custom_fields( $id, $index = NULL ) {

  $autoship_field_names = autoship_get_custom_metafield_names();
  $autoship_field_names = apply_filters( 'autoship_save_product_custom_fields_name_list', $autoship_field_names );

  foreach ( $autoship_field_names as $name => $settings ) {

    // If the index is NULL then this is a product and not a variation. Variations have indexes.
    if ( NULL === $index ){
  		$value = isset( $_POST[ $name ] ) ? sanitize_text_field( $_POST[ $name ] ) : '';
    } else {
  		$value = isset( $_POST[ $name ] ) && isset( $_POST[ $name ][$index] ) ? sanitize_text_field( $_POST[ $name ][$index] ) : '';
    }

    // Allow custom functions to filter / sanitize the value
    $val = apply_filters( "autoship_save_product_custom_{$name}_field", $value, $id );

		$original = get_post_meta( $id, $name, true );
		update_post_meta( $id, $name, $val );

    do_action( "autoship_update_product_{$name}_field", $id, $name, $value, $original );
	}

}

/**
 * Formats the _autoship_group_ids value before saving
 * @param string $value The current value
 * @param int $product_id The variation or product id.
 * @return string The sanitized String
 */
function autoship_format_autoship_group_ids_on_save( $value, $product_id ){

  $sanitized = array();
  $vals = explode( ',', $value );

  foreach ($vals as $id ) {

    // Strip Spaces from beginning and end
    $id = trim( $id );

    if ( preg_match( '/^[1-9][0-9]*$/', $id ) )
    $sanitized[] = $id;

  }

  return implode( ',', $sanitized );

}
add_filter( 'autoship_save_product_custom__autoship_group_ids_field', 'autoship_format_autoship_group_ids_on_save', 10, 2 );

/**
 * Formats the _autoship_checkout_price & _autoship_recurring_price value before saving
 * @param string $value The current value
 * @param int $product_id The variation or product id.
 * @return float The formatted value
 */
function autoship_format_autoship_prices_on_save( $value, $product_id ){
  return empty( $value ) ? "" : wc_format_decimal( $value );
}
add_filter( 'autoship_save_product_custom__autoship_checkout_price_field', 'autoship_format_autoship_prices_on_save', 10, 2 );
add_filter( 'autoship_save_product_custom__autoship_recurring_price_field','autoship_format_autoship_prices_on_save', 10, 2 );

/**
 * Filter out the non-updated fields since they are disabled in the form and are not included in POST.
 * @param array The array of meta fields.
 * @return array The updated array.
 */
function autoship_adjust_saved_product_meta_fields( $autoship_field_names ){

  unset( $autoship_field_names['_autoship_schedule_order_enabled']);
  unset( $autoship_field_names['_autoship_schedule_process_enabled']);

  return $autoship_field_names;

}

/**
 * Updates the Add to Scheduled Order option for Variations and Simple Products.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @param string $val.             Optional. The autoship enabled value. Default 'yes' - Enabled.
 *
 * @return int|bool false if not updated.
 */
function autoship_set_product_add_to_scheduled_order ( $product_id, $val = 'yes' ) {
  $updated = update_post_meta( $product_id, '_autoship_schedule_order_enabled', $val );
	return $updated;
}

/**
 * Updates the Process on Scheduled Orders option for Variations and Simple Products.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @param string $val.             Optional. The autoship enabled value. Default 'yes' - Enabled.
 *
 * @return int|bool false if not updated.
 */
function autoship_set_product_process_on_scheduled_order ( $product_id, $val = 'yes' ) {
  $updated = update_post_meta( $product_id, '_autoship_schedule_process_enabled', $val );
	return $updated;
}

/**
 * Updates the Add to Scheduled Order amd Process Scheduled Order Options for Variations and Simple Products.
 *
 * @param int|WC_Product    $product      The WC Product object or id.
 * @param string            $val.         Optional. The autoship enabled value. Default 'yes' - Enabled.
 */
function autoship_set_product_availability ( $product, $val = 'yes' ) {

  if ( is_numeric( $product ) )
  $product = wc_get_product( $product );

  if ( 'variable' == $product->get_type() ){

    $variations = autoship_get_available_variations( $product );

    foreach ( $variations as $variation ) {

      autoship_set_product_add_to_scheduled_order( $variation, $val );
      autoship_set_product_process_on_scheduled_order( $variation, $val );

    }

  } else {

    autoship_set_product_add_to_scheduled_order( $product->get_id(), $val );
    autoship_set_product_process_on_scheduled_order( $product->get_id(), $val );

  }

}


/**
 * Sets the value for the Global '_autoship_sync_all_products_enabled' field
 *
 * @param string $option 'yes' for enabled else 'no'
 */
function autoship_set_global_sync_active_enabled( $option = 'no' ) {
  update_option('_autoship_sync_all_products_enabled', $option );

  // Fire action that the global was changed so upgrade notice can be removed.
  do_action( 'autoship_set_global_sync_active_enabled', $option );
}

/**
 * Updates the Enable Schedule Options option for Variable and Simple Products.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @param string $val.             Optional. The autoship enabled value. Default 'yes' - Enabled.
 * @return int|bool false if not updated.
 */
function autoship_set_product_sync_active_enabled ( $product_id, $val = 'yes' ) {
  $updated = update_post_meta( $product_id, '_autoship_sync_active_enabled', $val );
	return $updated;
}

/**
 * Updates the Enable Schedule Options option for Variable and Simple Products.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @param string $val.             Optional. The autoship enabled value. Default 'yes' - Enabled.
 * @return int|bool false if not updated.
 */
function autoship_set_product_autoship_enabled ( $product_id, $val = 'yes' ) {
  $updated = update_post_meta( $product_id, '_autoship_schedule_options_enabled', $val );
	return $updated;
}

/**
 * Updates the Enable / Disables The Disable Schedule Options option for variations.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @param string $val.             Optional. The autoship enabled value. Default 'no' - Not Enabled.
 * @return int|bool false if not updated.
 */
function autoship_set_product_variation_autoship_disabled ( $product_id, $val = 'no' ) {
  $updated = update_post_meta( $product_id, '_autoship_dissable_schedule_order_options', $val );
	return $updated;
}

/**
 * Sets the Recurring price for a product.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @param float  $val              The recurring price.
 * @return int|bool false if not updated.
 */
function autoship_set_product_recurring_price( $product_id, $val ) {
  $updated = update_post_meta( $product_id, '_autoship_recurring_price', $val );
	return $updated;
}

/**
 * Sets the Recurring price for a product.
 *
 * @param int    $product_id.      The WC Product or variation id.
 * @param float  $val              The Checkout price.
 * @return int|bool false if not updated.
 */
function autoship_set_product_checkout_price( $product_id, $val ) {
  $updated = update_post_meta( $product_id, '_autoship_checkout_price', $val );
	return $updated;
}

// ==========================================================
// Product Data Expansions for Integrations
// ==========================================================

/**
 * Shipper HQ Product Metadata Integration
 *
 * @param array $metadata The current metadata array
 * @param WC_Product $wc_product The current wc product or variation.
 * @return array The filtered / updated metadata with the Shipper HQ fields
 */
function autoship_attach_product_shipperhq_metadata( $metadata, $wc_product ){

  // Default the id for where to pull the metadata from
  $id = $wc_product->get_id();

  // Allow devs to adjust/expand the shipperhq fields in case new ones are needed.
  // Note The array contains the field name matched to the id from where the metadata should be pulled
  // i.e. Variable or Variation
  $shipperhq_fields =  apply_filters( 'autoship_shipperhq_product_fields', array(
    'shipperhq_shipping_group'=> $id,
    'shipperhq_dim_group'     => $id,
    'ship_separately'         => $id,
    'shipperhq_warehouse'     => $id,
    'freight_class'           => $id,
    'must_ship_freight'       => $id,
    'shipperhq_hs_code'       => $id ),
  $wc_product );

  // Retrieve each Shipper HQ Field from the Parent product
  foreach ( $shipperhq_fields as $field => $product_id ) {

    // Note all fields are pulled from the parent product for variations
    if ( metadata_exists( 'post', $product_id, $field ) )
    $metadata[$field] = get_post_meta( $product_id, $field, true );

  }

  return $metadata;

}

// ==========================================================
// WC REST API Functions
// ==========================================================

/**
 * Filter the product data for a response.
 *
 * @param WP_REST_Response $response The response object.
 * @param WC_Data          $object   Object data.
 * @param WP_REST_Request  $request  Request object.
 */
function autoship_adjust_rest_product_data( $response, $object, $request ){

  // Retrieve the Custom Data
  $data = autoship_get_product_custom_data_values( $object );
  $response->data['name'] = $data['title'];

  return $response;

}

// ==========================================================
// Summary Utility Functions
// ==========================================================

// Allow for Plugability so data could be stored somewhere else.
if ( !function_exists( 'autoship_get_stored_all_products_sync_summary_data' ) ):
  /**
   * Gets the all products sync summary data from the Options table.
   * @return array|WP_Error $summary_data The Summary Data or WP_Error on failure
   */
  function autoship_get_stored_all_products_sync_summary_data (){
    return get_option( '_autoship_all_products_sync_summary_meta', array() );
  }
endif;

// Allow for Plugability so data could be stored somewhere else.
if ( !function_exists( 'autoship_store_all_products_summary_data' ) ):
  /**
   * Stores the all products sync summary data into the Options table.
   * @param array|WP_Error $summary_data The Summary Data or WP_Error on failure
   */
  function autoship_store_all_products_sync_summary_data ( $summary_data ){
    update_option( '_autoship_all_products_sync_summary_meta', $summary_data, false );
  }
endif;

// Allow for Plugability so data could be stored somewhere else.
if ( !function_exists( 'autoship_get_stored_product_sync_summary_data' ) ):
  /**
   * Gets the product sync summary data from the supplied product.
   *
   * @param mixed $id. Product id.
   * @return array|WP_Error $summary_data The Summary Data or WP_Error on failure
   */
  function autoship_get_stored_product_sync_summary_data ( $id ){
    $summary_data = get_post_meta( $id, '_autoship_product_sync_summary_meta', true );
    return empty( $summary_data ) ? array(): $summary_data;
  }
endif;

// Allow for Pluggability so data could be stored somewhere else.
if ( !function_exists( 'autoship_store_product_sync_summary_data' ) ):
  /**
   * Attaches the product summary data to this product.
   *
   * @param mixed $id. Product id.
   * @param array|WP_Error $summary_data The Summary Data or WP_Error on failure
   */
  function autoship_store_product_sync_summary_data ( $id, $summary_data ){
    update_post_meta( $id, '_autoship_product_sync_summary_meta', $summary_data );
  }
endif;

// ==========================================================
// Summary & Product API Get Functions
// ==========================================================

/**
 * Retrieves the product from QPilot.
 *
 * @param mixed $wc_product. The WC Product ID or WC_Product object.
 * @return stdClass|WP_Error The product object or wp_error on failure.
 */
function autoship_get_product ( $wc_product ){

  // Check if the id or the WC_Product was supplied.
  $id = !is_numeric( $wc_product ) ? $wc_product->get_id() : $wc_product;

  // Create the QPilot client
  $client = autoship_get_default_client();

  // Get the product via external id.
  try {
    $product = $client->get_product( $id );
  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'The supplied product can not be found in QPilot for Site ID %d. Additional Details: %s', $client->get_site_id(), $e->getMessage() ) );
      return new WP_Error( 'Product Not Found', sprintf( __( "The supplied product could not be found in your connected QPilot Site ( Site ID: %d ).", "autoship" ), $client->get_site_id() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );
      autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'Product Retrieval Failed. Additional Details: %s', $e->getMessage() ) );
      return new WP_Error( 'Product Retrieval Failed', __( $notice['desc'], "autoship" ) );
    }

  }

  return $product;

}

/**
 * Retrieves the product summary data for the All Products page.
 *
 * @param array $ids. The Product ids.
 * @param array $wc_products. The WC Products associated with these ids.
 * @return array|WP_Error The Summary Data or WP_Error on failure
 */
function autoship_get_all_products_sync_summary ( $ids, $wc_products = array() ){

  // Create the QPilot client
  // Grab the client and only continue if connection exists.
  if ( empty( $client = autoship_get_default_client() ) || empty( $client->get_token_auth() ) )
  return new WP_Error( 'All Products Summary Retrieval Failed', __( 'A problem was encountered while trying to retrieve the Product Sync Information for the supplied products.  Please confirm your Autoship Connection is healthy and setup correctly.', "autoship" ) );

  try {

    $summaries = $client->get_products_summary( $ids, array( 'pageSize' => count( $ids ) ) );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'The supplied product(s) can not be found in QPilot. Additional Details: %s', $e->getMessage() ) );
      return new WP_Error( 'Products Not Found', __( "The supplied product could not be found in QPilot", "autoship" ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );
      autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'All Products Summary Retrieval Failed. Additional Details: %s', $e->getMessage() ) );
      return new WP_Error( 'All Products Summary Retrieval Failed', sprintf( __( 'A problem was encountered while trying to retrieve this Product\'s Sync Information.  Please confirm your Autoship Connection is healthy and setup correctly. Additional Details: <i>%s</i>', "autoship" ), $notice['desc'] ) );
    }

  }


  if ( empty( $summaries ) )
  return new WP_Error( 'Product(s) Not Found', __( "The supplied product(s) could not be found in QPilot", "autoship" ) );

  $products = $errors = array();
  $totals = array(
    'TotalActive'                   => 0,
    'TotalAutoshipActive'           => 0,
    'TotalInactive'                 => 0,
    'TotalAddToScheduledOrder'      => 0,
    'TotalProcessScheduledOrder'    => 0,
    'TotalUnAvailable'              => 0,
    'TotalInStock'                  => 0,
    'TotalOutOfStock'               => 0,
    'QuantityScheduled'             => 0,
    'TotalQuantityScheduledActive'  => 0,
    'TotalQuantityScheduledPaused'  => 0,
    'TotalQuantityFailed'           => 0,
    'TotalQuantityProcessing'       => 0,
    'TotalVariations'               => 0,
    'TotalAutoshipVariations'       => 0,
    'TotalErrors'                   => 0,
    'TotalInvalids'                 => 0,
    'TotalValids'                   => 0,
    'TotalActiveInvalids'           => 0,
    'TotalActiveIds'                => array(),
    'TotalAutoshipActiveIds'        => array(),
    'TotalInactiveIds'              => array(),
    'TotalInStockIds'               => array(),
    'TotalOutOfStockIds'            => array(),
    'TotalInvalidsIds'              => array(),
    'TotalValidsIds'                => array(),
    'TotalActiveInvalidsIds'        => array(),
  );
  foreach ($summaries as $key => $summary) {

    // Try to get the product in WC
    $wc_product = empty( $wc_products ) || !isset( $wc_products[$summary->id] ) ?
    wc_get_product( $summary->id ) : $wc_products[$summary->id];

    // If product doesn't exist in WC but exists in QPilot we can't gather any info.
    // TODO: We need to better deal with this situation.
    if ( !$wc_product )
    continue;

    // Retrieve the legacy availability string.
    $availability = 'None';
    if ( $summary->addToScheduledOrder && $summary->processScheduledOrder ){
      $availability = 'AddToScheduledOrder,ProcessScheduledOrder';
    } else if ( $summary->addToScheduledOrder ){
      $availability = 'AddToScheduledOrder';
    } else if ( $summary->processScheduledOrder  ){
      $availability = 'ProcessScheduledOrder';
    }

    $products[$summary->id] = apply_filters( 'autoship_all_products_summary_data_row', array(
      'Id'                            => $summary->id,
      //'ExternalId'                    => $summary->ExternalId,
      'ProductName'                   => $summary->productName,
      'AddToScheduledOrder'           => $summary->addToScheduledOrder !== false,
      'ProcessScheduledOrder'         => $summary->processScheduledOrder !== false,
      'Availability'                  => $availability,
      'ProductType'                   => $summary->productType,
      'StockLevel'                    => $summary->availability,
      'TotalStock'                    => $summary->stock,
      'QuantityScheduled'             => $summary->totalQuantityScheduledActive +
                                         $summary->totalQuantityScheduledPaused +
                                         $summary->totalQuantityFailed +
                                         $summary->totalQuantityProcessing +
                                         $summary->totalQuantityQueued,
      'TotalQuantityScheduledActive'  => $summary->totalQuantityScheduledActive,
      'TotalQuantityScheduledPaused'  => $summary->totalQuantityScheduledPaused,
      'TotalQuantityFailed'           => $summary->totalQuantityFailed,
      'TotalQuantityProcessing'       => $summary->totalQuantityProcessing,
      'TotalQuantityQueued'           => $summary->totalQuantityQueued,
      'LifetimeValue'                 => $summary->lifetimeValue,
      'ShippingClass'                 => $summary->shippingClass,
      'Active'                        => $summary->active,
      'Valid'                         => $summary->valid,
      'ValidationErrorCode'           => $summary->validationErrorCode,
      'UpdatedUtc'                    => $summary->updatedUtc,
      'SyncError'                     => ''
    ), $summary );

    // Since we've already pulled the Active flag from QPilot get the flag from WooCommerce
    $products[$summary->id]['AutoshipActive'] = ( 'yes' === autoship_sync_active_enabled( $wc_product ) );

    // Run Sync Checks
    $products[$summary->id]['SyncError'] = apply_filters( 'autoship_product_summary_data_sync_error', $products[$summary->id]['SyncError'], $products[$summary->id], $summary, $wc_product, $summary->id );

    // Makes sure to update the Valid flag if the Sync Check coaught issue and QPilot didn't
    if ( !empty( $products[$summary->id]['SyncError'] ) )
    $products[$summary->id]['Valid'] = false;

    if ( is_wp_error( $products[$summary->id]['SyncError'] ) )
    $errors[$summary->id] = $products[$summary->id]['SyncError'];

    $totals['TotalAddToScheduledOrder']      += (int) $products[$summary->id]['AddToScheduledOrder'];
    $totals['TotalProcessScheduledOrder']    += (int) $products[$summary->id]['ProcessScheduledOrder'];
    $totals['TotalUnAvailable']              += (int) 'None' == $products[$summary->id]['Availability'];

    // Sum the total Active for this product from QPilot and the total Active from Autoship
    $totals['TotalActive']                   += (int) $products[$summary->id]['Active'];
    $totals['TotalAutoshipActive']           += (int) $products[$summary->id]['AutoshipActive'];

    if ( $products[$summary->id]['Active'] )
    $totals['TotalActiveIds'][$summary->id] = $summary->id;

    if ( $products[$summary->id]['AutoshipActive'] )
    $totals['TotalAutoshipActiveIds'][$summary->id] = $summary->id;

    $totals['TotalInactive']                 += (int)!$products[$summary->id]['Active'];

    if ( !$products[$summary->id]['Active'] )
    $totals['TotalInactiveIds'][$summary->id] = $summary->id;

    $totals['TotalInStock']                  += (int) ('InStock' == $products[$summary->id]['StockLevel']);

    if ( 'InStock' == $products[$summary->id]['StockLevel'] )
    $totals['TotalInStockIds'][$summary->id] = $summary->id;

    $totals['TotalOutOfStock']               += (int) ('InStock' != $products[$summary->id]['StockLevel']);

    if ( 'InStock' != $products[$summary->id]['StockLevel'] )
    $totals['TotalOutOfStockIds'][$summary->id] = $summary->id;

    $totals['QuantityScheduled']             += $products[$summary->id]['QuantityScheduled'];
    $totals['TotalQuantityScheduledActive']  += $products[$summary->id]['TotalQuantityScheduledActive'];
    $totals['TotalQuantityScheduledPaused']  += $products[$summary->id]['TotalQuantityScheduledPaused'];
    $totals['TotalQuantityFailed']           += $products[$summary->id]['TotalQuantityFailed'];
    $totals['TotalQuantityProcessing']       += $products[$summary->id]['TotalQuantityProcessing'];

    /**
     * Track total Variations & Total Variations active in Autoship
     * - used to compare totals between Autoship and QPilot
     */

    // Total Variations ( active and not active in WooCommerce / Autoship
    $totals['TotalVariations']               += (int) ( 'variation' == $products[$summary->id]['ProductType'] );

    // Total Variations Active in WooCommerce / Autoship
    if ( 'variation' == $products[$summary->id]['ProductType'] )
    $totals['TotalAutoshipVariations']       += (int) $products[$summary->id]['AutoshipActive'];

    $totals['TotalInvalids']                 += (int) ( !$products[$summary->id]['Valid'] );

    if ( !$products[$summary->id]['Valid'] )
    $totals['TotalInvalidsIds'][$summary->id] = $summary->id;

    $totals['TotalValids']                   += (int) ( $products[$summary->id]['Valid'] );

    if ( $products[$summary->id]['Valid'] )
    $totals['TotalValidsIds'][$summary->id] = $summary->id;

    // Not valid and active are a problem and should be addressed.
    $totals['TotalActiveInvalids']           += (int) ( !$products[$summary->id]['Valid'] && $products[$summary->id]['Active'] );

    if ( !$products[$summary->id]['Valid'] && $products[$summary->id]['Active'] )
    $totals['TotalActiveInvalidsIds'][$summary->id] = $summary->id;

  }

  $totals['SyncError']                     = reset( $errors );
  $totals['AllSyncError']                  = $errors;
  $totals['TotalErrors']                   = count( $errors );

  return array(
    'products' => $products,
    'totals'   => $totals,
  );

}

/**
 * Retrieves the product summary from QPilot for the supplied id..
 *
 * @param mixed $id. Product id.
 * @return array|WP_Error The Summary Data or WP_Error on failure
 */
function autoship_get_product_sync_summary ( $id ){

  // Get the product ( if invalid or wrong type or not correct status returns false )
  if ( ( $product = autoship_productize_and_validate( $id ) ) === false )
  return new WP_Error( 'Product Summary Retrieval Failed', __( 'Autoship Sync information is not available for this product.  Please confirm the product is a valid product type and status.', "autoship" ) );

  // Create the QPilot client
  // Grab the client and only continue if connection exists.
  if ( empty( $client = autoship_get_default_client() ) || empty( $client->get_token_auth() ) )
  return new WP_Error( 'Product Summary Retrieval Failed', __( 'A problem was encountered while trying to retrieve this Product\'s Sync Information.  Please confirm your Autoship Connection is healthy and setup correctly.', "autoship" ) );

  // Get the product via external id.
  $all_ids = array( $id );
  $ids = array();
  if ( 'variable' == $product->get_type() ){

    // If Variation get all children as well
    $ids = autoship_get_available_variations( $product, true );

    $all_ids = array_merge( $ids['valids'] , $ids['invalids'], array( $id ) );

  }

  try {

    $summaries = $client->get_products_summary( $all_ids );

  } catch ( Exception $e ) {

    if ( '404' == $e->getCode() ){
      autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'The supplied product can not be found in QPilot for Site ID %d. Additional Details: %s', $client->get_site_id(), $e->getMessage() ) );
      return new WP_Error( 'Product Not Found', sprintf( __( "The supplied product could not be found in your connected QPilot Site ( Site ID: %d ).", "autoship" ), $client->get_site_id() ) );
    } else {
      $notice = autoship_expand_http_code( $e->getCode() );
      autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'Product Summary Retrieval Failed. Additional Details: %s', $e->getMessage() ) );
      return new WP_Error( 'Product Summary Retrieval Failed', sprintf( __( 'A problem was encountered while trying to retrieve this Product\'s Sync Information.  Please confirm your Autoship Connection is healthy and setup correctly. Additional Details: <i>%s</i>', "autoship" ), $notice['desc'] ) );
    }

  }

  if ( empty( $summaries ) )
  return new WP_Error( 'Product Not Found', sprintf( __( "The supplied product could not be found in your connected QPilot Site ( Site ID: %d ).", "autoship" ), $client->get_site_id() ) );

  $totals = array();
  if ( 'variation' == $product->get_type() || 'simple' == $product->get_type() ){

    // Retrieve the legacy availability string.
    $availability = 'None';
    if ( $summaries[0]->addToScheduledOrder && $summaries[0]->processScheduledOrder ){
      $availability = 'AddToScheduledOrder,ProcessScheduledOrder';
    } else if ( $summaries[0]->addToScheduledOrder ){
      $availability = 'AddToScheduledOrder';
    } else if ( $summaries[0]->processScheduledOrder  ){
      $availability = 'ProcessScheduledOrder';
    }

    $totals = apply_filters( 'autoship_product_summary_data', array(
      'Id'                            => $summaries[0]->id,
      //'ExternalId'                    => $summaries[0]->ExternalId,
      'ProductName'                   => $summaries[0]->productName,
      'AddToScheduledOrder'           => $summaries[0]->addToScheduledOrder !== false,
      'ProcessScheduledOrder'         => $summaries[0]->processScheduledOrder !== false,
      'Availability'                  => $availability,
      'ProductType'                   => $summaries[0]->productType,
      'StockLevel'                    => $summaries[0]->availability,
      'TotalStock'                    => $summaries[0]->stock,
      'QuantityScheduled'             => $summaries[0]->totalQuantityScheduledActive +
                                         $summaries[0]->totalQuantityScheduledPaused +
                                         $summaries[0]->totalQuantityFailed +
                                         $summaries[0]->totalQuantityProcessing +
                                         $summaries[0]->totalQuantityQueued,
      'TotalQuantityScheduledActive'  => $summaries[0]->totalQuantityScheduledActive,
      'TotalQuantityScheduledPaused'  => $summaries[0]->totalQuantityScheduledPaused,
      'TotalQuantityFailed'           => $summaries[0]->totalQuantityFailed,
      'TotalQuantityProcessing'       => $summaries[0]->totalQuantityProcessing,
      'TotalQuantityQueued'           => $summaries[0]->totalQuantityQueued,
      'LifetimeValue'                 => $summaries[0]->lifetimeValue,
      'ShippingClass'                 => $summaries[0]->shippingClass,
      'Active'                        => $summaries[0]->active,
      'Valid'                         => $summaries[0]->valid,
      'ValidationErrorCode'           => $summaries[0]->validationErrorCode,
      'UpdatedUtc'                    => $summaries[0]->updatedUtc,
      'SyncError'                     => ''
    ), $summaries[0], $product );

    // Since we've already pulled the Active flag from QPilot get the flag from WooCommerce / Autoship
    $totals['AutoshipActive'] = ( 'yes' === autoship_sync_active_enabled( $product ) );

    // Run Sync Checks
    $totals['SyncError'] = apply_filters( 'autoship_product_summary_data_sync_error', $totals['SyncError'], $totals, $summaries[0], $product, $ids );

    return apply_filters( 'autoship_total_product_summary_data', $totals, $product, $summaries );

  // If this is a variable product then sum variations totals.
  } else {


    $totals = array(
      'TotalActive'                   => 0,
      'TotalAutoshipActive'           => 0,
      'TotalInactive'                 => 0,
      'TotalAddToScheduledOrder'      => 0,
      'TotalProcessScheduledOrder'    => 0,
      'TotalUnAvailable'              => 0,
      'TotalInStock'                  => 0,
      'TotalOutOfStock'               => 0,
      'QuantityScheduled'             => 0,
      'TotalQuantityScheduledActive'  => 0,
      'TotalQuantityScheduledPaused'  => 0,
      'TotalQuantityFailed'           => 0,
      'TotalQuantityProcessing'       => 0,
      'TotalVariations'               => 0,
      'TotalAutoshipVariations'       => 0,
      'TotalErrors'                   => 0,
      'TotalInvalids'                 => 0,
      'TotalValids'                   => 0,
      'TotalActiveInvalids'           => 0,
    );

    $variations = $autoship_variations = $errors = array();
    foreach ( $summaries as $key => $summary) {

      // Try to get the product in WC
      $row_product = wc_get_product( $summary->id );

      // If product variation doesn't exist in WC but exists in QPilot we can't gather any info.
      // TODO: We need to better deal with this situation.
      if ( !$row_product )
      continue;


      // Retrieve the legacy availability string.
      $availability = 'None';
      if ( $summary->addToScheduledOrder && $summary->processScheduledOrder ){
        $availability = 'AddToScheduledOrder,ProcessScheduledOrder';
      } else if ( $summary->addToScheduledOrder ){
        $availability = 'AddToScheduledOrder';
      } else if ( $summary->processScheduledOrder  ){
        $availability = 'ProcessScheduledOrder';
      }

      $variations[$summary->id] = apply_filters( 'autoship_variable_product_summary_data', array(
        'Id'                            => $summary->id,
        //'ExternalId'                    => $summary->ExternalId,
        'ProductName'                   => $summary->productName,
        'AddToScheduledOrder'           => $summary->addToScheduledOrder !== false,
        'ProcessScheduledOrder'         => $summary->processScheduledOrder !== false,
        'Availability'                  => $availability,
        'ProductType'                   => $summary->productType,
        'StockLevel'                    => $summary->availability,
        'TotalStock'                    => $summary->stock,
        'QuantityScheduled'             => $summary->totalQuantityScheduledActive +
                                           $summary->totalQuantityScheduledPaused +
                                           $summary->totalQuantityFailed +
                                           $summary->totalQuantityProcessing +
                                           $summary->totalQuantityQueued,
        'TotalQuantityScheduledActive'  => $summary->totalQuantityScheduledActive,
        'TotalQuantityScheduledPaused'  => $summary->totalQuantityScheduledPaused,
        'TotalQuantityFailed'           => $summary->totalQuantityFailed,
        'TotalQuantityProcessing'       => $summary->totalQuantityProcessing,
        'TotalQuantityQueued'           => $summary->totalQuantityQueued,
        'LifetimeValue'                 => $summary->lifetimeValue,
        'ShippingClass'                 => $summary->shippingClass,
        'Active'                        => $summary->active,
        'Valid'                         => $summary->valid,
        'ValidationErrorCode'           => $summary->validationErrorCode,
        'UpdatedUtc'                    => $summary->updatedUtc,
        'SyncError'                     => ''
      ), $summary, $product );

      // Since we've already pulled the Active flag from QPilot get the flag from WooCommerce / Autoship
      $variations[$summary->id]['AutoshipActive'] = ( 'yes' === autoship_sync_active_enabled( $row_product ) );

      // Run Sync Checks
      $variations[$summary->id]['SyncError'] = apply_filters( 'autoship_product_summary_data_sync_error', $variations[$summary->id]['SyncError'], $variations[$summary->id], $summary, $row_product, $ids );

      if ( is_wp_error( $variations[$summary->id]['SyncError'] ) )
      $errors[] = $variations[$summary->id]['SyncError'];

      // Only include variations in totals NOT variable
      if ( 'variation' == $summary->productType ){
        $totals['TotalAddToScheduledOrder']      += (int) $variations[$summary->id]['AddToScheduledOrder'];
        $totals['TotalProcessScheduledOrder']    += (int) $variations[$summary->id]['ProcessScheduledOrder'];
        $totals['TotalUnAvailable']              += (int) 'None' == $variations[$summary->id]['Availability'];
        $totals['TotalActive']                   += (int) $variations[$summary->id]['Active'];
        $totals['TotalAutoshipActive']           += (int) $variations[$summary->id]['AutoshipActive'];
        $totals['TotalInactive']                 += (int)!$variations[$summary->id]['Active'];
        $totals['TotalInStock']                  += (int) ('InStock' == $variations[$summary->id]['StockLevel']);
        $totals['TotalOutOfStock']               += (int) ('OutOfStock' == $variations[$summary->id]['StockLevel']);
        $totals['QuantityScheduled']             += $variations[$summary->id]['QuantityScheduled'];
        $totals['TotalQuantityScheduledActive']  += $variations[$summary->id]['TotalQuantityScheduledActive'];
        $totals['TotalQuantityScheduledPaused']  += $variations[$summary->id]['TotalQuantityScheduledPaused'];
        $totals['TotalQuantityFailed']           += $variations[$summary->id]['TotalQuantityFailed'];
        $totals['TotalQuantityProcessing']       += $variations[$summary->id]['TotalQuantityProcessing'];
        $totals['TotalVariations']               += (int) ( 'variation' == $summary->productType );
        $totals['TotalInvalids']                 += (int) ( !$variations[$summary->id]['Valid'] );
        $totals['TotalValids']                   += (int) ( $variations[$summary->id]['Valid'] );

        // Not valid and active are a problem and should be addressed.
        $totals['TotalActiveInvalids']           += (int) ( !$variations[$summary->id]['Valid'] && $variations[$summary->id]['Active'] );

        // Gather variations that are active in WooCommerce / Autoship
        if ( $variations[$summary->id]['AutoshipActive'] )
        $autoship_variations[$summary->id] = $summary->id;

      }

    }

    $totals['StockLevel']                    = $totals['TotalInStock'] ? 'InStock' : 'OutOfStock';
    $totals['Variations']                    = $variations;
    $totals['AutoshipVariations']            = $autoship_variations;
    $totals['SyncError']                     = reset( $errors );
    $totals['AllSyncError']                  = $errors;
    $totals['TotalErrors']                   = count( $errors );

    return apply_filters( 'autoship_total_variation_product_summary_data', $totals, $product, $ids, $summaries );

  }

}


// ==========================================================
// API Sync Error check Related Functions
// ==== WP Error Codes =====
// 'utc-out-of-sync'
// 'product-type-out-of-sync'
// 'stock-level-out-of-sync'
// 'active-flag-out-of-sync'
// 'valid-out-of-sync'
// 'active-invalid-out-of-sync'
// 'variations-out-of-sync'
// ==========================================================

/**
 * Check If error should be treated as a warning instead.
 *
 * @param bool $is_warning If the current error should be treated as a warning.
 * @param array $error. The current error code, message, type
 * @param WC_Product $product The WooCommerce product.
 * @return bool The adjusted flag.
 */
function autoship_adjust_sync_error_to_warning( $is_warning, $error, $product ){
  return !empty( $error ) && ( 'utc-out-of-sync' == $error['code'] ) ? true : $is_warning;
}

/**
 * Check for Product Last Updated Sync Error Issues
 * NOTE Case when a product hasn't been updated in more than a day in QPilot.
 *
 * @param WP_Error|string $sync_error The current sync error.
 * @param array $data The current summary data for this product.
 * @param stdClass $record The current api data for this product.
 * @param WC_Product $product The WooCommerce product.
 * @return WP_Error|string The filtered Sync Error.
 */
function autoship_product_summary_updatedutc_sync_check( $sync_error, $data, $record, $product ){

  // Check if Updated UTC is stale
  // Get the current DateTime object and last Updated DateTime object
  $now              = autoship_get_datetime ();
  $next             = autoship_get_datetime ($data['UpdatedUtc']);

  // Figure out if its stale.
  $interval         = $now->diff($next);
  $window           = $interval->format('%R%a');

  $products_report_url  = autoship_admin_products_page_url();
  $api_health_url       = autoship_admin_settings_page_url();

  return (int) $window >= -1 ? $sync_error : new WP_Error( 'utc-out-of-sync', sprintf( __( "Last Sync %d Days Ago", "autoship" ), -1 * (int) $window ) );
}

/**
 * Check for Product Type Sync Error Issues
 * NOTE Case when the product's type doesn't match between QPilot and WooCommerce.
 *
 * @param WP_Error|string $sync_error The current sync error.
 * @param array $data The current summary data for this product.
 * @param stdClass $record The current api data for this product.
 * @param WC_Product $product The WooCommerce product.
 * @return WP_Error|string The filtered Sync Error.
 */
function autoship_product_summary_type_sync_check( $sync_error, $data, $record, $product ){

  $products_report_url  = autoship_admin_products_page_url();
  $api_health_url       = autoship_admin_settings_page_url();

  return $product->get_type() == $data['ProductType'] ? $sync_error : new WP_Error( 'product-type-out-of-sync',
  'variation' === $product->get_type() ? sprintf( __( "Product not synchronized: Product Type is invalid.<br/>The Product Type of one or more product variations does not match between QPilot and WooCommerce. Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $api_health_url, $products_report_url ) :
  sprintf( __( "Product not synchronized: Product Type is invalid. <br/>The Product Type does not match between QPilot and WooCommerce. Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $api_health_url, $products_report_url ) );

}

/**
 * Check for Product Stock Level Sync Error Issues
 * NOTE Case when the product stock level doesn't match between QPilot and WooCommerce.
 *
 * @param WP_Error|string $sync_error The current sync error.
 * @param array $data The current summary data for this product.
 * @param stdClass $record The current api data for this product.
 * @param WC_Product $product The WooCommerce product.
 * @return WP_Error|string The filtered Sync Error.
 */
function autoship_product_summary_stocklevel_sync_check( $sync_error, $data, $record, $product ){

  $products_report_url  = autoship_admin_products_page_url();
  $api_health_url       = autoship_admin_settings_page_url();

  // Get the Stock Status and translate it to the QPilot Equivalent
  // Since QPilot doesn't track certain statuses.
  $stock_status = autoship_get_mapped_stocklevel( $product->get_stock_status() );

  return $stock_status == $data['StockLevel'] ? $sync_error :
  new WP_Error( 'stock-level-out-of-sync', sprintf(  'variation' === $product->get_type() ?
  __( "Product not synchronized: Unable to synchronize Stock Status for one or more variations.<br/>Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ) :
  __( "Product not synchronized: Unable to synchronize Stock Level.<br/>Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $api_health_url, $products_report_url ) );

}

/**
 * Check for Product QPilot Active flag Sync Error Issues
 * NOTE Case when the product is Not Active in QPilot yet Active in WooCommerce.
 *
 * @param WP_Error|string $sync_error The current sync error.
 * @param array $data The current summary data for this product.
 * @param stdClass $record The current api data for this product.
 * @param WC_Product $product The WooCommerce product.
 * @return WP_Error|string The filtered Sync Error.
 */
function autoship_product_summary_active_sync_check( $sync_error, $data, $record, $product ){

  $products_report_url  = autoship_admin_products_page_url();
  $api_health_url       = autoship_admin_settings_page_url();

  if ( !$data['Active'] && $data['AutoshipActive'] ){
    $sync_error = new WP_Error( 'active-flag-out-of-sync',   sprintf( __( "Product not synchronized: Unable to activate this product in QPilot. <br/>Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $api_health_url, $products_report_url ) );
  } else if ( $data['Active'] && !$data['AutoshipActive'] ){
    $sync_error = new WP_Error( 'active-flag-out-of-sync',   sprintf( __( "Product not synchronized: Invalid Product Data returned by QPilot. <br/>Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $api_health_url, $products_report_url ) );
  }

  return $sync_error;

}

/**
 * Check for Missing/Orphaned Product Variations
 * NOTE Case when the product is Active & Valid in QPilot yet not valid in WooCommerce.
 *
 * @param WP_Error|string $sync_error The current sync error.
 * @param array $data The current summary data for this product.
 * @param stdClass $record The current api data for this product.
 * @param WC_Product $product The WooCommerce product.
 * @param array|int $ids The valid and invalid ids or id.
 *
 * @return WP_Error|string The filtered Sync Error.
 */
function autoship_product_summary_orphaned_invalid_variations_sync_check( $sync_error, $data, $record, $product, $ids ){

  $products_report_url  = autoship_admin_products_page_url();
  $api_health_url       = autoship_admin_settings_page_url();

  // If a variable product is being supplied then check if the id is one of it's variations and
  // if that variation is suppose to be invalid.
  if ( 'variable' === $product->get_type() && is_array( $ids ) && isset( $ids['invalids'] ) ){

    $sync_error = $data['Active'] && $data['Valid'] && in_array( $data['Id'], $ids['invalids'] ) ? new WP_Error( 'valid-out-of-sync', sprintf( __( "Product not synchronized: Product data not found.<br/>One or more variations are Active in QPilot but not found in WooCommerce. Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $api_health_url, $products_report_url ) ) : $sync_error;

  // If a variation product is being supplied then check if it's valid.
  } else if ( 'variation' === $product->get_type() ){

    $sync_error = $data['Active'] && $data['Valid'] && !autoship_is_available_variation( $data['Id'] ) ? new WP_Error( 'valid-out-of-sync', sprintf( __( "Product not synchronized: Product data not found.<br/>This variation #%d is Active in QPilot but not found in WooCommerce. Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $data['Id'], $api_health_url, $products_report_url ) )  : $sync_error;

  }

  return  $sync_error;

}

/**
 * Check for Product QPilot Active Invalid flag Sync Error Issues
 * NOTE Case when the product is Active & InValid in QPilot.
 *
 * @param WP_Error|string $sync_error The current sync error.
 * @param array $data The current summary data for this product.
 * @param stdClass $record The current api data for this product.
 * @param WC_Product $product The WooCommerce product.
 *
 * @return WP_Error|string The filtered Sync Error.
 */
function autoship_product_summary_active_invalid_sync_check( $sync_error, $data, $record, $product ){

  $products_report_url  = autoship_admin_products_page_url();
  $api_health_url       = autoship_admin_settings_page_url();

  $error = $data['ValidationErrorCode'] ? autoship_expand_sync_invalid_product_code( $data['ValidationErrorCode'], 'desc' ) : '';

  return ( $data['Active'] && $data['Valid'] ) || $data['Valid'] ? $sync_error :
  new WP_Error( 'active-invalid-out-of-sync', ( 'variable' === $product->get_type() ) ?
  sprintf( __( "Product not synchronized: Invalid Product Data returned by QPilot for one or more variations.<br/> %s Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $error, $api_health_url, $products_report_url ) :
  sprintf( __( "Product not synchronized: Invalid Product Data returned by QPilot.<br/> %s Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $error, $api_health_url, $products_report_url ) );

}

/**
 * Check for Missing/Orphaned Product Variations
 * NOTE Case when the product variation is Active & Valid in WooCommerce yet does not exist in QPilot.
 *
 * @param array $totals The current total summary data for this product.
 * @param WC_Product $product The WooCommerce product.
 * @param array $ids The valid and invalid ids.
 *
 * @return array The filtered total summary data.
 */
function autoship_product_summary_orphaned_variations_sync_check( $totals, $product, $ids ){

  if ( 'variable' !== $product->get_type() )
  return $totals;

  $products_report_url  = autoship_admin_products_page_url();
  $api_health_url       = autoship_admin_settings_page_url();

  // Check if the total variations returned by QPilot less than Those in WooCommerce
  if ( $totals['TotalVariations'] < count( $ids['valids'] ) ){

    $missing_variations = array();
    foreach ( $ids['valids'] as $variation_id ) {
      if ( !array_key_exists( $variation_id, $totals['Variations']) )
      $missing_variations[] = $variation_id;
    }

    if( !empty($missing_variations) ){
      $totals['SyncError'] = new WP_Error( 'variations-out-of-sync', sprintf( __( "Product not synchronized: Invalid Product Data returned by QPilot for one or more variations.<br/>Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), $api_health_url, $products_report_url ) );
      $totals['AllSyncError'][] = $totals['SyncError'];
    }

  // Check if the total active variations returned by QPilot are less than those active in WooCommerce
  } else if ( $totals['TotalAutoshipVariations'] > count( $ids['valids'] ) ){

    $missing_variations = array();
    foreach ( $totals['AutoshipVariations'] as $autoship_variation_id ) {
      if ( !array_key_exists( $autoship_variation_id, $ids['valids'] ) )
      $missing_variations[] = $autoship_variation_id;
    }

    if( !empty($missing_variations) ){
      $totals['SyncError'] = new WP_Error( 'variations-out-of-sync', sprintf( __( "Product not synchronized: %d Active variation(s) not found in QPilot: %s.<br/>Please confirm your <a href=\"%s\">API connection is healthy</a> and ensure there are no issues with this product in the <a href=\"%s\">Autoship Cloud > Products</a> report.", "autoship" ), count( $missing_variations ), implode( ', ', $missing_variations ), $api_health_url, $products_report_url ) );
      $totals['AllSyncError'][] = $totals['SyncError'];
    }

  }

  return $totals;

}

// ==========================================================
// API Sync Delete Related Functions
// ==========================================================

/**
 * Sync Product on Active metadata change
 * If a product's __autoship_sync_active_enabled is changed to no the
 * product should be removed from QPilot if possible
 *
 * @see autoship_delete_product()
 *
 * @param int $id the WC Product ID.
 */
function autoship_delete_sync_product_on_delete_post( $id ){

  // If sync is enabled delete the product from QPilot
  if( 'yes' == autoship_sync_active_enabled( $id ) )
  autoship_cascade_delete_products ( $id );

}

/**
 * Cascade Deletes a products variations in QPilot before the product.
 *
 * @param mixed $id. Post ID of the product.
 */
function autoship_cascade_delete_products ( $id ){

  $product = wc_get_product( $id );

  if ( !$product )
  return;

  // Check if we need to delete all variations if this is a variable product.
  if ( 'variable' == $product->get_type() && $product->has_child() ) {

    // Delete the child variations
    foreach ( $product->get_children() as $variation )
    autoship_delete_product( $variation );

  }

  autoship_delete_product ( $id );

}

// ==========================================================
// API Sync Upsert Related Functions
// ==========================================================

/**
 * Main Product Update Sync function
 * @see autoship_push_product()
 *
 * @param int $id the WC Product ID.
 */
function autoship_sync_product( $id ){

  // Push Product only if sync active and is valid type and status.
  if( apply_filters( 'autoship_sync_product' , ( 'yes' == autoship_sync_active_enabled( $id ) ) && autoship_is_valid_sync_product( $id ), $id ) && !( did_action( 'wp_ajax_woocommerce_save_variations' ) && doing_action( 'woocommerce_update_product_variation' ) ) ){

    $overrides = ( 'yes' == autoship_schedule_order_enabled( $id ) && 'yes' == autoship_schedule_process_enabled( $id ) ) ?
    array( 'addToScheduledOrder' => true, 'processScheduledOrder' => true ) : array();

    $result = autoship_push_product( $id, $overrides );

    // Check if we're doing an upsert from the Edit Product screen and if there was any errors with the upsert
    if ( is_wp_error( $result ) &&
       ( doing_action( 'woocommerce_update_product' ) || doing_action( 'woocommerce_update_product_variation' ) || doing_action( 'woocommerce_save_product_variation' ) ) ){

      $error = sprintf( __("Your last product update did not synchronize with your connected QPilot Site successfully. Please review the error before attempting to \"Update & Sync\" again. <br/><strong>Error Details:</strong> %s <br/><strong>Download the full Error Details by exporting your site's Autoship Cloud Log File <a href=\"%s\">here</a>.</strong>" ), substr( $result->get_error_message(), 0, 280 ), admin_url( 'admin.php?page=autoship&tab=autoship-logs' ) );

      if( wp_doing_ajax() ){

        WC_Admin_Meta_Boxes::add_error( $error );

        // Log the notice for page load check
        autoship_add_message( $error, 'autoship_sync_error', 'ajax_autoship_sync_product', true );

      } else {;
        autoship_notice_handler( 'general_error' , $error, false , "autoship_product_upsert_messages" );
      }

    }

  }

}

/**
 * Main New Product Sync function
 *
 * @see autoship_push_product()
 *
 * @param int $id the WC Product ID.
 */
function autoship_sync_new_product_variation( $id ){

  // Push Product only if sync active and is valid type and status.
  if( apply_filters( 'autoship_sync_new_product_variation' , ( 'yes' == autoship_sync_active_enabled( $id ) ) && autoship_is_valid_sync_product( $id ), $id ) )
  autoship_set_product_availability( $id, 'yes' );

}

/**
 * Sync Product on Active metadata change
 * If a product's __autoship_sync_active_enabled is changed to no the
 * product should be sync'd once with QPilot to disable active flag
 *
 * @see autoship_push_product ()
 *
 * @param int $id the WC Product ID.
 */
function autoship_sync_product_on_active_change( $id, $name, $new, $original ){

  // If the active flag is switched update it in metadata
  if ( apply_filters( 'autoship_sync_product_on_active_change' , $original != $new, $id, $new, $original ) ){

    if ( 'yes' == $new ) {

      autoship_set_product_availability( $id, 'yes' );

    // We push the product once on active flag flip
    } else {

      autoship_push_product( $id );

    }

  }

}

/**
 * Upsert Sync'd Once on Product Type change from Valid to Invalid
 * If a product's type is changed & it's active we need to upsert to QPilot.
 *
 * @see autoship_push_product()
 *
 * @param WC_Product $product The WC Product changing.
 * @param string $old_type The old type.
 * @param string $new_type The new type.
 *
 */
function autoship_push_sync_product_on_type_change( $product, $old_type, $new_type ){

  // Check if this is a woocommerce product type change from a valid type.
  if ( ( $old_type != $new_type ) && in_array( $old_type, autoship_valid_product_sync_types() ) && !in_array( $new_type, autoship_valid_product_sync_types() ) ){

    // Get the product and see if it's being sync'd
    if ( apply_filters( 'autoship_push_sync_product_on_type_change' , 'yes' == autoship_sync_active_enabled( $product->get_id() ), $product->get_id(), $old_type, $new_type ) ){

      $overrides = ( 'yes' == autoship_schedule_order_enabled( $product->get_id() ) && 'yes' == autoship_schedule_process_enabled( $product->get_id() ) ) ?
      array( 'addToScheduledOrder' => true, 'processScheduledOrder' => true ) : array();

      autoship_push_product ( $product->get_id(), $overrides );

    }

  }

}

/**
 * Upserts a Sync'd Product on Status change
 * If a product's status is changed to a supported sync status
 * and it's active we need to upsert it to QPilot.
 *
 * @see autoship_push_product()
 *
 * @param string $new_status The new status.
 * @param string $old_status The old status.
 * @param WP_Post $post The WP Post being changed.
 */
function autoship_push_sync_product_on_status_change( $new_status, $old_status, $post ){

  // Check if this is a woocommerce product status change.
  if ( apply_filters( 'autoship_push_sync_product_on_status_change' , autoship_is_valid_sync_product( $post->ID ) &&
      ( $new_status != $old_status ) &&
      ( 'yes' == autoship_sync_active_enabled( $post->ID ) ), $post->ID, $new_status, $old_status ) ){


      $overrides = ( 'yes' == autoship_schedule_order_enabled( $post->ID ) && 'yes' == autoship_schedule_process_enabled( $post->ID ) ) ?
      array( 'addToScheduledOrder' => true, 'processScheduledOrder' => true ) : array();

      autoship_push_product ( $post->ID, $overrides );

  }

}

/**
 * Main Upload Function Creates the product / product variations in QPilot.
 *
 * Receives a Product to create, checks for the QPilot client,
 * validates the product via {@see autoship_productize_and_validate()}, and then upserts ( updates or creates ) the product and it's
 * variations in QPilot.
 *
 * @param mixed $id. Post object or post ID of the product.
 * @param array $overrides. Optional. Product Data to override in the upsert
 * @return bool|WP_Error True on success else false|wp_error
 */
function autoship_push_product ( $id, $overrides = array() ){

  // Create the QPilot client
  // Grab the client and only continue if connection exists.
  if ( empty( $client = autoship_get_default_client() ) || empty( $client->get_token_auth() ) )
  return false;

  // Get the product ( if invalid or wrong type or not correct status returns false )
  if ( ( $product = autoship_productize_and_validate( $id ) ) === false )
  return false;

  if ( did_action( 'autoship_push_product_' . $id ) )
  return false;

  // Save the originals
  $original_overrides = $overrides;

  try {

      // If this is a variation then lets initialize Autoship fields
      // If they aren't already
      if ( 'variation' == $product->get_type() ){

        // Since this is a variation we need to ensure the parent exists.
        // Currently the best route is to try and get the product via external id.
        try {
          $Qproduct = $client->get_product( $product->get_parent_id() );
        } catch ( Exception $e ) {

          // Since this is an create / update for a variation
          // and a 404 is thrown Assume the parent needs to be created
          // before moving on.
          if ( $e->getCode() == 404 ) {
           do_action( 'autoship_push_product_404_missing_variable_parent', $product, $product->get_parent_id() );
           autoship_push_product ( $product->get_parent_id(), $overrides );
          }
          autoship_log_entry( __( 'Autoship Products', 'autoship' ), $e->getMessage() );
        }

        // Initialize the variation - any missing fields add them.
        $product = autoship_init_variation_metafields( $product->get_id() );

      } else if ( 'variable' == $product->get_type() ){

        if ( !empty( $overrides ) )
        $overrides = array_merge( $overrides, array( 'addToScheduledOrder' => false, 'processScheduledOrder' => false ) );

      }

      // Gather the data to send
      if ( empty( $upsert_data = autoship_generate_product_upsert_data ( $product ) ) )
      return new WP_Error( 'Product Data Not Found', __( "The Supplied Product's data could not be found.", "autoship" ) );

      // Merge the products data with any supplied overrides
      $upsert_data = array_merge( $upsert_data, $overrides );

      // Send / Create product
      $result = $client->upsert_product( $upsert_data['id'], $upsert_data['title'], $upsert_data );

      /**
      * Check if the product is a variable product &
      * If variable & has children we should loop through
      * children to update if needed if we are not curring in save variations loop.
      * Metadata is not yet saved for variations if in loop
      */
      if ( !doing_action( 'wp_ajax_woocommerce_save_variations' ) ){

        $variations = autoship_get_available_variations( $product );

        // Update the child variations if they exist
        // Recursively push the variations.
        $last_error = NULL;
        foreach ( $variations as $variation ){
          
          if ( is_wp_error( $result = autoship_push_product( $variation, $original_overrides ) ) )
          $last_error = $result;
        
        }

        // If there was an error upserting 1 or more variations return it
        if ( !empty( $last_error ) )
        return $last_error;

      }

  } catch (Exception $e) {

    $notice = autoship_expand_http_code( $e->getCode() );
    autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'Product Upsert Failed Additional Details: %s', $e->getMessage() ) );
    return new WP_Error( 'Product Upsert Failed', $e->getMessage() );
  }

  // Fire this products specific action
  do_action( 'autoship_push_product_' . $id, $product, $overrides );

  // Fire the general push action
  do_action( 'autoship_after_push_product', $product );

  return true;

}

/**
 * Deletes a product / product variations in QPilot.
 *
 * @param mixed $product. WC Product ID or WC_Product Object.
 * @return bool|WP_Error True on success else false|wp_error
 */
function autoship_delete_product ( $product ){

  // Create the QPilot client
  // Grab the client and only continue if connection exists.
  if ( empty( $client = autoship_get_default_client() ) || empty( $client->get_token_auth() ) || !$product )
  return false;

  $id = !is_numeric( $product ) ? $product->get_id() : $product;

  try {

    // Delete product
    $client->delete_product( $id );

  } catch (Exception $e) {
    $notice = autoship_expand_http_code( $e->getCode() );
    autoship_log_entry( __( 'Autoship Products', 'autoship' ), sprintf( 'Product Delete Failed. Additional Details: %s', $e->getMessage() ) );
    return new WP_Error( 'Product Delete Failed', __( $notice['desc'], "autoship" ) );
  }

  do_action( 'autoship_after_delete_product', $id );
  return true;

}

/**
 * Updates the product / product variation Availability in QPilot.
 *
 * @param mixed $id. Post object or post ID of the product.
 * @param string $availability The product availability to update. Values should be
 *                             "AddToScheduledOrder,ProcessScheduledOrder", "AddToScheduledOrder",
 *                             "ProcessScheduledOrder" or empty string
 */
function autoship_update_product_availability ( $id, $availability = '' ){

  // Create the QPilot client
  // Grab the client and only continue if connection exists.
  if ( empty( $client = autoship_get_default_client() ) || empty( $client->get_token_auth() ) )
  return false;

  // Get the product ( if invalid or wrong type or not correct status returns false )
  if ( ( $product = autoship_productize_and_validate( $id ) ) === false )
  return false;

  try {

    // If this is a variation then lets initialize Autoship fields
    // If they aren't already
    if ( 'variation' == $product->get_type() ){


      // Since this is a variation we need to ensure the parent exists.
      // Currently the best route is to try and get the product via external id.
      try {
        $Qproduct = $client->get_product( $product->get_parent_id() );
      } catch ( Exception $e ) {

        // Since this is an create / update for a variation
        // and a 404 is thrown Assume the parent needs to be created
        // before moving on.
        if ( $e->getCode() == 404 ) {
         do_action( 'autoship_push_product_404_missing_variable_parent', $product, $product->get_parent_id() );
         autoship_push_product ( $product->get_parent_id() );
        }
        autoship_log_entry( __( 'Autoship Products', 'autoship' ), $e->getMessage() );
      }

      // Initialize the variation - any missing fields add them.
      $product = autoship_init_variation_metafields( $product->get_id() );

    }

    // Based on supplied value set the flags
    $addToScheduledOrder = strpos($availability, 'AddToScheduledOrder') !== false;
    $processScheduledOrder = strpos($availability, 'ProcessScheduledOrder') !== false;

    // update product availability
    $client->update_product_availability( $product->get_id() , $addToScheduledOrder, $processScheduledOrder );

  }
  catch (Exception $e) {
    autoship_log_entry( __( 'Autoship Products', 'autoship' ), $e->getMessage() );
  }

  do_action( 'autoship_after_update_product_availability', $product );

  return true;

}

/**
 * Updates the Active flag for all products in QPilot except for those supplied.
 *
 * @param string $enable.      'yes' to activate all else 'no'
 * @param array $exclude_ids   An array of product ids to exclude.
 */
function autoship_reset_all_products_activate ( $enable, $exclude_ids = array() ){

  // Create the QPilot client
  // Grab the client and only continue if connection exists.
  if ( empty( $client = autoship_get_default_client() ) || empty( $client->get_token_auth() ) )
  return false;

  $activate = 'yes' === $enable ? 'true' : 'false';

  try {

    // update product availability
    $result = $client->batch_activate_deactivate_products( $activate , $exclude_ids );

  }
  catch (Exception $e) {
    autoship_log_entry( __( 'Autoship Products', 'autoship' ), $e->getMessage() );
  }

  do_action( 'autoship_after_update_product_activate', $enable, $exclude_ids );

  return true;

}

/**
 * Triggers the Event when the page is loaded
 * @param WC_Product $product The WooCommerce Product.
 */
function autoship_trigger_js_event_on_default( $product ){

  if ( ( 'no' == apply_filters( 'autoship_default_product_schedule_options_choice_value' , 'no', $product ) ) &&
                 apply_filters( 'autoship_product_not_loaded_via_ajax' , !wp_doing_ajax(), $product ) )
  return;

  // Trigger our refresh event if the Autoship Option is set to default or the product is loaded via ajax
  ?><script>jQuery( function ($) { $(document).trigger( 'refresh_autoship_data', $ ) });</script><?php

}

/**
 * Retrieves a product from QPilot
 *
 * @param int    $group_id The Product Group ID.
 * @return stdClass The QPilot Product Group Object.
 */
function autoship_get_product_group_by_id( $group_id ){
  // Create QPilot client instance.
	$client = autoship_get_default_client();

	try {

    // Get The Product Group from QPilot.
		$product_group = $client->get_product_group( $group_id );
  } catch ( Exception $e ) {

    $notice = autoship_expand_http_code( $e->getCode() );
    $notice = new WP_Error( 'Product Group Retrieval Failed', __( $notice['desc'], "autoship" ) );
    autoship_log_entry( __( 'Autoship Product Group', 'autoship' ), sprintf( 'Retrieving the product group failed. Additional Details: Error Code %s - %s', $e->getCode(), $e->getMessage() ) );
    return $notice;

  }

  return $product_group;

}

// ==========================================================
// DEFAULT HOOKED ACTIONS
// ==========================================================

/**
 * WC Query Adjustments
 * @see autoship_handling_meta_query_keys()
 * @see autoship_handle_custom_sync_active_meta_query()
 */
add_filter( 'woocommerce_product_data_store_cpt_get_products_query', 'autoship_handling_meta_query_keys', 10, 3 );
add_filter( 'woocommerce_product_data_store_cpt_get_products_query', 'autoship_handle_custom_sync_active_meta_query', 10, 3 );

/**
 * Ajax Callback methods for the Edit Product Screen
 *
 * @see autoship_ajax_product_discount()
 */
add_action( 'wp_ajax_autoship_product_discount', 'autoship_ajax_product_discount' );
add_action( 'wp_ajax_nopriv_autoship_product_discount', 'autoship_ajax_product_discount' );

/**
 * Callback methods for Saving custom data on the Edit Product Screen
 *
 * @see autoship_save_product_custom_fields()
 * @see autoship_adjust_saved_product_meta_fields()
 */
add_action( 'woocommerce_process_product_meta', 'autoship_save_product_custom_fields', 10, 1 );
add_action( 'woocommerce_save_product_variation', 'autoship_save_product_custom_fields', 10, 2 );
add_filter( 'autoship_save_product_custom_fields_name_list', 'autoship_adjust_saved_product_meta_fields', 10, 1 );


/**
 * Hooked API Sync Check Functions
 *
 * ==== WP Error Codes =====
 * 'utc-out-of-sync'
 * 'product-type-out-of-sync'
 * 'stock-level-out-of-sync'
 * 'active-flag-out-of-sync'
 * 'valid-out-of-sync'
 * 'active-invalid-out-of-sync'
 * 'variations-out-of-sync'
 *
 *
 * @see autoship_product_summary_updatedutc_sync_check()
 * @see autoship_product_summary_type_sync_check()
 * @see autoship_product_summary_stocklevel_sync_check()
 * @see autoship_product_summary_active_sync_check()
 * @see autoship_product_summary_orphaned_invalid_variations_sync_check()
 * @see autoship_product_summary_active_invalid_sync_check()
 * @see autoship_product_summary_orphaned_variations_sync_check()
 * @see autoship_adjust_sync_error_to_warning()
 */
add_filter( 'autoship_product_summary_data_sync_error', 'autoship_product_summary_updatedutc_sync_check', 8, 4 );
add_filter( 'autoship_product_summary_data_sync_error', 'autoship_product_summary_type_sync_check', 9, 4 );
add_filter( 'autoship_product_summary_data_sync_error', 'autoship_product_summary_stocklevel_sync_check', 10, 4 );
add_filter( 'autoship_product_summary_data_sync_error', 'autoship_product_summary_active_sync_check', 11, 4 );
add_filter( 'autoship_product_summary_data_sync_error', 'autoship_product_summary_orphaned_invalid_variations_sync_check', 12, 5 );
add_filter( 'autoship_product_summary_data_sync_error', 'autoship_product_summary_active_invalid_sync_check', 12, 4 );

// Final error checks using totals.
add_filter( 'autoship_total_variation_product_summary_data', 'autoship_product_summary_orphaned_variations_sync_check', 10, 3 );

// Filter Errors for Warnings
add_filter( 'autoship_sync_status_error_as_warning', 'autoship_adjust_sync_error_to_warning', 10, 3 );

/**
 * Sync functions for when a product and/or variation is deleted
 *
 * @see autoship_delete_sync_product_on_delete_post()
 * @see autoship_delete_sync_product_on_delete_post()
 * @see autoship_delete_sync_product_on_delete_post()
 */
add_action( 'woocommerce_before_delete_product', 'autoship_delete_sync_product_on_delete_post', 10, 1 );
add_action( 'woocommerce_before_delete_product_variation', 'autoship_delete_sync_product_on_delete_post', 10, 1 );
add_action( 'before_delete_post', 'autoship_delete_sync_product_on_delete_post', 10, 1 );

/**
 * Sync functions for when a product and/or variation is added or updated
 *
 * @see autoship_sync_new_product_variation()
 * @see autoship_push_sync_product_on_type_change()
 * @see autoship_push_sync_product_on_status_change()
 * @see autoship_sync_product_on_active_change()
 */
// add_action( 'woocommerce_new_product', 'autoship_sync_new_product', 20 );
add_action( 'woocommerce_new_product_variation', 'autoship_sync_new_product_variation', 20 );
add_action( 'woocommerce_update_product', 'autoship_sync_product', 20 );
add_action( 'woocommerce_update_product_variation', 'autoship_sync_product', 20 );
add_action( 'woocommerce_save_product_variation', 'autoship_sync_product', 20 );
add_action( 'woocommerce_product_type_changed', 'autoship_push_sync_product_on_type_change', 10, 3);
add_action( 'transition_post_status', 'autoship_push_sync_product_on_status_change', 10, 3 );
add_action( 'autoship_update_product__autoship_sync_active_enabled_field', 'autoship_sync_product_on_active_change', 10, 4 );

/**
 * Hooked functions for the schedule options template so that the js trigger is
 * fired for ajax loaded templates.
 *
 * @see autoship_trigger_js_event_on_default()
 */
add_action( 'autoship_after_schedule_options_template', 'autoship_trigger_js_event_on_default', 10, 1 );
add_action( 'autoship_after_schedule_options_variable_template', 'autoship_trigger_js_event_on_default', 10, 1 );

/**
 * Hooked functions that expand on the upserted metadata
 * Used for build in integrations like Shipper HQ
 *
 * @see autoship_add_product_shipperhq_metadata_fields()
 */
add_filter( 'autoship_product_upsert_metadata', 'autoship_attach_product_shipperhq_metadata', 10, 2 );

/**
 * Hooked functions that adjust WC Rest API Response Product Data
 * Used for product overrides
 *
 * @see autoship_adjust_rest_product_data()
 */
add_filter( 'woocommerce_rest_prepare_product_object', 'autoship_adjust_rest_product_data', 10, 3 );
