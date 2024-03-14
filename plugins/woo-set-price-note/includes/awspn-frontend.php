<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Woo Set Price Note
 *
 * Allows user to get WooCommerce Set Price Note.
 *
 * @class   Woo_Set_Price_Note_Frontend 
 */


class Woo_Set_Price_Note_Frontend {



	/**
	 * Init and hook in the integration.
	 *
	 * @return void
	 */


	public function __construct() {
		$this->id                 = 'Woo_Set_Price_Note_Frontend';
		$this->method_title       = __( 'WooCommerce Set Price Note', 'woo-set-price-note' );
		$this->method_description = __( 'WooCommerce Set Price Note', 'woo-set-price-note' );	
	
		
		// Filters
		// Add saved price note
		add_filter( 'woocommerce_get_price_html', array( $this, 'awspn_display_price_note'), 99, 2 );
		
		// Display price note on cart page
		add_filter( 'woocommerce_cart_item_price', array( $this, 'awspn_display_cart_price_note'), 10, 2 );
		

		// Display price note on checkout page
		add_filter( 'woocommerce_cart_item_name', array( $this, 'awspn_display_checkout_price_note'), 10, 2 );

		// Add price note to the cart item meta
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'awspn_add_price_note_to_cart_item'), 10, 3 );

		// Add price note to the order item meta
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'awspn_add_price_note_to_order_item_meta'), 10, 3 );


		// Add item meta from cart to order
		if ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ) {
			add_action( 'woocommerce_add_order_item_meta', array( $this, 'awspn_add_price_note_to_order_item_meta' ),    10, 3 );
		} else {
			add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'awspn_save_values_in_item' ), 10, 4 );
			add_action( 'woocommerce_new_order_item', array( $this, 'awspn_add_price_note_to_order_item_meta_wc3' ), 10, 3 );
		}

		// Load small styling
		add_action('wp_footer', array( $this, 'awspn_print_price_note_style'));
		
	}


	/**
	 * Loading  price note options on WooCommerce product section.
	 *
	 * @return string
	 */


	public function awspn_display_price_note( $price, $product ){
	    //woocommerce 3.0 compatible
	    if(method_exists($product, 'get_id')){
	    	$product_id = $product->get_id();	    	
	    }else{
	    	$product_id = $product->id;
	    }
	 	return $this->awspn_format_price_note($product_id, $price);
	}

	public function awspn_display_cart_price_note( $price, $cart_item ){
		//print_pre($cart_item['price-note-text']);
		$product_id = $cart_item['product_id'];
	    return $this->awspn_format_price_note($product_id, $price);
	    	
	}

	public function awspn_format_price_note($product_id, $price){
			
			$price_note_text = esc_attr( get_post_meta( $product_id, 'awspn_product_price_note', true ) ); 
		    $price_note_separator = esc_attr( get_post_meta( $product_id, 'awspn_product_price_note_separator', true ) );  		   
		   
		   if( !empty( $price_note_text ) && isset( $price_note_text ) ){
				
		    	if( !empty( $price_note_separator ) && isset( $price_note_separator ) ){				    	
		    		return $price .'<span class="awspn_price_note" >&nbsp;'. $price_note_separator .'&nbsp;'. $price_note_text .'</span>';
				} else {
		    		return $price .'<span class="awspn_price_note" >&nbsp;/&nbsp;'. $price_note_text .'</span>';
				}

			} else {		    	
		    	return $price;
			}
		}

	

	public function awspn_display_checkout_price_note($cart_item_name, $cart_item){
		/*print_pre($cart_item);*/
		if(!is_checkout()){
			return $cart_item_name;
		}

			
			$product_price = $cart_item['data']->get_price();			
			$product_price = wc_price($product_price);			

			$product_id = $cart_item['product_id'];
			$price_note_text = esc_attr( get_post_meta( $product_id, 'awspn_product_price_note', true ) ); 
	    	$price_note_separator = esc_attr( get_post_meta( $product_id, 'awspn_product_price_note_separator', true ) ); 
			//print_pre($cart_item);
			if($price_note_text){
				if( !empty( $price_note_separator ) && isset( $price_note_separator ) ){
					return $cart_item_name .'<span class="awspn_price_note awspn_with_title" >&nbsp;('.$product_price.'&nbsp;'.$price_note_separator.'&nbsp;'.$price_note_text.')</span>';
					} else {
						return $cart_item_name .'<span class="awspn_price_note awspn_with_title" >&nbsp;('.$product_price.'/&nbsp;'.$price_note_text.')</span>'; 
					}
					
			}else{
				return $cart_item_name;

			}
	}


	/**
	 * Add awspn text to cart item.
	 *
	 * @param array $cart_item_data
	 * @param int   $product_id
	 * @param int   $variation_id
	 *
	 * @return array
	 */
	public function awspn_add_price_note_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
			
		//$product_id = $cart_item_data['product_id'];
				
		$awspn_show_on_oe = esc_attr( get_post_meta( $product_id, 'awspn_show_on_order_and_email', true ) ); 
		if(!empty($awspn_show_on_oe)){
			$price_note_text = esc_attr( get_post_meta( $product_id, 'awspn_product_price_note', true ) );		
			if ( !empty( $price_note_text ) ) {
				$awspn_excl_price_on_oe = esc_attr( get_post_meta( $product_id, 'awspn_excl_price_on_order_and_email', true ) ); 
				$awspn_excl_sep_on_oe 	= esc_attr( get_post_meta( $product_id, 'awspn_excl_sep_on_order_and_email', true ) ); 
				$price_note_separator 	= esc_attr( get_post_meta( $product_id, 'awspn_product_price_note_separator', true ) ); 
				
				$awspn_ctexts = esc_attr( get_post_meta( $product_id, 'awspn_product_price_note_oe_texts', true ) );
				
				if(!empty($awspn_ctexts)){
					$price_note_text = $awspn_ctexts;
				}
				
				if(!empty($awspn_excl_price_on_oe) && !empty($awspn_excl_sep_on_oe)){
								
					$awspn_oe_texts = $price_note_text;

				} elseif(!empty($awspn_excl_price_on_oe) && empty($awspn_excl_sep_on_oe)){

					$awspn_oe_texts = $price_note_separator.'&nbsp;'.$price_note_text;

				} elseif(empty($awspn_excl_price_on_oe) && !empty($awspn_excl_sep_on_oe)){

					$_product = new WC_Product( $product_id );
					$product_price = $_product->get_price();			
					$product_price = wc_price($product_price);			
					$awspn_oe_texts = $product_price.'&nbsp;'.$price_note_text;
					
				} elseif(empty($awspn_excl_price_on_oe) && empty($awspn_excl_sep_on_oe)){

					$_product = new WC_Product( $product_id );
					$product_price = $_product->get_price();			
					$product_price = wc_price($product_price);			
					$awspn_oe_texts = $product_price.'&nbsp;'.$price_note_separator.'&nbsp;'.$price_note_text;

				}

				
				 
				$cart_item_data['price-note-text'] = $awspn_oe_texts;
			} 	
		} 	
				//$cart_item_data['price-note-text'] = $price_note_text;

		return $cart_item_data;
	}

	/**
	 * awspn_save_values_in_item.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public function awspn_save_values_in_item( $item, $cart_item_key, $values, $order ) {
		$awspn_values = 'price-note-text';
		$item->$awspn_values = $values;
	}

	/**
	 * awspn_add_price_note_to_order_item_meta_wc3.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	public function awspn_add_price_note_to_order_item_meta_wc3( $item_id, $item, $order_id ) {
		$awspn_values = 'price-note-text';
		if ( isset( $item->$awspn_values ) ) {
			$this->awspn_add_price_note_to_order_item_meta( $item_id, $item->$awspn_values, null );
		}
	}

	/**
	 * Add awspn text to order item.
	 *
	 * @param array $cart_item_data
	 * @param array $item_values 
	 * @param array $item_key 
	 *
	 * @return array
	 */
	public function awspn_add_price_note_to_order_item_meta( $item_id, $item_values, $item_key  ) {		
			
		if( !empty( $item_values['price-note-text'] ) ){
			$awspn_clabel = esc_attr( get_post_meta( $item_values['product_id'], 'awspn_product_price_note_oe_label', true ) ); 	
			if(!empty($awspn_clabel)){
					wc_add_order_item_meta( $item_id, $awspn_clabel, $item_values['price-note-text'], true );
				} else {
					wc_add_order_item_meta( $item_id, 'Price note', $item_values['price-note-text'], true );
				}
		}
	}


	public function awspn_print_price_note_style(){
			echo "<style type='text/css'>";
			echo '.awspn_price_note{font-style:italic; font-size:85%;}';
			echo '.awspn_with_title{display: inline-block;}';
			echo '</style>';
		}

}

$awspn_frontend = new Woo_Set_Price_Note_Frontend();