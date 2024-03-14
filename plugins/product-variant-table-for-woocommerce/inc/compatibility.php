<?php


/**
 * ==============================================
 * Rey Theme and Rey Core Plugin Support
 * ==============================================
 */
if ( class_exists('ReyCore') && PVTFW_COMMON::pvtfw_get_options()->cart_redirect == '' ):
    if( !function_exists('reycore_support') ):
        function reycore_support(){
            return false;
        }
        add_filter( 'pvtfw_added_cart_filter', 'reycore_support' );
    endif;
endif;


/**
 * ==============================================
 * Divi Theme Support
 * ==============================================
 */

if( !function_exists( 'pvtfw_divi_mini_cart_fragment' ) ):

    function pvtfw_divi_mini_cart_fragment( $fragments ) {

        $theme = wp_get_theme();

        if( WC()->cart->get_cart_contents_count() == 1 ){
            $item_text = __( 'Item', 'product-variant-table-for-woocommerce' );
        }
        else{
            $item_text = __( 'Items', 'product-variant-table-for-woocommerce' );
        }

        if ( 'Divi' == $theme->name || 'Divi' == $theme->parent_theme ) {
            $fragments['.et-cart-info span'] = "<span>" . WC()->cart->get_cart_contents_count() . " $item_text</span>";
            return $fragments; 
        }

        return $fragments;
    }
    add_filter( 'woocommerce_add_to_cart_fragments', 'pvtfw_divi_mini_cart_fragment', 10, 1 ); 

endif;


/**
 * =============================================================================
 * Whols plugin by Woolentor support 
 * =============================================================================
 */


if ( !function_exists( 'pvt_whols_plugin_support' ) && PVTFW_COMMON::check_plugin_state('whols') ){

    

    function pvt_whols_plugin_support( $price_html, $single_variation ){

        $whols_plugin_options = (array) get_option( 'whols_options' );

        if( $whols_plugin_options['price_type_1_properties']['enable_this_pricing'] == 0  ){
            return $single_variation->get_price_html();
        }

        $wholesale_status    = whols_is_on_wholesale( $single_variation );
        $enable_this_pricing = $wholesale_status['enable_this_pricing'];
        $price_type          = $wholesale_status['price_type'];
        $price_value         = $wholesale_status['price_value'];
        $minimum_quantity    = $wholesale_status['minimum_quantity'];

        if( $enable_this_pricing ){
            if($price_type == 'flat_rate'){
                $price_per_unit = $price_value;
            } elseif($price_type == 'percent'){
                $price_per_unit = whols_get_percent_of( $single_variation->get_regular_price(), $price_value );
            }

            $whols_price = '<span class="price">' .  wc_price( $price_per_unit ) . '</span>';
        }
        
        return $whols_price;

    }
    add_filter( 'pvtfw_price_html', 'pvt_whols_plugin_support', 20, 2 );

}


/**
 * =============================================================================
 * Applying `get_price_html` by adding filter for backward compatibility
 * =============================================================================
 */


if ( !function_exists( 'pvt_get_price_html' ) ){

    function pvt_get_price_html( $price_html, $single_variation ){

        /**
         * =============================================================================
         * Condition removed and added to PVTFW_COMMON line number: 193
         * @since 1.4.15
         * =============================================================================
         */

        return $single_variation->get_price_html();

    }
    add_filter( 'pvtfw_price_html', 'pvt_get_price_html', 10, 2 );

}

/**
 * =============================================================================
 * Initialize +/- button by PVT
 * @since 1.4.14
 * Updated it in version 1.4.15
 * =============================================================================
 */

if( !function_exists( 'pvt_display_qty_plus_minus_button' ) ){

    function pvt_display_qty_plus_minus_button( $args ){

        if( is_array( $args ) ){

            ob_start();

            echo '<div class="pvt-qty-input">';
                echo '<button class="qty-count qty-count--minus" data-action="minus" type="button">-</button>';

                /**
                 * =============================================================================
                 * woocommerce_quantity_input($args) removed added pvtfw_quantity_input hook
                 * @since 1.4.15
                 * =============================================================================
                 */
                do_action('pvtfw_quantity_input', $args);

                echo '<button class="qty-count qty-count--add" data-action="add" type="button">+</button>';
            echo '</div>';

            return ob_get_clean();

        }

    }

    add_filter( 'pvt_print_plus_minus_qty_field', 'pvt_display_qty_plus_minus_button', 10, 1 );

}


/**
 * =============================================================================
 * PVT self quantity markup
 * @since 1.4.15
 * =============================================================================
 */

if( !function_exists( 'pvt_quantity_input_markup' ) ){

    function pvt_quantity_input_markup( $args ){

        $label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );

        $read_only = $args['readonly'] ? 'readonly="readonly"' : '';

        /**
         * The input type attribute will generally be 'number'. An exception is made for non-hidden readonly inputs: in this case we set the
         * type to 'text' (this prevents most browsers from rendering increment/decrement arrows, which are useless
         * and/or confusing in this context).
         */
        $type = 'number';
        $type = $args['readonly'] ? 'text' : $type;

        echo sprintf( '
            <div class="pvtfw-quantity">
                <label class="screen-reader-text" for="%3$s">%7$s</label>
                <input
                    type="%1$s"
                    %2$s
                    id="%3$s"
                    class="%4$s"
                    name="%5$s"
                    value="%6$s"
                    aria-label="%8$s"
                    size="4"
                    min="%9$s"
                    max="%10$s"
                    %11$s
                />
            </div>',
           $type,
           $read_only,
           esc_attr( $args['input_id'] ),
           esc_attr( join( ' ', (array) $args['classes'] ) ),
           esc_attr( $args['input_name'] ),
           esc_attr( $args['input_value'] ),
           $label,
           __( 'Product quantity', 'woocommerce' ),
           esc_attr( $args['min_value'] ),
           esc_attr( 0 < $args['max_value'] ? $args['max_value'] : '' ),
           ( ! $args['readonly'] ) ? sprintf('
                step="%1$s"
                placeholder="%2$s"
                inputmode="%3$s"
                autocomplete="%4$s" ',
                // Values of sprintf
                esc_attr( $args['step'] ),
                esc_attr( $args['placeholder'] ),
                esc_attr( $args['inputmode'] ),
                esc_attr( isset( $args['autocomplete'] ) ? $args['autocomplete'] : 'on' )
            ) : ''
        )."<input type='hidden' name='hidden_price' class='hidden_price' value='".$args['price']."'> <input type='hidden' name='pvt_variation_availability' value='".$args['availability']."'>"; // Additional hidden field to control the price and availability
    }

    add_action( 'pvtfw_quantity_input', 'pvt_quantity_input_markup', 10, 1 );

}
