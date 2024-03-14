<?php 

namespace Element_Ready\Base;
use Element_Ready\Base\BaseController;

class Shopping_Cart extends BaseController
{
	public function register() {
 
        add_action( 'wp_ajax_element_ready_wc_cart_item_remove', [$this,'item_remove'] );
        add_action( 'wp_ajax_nopriv_element_ready_wc_cart_item_remove', [$this,'item_remove'] );
        // product add to cart
        add_action( 'wp_ajax_element_ready_wc_cart_item_add', [$this,'wc_add_cart_item'] );
        add_action( 'wp_ajax_nopriv_element_ready_wc_cart_item_add', [$this,'wc_add_cart_item'] );
        
	}
	
	function item_remove() {

     
        $cart_item_key = sanitize_key($_REQUEST['cart_product_key']);
        
        if(!isset($_REQUEST['cart_product_key'])){
            echo wp_kses_post( WC()->cart->total );
            return;
        }
       
        WC()->cart->remove_cart_item($cart_item_key);
            wp_send_json_success(['total'=>wp_kses_post( WC()->cart->total ),'count'=> wp_kses_post( WC()->cart->get_cart_contents_count() )]);
        wp_die();
        
    }
    
    function wc_add_cart_item(){
        $return_data = null;

        if(isset($_REQUEST['product_id'])){
             
            try{
           
                $return_data    = $this->get_cart_item_array();
                wp_send_json_success(['items'=>$return_data,'count'=> WC()->cart->get_cart_contents_count()]);
             } catch(\Exception $e) {

                wp_send_json_error(esc_html__('Server Error','element-ready-lite'));
            }
              
        }
     
        wp_die();
    }

    function get_cart_item_array(){
       
        $return_data = [];
        
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ):
            
            $product = $cart_item['data'];
            $_data   = [];
            $_data['item_key']   = esc_html( $cart_item_key );
            $_data['quantity']   = esc_html__('Qty: ','element-ready-lite'). $cart_item['quantity'];
            $_data['image_url']  = wp_get_attachment_url( $product->get_image_id() );
            $_data['name']       = wp_kses_post( $product->get_name( $cart_item ) );
            $_data['price']      = wp_kses_post( WC()->cart->get_product_price( $product ) );
            $_data['link']       = esc_url($product->get_permalink( $cart_item ));
            $return_data[]       = $_data;

        endforeach;

        $return_data['cart_total'] = wp_kses_post( WC()->cart->total );
        return $return_data;
    }
    
    
	
}