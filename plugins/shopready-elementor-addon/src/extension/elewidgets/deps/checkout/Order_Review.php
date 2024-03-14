<?php

namespace Shop_Ready\extension\elewidgets\deps\checkout;
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;
/** 
* @since 1.0 
* WooCommerce Checkout Page Order Review
* Add to Cart Update Event
* Mini Cart Update Event
* Payment Update Event  
* Cart Product qty Update
* use in Widget folder checkout
* @author quomodosoft.com 
*/

class Order_Review {
  
    public function register(){
        // mini cart fragement
           // cart Page move to cart folder
        add_action('wp_ajax_remove_item_from_cart', [ $this , 'remove_item_from_cart']);
        add_action('wp_ajax_nopriv_remove_item_from_cart', [ $this , 'remove_item_from_cart']);
        add_filter( 'woocommerce_add_to_cart_fragments', [ $this , 'mini_cart_fragment'], 30, 1 );
        
        if( shop_ready_template_is_active_gl( 'checkout' ) ){

            add_action( 'woocommerce_before_checkout_form', [ $this , 'remove_checkout_coupon_form' ], 9 );
            add_action( 'wp_ajax_nopriv_wr_woocommerce_shipping', [ $this , 'woocommerce_shipping_init' ] );
          
            add_filter ( 'woocommerce_update_order_review_fragments', [ $this , 'payment_fragemnt' ] , 10, 1 );
            add_filter ( 'woocommerce_update_order_review_fragments', [ $this , 'order_review_table_fragemnt' ] , 10, 1 );
        
            add_action('woocommerce_checkout_before_order_review', [ $this,'before_order_review_heading']);
         
        }

    }

