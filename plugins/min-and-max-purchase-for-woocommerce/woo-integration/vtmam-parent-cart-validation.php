<?php
/*
VarkTech Maximum Purchase for WooCommerce
Woo-specific functions
Parent Plugin Integration
*/


class VTMAM_Parent_Cart_Validation {
	
	public function __construct(){
     global $vtmam_info, $woocommerce; //$woocommerce_checkout = $woocommerce->checkout();
     /*  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++   
     *        Apply Maximum Amount Rules to ecommerce activity
     *                                                          
     *          WOO-Specific Checkout Logic and triggers 
     *                                               
     *  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++   */

    //v1.07.7   changed to be direct, wp_loaded is correct!! ...
    add_action( 'wp_loaded',                           array(&$this, 'vtmam_woo_apply_checkout_cntl'),99,1 ); //loaded passes no values, but needed for other call!!!

     /*   Priority of 99 in the action above, to delay add_action execution. The
          priority delays us in the exec sequence until after any quantity change has
          occurred, so we pick up the correct altered state. */

    //if "place order" button hit, this action catches and errors as appropriate
    //add_action( 'woocommerce_before_checkout_process', array(&$this, 'vtmam_woo_place_order_cntl') );   
    //if "place order" button hit, this action catches and errors as appropriate
    add_action( 'woocommerce_before_checkout_process', array(&$this, 'vtmam_woo_check_click_to_pay') );  //v1.07.7 

    
    
    //save info to Lifetime tables following purchase       
     add_action('woocommerce_checkout_order_processed', array( &$this, 'vtmam_pre_purchase_save_session' ) );  // v1.07.4
     add_action('woocommerce_thankyou',                 array( &$this, 'vtmam_post_purchase_save_info' ) );    // v1.07.4  
    /*  =============+ */      
                                                                                
	}

           
  /* ************************************************
  **   Application - Apply Rules at E-Commerce Checkout
  *************************************************** */
	public function vtmam_woo_apply_checkout_cntl(){
    global $vtmam_cart, $vtmam_cart_item, $vtmam_rules_set, $vtmam_rule, $vtmam_info, $woocommerce;
    vtmam_debug_options();  //v1.07 
  

    //v1.07.7   begin
     //if ( (function_exists( 'get_current_screen' ) ) ||    // get_current_screen ONLY exists in ADMIN!!!  //v1.08.2  
     if ( (strpos($_SERVER["REQUEST_URI"],'wp-admin') !== false) ||    // replace get_current_screen  //v1.08.2.6
          ( is_admin() ) ||
          ( defined( 'DOING_CRON' ) ) ) {   //v1.08.2  
      return;
    }
    //v1.08 begin
    if(!isset($_SESSION)){
      session_start();
      header("Cache-Control: no-cache");
      header("Pragma: no-cache");
    }
    //v1.08 end
    
     
/*  //v1.08 MOVED TO vt-minandmax-purchase main file
    $vtmam_info['woo_cart_url']      =  $this->vtmam_woo_get_url('cart'); 
    $vtmam_info['woo_checkout_url']  =  $this->vtmam_woo_get_url('checkout');
    $vtmam_info['currPageURL']       =  $this->vtmam_currPageURL();
*/  
   //v1.08.1 begin
   global $vtmam_setup_options;
   /*
    IF YOU WANT MESSAGES TO SHOW ON *MORE* SHOP PAGES
		1. ADD the following wordpress filter:
    
          // VALID return VALUES: 
          // 'productPages' => Show On single product pages, Cart page and Checkout page 
          // 'all' => Show On ALL SHop pages.  ** Includes special refresh on ajax add-to-cart. **
          // 'false' => As normal, JUST Cart page and Checkout page
          
      		// Sample filter execution ==>>  put into your theme's functions.php file (at the BOTTOM is best), so it's not affected by plugin updates
         	 function show_errors_on_more_pages() {
           		 return 'productPages';  //valid values: 'productPages' 'all' 'false'
          	}
          	add_filter('vtmam_show_errors_on_more_pages', 'show_errors_on_more_pages', 10);   
   */

  //error_log( print_r(  'BEGIN vtmam_woo_apply_checkout_cntl', true ) ); 
  //error_log( print_r(  'show_errors_on_more_pages= ' .$vtmam_setup_options['show_errors_on_more_pages'], true ) ); 
     
    global $woocommerce; //v2.0.0  
    
     //v2.0.0a  begin
    if ( (isset($woocommerce) ) &&
         (isset($woocommerce->cart)) ) { 
      $wooCart = $woocommerce->cart->get_cart();
      $sizeof_wooCart = is_array($wooCart) ? sizeof($wooCart) : 0; //v2.0.0
      if ($sizeof_wooCart == 0) {
        return;;
      }
    }
    /*v2.0.0a replaced with above
    $wooCart = $woocommerce->cart->get_cart(); //v2.0.0 
    $sizeof_wooCart = is_array($wooCart) ? sizeof($wooCart) : 0; //v2.0.0  
    
    if ( (isset($woocommerce)) &&
         (isset($woocommerce->cart)) && //v1.08.2.5
         ($sizeof_wooCart > 0) ) {
         //(sizeof($woocommerce->cart->get_cart())>0) ) {
      $carry_on = true;     
    } else {
  //error_log( print_r(  'return001', true ) );
      return;
    }
    */
    //v2.0.0a  end

    if (isset( $vtmam_setup_options['show_errors_on_more_pages'] )) {
      switch( $vtmam_setup_options['show_errors_on_more_pages'] ) {
/*
        case 'productPages':
   //error_log( print_r(  'productPages', true ) );            
              //from https://wordpress.stackexchange.com/questions/2885/is-home-and-is-single-not-working-as-expected-with-custom-post-types
              $post_type = get_query_var('post_type');
              if ( is_single() || !empty($post_type) ) {
   //error_log( print_r(  'productPages is_product', true ) );            
                $do_cart_checkout_test = false; //not need, already on product page
              } else {
   //error_log( print_r(  'productPages do_cart_checkout_test', true ) );             
                $do_cart_checkout_test = true;
              }  
                   
           // }
          break;
*/          
        case 'all':  
             $do_cart_checkout_test = false; //not need, all pages
          break;
           
        default:  
             $do_cart_checkout_test = true;
          break; 
          
      }  //end switch
      
    } else {
      $do_cart_checkout_test = true;
    }
    
                  
    if ($do_cart_checkout_test) {
      $currPageURL      = $vtmam_info['currPageURL'];
      $woo_cart_url     = $vtmam_info['woo_cart_url'];
      $woo_checkout_url = $vtmam_info['woo_checkout_url'];
      
      // if an ITEM HAS BEEN REMOVED, url is apemnded to (&...) , can't look for equality - look for a substring
      //     (if CUSTOM MESSAGE not used, JS message does NOT come across in the situation where all was good, and then an item is removed)
      if ( (strpos($currPageURL,$woo_cart_url )     !== false) ||  //BOOLEAN == true...
           (strpos($currPageURL,$woo_checkout_url ) !== false) ) {  //BOOLEAN == true...   
       $carry_on = true; //v1.08.1
      } else {  
 //error_log( print_r(  'return2', true ) );           
        return;
      } 
    }
      //v1.07.7  end
      //v1.08.1 end   
      
        
     //v1.08 begin  clears old messages first
     // wc_clear_notices DOES NOT WORK when a product is deleted!!!!!!!  

     
     if ( (isset ($_SESSION['error_message_sent'])) &&
         ($_SESSION['error_message_sent']) ) {
       $_SESSION['error_message_sent'] = false;
       wc_clear_notices(); //- CLEAR does not work when an item is DELETED, added JS via filter   
     }
     //v1.08 end      
                     
    //input and output to the apply_rules routine in the global variables.
    //    results are put into $vtmam_cart
    
    /*  removed v1.07  $vtmam_cart is not available until after apply_rules is run!!
    if ( $vtmam_cart->error_messages_processed == 'yes' ) {  
      wc_add_notice( __('Purchase error found.', 'vtmam'), $notice_type = 'error' );  //supplies an error msg and prevents payment from completing   v1.07 change to use wc_add_notice      
      return;
    }
    */
     $vtmam_apply_rules = new VTMAM_Apply_Rules;   
    
    //wc_clear_notices(); //v1.07.91  - clear out existing notices //v1.08 removed
    
    //ERROR Message Path
    $sizeof_error_messages = is_array($vtmam_cart->error_messages) ? sizeof($vtmam_cart->error_messages) : 0; //v2.0.0
    if ( $sizeof_error_messages > 0 ) { 
    //if ( sizeof($vtmam_cart->error_messages) > 0 ) {  
    
      //v1.08 changes begin
        switch( $vtmam_cart->error_messages_are_custom ) {  
          case 'all':
               $this->vtmam_display_custom_messages();
            break;
          case 'some':    
               $this->vtmam_display_custom_messages();
               $this->vtmam_display_standard_messages();
            break;           
          default:  //'none' / no state set yet
               $this->vtmam_display_standard_messages();
              //v1.07.7   REMOVED
              /*
              //v1.07.2 begin
              $current_version =  WOOCOMMERCE_VERSION;
              if( (version_compare(strval('2.1.0'), strval($current_version), '>') == 1) ) {   //'==1' = 2nd value is lower     
                $woocommerce->add_error(  __('Purchase error found.', 'vtmam') );  //supplies an error msg and prevents payment from completing 
              } else {
               //added in woo 2.1
                wc_add_notice( __('Purchase error found.', 'vtmam'), $notice_type = 'error' );   //supplies an error msg and prevents payment from completing 
              } 
              */
              //v1.07.2  end            
            break;                    
        }

      //v1.08 changes end     

    }     
  }


