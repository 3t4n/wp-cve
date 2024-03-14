<?php

namespace Shop_Ready\extension\elewidgets\document;

use Shop_Ready\base\elementor\Document_Settings;
use \Elementor\Controls_Manage;
use Elementor\Plugin;
use Elementor\Core\Settings\Manager as SettingsManager;

/**
* Checkout Address Related Global Settings
* Settings Exist in Elementor Editor->site settings -> Billing Section 
* @see https://prnt.sc/1aptmeu
* @since 1.0
* @author @quomodosoft.com
*/
Class Checkout_Hook extends Document_Settings{
    
    public function register(){

      add_filter( 'init',[ $this,'billing_address' ] , 200 );
      
      add_filter( 'woocommerce_billing_fields',[ $this , '_billing_fields'  ] );
      add_filter( 'woocommerce_shipping_fields',[ $this , '_shipping_fields' ]  );
      add_filter( 'woocommerce_cart_needs_shipping',[ $this , 'needs_shipping'  ] );
      add_filter( 'woocommerce_order_button_text',[ $this,'woocommerce_order_button_text' ] , 50, 1 );
      add_filter( 'woocommerce_checkout_show_terms',[ $this,'woocommerce_checkout_show_terms' ] , 50, 1 );
      add_filter( 'woo_ready_checkout_cart_item_quantity',[ $this,'_checkout_cart_item_quantity' ] , 30, 5 );
    
    }

    /**
     * Change Product Item Quantity Html
     * tunr into input 
     * @settings id wr_checkout_order_review_qty_editable , wr_checkout_order_review_price
     * add Product Price
     */
    public function _checkout_cart_item_quantity($html, $cart_item, $key, $is_qty , $show_price){
     
      //product_id
      $price = '';

      if( $show_price == 'yes' ){

        $product = wc_get_product( $cart_item[ 'product_id' ] );
        $price   = $product->get_price();

      }

      if( $is_qty == 'yes' ){
       
       return '<div class="wooready_product_quantity">'.'<span class="shop-ready-order-review-price">'.$price.'</span>'. '<div class="product-quantity">'.'<button type="button" class="woo-ready-qty-sub woo-ready-qty-sub-js">-</button><input type="number" data-item_key="'.$key.'" class="product-quantity qty min-width:70 width:80" value="'.$cart_item['quantity'].'"/> <button type="button" class="woo-ready-qty-add woo-ready-qty-add-js">+</button> </div> </div>';
      }
      
      if($show_price == 'yes'){
        return $price.' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', esc_html($cart_item['quantity']) ) . '</strong>';
      }
      return $price.' <strong class="product-quantity">' . sprintf( '&nbsp;%s', esc_html($cart_item['quantity']) ) . '</strong>'; 
    }
    
     /**
      * Checkout Terms Show Hide
      */
    public function woocommerce_checkout_show_terms($show){
     
      return shop_ready_gl_get_setting('wr_checkout_terms') == 'yes' ? true : false;
   
    }
    /**
     * Checkout Order Button Text Change
     * @see Elementor Global Settings
     */
    public function woocommerce_order_button_text($order_btn){

      return shop_ready_gl_get_setting('wr_order_button_text') == '' ? $order_btn : shop_ready_gl_get_setting('wr_order_button_text');
     
    }

    /**
     * remove fields depends on global settngs
     * @return array
     */
    public function _billing_fields($fields = array()){
    
      if(shop_ready_gl_get_setting('wr_checkout_address_modify') === 'yes'){

          $global_settings = shop_ready_gl_get_setting('wr_checkout_billing_address_list');

          if( is_array( $global_settings ) ){

            foreach($global_settings as $item_field){

                $item = []; 
                if( isset($item_field['list_field']) && 
                    isset( $item_field[ 'list_disable' ] ) &&
                    $item_field[ 'list_disable' ] == 'yes'){

                      unset($fields[$item_field['list_field']]);    

                }elseif( $item_field['list_field'] != '' && isset($fields[ $item_field['list_field'] ]) &&  $item_field[ 'list_disable' ] != 'yes' ){

                      $this_fld = $fields[ $item_field['list_field'] ];
                      if($item_field['list_label_change'] == 'yes' && $item_field[ 'list_title' ] !=''){
                        $this_fld[ 'label' ] = $item_field[ 'list_title' ];
                      }
                      
                        $this_fld[ 'required' ] = $item_field[ 'list_required' ] =='yes' ? true :false;
                        $this_fld[ 'priority' ] = $item_field[ 'list_priority' ] == '' ? 10 :$item_field[ 'list_priority' ];
                        
                        if( $item_field[ 'list_col_wdith' ] !='' ){
                           array_push($this_fld[ 'class' ],$item_field[ 'list_col_wdith' ]);
                        }
                
                      $fields[ $item_field['list_field'] ] = $this_fld;
               
                }

            }

          }
          
      }
    
      return $fields;
    }
    /**
     * Remove Shipping Address Globally
     */
    public function needs_shipping($needs_shipping){

      if( shop_ready_gl_get_setting('disable_shipping_address') === 'yes' ){
        return false;
      }

      return $needs_shipping;
    }

     /**
     * remove fields depends on global settngs
     * @return array
     */
    public function _shipping_fields($fields = array()){

          $global_settings = shop_ready_gl_get_setting('wr_checkout_shipping_address_list');
       
          if( is_array( $global_settings ) ){

            foreach($global_settings as $item_field){

                $item = []; 
                if( isset($item_field['list_field']) && 
                    $item_field['list_field'] != ''  && 
                    isset( $item_field[ 'list_disable' ] ) &&
                    $item_field[ 'list_disable' ] == 'yes'){

                      unset($fields[$item_field['list_field']]);    

                }elseif( $item_field['list_field'] != '' && isset($fields[ $item_field['list_field'] ]) &&  $item_field[ 'list_disable' ] != 'yes' ){
                      
                      $this_fld = $fields[ $item_field['list_field'] ];
                      if($item_field['list_label_change'] == 'yes' && $item_field[ 'list_title' ] !=''){
                        $this_fld[ 'label' ] = $item_field[ 'list_title' ];
                      }
                      
                        $this_fld[ 'required' ] = $item_field[ 'list_required' ] =='yes' ? true :false;
                        $this_fld[ 'priority' ] = $item_field[ 'list_priority' ] == '' ? 10 :$item_field[ 'list_priority' ];
                        
                        if( $item_field[ 'list_col_wdith' ] !='' ){
                           array_push($this_fld[ 'class' ],$item_field[ 'list_col_wdith' ]);
                        }
                
                      $fields[ $item_field['list_field'] ] = $this_fld;
               
                }

            }

          }

      return $fields;

    }
  
    
    /** 
    * Modify Billing Address in Checkout Page
    * Remove Some Fields
    * Settings Exist in Elementor Site Settings and global_settings.php
    * @since 1.0
    * @author quomodosoft.com
    */
    public function billing_address(){
        
        if( shop_ready_gl_get_setting( 'wr_checkout_address_modify' ) === 'yes' ){
          return false;
        }
    
        return true;
        
    }

    
 
}