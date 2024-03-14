<?php
/*
VarkTech Min and Max Purchase for WooCommerce
WOO-specific functions
Parent Plugin Integration
*/

	
	function vtmam_load_vtmam_cart_for_processing(){
      global $wpdb,  $woocommerce, $vtmam_cart, $vtmam_cart_item, $vtmam_info; 
      
      // from Woocommerce/templates/cart/mini-cart.php  and  Woocommerce/templates/checkout/review-order.php
        
      $wooCart = $woocommerce->cart->get_cart(); //v2.0.0 
      $sizeof_wooCart = is_array($wooCart) ? sizeof($wooCart) : 0; //v2.0.0        
      if ($sizeof_wooCart > 0) {  //v2.0.0 
      
		  $woocommerce->cart->calculate_totals(); //1.07.6 calculation includes generating line subtotals, used below
          
		  $vtmam_cart = new VTMAM_Cart; 
                    
          foreach ( $wooCart as $cart_item_key => $cart_item ) {   //v2.0.0
						$_product = $cart_item['data'];
						if ($_product->exists() && $cart_item['quantity'] > 0) {
							$vtmam_cart_item                = new VTMAM_Cart_Item;
             
              //the product id does not change in woo if variation purchased.  
              //  Load expected variation id, if there, along with constructed product title.
              $varLabels = ' ';
              if ($cart_item['variation_id'] > '0') { //v2.0.0
              //if ($cart_item['variation_id'] > ' ') {       
                 
                  // get parent title
                  $parent_post = get_post($cart_item['product_id']);
                  
                  // get variation names to string onto parent title
                  foreach($cart_item['variation'] as $key => $value) {          
                    $varLabels .= $value . '&nbsp;';           
                  }
                  
                  $vtmam_cart_item->product_id    = $cart_item['variation_id'];
                  $vtmam_cart_item->product_name  = $parent_post->post_title . '&nbsp;' . $varLabels ;

              } else { 
                  $vtmam_cart_item->product_id    = $cart_item['product_id'];
                  //$vtmam_cart_item->product_name  = $_product->get_title().$woocommerce->cart->get_item_data( $cart_item );
                  //v1.08.2.1 begin
                  if ( version_compare( WC_VERSION, '3.3.0', '>=' ) ) {
                    $vtmam_cart_item->product_name  = $_product->get_title().wc_get_formatted_cart_item_data($cart_item); 
                  } else {
                    $vtmam_cart_item->product_name  = $_product->get_title().$woocommerce->cart->get_item_data( $cart_item ); 
                  }
                  //v1.08.2.1 end                    
              }
  
              
              $vtmam_cart_item->quantity      = $cart_item['quantity'];
              
              //v1.07.6 commented unit price
              //$vtmam_cart_item->unit_price    = get_option( 'woocommerce_display_cart_prices_excluding_tax' ) == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price();
              
              /*
              $quantity = 1; //v1.08 vat fix
              $vtmam_cart_item->unit_price    = get_option( 'woocommerce_display_cart_prices_excluding_tax' ) == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax( $quantity ); //$_product->get_price();   //v1.08 vat fix
              */ 
              
              //v1.07.6 commented total_price             
              //$vtmam_cart_item->total_price   = $vtmam_cart_item->quantity * $vtmam_cart_item->unit_price;
              
              
              //v1.07.6 begin
              //  pick up unit price from line subtotal only - 
              //  will include all taxation and price adjustments from other plugins
              if ( ( get_option( 'woocommerce_calc_taxes' ) == 'no' ) ||
                   ( get_option( 'woocommerce_prices_include_tax' ) == 'no' ) ) {      
                 //NO VAT included in price
                 $vtmam_cart_item->unit_price  =  $cart_item['line_subtotal'] / $cart_item['quantity'];  
                 $vtmam_cart_item->total_price =  $cart_item['line_subtotal'];                                                
              } else {
                 
                 //v1.0.7.4 begin
                 //TAX included in price in DB, and Woo $cart_item pricing **has already subtracted out the TAX **, so restore the TAX
                 //  this price reflects the tax situation of the ORIGINAL price - so if the price was originally entered with tax, this will reflect tax
                 $price           =  $cart_item['line_subtotal']  / $cart_item['quantity'];    
                 $qty = 1;           
                 $_tax  = new WC_Tax();                
                // $product = get_product( $product_id ); 
                 $product = wc_get_product( $vtmam_cart_item->product_id  ); //v1.08 replace get_product with wc_get_product
                 $tax_rates  = $_tax->get_rates( $product->get_tax_class() );
        			 	 $taxes      = $_tax->calc_tax( $price  * $qty, $tax_rates, false );
        				 $tax_amount = $_tax->get_tax_total( $taxes );
        				 $vtmam_cart_item->unit_price  = round( $price  * $qty + $tax_amount, absint( get_option( 'woocommerce_price_num_decimals' ) ) ); 
                 $vtmam_cart_item->total_price = ($vtmam_cart_item->unit_price * $cart_item['quantity']);
               }              
              //v1.07.6 end
              
              
              /*  *********************************
              ***  JUST the cat *ids* please...
              ************************************ */
              $vtmam_cart_item->prod_cat_list = wp_get_object_terms( $cart_item['product_id'], $vtmam_info['parent_plugin_taxonomy'], $args = array('fields' => 'ids') );
              $vtmam_cart_item->rule_cat_list = wp_get_object_terms( $cart_item['product_id'], $vtmam_info['rulecat_taxonomy'], $args = array('fields' => 'ids') );
        
              //add cart_item to cart array
              $vtmam_cart->cart_items[]       = $vtmam_cart_item;

              $vtmam_cart->purchaser_ip_address = $vtmam_info['purchaser_ip_address']; // v1.07.4
              
				    }
        } //	endforeach;
        
              /*
       ($vtmam_info['get_purchaser_info'] == 'yes') is set in parent-cart-validation.php in 
       function vtmam_wpsc_checkout_form_validation only.  This is executed only at 'pay' button,
       the only time we can be sure that the purchaser info is there.
      */ 
       //     if( defined('VTMAM_PRO_DIRNAME') && ($vtmam_info['get_purchaser_info'] == 'yes') )  {
      if(defined('VTMAM_PRO_DIRNAME')) {
        //v1.07.91 begin
        require ( VTMAM_PRO_DIRNAME . '/woo-integration/vtmam-get-purchaser-info.php' ); 
        //require_once ( VTMAM_PRO_DIRNAME . '/woo-integration/vtmam-get-purchaser-info.php' ); 
        //v1.07.91 end  
      }
        
        
		} //end  if (sizeof($woocommerce->cart->get_cart())>0) 
           
  }      

 
   //  checked_list (o) - selection list from previous iteration of rule selection                                 
    function vtmam_fill_variations_checklist ($tax_class, $product_ID, $product_variation_IDs, $checked_list = NULL) {        //v2.0.0 null possible $checked_list must be last 
        global $post;
        // *** ------------------------------------------------------------------------------------------------------- ***
        // additional code from:  woocommerce/admin/post-types/writepanels/writepanel-product-type-variable.php
        // *** ------------------------------------------------------------------------------------------------------- ***
        //    woo doesn't keep the variation title in post title of the variation ID post, additional logic constructs the title ...
        
        $parent_post = get_post($product_ID);
        
        $attributes = (array) maybe_unserialize( get_post_meta($product_ID, '_product_attributes', true) );
    
        $attribute = array(); //v2.0.0
        $attribute['name'] = null; //v2.0.0
    
        $parent_post_terms = wp_get_post_terms( $post->ID, $attribute['name'] );
       
        // woo parent product title only carried on parent post
        echo '<h3>' .$parent_post->post_title.    ' - Variations</h3>'; 
        
        $sizeof_product_variation_IDs = is_array($product_variation_IDs) ? sizeof($product_variation_IDs) : 0; //v2.0.0
        if ($sizeof_product_variation_IDs > 0) {   //v2.0.0
            foreach ($product_variation_IDs as $product_variation_ID) {     //($product_variation_IDs as $product_variation_ID => $info)
                // $variation_post = get_post($product_variation_ID);
             
                $output  = '<li id='.$product_variation_ID.'>' ;
                $output  .= '<label class="selectit">' ;
                $output  .= '<input id="'.$product_variation_ID.'_'.$tax_class.' " ';
                $output  .= 'type="checkbox" name="tax-input-' .  $tax_class . '[]" ';
                $output  .= 'value="'.$product_variation_ID.'" ';
                if ($checked_list) {
                    if (in_array($product_variation_ID, $checked_list)) {   //if variation is in previously checked_list   
                       $output  .= 'checked="checked"';
                    }                
                }
                $output  .= '>'; //end input statement
     
                $variation_label = ''; //initialize label
                
                //get the variation names
                foreach ($attributes as $attribute) :
    
									    //v2.0.0a begin
                                        // Only deal with attributes that are variations
									    //if ( !$attribute['is_variation'] ) continue; //v2.0.0a
                                        if ( (isset($attribute['is_variation'])) &&
                                             ($attribute['is_variation']) ) {
                                            $carry_on = true;
                                        } else {
                                            continue;
                                        }
                                        //v2.0.0a end
    
    									// Get current value for variation (if set)
    									$variation_selected_value = get_post_meta( $product_variation_ID, 'attribute_' . sanitize_title($attribute['name']), true );
    
    									// Get terms for attribute taxonomy or value if its a custom attribute
    									if ($attribute['is_taxonomy']) :
    										$post_terms = wp_get_post_terms( $product_ID, $attribute['name'] );
    										foreach ($post_terms as $term) :
    											if ($variation_selected_value == $term->slug) {
                              $variation_label .= $term->name . '&nbsp;&nbsp;' ;
                          }
    										endforeach;
    									else :
    										$options = explode('|', $attribute['value']);
    										foreach ($options as $option) :
    											if ($variation_selected_value == $option) {
                            $variation_label .= ucfirst($option) . '&nbsp;&nbsp;' ;
                          }
    										endforeach;
    									endif;
    
    						endforeach;
                    
                $output  .= '&nbsp;&nbsp; #' .$product_variation_ID. '&nbsp;&nbsp; - &nbsp;&nbsp;' .$variation_label;
                $output  .= '</label>';            
                $output  .= '</li>'; 
                echo $output ;             
             }   
         }
         
         
         //wooCommerce 2.0 alteration...
         if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
            // Pre 2.0
         } else {
            // 2.0
         }
         
               
        return;   
    }
    

  /* ************************************************
  **   Get all variations for product
  *************************************************** */
  function vtmam_get_variations_list($product_ID) {
        
    //sql from woocommerce/classes/class-wc-product.php
   $variations = get_posts( array(
			'post_parent' 	=> $product_ID,
			'posts_per_page'=> -1,
			'post_type' 	  => 'product_variation',
			'fields' 		    => 'ids',
			'post_status'	  => 'publish',
      'order'         => 'ASC'
	  ));
   if ($variations)  {    
      $product_variations_list = array();
      foreach ( $variations as $variation) {
        $product_variations_list [] = $variation;             
    	}
    } else  {
      $product_variations_list;
    }
    
    return ($product_variations_list);
  } 
  
  
  function vtmam_test_for_variations ($prod_ID) { 
      
     $vartest_response = 'no';

     // code from:  woocommerce/admin/post-types/writepanels/writepanel-product-type-variable.php
     
     $attributes = (array) maybe_unserialize( get_post_meta($prod_ID, '_product_attributes', true) );
     $sizeof_attributes = is_array($attributes) ? sizeof($attributes) : 0; //v2.0.0a
     if ($sizeof_attributes > 0) {   //v2.0.0a    
       foreach ($attributes as $attribute) {
         if ( (isset($attribute['is_variation'])) &&    //v2.0.0a
              ($attribute['is_variation']) ) {
            $vartest_response = 'yes';
            break;
         }
       }
     }
     
     return ($vartest_response);   
  }     


  //v1.07 begin
    
   function vtmam_format_money_element($price) { 
      //from woocommerce/woocommerce-core-function.php   function woocommerce_price
    	$return          = '';
    	$num_decimals    = (int) get_option( 'woocommerce_price_num_decimals' );
    	$currency_pos    = get_option( 'woocommerce_currency_pos' );
    	$currency_symbol = get_woocommerce_currency_symbol();
    	$decimal_sep     = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), ENT_QUOTES );
    	$thousands_sep   = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ), ENT_QUOTES );
    
    	$price           = apply_filters( 'raw_woocommerce_price', (double) $price );
    	$price           = number_format( $price, $num_decimals, $decimal_sep, $thousands_sep );
    
    	if ( get_option( 'woocommerce_price_trim_zeros' ) == 'yes' && $num_decimals > 0 )
    		$price = woocommerce_trim_zeros( $price );
    
    	//$return = '<span class="amount">' . sprintf( get_woocommerce_price_format(), $currency_symbol, $price ) . '</span>'; 

    $current_version =  WOOCOMMERCE_VERSION;
    if( (version_compare(strval('2'), strval($current_version), '>') == 1) ) {   //'==1' = 2nd value is lower     
      $formatted = number_format( $price, $num_decimals, stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) );
      $formatted = $currency_symbol . $formatted;
    } else {
      $formatted = sprintf( get_woocommerce_price_format(), $currency_symbol, $price );
    }
          
     return $formatted;
   }
   
   //****************************
   // Gets Currency Symbol from PARENT plugin   - only used in backend UI during rules update
   //****************************   
  function vtmam_get_currency_symbol() {    
    return get_woocommerce_currency_symbol();  
  } 

  function vtmam_debug_options(){
     //test test
     //return;
   
    global $vtmam_setup_options;
    if ( ( isset( $vtmam_setup_options['debugging_mode_on'] )) &&
         ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ) ) {  
      error_reporting(E_ALL);  
    }  else {
      error_reporting(E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR);    //only allow FATAL error types 
    } 
    
    return;
  }
  //v1.07 end

  //*************************************************************
  //v1.08 begin - MOVED HERE from parent-cart-validation
  //*************************************************************
  // get rid of '$this->' !!!
  // get rid of 'public'
  
  // from woocommerce/classes/class-wc-cart.php 
  function vtmam_woo_get_url ($pageName) {            
     global $woocommerce;
      $checkout_page_id = vtmam_woo_get_page_id($pageName);
  		if ( $checkout_page_id ) {
  			if ( is_ssl() )
  				return str_replace( 'http:', 'https:', get_permalink($checkout_page_id)   ?? '' ); //v2.0.0
  			else
  				return apply_filters( 'woocommerce_get_checkout_url', get_permalink($checkout_page_id) );
  		}
  }
      
  // from woocommerce/woocommerce-core-functions.php 
  function vtmam_woo_get_page_id ($pageName) { 
    $page = apply_filters('woocommerce_get_' . $pageName . '_page_id', get_option('woocommerce_' . $pageName . '_page_id'));
		return ( $page ) ? $page : -1;
  }    
 /*  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++    */
    
 
   //v1.07 begin
  /* ************************************************
  **   Application - get current page url
  *       
  *       The code checking for 'www.' is included since
  *       some server configurations do not respond with the
  *       actual info, as to whether 'www.' is part of the 
  *       URL.  The additional code balances out the currURL,
  *       relative to the Parent Plugin's recorded URLs           
  *************************************************** */ 
 function vtmam_currPageURL() {
     global $vtmam_info;
     $currPageURL = vtmam_get_currPageURL();
     $www = 'www.';
     
     $curr_has_www = 'no';
     if (strpos($currPageURL, $www )) {
         $curr_has_www = 'yes';
     }
     
     //use checkout URL as an example of all setup URLs
     $checkout_has_www = 'no';
     if (strpos($vtmam_info['woo_checkout_url'], $www )) {
         $checkout_has_www = 'yes';
     }     
         
     switch( true ) {
        case ( ($curr_has_www == 'yes') && ($checkout_has_www == 'yes') ):
        case ( ($curr_has_www == 'no')  && ($checkout_has_www == 'no') ): 
            //all good, no action necessary
          break;
        case ( ($curr_has_www == 'no') && ($checkout_has_www == 'yes') ):
            //reconstruct the URL with 'www.' included.
            $currPageURL = vtmam_get_currPageURL($www); 
          break;
        case ( ($curr_has_www == 'yes') && ($checkout_has_www == 'no') ): 
            //all of the woo URLs have no 'www.', and curr has it, so remove the string 
            $currPageURL = str_replace($www, "", $currPageURL ?? '' ); //v2.0.0
          break;
     } 
 
     return $currPageURL;
  } 
 
  function vtmam_get_currPageURL($www = null) {
     global $vtmam_info;
     $pageURL = 'http';
     //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
     if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) { $pageURL .= "s";}
     $pageURL .= "://";
     $pageURL .= $www;   //mostly null, only active rarely, 2nd time through - see above
     
     //v2.0.0a begin
     //NEVER create the URL with the port name!!!!!!!!!!!!!!
     //$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
     if (isset($_SERVER["SERVER_NAME"])) {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
     } else {
        $pageURL .= $_SERVER["REQUEST_URI"];
     }
     //v2.0.0a end
     
     /* 
     if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
     } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
     }
     */
     return $pageURL;
  }  
   //v1.07 end    

  //*************************************************************
  //v1.08 END - MOVED HERE from parent-cart-validation
  //************************************************************* 


  
               
   //**********************************
   //V2.0.0  New Function
   //  USAGE:
   //  $allowed_html = vtmam_get_allowed_html();
   //**********************************     
  function  vtmam_get_allowed_html() { 
    //v2.0.0 begin  -  allowed_html used in "echo wp_kses" statements (which replace straight echo statements), for use with inline styles and other uses

    $allowed_html = array(      
                 'type'  => array(),
                 'class' => array(),
                 'id'    => array(),
                 'title' => array(),
                 'name'  => array(),
                 'value' => array(),
                 'style' => array(
                    '-webkit-box-shadow' => array(),
                    'box-shadow' => array(),                                                                                                                            
                    ), 
                 '-webkit-box-shadow' => array(), 
                 'box-shadow' => array(),  
	  	 		 'div'   => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                 
			  	 'p'     => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                    
				 'span'  => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                  
                 'br'    => array(),
                 'u'     => array(),
                 'img'   => array(),   
                 'strong' => array(),    
                 'a'     => array( 
                     'href'  => array(),                  
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                
                    ),
                 'i'   => array(
                     'class' => array(),
                     'id'    => array(),                
                    ),    
                 'table' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),
                     'cellspacing='  => array(),                
                    ),                 
                 'thead' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),
                  'tfoot' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ), 
                  'tbody' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                                  
                 'tr' => array(
                     'colspan' => array(),
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),
                     'scope' => array(),                 
                    ),                 
                 'td' => array(                    
                    'colspan' => array(),                                                                                                                            
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(), 
                     'scope' => array(),                
                    ),
                 'colspan' => array(),
                 'th' => array(
                     'colspan' => array(),
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(), 
                     'scope' => array(),                
                    ),                 
                 'dt' => array(                     
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),
                 'dd' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                 
                 'dl' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                                  
                 'em' => array(),
                 'h1' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),
                 'h2' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                                   
                 'h3' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),
                 'h4' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                  
            	 'strike' => array(),
          		 'ul' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                 
          		 'li' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                
                 'input' => array(
                     'type'  => array(),
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),
                     'name'  => array(),
                     'value' => array(), 
                     'checked' => array(),               
                    ),
                 'option' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),
                     'value' => array(),
                     'selected' => array(),                 
                    ),
                 'select' => array(
                     'id'    => array(),
                     'class' => array(),
                     'name'  => array(),
                    ),
                 'label' => array(
                     'id'    => array(),
                     'class' => array(),
                     'for'  => array(),
                    ),                                                         
                 'selected' => array(),
                 'checked' => array(),
                 'disabled' => array(),
                 '&nbsp;' => array(),
          		 'form' => array(
                     'class' => array(),
                     'id'    => array(),
                     'style' => array(),                 
                    ),                 
          		 'textarea' => array(
        			'type' => array(),
                    'rows' => array(),
                    'cols' => array(),
                    'name' => array(),
                    'readonly' => array(),
                    'onclick' => array(),                                                                                                                            
                    'class' => array(),
                    'id'    => array(),
                    'style' => array(),
                    'title' => array(),                          
                    ), 
        		 'rows' => array(),
                 'cols' => array(),
                 'readonly' => array(),
                 'onclick' => array(),
                 'ins' => array(),
          );      
                             
 
    return $allowed_html;
         
  } 

    
  //*************************
  //v2.0.0 New Function   (PHP 8.0 was kicking this out as a non-array)
  // ALWAYS Execute as::  $vtmam_rules_set = vtmam_get_rules_set();
  //*************************   
    function vtmam_get_rules_set() {  
        //global $vtmam_rules_set; - don't use global, array is returned
        
        $vtmam_rules_set_array   = get_option( 'vtmam_rules_set' ); 
        
        If ($vtmam_rules_set_array) {
        	$vtmam_rules_set   = maybe_unserialize($vtmam_rules_set_array);  //maybe_unserialize is a WP function, performs the unserialize as needed
        } else {
            $vtmam_rules_set   = array();
        }
        
        return $vtmam_rules_set; 
   }   

  //*************************
  //v2.0.0 New Function   (PHP 8.0 was kicking this out as a non-array)
  // ALWAYS Execute as::  vtmam_set_rules_set($vtmam_rules_set);
  //*************************   
    function vtmam_set_rules_set($vtmam_rules_set) {  
        //global $vtmam_rules_set; - don't use global, array is always passed
        
        $vtmam_rules_set_array   = serialize($vtmam_rules_set);  
        update_option( 'vtmam_rules_set', $vtmam_rules_set_array );      //update_option also ADDS the option, if it's not there

        return; 
   }
   
   
  //*************************
  //v2.0.0 New Function   (PHP 8.0 issue with wp_popular_terms_checklist)
  // ALWAYS Execute as::  vtmam_set_rules_set($vtmam_rules_set);
  //*************************   
   //copied from wp_popular_terms_checklist, altered as needed.
   function vtmam_popular_terms_checklist( $taxonomy, $default_term = 0, $number = 10, $display = true ) {
	$post = get_post();
    
	      //error_log( print_r(  ' ', true ) );
          //error_log( print_r(  'function begin temp_popular_terms_checklist, $taxonomy= ' .$taxonomy, true ) );
    
	if ( $post && $post->ID ) {
		$checked_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
	} else {
		$checked_terms = array();
	}

	$terms = get_terms(
		array(
			'taxonomy'     => $taxonomy,
			'orderby'      => 'count',
			'order'        => 'DESC',
			'number'       => $number,
			'hierarchical' => false,
		)
	);
    
          //error_log( print_r(  'terms array', true ) );
          //error_log( var_export($terms, true ) );
    
	$tax = get_taxonomy( $taxonomy );

	$popular_ids = array();

	foreach ( (array) $terms as $term ) {
        
        //v2.0.3 begin - fix for  'invalid_taxonomy' result
        if ( (!isset($term->term_id)) ||
             ($term->term_id <= ' ') ) {
           	      //error_log( print_r(  'no term_id - cannot do in_array test with no needle. exit,  ', true ) );
			continue;   //skip if no valid data      
        }
        //v2.0.3 end
        
		$popular_ids[] = $term->term_id;

		if ( ! $display ) { // Hack for Ajax use.
			continue;
		}


		$id      = "popular-$taxonomy-$term->term_id";
        
	      //error_log( print_r(  ' ', true ) );
          //error_log( print_r(  'before in_array, $term->term_id= ' .$term->term_id.' $checked_terms=', true ) );
          //error_log( var_export($checked_terms, true ) );
          //error_log( print_r(  '$popular_ids in foreach', true ) );
          //error_log( var_export($popular_ids, true ) );
        
        $checked = in_array( $term->term_id, $checked_terms, true ) ? 'checked="checked"' : '';
		?>

		<li id="<?php echo $id; ?>" class="popular-category">
			<label class="selectit">
				<input id="in-<?php echo $id; ?>" type="checkbox" <?php echo $checked; ?> value="<?php echo (int) $term->term_id; ?>" <?php disabled( ! current_user_can( $tax->cap->assign_terms ) ); ?> />
				<?php
				/** This filter is documented in wp-includes/category-template.php */
				echo esc_html( apply_filters( 'the_category', $term->name, '', '' ) );
				?>
			</label>
		</li>

		<?php
	}
          //error_log( print_r(  '$popular_ids AT END', true ) );
          //error_log( var_export($popular_ids, true ) );    
    
	return $popular_ids;
    
    }

  //************************  
  //v2.0.0a new function
  //************************
  //from http://stackoverflow.com/questions/15699101/get-client-ip-address-using-php
  function  vtmam_full_pro_upd_msg() {
  
      global $vtmam_license_options; 
      //FULL message for rego screen
      $message  =  '<strong>' . __('Pro Plugin  ** Update Required ** ' , 'vtmam') .'</strong>' ;
      $message .=  "<span style='color:grey !important;'><em>&nbsp;&nbsp;&nbsp; (pro plugin will not discount until updated)</em></span>" ;
      
      $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __('Your Pro Version = ' , 'vtmam') .$vtmam_license_options['pro_version'] .'&nbsp;&nbsp;<strong>' . __(' Required Pro Version = ' , 'vtmam') .VTMAM_MINIMUM_PRO_VERSION .'</strong>'; 
      
      //v2.0.0 begin     
      
      //(1)
      $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;   <strong>1)   &nbsp;&nbsp;Log into the </strong>&nbsp; ';
      $homeURL = 'https://www.varktech.com/your-account/your-login/';
      
      $message .=  '<a target="_blank" href="' .esc_url($homeURL). '">Varktech.com - Your Login</a> &nbsp; page';          
     

      //(2)
      $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;   <strong>2)   &nbsp;&nbsp;Download the new PRO zip file version</strong>&nbsp; from ';
      $homeURL = 'https://www.varktech.com/checkout/purchase-history/';
                     
      $message .=  'your &nbsp;&nbsp;  <a target="_blank" href="' .esc_url($homeURL). '">Varktech.com - Purchase History</a> &nbsp; page &nbsp;&nbsp;  ';            
                 
      
      //(3)
      $homeURL1 = VTMAM_ADMIN_URL.'plugins.php';
      $homeURL2 = vtmam_strip_out_http($homeURL1); 
      $homeNAME = str_replace( '/wp-admin/plugins.php', '', $homeURL2  ?? '' );      //v2.0.3            
      
      
      
      $homeURL = VTMAM_ADMIN_URL.'plugin-install.php';
      $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;  <strong>3)   &nbsp;&nbsp;Go to your &nbsp; </strong>  <a target="_blank" href="' .esc_url($homeURL1). '">' .esc_textarea($homeNAME). ' - Plugins Page</a> , ';
      $message .=  '&nbsp; and use  &nbsp; <a target="_blank" href="' .esc_url($homeURL). '">Add New</a>';
      $message .=  '<strong>&nbsp; to upload and activate the new zip file </strong>' ;
             
      $message .= '<span style="color:grey !important">';
      
      $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;In your website back end Plugins Page, &nbsp;&nbsp; delete the old version of the * Pro Plugin * as needed <em>(no settings will be lost)</em>. ';
      $message .=  '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;In your website back end ADD NEW Page,  &nbsp;&nbsp; UPload and Activate the Pro Plugin  &nbsp;&nbsp; <em>Using the new zip file downloaded from Varktech!</em> ';
      $message .=  "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;Apple Mac Users Users: &nbsp;&nbsp;  Macs often unzip files during download.   &nbsp;&nbsp;  <em> If the folder is delivered unzipped, you'll need to rezip the folder.</em> ";   //v2.0.0a   
      $message .=  '</strong>';
      
      $message .= '<br>&nbsp;';
      $message .= '</span>';
      
      //$message .=  "<span style='color:grey !important;'><br><br><em>&nbsp;&nbsp;&nbsp; (This message displays when the Pro version is installed, regardless of whether it's active)</em></span>" ;
      
      $admin_notices = '<div id="message" class="error fade" style="background-color: #FFEBE8 !important;"> <p style="font-size: 18px; !important;">' . $message . ' </p></div>';   
      return $admin_notices;
  }
    
  //************************  
  //v2.0.0a new function
  //************************
  //from http://stackoverflow.com/questions/15699101/get-client-ip-address-using-php
  function  vtmam_strip_out_http($url) {
      $url = str_replace( 'https://', '', $url   ?? '' ); //v2.0.0 
      $url = str_replace( 'http://', '', $url   ?? '' ); //v2.0.0 
      $url = rtrim($url, "/" ); //remove trailing slash
      //$url = str_replace( 'www.', '', $url  ) ; //v1.1.8.2 strip out WWW
      return $url;
  }
    
  //************************  
  //v2.0.0b new function, code moved here
  //************************
  function  vtmam_maybe_update_pro_version_num() {
      global $vtmam_license_options, $vtmam_setup_options;

      if (defined('VTMAM_PRO_VERSION')) { 
        if( (isset($vtmam_setup_options['current_pro_version'])) &&
            ($vtmam_setup_options['current_pro_version'] == VTMAM_PRO_VERSION) ) {
           //error_log( print_r(  'vtmam_maybe_update_version_num, current_pro_version001 = ' .$vtmam_setup_options['current_pro_version'], true ) );
          $carry_on = true;
        } else {
          $vtmam_setup_options['current_pro_version'] = VTMAM_PRO_VERSION; 
          update_option( 'vtmam_setup_options',$vtmam_setup_options ); 
           //error_log( print_r(  'vtmam_maybe_update_version_num, current_pro_version002 = ' .$vtmam_setup_options['current_pro_version'], true ) );
     
          delete_option('vtmam_new_version_in_progress');    
        }
      } else {
  
        $pro_plugin_installed = vtmam_check_pro_plugin_installed();
        
        //verify if version number, from http://stackoverflow.com/questions/28903203/test-if-string-given-is-a-version-number
        if( version_compare( $pro_plugin_installed, '0.0.1', '>=' ) >= 0 ) {
          if ( (isset($vtmam_setup_options['current_pro_version'])) &&
              ($vtmam_setup_options['current_pro_version'] == $pro_plugin_installed) ) {
           //error_log( print_r(  'vtmam_maybe_update_version_num, current_pro_version003 = ' .$vtmam_setup_options['current_pro_version'], true ) );            
            $carry_on = true;
          } else {
            $vtmam_setup_options['current_pro_version'] = $pro_plugin_installed; 
           //error_log( print_r(  'vtmam_maybe_update_version_num, current_pro_version004 = ' .$vtmam_setup_options['current_pro_version'], true ) );           
            update_option( 'vtmam_setup_options',$vtmam_setup_options );
            delete_option('vtmam_new_version_in_progress');  
          }  
         }   
      }      
      
      return;
  }
  
   //*************************
   // v1.08.2.6 new function  //v2.0.0
   //*************************
  function vtmam_auto_update_setting_html1 ( $html, $plugin_file, $plugin_data ) {
    if ( 'min-and-max-purchase-for-woocommerce/vt-minandmax-purchase.php' 	   ===  $plugin_file ) {
        $html = __( 'Auto-updates are not available for this plugin.', 'min-and-max-purchase-for-woocommerce' );
    }
 
    return $html;
  }
  add_filter( 'plugin_auto_update_setting_html', 'vtmam_auto_update_setting_html1', 10, 3 );

    
   //*************************
   // v1.08.2.6 new function   //v2.0.0
   //*************************
  function vtmam_auto_update_setting_html2 ( $html, $plugin_file, $plugin_data ) {
    if ( 'min-and-max-purchase-pro-for-woocommerce/vt-minandmax-purchase-pro.php' 	   ===  $plugin_file ) {
        $html = __( 'Auto-updates are not available for this plugin.', 'min-and-max-purchase-pro-for-woocommerce' );
    }
 
    return $html;
  }
  add_filter( 'plugin_auto_update_setting_html', 'vtmam_auto_update_setting_html2', 10, 3 ); 