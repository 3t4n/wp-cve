<?php

/**
*
* Action hooks and filters related to WooCommerce Product Add-Ons.
* @version 3.1.7
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* WooCommerce Product Add-Ons compatibilty
* Adds an option to multiply addon cost by booking duration
* @param WP_POST - $post
* @param array- $addon
* @param int - $loop
*
**/
function wceb_pao_multiply_option( $post, $addon, $loop ) {

    $multiply_addon = isset( $addon['multiply_by_booking_duration'] ) ? $addon['multiply_by_booking_duration'] : 0;

    ?>

	<div class="wc-pao-addons-secondary-settings show_if_bookable">
        <div class="wc-pao-row wc-pao-addon-multiply-setting">
            <label for="wc-pao-addon-multiply-<?php echo esc_attr( $loop ); ?>">
                <input type="checkbox" id="wc-pao-addon-multiply-<?php echo esc_attr( $loop ); ?>" name="product_addon_multiply[<?php echo esc_attr( $loop ); ?>]" <?php checked( $multiply_addon, 1 ); ?> />
                    <?php esc_html_e( 'Multiply addon cost by booking duration?', 'woocommerce-easy-booking-system' ); ?>
            </label>
        </div>
    </div>

    <?php

}

add_action( 'woocommerce_product_addons_panel_before_options', 'wceb_pao_multiply_option', 10, 3 );

/**
*
* WooCommerce Product Add-Ons compatibilty
* Saves option to multiply addon cost by booking duration
* @param array - $data
* @param int - $i
* @return array - $data
*
**/
function wceb_pao_save_multiply_option( $data, $i ) {

    $multiply_addon = isset( $_POST['product_addon_multiply'] ) ? $_POST['product_addon_multiply'] : array();

    $data['multiply_by_booking_duration'] = isset( $multiply_addon[$i] ) ? 1 : 0;

    // Also have multiply option in each addon option to display "/ day" price.
    foreach ( $data['options'] as $i => $option ) {
        $data['options'][$i]['multiply'] = $data['multiply_by_booking_duration'];
    }

    return $data;

}

add_filter( 'woocommerce_product_addons_save_data', 'wceb_pao_save_multiply_option', 10, 2 );

/**
*
* WooCommerce Product Add-Ons compatibilty
* Displays a custom price if the addon cost is multiplied by booking duration
* @param str $price - Product price
* @param array - $addon
* @param int - $key
* @param str - $type
* @return str $price - Custom or base price
*
**/
function wceb_pao_product_addons_price( $price, $addon, $key, $type ) {
    global $product;

    // Small verification because WC Product Addons is very well coded (...) and the same filter is used to display price in input label and in html data attribute.
    if ( is_float( $price ) ) {
        return $price;
    }

    if ( wceb_is_bookable( $product ) ) {

        $adjust_price = ! empty( $addon['adjust_price'] ) ? $addon['adjust_price'] : '';

        if ( $adjust_price != '1' ) {
            return $price;
        }

        $maybe_multiply = isset( $addon['multiply_by_booking_duration'] ) ? $addon['multiply_by_booking_duration'] : 0;

        if ( $maybe_multiply ) {
            
            $addon_price  = ! empty( $addon['price'] ) ? $addon['price'] : '';
            $price_prefix = 0 < $addon_price ? '+' : '';
            $price_raw    = apply_filters( 'woocommerce_product_addons_price_raw', $addon_price, $addon );

            if ( ! $price_raw ) {
                return $price;
            }

            $price_type = ! empty( $addon['price_type'] ) ? $addon['price_type'] : '';

            if ( 'percentage_based' === $price_type ) {
                $content = $price_prefix . $price_raw . '%';
            } else {
                $content = $price_prefix . wc_price( WC_Product_Addons_Helper::get_product_addon_price_for_display( $price_raw ) );
            }

            $price_suffix = wceb_get_product_price_suffix( $product );

            $wceb_addon_price = apply_filters(
                'easy_booking_price_html',
                $content . '<span class="wceb-price-format">' . esc_html( $price_suffix ) . '</span>',
                $product,
                $content
            );

            $price = '(' . $wceb_addon_price . ')';

        }

    }

    return $price;

}

add_filter( 'woocommerce_product_addons_price', 'wceb_pao_product_addons_price', 10, 4 );

/**
*
* WooCommerce Product Add-Ons compatibilty
* Displays a custom price if the addon option cost is multiplied by booking duration
* This is for addons with options (multiple choice or checkbox)
*
* @param str $price - Product price
* @param array - $option
* @param int - $key
* @param str - $type
* @return str $price - Custom or base price
*
**/
function wceb_pao_product_addons_option_price( $price, $option, $key, $type ) {
    global $product;

    // Small verification because WC Product Addons is very well coded (...) and the same filter is used to display price in input label and in html data attribute.
    if ( is_float( $price ) ) {
        return $price;
    }

    if ( wceb_is_bookable( $product ) ) {

        $maybe_multiply = isset( $option['multiply'] ) ? $option['multiply'] : 0;

        if ( $maybe_multiply ) {
            
            $option_price = ! empty( $option['price'] ) ? $option['price'] : '';
            $price_prefix = 0 < $option_price ? '+' : '';
            $price_raw    = apply_filters( 'woocommerce_product_addons_option_price_raw', $option_price, $option );

            if ( ! $price_raw ) {
                return $price;
            }

            $price_type = ! empty( $option['price_type'] ) ? $option['price_type'] : '';

            if ( 'percentage_based' === $price_type ) {
                $content = $price_prefix . $price_raw . '%';
            } else {
                $content = $price_prefix . wc_price( WC_Product_Addons_Helper::get_product_addon_price_for_display( $price_raw ) );
            }

            $price_suffix = wceb_get_product_price_suffix( $product );

            $wceb_addon_price = apply_filters(
                'easy_booking_price_html',
                $content . '<span class="wceb-price-format">' . esc_html( $price_suffix ) . '</span>',
                $product,
                $content
            );

            $price = '(' . $wceb_addon_price . ')';

        }

    }

    return $price;

}