  /* ************************************************
  **   v1.08 New Function
  *************************************************** */
  public function vtmam_display_standard_messages() {
    global $vtmam_cart, $vtmam_cart_item, $vtmam_rules_set, $vtmam_rule, $vtmam_info, $woocommerce;
    //insert error messages into checkout page
    add_action( "wp_enqueue_scripts", array($this, 'vtmam_enqueue_error_msg_css') );
    add_action('wp_head', array(&$this, 'vtmam_display_rule_error_msg_at_checkout') );  //JS to insert error msgs 
    
    $vtmam_cart->error_messages_processed = 'yes';
  } 

  /* ************************************************
  **   v1.08 New Function
  *************************************************** */
  public function vtmam_display_custom_messages() {
    global $vtmam_cart, $vtmam_cart_item, $vtmam_rules_set, $vtmam_rule, $vtmam_info, $woocommerce;
            
  	//v1.08 begin
  	if(!isset($_SESSION)){
  	  session_start();
  	  header("Cache-Control: no-cache");
  	  header("Pragma: no-cache");
  	}
  	//v1.08 end
    $sizeof_error_messages = is_array($vtmam_cart->error_messages) ? sizeof($vtmam_cart->error_messages) : 0; //v2.0.0  
    for($i=0; $i < $sizeof_error_messages; $i++) {  
       if ($vtmam_cart->error_messages[$i]['msg_is_custom'] == 'yes') {  //v1.08 ==>> show custom messages here...
          //v1.07.2 begin
          $current_version =  WOOCOMMERCE_VERSION;
          if( (version_compare(strval('2.1.0'), strval($current_version), '>') == 1) ) {   //'==1' = 2nd value is lower     
            $woocommerce->add_error(  $vtmam_cart->error_messages[$i]['msg_text'] );  //supplies an error msg and prevents payment from completing 
          } else {
           //added in woo 2.1
            wc_add_notice( stripslashes($vtmam_cart->error_messages[$i]['msg_text']), $notice_type = 'error' );   //supplies an error msg and prevents payment from completing //v1.07.7  added stripslashes
            $_SESSION['error_message_sent'] = true;  //v1.08
          } 
          //v1.07.2  end       
       } //end if
    }  //end 'for' loop    
  }   
        
           
  /* ************************************************
  **   Application - 
  *   //v1.07.7 REFACTCORED
  *************************************************** */
	public function vtmam_woo_check_click_to_pay(){
    global $vtmam_cart, $vtmam_cart_item, $vtmam_rules_set, $vtmam_rule, $vtmam_info, $woocommerce, $vtmam_setup_options;
    vtmam_debug_options();  //v1.07     
    //input and output to the apply_rules routine in the global variables.
    //    results are put into $vtmam_cart
   
   /* v1.07.2  cart not there yet
    if ( $vtmam_cart->error_messages_processed == 'yes' ) {  
      wc_add_notice( __('Purchase error found.', 'vtmam'), $notice_type = 'error' );  //supplies an error msg and prevents payment from completing   v1.07 change to use wc_add_notice                      
      return;
    }
    */
    
     $vtmam_apply_rules = new VTMAM_Apply_Rules;   
    
  /*  *********************************************************************************************************
      These two add_actiions cannot be used to display error msgs in this situation, as they are not executed when
      errors are found at "place order" time in woo land.  They depend on a screen refresh, and woo doesn't do one...
         // add_action( "wp_enqueue_scripts", array($this, 'vtmam_enqueue_error_msg_css') );
         // add_action('wp_head', array(&$this, 'vtmam_display_rule_error_msg_at_checkout') );  //JS to insert error msgs 
      *********************************************************************************************************  
    */
    

    //v1.07.6 begin
    if(!isset($_SESSION)){
      session_start();
      header("Cache-Control: no-cache");
      header("Pragma: no-cache");
    }
                   
     //clears old messages first  
     if ( (isset ($_SESSION['error_message_sent'])) &&
         ($_SESSION['error_message_sent']) ) {
       $_SESSION['error_message_sent'] = false;
       wc_clear_notices();   
     }

    //v1.07.6 end

    
    
    //ERROR Message Path
    //if ( sizeof($vtmam_cart->error_messages) > 0 ) {  
    $sizeof_error_messages = is_array($vtmam_cart->error_messages) ? sizeof($vtmam_cart->error_messages) : 0; //v2.0.0   
    if ( $sizeof_error_messages > 0 ) {         
      //v1.07.7  REMOVED
      /*
      //insert error messages into checkout page
      //this echo may result in multiple versions of the css file being called for, can't be helped.
      echo '<link rel="stylesheet" type="text/css" media="all" href="'.VTMAM_URL.'/core/css/vtmam-error-style.css" />' ;     //mwnt
            
      // WOO crazy error display, in this situation only:
          {"result":"failure","messages":"
            \n\t\t\t
            Purchase error found.<\/li>\n\t<\/ul>","refresh":"false"}     

      //  These are the incorrectly displayed contens of the 'add_error' function below, and are only a problem in this particular situation
      echo '<div class="woo-apply-checkout-cntl">';  // This 'echo' allows the incorrectly displayed error msg to fall within the 'woo-apply-checkout-cntl' div, and be deleted by following JS
      $woo_apply_checkout_cntl = 'yes';
      
      //display VTMAM error msgs   
      //mwnTEST  $this->vtmam_display_rule_error_msg_at_checkout();      
      */
      
      
      $vtmam_cart->error_messages_processed = 'yes';
      
      //tell WOO that an error has occurred, and not to proceed further
      //v1.07.2 changes begin
        switch( $vtmam_cart->error_messages_are_custom ) {  
          case 'all':
               $this->vtmam_display_custom_messages();
            break;
          case 'some':    
               $this->vtmam_display_custom_messages();
               $this->vtmam_display_standard_messages();
               
                //v1.07.7 ADDED
              $sizeof_error_messages = is_array($vtmam_cart->error_messages) ? sizeof($vtmam_cart->error_messages) : 0; //v2.0.0   
              for($i=0; $i < $sizeof_error_messages; $i++) { 
                 if ($vtmam_cart->error_messages[$i]['msg_is_custom'] != 'yes') {  //v1.08 ==>> don't show custom messages here...             
                    $message = '<div class="vtmam-error" id="line-cnt' . $vtmam_info['line_cnt'] .  '"><h3 class="error-title">Minimum Purchase Error</h3><p>' . $vtmam_cart->error_messages[$i]['msg_text']. '</p></div>';
                    wc_add_notice( $message, 'error' );
                    $_SESSION['error_message_sent'] = true;  //v1.08
                  }
                }
               
               //v1.07.7  REMOVED
               /*
               //v1.07.6 begin  
               //  Fixes an AJAX issue - with standard msgs, the inserted JS never gets where it needs to go.
               //     rather than do the standard method, just show  the msgs and FORCE an AJAX exit.
    
               if ( $vtmam_setup_options[max_purch_rule_lifetime_limit_by_ip] != 'yes' )  { //v1.07.6 if ip check included, the msg will be there from previous!!
                 $this->vtmam_display_rule_error_msg_at_checkout('yes');
                 exit();
               } 
               //v1.07.6 end
               */
                             
            break;           
          default:  //'none' / no state set yet
               $this->vtmam_display_standard_messages();
               
                //v1.07.7 ADDED
                $sizeof_error_messages = is_array($vtmam_cart->error_messages) ? sizeof($vtmam_cart->error_messages) : 0; //v2.0.0   
                for($i=0; $i < $sizeof_error_messages; $i++) {                 
                //for($i=0; $i < sizeof($vtmam_cart->error_messages); $i++) { 
                 if ($vtmam_cart->error_messages[$i]['msg_is_custom'] != 'yes') {  //v1.08 ==>> don't show custom messages here...             
                    $message = '<div class="vtmam-error" id="line-cnt' . $vtmam_info['line_cnt'] .  '"><h3 class="error-title">Minimum Purchase Error</h3><p>' . $vtmam_cart->error_messages[$i]['msg_text']. '</p></div>';
                    wc_add_notice( $message, 'error' );
                    $_SESSION['error_message_sent'] = true;  //v1.08
                  }
                }
               
               //v1.07.7  REMOVED               
               /*                             
              //v1.07.2 begin
              $current_version =  WOOCOMMERCE_VERSION;
              if( (version_compare(strval('2.1.0'), strval($current_version), '>') == 1) ) {   //'==1' = 2nd value is lower     
                $woocommerce->add_error(  __('Purchase error found .', 'vtmam') );  //supplies an error msg and prevents payment from completing 
              } else {
               //added in woo 2.1
                wc_add_notice( __('Purchase error found.', 'vtmam'), $notice_type = 'error' );   //supplies an error msg and prevents payment from completing 
              } 
               
               //v1.07.6 begin  
               //  Fixes an AJAX issue - with standard msgs, the inserted JS never gets where it needs to go.
               //     rather than do the standard method, just show  the msgs and FORCE an AJAX exit.
               if ( $vtmam_setup_options[max_purch_rule_lifetime_limit_by_ip] != 'yes' )  { //v1.07.6 if ip check included, the msg will be there from previous!! 
                 $this->vtmam_display_rule_error_msg_at_checkout('yes');
                 exit();
               }  
               //v1.07.6 end
               */                  
            break;                    
        }
        //v1.07.2 
    }  
   
  }  

  
  /* ************************************************
  **   Application - On Error Display Message on E-Commerce Checkout Screen  
  * //v1.07.7 REFACTORED  
  *************************************************** */ 
  public function vtmam_display_rule_error_msg_at_checkout($woo_apply_checkout_cntl = null){
    global $vtmam_cart, $vtmam_cart_item, $vtmam_rules_set, $vtmam_rule, $vtmam_info, $woocommerce, $vtmam_setup_options;

           
        //v2.0.0  begin
         if ( (strpos($_SERVER["REQUEST_URI"],'wp-admin') !== false) ||  
              ( defined( 'DOING_CRON' ) ) ) {   //v1.09.9  
          //error_log( print_r(  '001a RETURN', true ) );           
          return;
        }
        //v2.0.0  end
            
      	//v1.08 begin
      	if(!isset($_SESSION)){
      	  session_start();
      	}
      	//v1.08 end
          
        //$purchase_error_title doesn't actually work!!  
        $purchase_error_title = null;   //v2.0.0 
        $sizeof_error_messages = is_array($vtmam_cart->error_messages) ? sizeof($vtmam_cart->error_messages) : 0; //v2.0.0   
        for($i=0; $i < $sizeof_error_messages; $i++) {         
        //for($i=0; $i < sizeof($vtmam_cart->error_messages); $i++) { 
         if ($vtmam_cart->error_messages[$i]['msg_is_custom'] != 'yes') {  //v1.08 ==>> don't show custom messages here...             
            $message = '<div class="vtmam-error" id="line-cnt' . $vtmam_info['line_cnt'] .  '"><h3 class="error-title">' .$purchase_error_title. '</h3><p>' . $vtmam_cart->error_messages[$i]['msg_text']. '</p></div>';
            wc_add_notice( $message, 'error' );
            $_SESSION['error_message_sent'] = true;  //v1.08
          }
        }

     /* ***********************************
        CUSTOM ERROR MSG CSS AT CHECKOUT
        *********************************** */
     if ($vtmam_setup_options['custom_error_msg_css_at_checkout'] > ' ' )  {
        echo '<style type="text/css">';
        echo $vtmam_setup_options['custom_error_msg_css_at_checkout'];
        echo '</style>';
     }
     
     /*
      Turn off the messages processed switch.  As this function is only executed out
      of wp_head, the switch is only cleared when the next screenful is sent.
     */
     $vtmam_cart->error_messages_processed = 'no';   
 } 
 
 