    function before_order_review_heading(){

        $review_heading = WReady_Helper::get_global_setting('shop_ready_pro_order_review_heading','Order Review'); 
        if($review_heading !=''){
            echo wp_kses_post(sprintf( '<h3 class="shop-ready-review-order-heading"> %s </h3>', esc_html($review_heading) ));
        }
        
    }
    function remove_checkout_coupon_form(){
        remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
    }
    function remove_item_from_cart(){
         // sanitize
        $cart_item_key = sanitize_key($_POST['cart_item_key']);
    
        if($cart_item_key){

            WC()->cart->remove_cart_item($cart_item_key);
       
        } 

        ob_start();

		woocommerce_mini_cart();

		$mini_cart = ob_get_clean();

		$data = array(
			'fragments' => apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.woo-ready-mini-cart-container' => '<div class="woo-ready-mini-cart-container sready-minicart-order-review ajax-fragemnt display:flex flex-direction:column">' . $mini_cart . '</div>',
                    'span.wr-mini-cart-subtotal-bill' => '<span class="wr-mini-cart-subtotal-bill">' . WC()->cart->get_cart_subtotal() . '</span>',
                    '.wr-checkout-cart-total-bill' => '<div class="wr-checkout-cart-total-bill">' . WC()->cart->get_cart_subtotal() . '</div>',
                    'div.wr-tax-amount' => '<div class="wr-tax-amount">' . wc_cart_totals_taxes_total_html() . '</div>',
  
				)
			),
			'cart_hash' => WC()->cart->get_cart_hash(),
		);
       
		wp_send_json( $data );
       
       
    }

    public function woocommerce_quantity_input_args($args, $product){
    
      return $args;
    }
   
    public function woocommerce_shipping_init(){
      
        WC()->session->set('chosen_shipping_methods', array( sanitize_text_field($_POST['shipping_method']) ) );
        exit();
       
    }
  

    public function mini_cart_fragment($data){
      
        shop_ready_get_template('mini-cart.php',[], SHOP_READY_ADDONS_DIR_PATH , SHOP_READY_ADDONS_DIR_PATH . '/templates/presets/cart/');
        $mini_cart = ob_get_clean();
        $data['div.woo-ready-mini-cart-container'] = '<div class="woo-ready-mini-cart-container sready-minicart-order-review ajax-fragemnt display:flex flex-direction:column">' . $mini_cart . '</div>' ;
        $data['span.wr-mini-cart-subtotal-bill'] = '<span class="wr-mini-cart-subtotal-bill">' . WC()->cart->get_cart_subtotal() . '</span>' ;
      
        ob_start();
        wc_print_notice( sprintf( 'Update Cart. <a href="%s" class="shop-rady-cart-view-link"> %s </a>', esc_url(wc_get_cart_url()), esc_html__('View Cart','shopready-elementor-addon') ), 'success' );
        $notice_content = ob_get_clean();
        $data['.woocommerce-product-page-notice-wrapper'] = '<div class="woocommerce-product-page-notice-wrapper width:100% update-shop-ready-single-product">' . $notice_content . '</div>' ;
        
        return $data;
    }

  
    
    public function order_review_table_fragemnt( $woocommerce_order ){
	
		// Get order review fragment.
		ob_start();
        shop_ready_widget_template_part(
            'checkout/template-part/review-order.php',
            array(
                'checkout' => WC()->checkout(),
            )
        );

		$woocommerce_order_review = ob_get_clean();

        $woocommerce_order['.woocommerce-checkout-review-order-table'] = $woocommerce_order_review;
        return $woocommerce_order;
    }
    
    /** 
    * Checkout Update 
    * update_checkout js trigger event
    * @return array 
    */
    public function payment_fragemnt( $woocommerce_order ){
        
        if ( WC()->cart->needs_payment() ) {
			$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
			WC()->payment_gateways()->set_current_gateway( $available_gateways );
		} else {
			$available_gateways = array();
		}

        ob_start();

		shop_ready_widget_template_part(
			'checkout/template-part/payment.php',
			array(
				'checkout'           => WC()->checkout(),
				'available_gateways' => $available_gateways,
				'order_button_text'  => apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'shopready-elementor-addon' ) ),
			)
		);
    
		$woocommerce_checkout_payment = ob_get_clean();

        $reload_checkout = isset( WC()->session->reload_checkout );
		if ( ! $reload_checkout ) {
			$messages = wc_print_notices( true );
		} else {
			$messages = '';
		}

		unset( WC()->session->refresh_totals, WC()->session->reload_checkout );

        $woocommerce_order['.woocommerce-checkout-payment'] = $woocommerce_checkout_payment;
       
        return $woocommerce_order;
    }

    /** 
    * Order Review product qty
    * remove the selected quantity count from the checkout page table.
    * @since 1.0
    * @return int 
    */
    public function cart_item_quantity($html, $cart_item ,$cart_item_key, $is_qty){
        
        if($is_qty == 'yes'){
            return '<button type="button" class="woo-ready-qty-sub woo-ready-qty-sub-js">-</button>'.$html.'<button type="button" class="woo-ready-qty-add woo-ready-qty-add-js">+</button>';
        }else{
         return $html;   
        }
     
    }
    /**
     * Add product product qty  
     * @since 1.0
     */ 
    function cart_item_name( $product_title, $cart_item, $cart_item_key ) {
  
        if (  is_checkout() ) {
        
            $cart = WC()->cart->get_cart();
        
            foreach ( $cart as $cart_key => $cart_value ){
        
                if ( $cart_key == $cart_item_key ){
                
                    $product_id = $cart_item['product_id'];
                    $_product   = $cart_item['data'] ;
                    $return_value = sprintf(
                        '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                        esc_url( wc_get_cart_remove_url( $cart_key ) ),
                        esc_html__( 'Delete', 'shopready-elementor-addon' ),
                        esc_attr( $product_id ),
                        esc_attr( $_product->get_sku() )
                    );
       
                    $return_value .= '&nbsp; <span class = "product_name" >' . $product_title . '</span>' ;
        
                    if ( $_product->is_sold_individually() ) {
                    
                        $return_value .= sprintf( ' <input type="hidden" name="cart[%s][qty]" value="1" />', esc_attr($cart_key) );
                    
                    } else {
                    
                        $return_value .= woocommerce_quantity_input( array(
                        
                        'input_name'  => "cart[{$cart_key}][qty]",
                        
                        'input_value' => $cart_item['quantity'],
                        
                        'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                        
                        'min_value'   => '1'
                        
                        ), $_product, false );
                    }

                    return $return_value;
        
                }
        
            } // foreach
        
        }

  
        $_product   = $cart_item['data'] ;
        $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
        
        if ( ! $product_permalink ) {
            $return_value = $_product->get_title() . '&nbsp;';
        } else {
            $return_value = sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), esc_html( $_product->get_title() ));
        }

        return $return_value;
        
    }

}