add_filter( 'woocommerce_product_addons_option_price', 'wceb_pao_product_addons_option_price', 10, 4 );

/**
*
* WooCommerce Product Add-Ons compatibilty.
* Maybe add additional costs to booking price after selecting dates (not in cart).
* @param str - $price
* @param int - $_product_id
* @param array - $data
* @return str - $price
*
**/
function wceb_pao_add_selected_addons_cost( $price, $_product_id, $data ) {

    $_product = wc_get_product( $_product_id );

    // Get additional cost (from WooCommerce Product Addons)
    $additional_cost = EasyBooking\Pao_Functions::get_selected_addons_cost( $_product, $data, $price );

    if ( $additional_cost && ! empty( $additional_cost ) ) {
        $price += $additional_cost;
    }

    return wc_format_decimal( $price );

}

add_filter( 'easy_booking_new_price_to_display', 'wceb_pao_add_selected_addons_cost', 10, 3 );
add_filter( 'easy_booking_new_regular_price_to_display', 'wceb_pao_add_selected_addons_cost', 10, 3 );

/**
*
* WooCommerce Product Add-Ons compatibilty.
* Don't adjust addons price in cart if product is bookable. We will be calculating it later.
* @param bool - $adjust
* @param array - $cart_item_data
* @return bool - $adjust
*
**/
function wceb_pao_product_addons_adjust_price( $adjust, $cart_item_data ) {

    if ( isset( $cart_item_data['_booking_price'] ) ) {
        $adjust = false;
    }

    return $adjust;

}

add_filter( 'woocommerce_product_addons_adjust_price', 'wceb_pao_product_addons_adjust_price', 99, 2 );

/**
*
* WooCommerce Product Add-Ons compatibilty.
* Maybe adjust addons prices and booking price.
* @param array - $cart_item
* @return array - $cart_item
*
**/
function wceb_pao_cart_item( $cart_item ) {

    // Check if there are addons in cart
    if ( isset( $cart_item['addons'] ) && ! empty( $cart_item['addons'] ) ) {

        // Store booking price before addons for future use.
        if ( ! isset( $cart_item['addons_price_before_calc'] ) ) {
            $cart_item['addons_price_before_calc'] = (float) $cart_item['_booking_price'];
        }
        
        $booking_price = $cart_item['addons_price_before_calc'];

        foreach ( $cart_item['addons'] as $i => $addon ) {

            // The function runs several times so we need to make sure to get "raw" addon price.
            $addon_price = isset( $addon['raw_addon_price'] ) ? $addon['raw_addon_price'] : $addon['price'];

            // Calculate addon price depending on booking duration.
            $addon_price = EasyBooking\Pao_Functions::calc_addon_cost( $addon_price, $addon['price_type'], $booking_price, $cart_item['_booking_duration'], $cart_item['quantity'], $addon['multiply'] );

            // Add addon price to product booking price.
            $booking_price += $addon_price; 

            // For percentage based addons, cost will never change (for example: 10%) so we adjust only flat fees and quantity based.
            if ( 'percentage_based' !== $addon['price_type'] ) {

                // Store addon price before updating it to new calculated price.
                $cart_item['addons'][$i]['raw_addon_price'] = $addon['price'];

                // Store new addon price.
                $cart_item['addons'][$i]['price'] = strval( $addon_price );

            }
            
        }

        $cart_item['_booking_price'] = (float) $booking_price;

    }

    return $cart_item;

}

add_filter( 'easy_booking_cart_item', 'wceb_pao_cart_item', 10, 1 );

/**
*
* WooCommerce Product Add-Ons compatibilty
* Store multiply by booking duration in each addon when adding a product to cart
*
* @param array - $data
* @param array - $addon
* @param int - $product_id
* @param array - $post_data
* @return array - $data
*
**/
function wceb_pao_product_addon_cart_item_data( $data, $addon, $product_id, $post_data ) {

    $maybe_multiply = isset( $addon['multiply_by_booking_duration'] ) ? $addon['multiply_by_booking_duration'] : 0;

    foreach ( $data as $i => $addon_data ) {
        $data[$i]['multiply'] = intval( $maybe_multiply );
    }

    return $data;

}

add_filter( 'woocommerce_product_addon_cart_item_data', 'wceb_pao_product_addon_cart_item_data', 10, 4 );