  /* ************************************************
  **   Application - On Error enqueue error style
  *************************************************** */
  public function vtmam_enqueue_error_msg_css() {
    wp_register_style( 'vtmam-error-style', VTMAM_URL.'/core/css/vtmam-error-style.css' );  
    wp_enqueue_style('vtmam-error-style');
  } 
 
 
  // v1.07.4 begin
  /* ************************************************
  **   before purchase, save info to session
  *************************************************** */ 
  function vtmam_pre_purchase_save_session() { 
    global $post, $wpdb, $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info;
              
      if(!isset($_SESSION)){
        session_start();
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
      } 
      $data_chain = array();
      $data_chain[] = $vtmam_rules_set;
      $data_chain[] = $vtmam_cart;
      $data_chain[] = $vtmam_info;
      $_SESSION['data_chain'] = serialize($data_chain);  
    
    return; 
    
  } 
  // v1.07.4 end
  
  /* ************************************************
  **   After purchase, store max purchase info for lifetime rules on db
  *************************************************** */ 
  function vtmam_post_purchase_save_info () {
   
    if(defined('VTMAM_PRO_DIRNAME')) {
      require ( VTMAM_PRO_DIRNAME . '/woo-integration/vtmam-save-purchase-info.php');
    } 
   
  } // end  function vtmam_store_max_purchaser_info() 
 
 
   // v1.07.4 begin
   function vtmam_get_data_chain() {
         
      if(!isset($_SESSION)){
        session_start();
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
      }   
      global $vtmam_rules_set, $vtmam_cart, $vtmam_info;
      
      if (isset($_SESSION['data_chain'])) {
        $data_chain      = unserialize($_SESSION['data_chain']);
      } else {
        $data_chain = array();
      }
         
      //v1.07.8 begin
      /*  REMOVED - do data chain moves ALWAYS
      if ($vtmam_rules_set == '') {        
        $vtmam_rules_set = $data_chain[0];
        $vtmam_cart      = $data_chain[1];
        $vtmam_info      = $data_chain[2];
      }
      */
      $vtmam_rules_set = $data_chain[0];
      $vtmam_cart      = $data_chain[1];
      $vtmam_info      = $data_chain[2]; 
      //v1.07.8 end     

      return $data_chain;
   }
   // v1.07.4  end
    
} //end class
$vtmam_parent_cart_validation = new VTMAM_Parent_Cart_Validation;
