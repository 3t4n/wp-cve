<?php

namespace Shop_Ready\extension\elewidgets\deps\product;

/** 
* @since 1.0 
* WooCommerce Product Tabs Data
* Cart Product qty Update
* use in Widget folder checkout
* @author quomodosoft.com 
*/

class Tabs {
  
  public $key = 'wready_product_tab_data_keys'; 
  
  public function register(){
    
    add_filter('woocommerce_product_tabs',[$this,'store_tabs_key'],99);
    add_filter('woocommerce_product_tabs',[$this,'woocommerce_default_product_tabs'],99);
    
  }

  public function store_tabs_key($tabs){
 
    $items = [];
   
    foreach($tabs as $key => $val){
        $items[$key] = sanitize_text_field($val['title']);
    } 
    
    if(!empty($items)){
        update_option( $this->key ,$items );
    }
 
    return $tabs;
  }

  function woocommerce_default_product_tabs( $tabs = array() ) {
		global $product, $post;
   
    if(!shop_ready_is_elementor_mode()){
      return $tabs;  
    }
		// Description tab - shows product content.
		if ( $post->post_content ) {
			$tabs['description'] = array(
				'title'    => __( 'Description', 'shop-ready' ),
				'priority' => 10,
				'callback' => 'woocommerce_product_description_tab',
			);
		}
    
		// Additional information tab - shows attributes.
		if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
			$tabs['additional_information'] = array(
				'title'    => __( 'Additional information', 'shop-ready' ),
				'priority' => 20,
				'callback' => 'woocommerce_product_additional_information_tab',
			);
		}


		return $tabs;
	}

}