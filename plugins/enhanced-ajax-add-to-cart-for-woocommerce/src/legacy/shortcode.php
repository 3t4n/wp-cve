<?php
class Legacy_EAA2C {
	/**
	 * Generates html and sets up variables for javascript calls
	 * 
	 * @since 1.0.0
	 * 
	 * @param $att_array	array	attributes passed by shortcode
	 * 
	 * @return via writing and echoing html, returns all the html for buttons
	 */
	public function display_variable_product_add_to_cart( $att_array ) {

		$a2c_html = '';
		$product = false;
		$product_id = false;
		$variation = false;
		$variation_id = false;
		$price = false;

		$button_text = '';
		$disable_button = '';

		$product_id = $att_array['product'];
		$title = strtolower( $att_array['title'] );
		$show_price = strtolower( $att_array['show_price'] );

		$product = wc_get_product( $product_id );
		
		if ( $att_array['variation'] != '' ) {
			$variation_id = $att_array['variation'];
			$variation = wc_get_product( $variation_id );
		}

		if ( ! is_null( $product ) && $product !== false ) {

			$price_display = get_woocommerce_currency_symbol() . $product->get_price();
			if ( $variation !== null && $variation !== false ) {
				$price_display = get_woocommerce_currency_symbol() . $variation->get_price();
			}

			if ( false != $variation_id )
				$a2c_html .= '<div class="woocommerce-variation-add-to-cart variations_button">';
			else
				$a2c_html .= '<div class="woocommerce-simple-add-to-cart simple_button">';
			
			
			/** Added conditional to display title of ajax button and quantity or not based on "title" attribute
			 *  if title=attributes then display only the attributes/variation qualifiers
			 *  if title=none dont display anything
			 *  else display the full variation name
			 * 
			 *  @since 1.1.0
			 */
			if ( $title == 'attributes' || $title == 'attribute' || $title == 'att' ) {

				$att_title = '';
				if ( strpos( $show_price, 'b' ) !== false ) {
					$att_title .= '<span class="ea-price">' . $price_display .'</span><span class="ea-separator"></span>';
				}
				if ( $variation instanceof WC_Product ) {
					foreach ( $variation->get_variation_attributes() as $key => $attribute )
						$att_title .= $attribute . ' ';
				}
				if ( strpos( $show_price, 'a' ) !== false ) {
					$att_title .= '<span class="ea-separator"></span><span class="ea-price"> ' . $price_display . ' </span>';
				}
				$a2c_html .= '<span class="ea-line ea-text">' . $att_title . '</span>';
			}
			elseif ( $title !== 'none' ) {
				$name = '';

				if ( strpos( $show_price, 'b' ) !== false ) {
					$name .= '<span class="ea-price">' . $price_display . ' </span><span class="ea-separator"></span>';
				}
				
				if ( $variation instanceof WC_Product && $title !== 'base' ) {
					$name .= $variation->get_name();
				}
				elseif ( $product instanceof WC_Product ) {
					$name .= $product->get_name();
				}
				if ( strpos( $show_price, 'a' ) !== false ) {
					$name .= '<span class="ea-separator"></span><span class="ea-price"> ' . $price_display . ' </span>';
				}
				$a2c_html .= '<span class="ea-line ea-text">' . $name . '</span>';
			}
			else {
				if ( strpos( $show_price, 'b' ) !== false ) {
					$name = '<span class="ea-line ea-text">' . $price_display . ' </span>';
					$a2c_html .= '<span class="ea-line">' . $name . '</span>';
				}
			}

			$a2c_html .= '<span class="ea-line quantity-container">';
			
			// Input values for the number input box and related fields
			$input_id    = 'product_' . ( false !== $variation_id ? $variation_id : $product_id ). '_qty';
			$input_name  = 'quantity';
			
			// Added version 1.1.0
			// If there was quantity specified, start processing for default quantity
			if ( $att_array['quantity'] != '' && $att_array['show_quantity'] != 'yes' ) {
				$a2c_html .= '<input type="hidden" class="input-text qty text" id="' . esc_attr( $input_id ) . '" name="' . esc_attr( $input_name ) .
								'" value="' . esc_attr( $att_array['quantity'] ) . '">';
			}
			if ( $att_array['quantity'] == '' || $att_array['show_quantity'] == 'yes' ) {
			
				// If there was a quantity specified on the shortcode, and there is to be number input box
				// Set the input value to be the quantity specified
				if ( $att_array['show_quantity'] == 'yes' && $att_array['quantity'] != '' )
					$input_value = $att_array['quantity'];
				// Otherwise continue as normal
				else
					$input_value = isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity();
				
				$max_value   = apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product );
				$min_value   = apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
				$step        = apply_filters( 'woocommerce_quantity_input_step', 1, $product );
				$pattern     = apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' );
				$inputmode   = apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' );

				$a2c_html .= '<div class="quantity">';
				$a2c_html .= '<input type="number" id="' . esc_attr( $input_id ) . '" class="input-text qty text" step="' . esc_attr( $step ) . '" min="' .
								esc_attr( $min_value ) . '" max="' . esc_attr( 0 < $max_value ? $max_value : '' ) . '" name="' . esc_attr( $input_name ) . 
								'" value="' . esc_attr( $input_value ) . '" title="' . esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) . 
								'" size="4" pattern="' . esc_attr( $pattern ) . '" inputmode="' . esc_attr( $inputmode ) . '" />';
				$a2c_html .= '</div>';
				// End quantity box and related variables usage
			}

			$a2c_html .= '</span>';

			if ( '' !== $att_array['button_text'] ) {
				$button_text = esc_html( wp_strip_all_tags( $att_array['button_text'] ) );
			}
			else {
				$button_text = esc_html( $product->single_add_to_cart_text() );
			}

			if ( $variation !== false && $variation instanceof WC_Product_Variation ) {
				if ( false === $variation->is_in_stock() ) {
					$button_text = __( 'Out of stock', 'enhanced-ajax-add-to-cart-wc' );
					$disable_button = 'disabled';
				}
			}
			elseif ( $product !== false && $variation === false && $product instanceof WC_Product ) {
				if ( false === $product->is_in_stock() ) {
					$button_text = __( 'Out of stock', 'enhanced-ajax-add-to-cart-wc' );
					$disable_button = 'disabled';
				}
			}
			if ( $variation_id !== false ) {
				$a2c_html .= '<button type="submit" class="eaa2c_add_to_cart_button variation button alt" data-pid="' . absint( $product->get_id() ) .
							'" data-vid="' . absint( $variation_id ) . '" ' . $disable_button . '>' . $button_text . '</button>';
			}
			else {
				$a2c_html .= '<button type="submit" class="eaa2c_add_to_cart_button simple button alt" data-pid="' . absint( $product->get_id() ) .
							'" ' . $disable_button . '>' . $button_text . '</button>';
			}

			if ( strpos( $show_price, 'r' ) !== false && strpos( $show_price, 'e' ) === false ) {
				$a2c_html .= '<span class="ea-separator"></span><span class="ea-price"> ' . $price_display . '</span>';
			}

			$a2c_html .= '</div>';
		}

		return $a2c_html;
	}
}