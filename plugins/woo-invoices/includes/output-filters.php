<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
	exit; 
}
    
    // some global type filters
    add_filter( 'sliced_totals_global_tax', 'sliced_set_woocommerce_tax_rate', 999, 2 );
    add_filter( 'sliced_invoice_totals', 'sliced_get_woocommerce_totals', 999, 2 );
    //add_filter( 'sliced_get_formatted_currency', 'sliced_get_woocommerce_formatted_total', 999 );

    // for payment gateways
    add_filter( 'sliced_get_invoice_sub_total_raw', 'sliced_get_woocommerce_invoice_sub_total_raw', 999, 2 );
    //add_filter( 'sliced_get_invoice_total_raw', 'sliced_get_woocommerce_invoice_total_raw', 999, 2 );
    add_filter( 'sliced_get_invoice_tax_raw', 'sliced_get_woocommerce_invoice_tax_raw', 999, 2 );
    add_filter( 'sliced_get_currency_symbol', 'sliced_get_woocommerce_currency_symbol', 999, 2 );

    // modify output in the admin area
	add_action( 'sliced_after_line_items_totals', 'sliced_woocommerce_hide_discount_field', 10, 2 );
    add_filter( 'sliced_display_the_line_totals', 'sliced_woocommerce_display_the_line_totals_admin', 999, 2 );
    
    // modify the HTML output on the front end
    add_action( 'sliced_quote_before_totals_table', 'sliced_display_woocommerce_totals' );
	add_action( 'sliced_invoice_before_totals_table', 'sliced_display_woocommerce_totals' );
    add_filter( 'sliced_invoice_line_items_output', 'sliced_display_woocommerce_line_items', 1, 1 );
    add_filter( 'sliced_to_address_output', 'sliced_display_woocommerce_to_address', 1, 1 );

    // hide the adjust filed. Not used in woocommerce
    add_filter( 'sliced_hide_adjust_field', 'sliced_woocommerce_hide_adjust_field', 999 );
	
	// kill global tax. Let woocommerce handle.
	add_filter( 'sliced_totals_global_tax', 'sliced_woo_invoices_return_zero', 1 );


	/*
	 * @TODO: consider if we even need this in a future version or not. (was a cool idea, but nobody has actually asked for it)
	 */
	// change button text
    // add_filter( 'woocommerce_product_add_to_cart_text', 'sliced_woocommerce_custom_button_text' ); 
    // add_filter( 'woocommerce_product_single_add_to_cart_text', 'sliced_woocommerce_custom_button_text' );

    // function sliced_woocommerce_custom_button_text() {
        
        // global $product;
    
        // $product_type = $product->product_type;

        // $wc_si  = get_option( 'woocommerce_sliced-invoices_settings' );
        // $text   = $wc_si['custom_button_text'];
        // $types  = $wc_si['button_product_types'] != "" ? $wc_si['button_product_types'] : array();
            
        // switch ( $product_type ) {
            // case 'external':
                // if( in_array( $product_type, $types) ) {
                    // return $text;
                // }
                // return __( 'Buy product', 'woocommerce' );
            // break;
            // case 'grouped':
                // if( in_array( $product_type, $types) ) {
                    // return $text;
                // }
                // return __( 'View products', 'woocommerce' );
            // break;
            // case 'simple':
                // if( in_array( $product_type, $types) ) {
                    // return $text;
                // }
                // return __( 'Add to cart', 'woocommerce' );
            // break;
            // case 'variable':
                // if( in_array( $product_type, $types) ) {
                    // return $text;
                // }
                // return __( 'Select options', 'woocommerce' );
            // break;
            // default:
                // return __( 'Read more', 'woocommerce' );
        // }    


    // }


    /**
     * Gets the invoice id relating to the Woocommerce order
     * @since   1.0
     */
    function sliced_woocommerce_get_invoice_id( $order_id ) {
        
        $args = array(
            'post_type' => array( 'sliced_invoice', 'sliced_quote' ),
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key'     => '_sliced_invoice_woocommerce_order',
                    'value'   => $order_id,
                    'compare' => '=',
                ),
                array(
                    'key'     => '_sliced_quote_woocommerce_order',
                    'value'   => $order_id,
                    'compare' => '=',
                ),
            ),
        );
        $the_query = new WP_Query( $args );
        if( $the_query->posts ) {
            $id = $the_query->posts[0];
            return $id;
        };

        return false;

    }    

    /**
     * Gets the order id relating to the sliced invoices invoice
     * @since   1.0
     */
    function sliced_woocommerce_get_order_id( $invoice_id = null ) {
        if ( ! $invoice_id ) {
            $invoice_id = sliced_get_the_id( $invoice_id );
        }
        $type = sliced_get_the_type($invoice_id);
        $order_id = get_post_meta( $invoice_id, '_sliced_' . $type . '_woocommerce_order', true );
        return $order_id;
    }

    /**
     * Gets the order relating to the sliced invoices invoice
     * @since   1.0
     */
    function sliced_woocommerce_get_order( $id = null ) {
        $order_id = sliced_woocommerce_get_order_id( $id );
        $order = wc_get_order( $order_id );
        return $order;
    }


	/**
	 * Display the totals in the admin area.
	 * 
	 * @version 1.2.4
	 * @since   1.0.0
	 */
    function sliced_woocommerce_display_the_line_totals_admin( $output ) {

        $order = sliced_woocommerce_get_order();

        if( ! isset( $order ) || $order == false || empty( $order ) )
            return $output;

        $amount   = sliced_woocommerce_order_amounts();

        $output = '<div class="alignright sliced_totals">';
        $output .= '<h3>' . sprintf( __( '%s Totals', 'woo-invoices' ), esc_html( sliced_get_label() ) ) .'</h3>';  
            // loop through each item
            foreach ( $order->get_order_item_totals() as $key => $total ) {

                $output .= '<div>' .  wp_kses_post( $total['label'] ) . '';
                $output .= '<span class="alignright">' . wp_kses_post( $total['value'] ) . '</span>';
                $output .= '</div>';

            }
        $output .= '</div>';

        return $output;

    }  

    /**
     * Display the totals in the admin area
     * @since  1.0
     */ 
    function sliced_woocommerce_order_amounts( $invoice_id = null ) {

		if ( ! $invoice_id ) {
			$invoice_id = sliced_get_the_id( $invoice_id );
		}
		
        $order_id = sliced_woocommerce_get_order_id( $invoice_id );
        $order = sliced_woocommerce_get_order( $invoice_id );

        if( ! $order) 
            return;

        $cart_tax       = $order->get_cart_tax( $order_id );
        $shipping_tax   = $order->get_shipping_tax( $order_id );
        $total_tax      = $order->get_total_tax( $order_id );
        $shipping       = $order->get_shipping_to_display( $order_id );
        $discount       = $order->get_discount_to_display( $order_id );
        $sub_total      = $order->get_subtotal_to_display( $order_id );
        $total          = $order->get_formatted_order_total( $order_id );
        $total_raw      = $order->get_total( $order_id );
        $subtotal_raw   = $order->get_subtotal( $order_id );
        $shipping_raw   = $order->get_total_shipping( $order_id );

        return array(
            'cart_tax'      => $cart_tax,
            'shipping_tax'  => $shipping_tax,
            'total_tax'     => $total_tax,
            'shipping'      => $shipping,
            'discount'      => $discount,
            'sub_total'     => $sub_total,
            'total'         => $total,
            'total_raw'     => $total_raw,
            'subtotal_raw'  => $subtotal_raw,
            'shipping_raw'  => $shipping_raw,
        );

    }


    /**
     * Get the raw total for payment gateways
     * @since  1.0
     */ 
    function sliced_get_woocommerce_invoice_sub_total_raw( $total, $invoice_id = 0 ) {
        $order_id = sliced_woocommerce_get_order_id( $invoice_id );
        if( ! isset( $order_id ) || $order_id == false || empty( $order_id ) )
            return $total;
        $totals     = sliced_woocommerce_order_amounts( $invoice_id );
        $sub_total  = round( $totals['subtotal_raw'], sliced_get_decimals());
        $shipping   = round( $totals['shipping_raw'], sliced_get_decimals());
        $total      = $sub_total + $shipping;
        return $total;
    }

    /**
     * Get the raw total for payment gateways
     * @since  1.0
     */ 
    function sliced_get_woocommerce_invoice_total_raw( $total, $invoice_id = 0 ) {
        $order_id = sliced_woocommerce_get_order_id( $invoice_id );
        if( ! isset( $order_id ) || $order_id == false || empty( $order_id ) )
            return $total;
        $totals = sliced_woocommerce_order_amounts( $invoice_id );
        $total  = round( $totals['total_raw'], sliced_get_decimals());
        return $total;
    }

    /**
     * Get the raw total for payment gateways
     * @since  1.0
     */ 
    function sliced_get_woocommerce_invoice_tax_raw( $total, $invoice_id = 0 ) {
        $order_id = sliced_woocommerce_get_order_id( $invoice_id );
        if( ! isset( $order_id ) || $order_id == false || empty( $order_id ) )
            return $total;
        $totals = sliced_woocommerce_order_amounts( $invoice_id );
        $total = round( $totals['total_tax'], sliced_get_decimals());
        return $total;
    }

	/**
	 * Get the currency symbol for payment gateways.
	 * 
	 * @version 1.2.4
	 * @since   1.0.0
	 */
    function sliced_get_woocommerce_currency_symbol( $symbol, $invoice_id = 0 ) {
        $order_id = sliced_woocommerce_get_order_id( $invoice_id );
        if( ! isset( $order_id ) || $order_id == false || empty( $order_id ) )
            return $symbol;
        $symbol = html_entity_decode( get_woocommerce_currency_symbol() );
        return $symbol;
    }

    /**
     * Get the formatted total
     * @since  1.0
     */ 
    function sliced_get_woocommerce_formatted_total( $formatted ) {
        $order_id = sliced_woocommerce_get_order_id();
        if( ! isset( $order_id ) || $order_id == false || empty( $order_id ) )
            return $formatted;
        $order = new WC_Order($order_id);
		
        $formatted = $order->get_formatted_order_total();
        return $formatted;
    }
	
	/**
	 * Get order item metas
	 * @since 1.1
	 */
	function sliced_get_woocommerce_order_item_metas( $item ) {
		$item_metas = array();
		$item_meta_data = $item->get_meta_data();
		foreach ( $item_meta_data as $key => $value ) {
			$data = $value->get_data();
			$item_metas[ $data['key'] ] = $data['value'];
		}
		return apply_filters( 'sliced_woocommerce_order_item_metas', $item_metas );
	}

    /**
     * Do prices include tax
     * @since  1.0
     */ 
    function sliced_woocommerce_prices_include_tax() {
        $includes = get_option( 'woocommerce_prices_include_tax' );
        if($includes == 'yes') {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Set the tax rate to 0 if tax is not enabled in Woocommerce,
     * and only on Woocommerce invoices/orders.
     * @since  1.0
     */ 
    function sliced_set_woocommerce_tax_rate( $rate, $id ) {
        $order_id = sliced_woocommerce_get_order_id( $id );
        if( $order_id != "" && ! wc_tax_enabled() ) {
            return 0;
        }
        return $rate;
    }


    /**
     * Work out the totals and tax rates from woocommerce and filter this on each order.
     * Used in the admin area and the front end
     * @since  1.0
     */ 
    function sliced_get_woocommerce_totals( $data, $invoice_id = 0 ) {

        $order = sliced_woocommerce_get_order( $invoice_id );

        if( ! isset( $order ) || $order == false || empty( $order ) ) {
            return $data;
		}

		$amount = sliced_woocommerce_order_amounts( $invoice_id );
		$total  = $amount['total_raw'];

		if( wc_tax_enabled() ) {
			$tax    = $order->get_total_tax();
			// work out the tax rate of the total order
			$tax_rate = $tax != 0 ? ($tax / $total) * 100 : 0;
			$data['tax'] = $tax;
		}

		$data['total'] = $total;
		
		// Fix for old get_totals(), i.e. Sliced Invoices < 3.6.0
		// will be removed at some point in the future
		if ( defined( 'SLICED_VERSION' ) && version_compare( SLICED_VERSION, '3.6.0', '<' ) ) {
			if ( isset( $data['deposit'] ) && is_array( $data['deposit'] ) ) {
				$data['total'] = $data['deposit']['total'];
			}
		}
		
        return $data;
    }
	
	/**
	 * Zero out Sliced Invoices tax setting in favor of WooCommerce tax setting, but only if this invoice is
	 * associated with a WooCommerce Order
	 * @since 1.0.2
	 */
	function sliced_woo_invoices_return_zero( $tax ) {
		global $post;
		if (
			isset( $post->post_type ) &&
			$post->post_type === 'sliced_invoice' &&
			sliced_woocommerce_get_order_id( $post->ID ) > ''
		) {
			return 0;
		}
		return $tax;
	}


    /**
     * Display the totals on the invoice, direct form the Woocommerce order.
     * @since  1.0
     */ 
    function sliced_display_woocommerce_totals( $output ) {
        
        $order = sliced_woocommerce_get_order();

        if( ! isset( $order ) || $order == false || empty( $order ) )
            return $output;

        $output = null;
        $output ='<table class="table table-sm table-bordered woo">';
            $output .= '<tbody>';

            foreach ( $order->get_order_item_totals() as $key => $total ) {

                $output .= '<tr class="' . sanitize_title( $total['label'] ) . '">';
                    $output .= '<th scope="row">' .  wp_kses_post( $total['label'] ) . '</th>';
                    $output .= '<td>' . wp_kses_post( $total['value'] ) . '</td>';
                $output .= '</tr>';

            }

            $output .= '</tbody>';

        $output .= '</table>';

        echo $output;

    }

	/**
	 * Display the totals on the invoice, direct form the Woocommerce order.
	 * 
	 * @version 1.2.5
	 * @since   1.0.0
	 */
    function sliced_display_woocommerce_line_items( $output ) {
        
        $order = sliced_woocommerce_get_order();

        if( ! isset( $order ) || $order == false || empty( $order ) )
            return $output;
			
		$settings = get_option( 'woocommerce_sliced-invoices_settings', true );

        $output = '<table class="table table-sm table-bordered table-striped">
            <thead>
                <tr>
                    <th class="qty"><strong>' . __( 'Hrs/Qty', 'woo-invoices' ) . '</strong></th>
                    <th class="service"><strong>' . __( 'Service', 'woo-invoices' ) . '</strong></th>
                    <th class="rate"><strong>' . __( 'Rate/Price', 'woo-invoices' ) . '</strong></th>
					<th class="total"><strong>' . __( 'Sub Total', 'woo-invoices' ) . '</strong></th>
                </tr>
            </thead>
            <tbody>';
			
			//$sliced_items = sliced_get_invoice_line_items();
			
			$count = 0;
			foreach( $order->get_items() as $item_id => $item ) {

                $class = ($count % 2 == 0) ? "even" : "odd";
				
				if ( version_compare( WC()->version, '4.4.0', '>=' ) ) {
					$product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );
				} else {
					$product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
				}
                
				//$purchase_note = get_post_meta( $product->get_id(), '_purchase_note', true );

                $is_visible = $product && $product->is_visible();

                $output .= '<tr class="row_' . esc_attr( $class ) . ' sliced-item">';
                            
                    $output .= '<td class="qty">' . apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '%s', wp_kses_post( $item['qty'] ) ) . '</strong>', $item ) . '</td>';
                    
                    $output .= '<td class="service">' . apply_filters( 'woocommerce_order_item_name', $is_visible ? sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ) : $item['name'], $item, $is_visible );
					
					if ( $product && isset( $settings['show_order_item_sku'] ) && $settings['show_order_item_sku'] === 'yes' ) {
						$sku = $product->get_sku();
						if ( $sku > '' ) {
							$output .= '<br/><span class="description"><strong>SKU:</strong> '.$sku.'</span>';
						}
					}
					
					if ( isset( $settings['show_order_item_meta'] ) && $settings['show_order_item_meta'] === 'yes' ) {
						$output .= '<br/><span class="description">';
						$item_metas = sliced_get_woocommerce_order_item_metas( $item );
						foreach ( $item_metas as $key => $value ) {
							if ( $value > '' ) {
								$output .= '<strong>'.$key.':</strong> '.$value.'<br />';
							}
						}
						$output .= '</span>';
					}
					
					/*
					if ( isset( $sliced_items["description"] ) ) {
						$output .= '<br/><span class="description">' . wpautop( wp_kses_post( $item["description"] ) ) . '</span>';
					}
					*/
					
					$output .= '</td>';
                    
                    $output .= '<td class="rate">' . wp_kses_post( wc_price( $order->get_item_subtotal( $item ) ) ) . '</td>';

                    $output .= '<td class="total">' . wp_kses_post( $order->get_formatted_line_subtotal( $item ) ) . '</td>';

                $output .= '</tr>';

				$count++; 
            } 

        $output .= '</tbody></table>';

        return $output;
    }

	/**
	 * Display the 'to' address using Woocommerce customer.
	 * 
	 * @version 1.2.4
	 * @since   1.0.0
	 */
    function sliced_display_woocommerce_to_address( $output ) {
        
        $order = sliced_woocommerce_get_order();

        if( ! isset( $order ) || $order == false || empty( $order ) )
            return $output;

        $output = null;

        $output .= '<div class="col-xs-12 col-sm-4">';
        $output .= '<div class="to"><strong>' . __( 'Billing address', 'woo-invoices' ) . '</strong></div>';
        $output .= ( $address = $order->get_formatted_billing_address() ) ? '<div class="address">' . $address . '</div>' : __( 'N/A', 'woo-invoices' );
        $output .= '</div>';

        if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) :
            $output .= '<div class="col-xs-12 col-sm-4">';
            $output .= '<div class="to"><strong>' . __( 'Shipping address', 'woo-invoices' ) . '</strong></div>';
            $output .= ( $address = $order->get_formatted_shipping_address() ) ? '<div class="address">' . $address . '</div>' : __( 'N/A', 'woo-invoices' );
            $output .= '</div>';
        endif; 

        return $output;
    }
    
    /**
     * Hide adjust field
     *
     * @since   ?
     */
    function sliced_woocommerce_hide_adjust_field( $value ) {
        if ( sliced_woocommerce_get_order_id( null ) ) {
            return true;
		}
		return $value;
    }
    
    /**
     * Hide discount field
     *
	 * @version 1.2.3
     * @since   1.1.4
     */
    function sliced_woocommerce_hide_discount_field( $line_items_group_id, $line_items ) {
        if ( sliced_woocommerce_get_order_id( null ) ) {
			$line_items->remove_field( '_sliced_discount' );               // for Sliced Invoices >= 3.9.0
			$line_items->remove_field( '_sliced_discount_type' );          // for Sliced Invoices >= 3.9.0
			$line_items->remove_field( '_sliced_discount_tax_treatment' ); // for Sliced Invoices >= 3.9.0
			$line_items->remove_field( 'sliced_invoice_discount' );        // for Sliced Invoices < 3.9.0
		}
    }
