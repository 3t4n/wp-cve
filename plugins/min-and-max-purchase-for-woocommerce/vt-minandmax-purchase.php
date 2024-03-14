<?php
/*
Plugin Name: VarkTech Min and Max Purchase for WooCommerce
Plugin URI: http://varktech.com
Description: An e-commerce add-on for WooCommerce, supplying minimum and maximum purchase functionality. php 8.1+ compatible. 
Version: 2.0.0
Author: Vark
Author URI: http://varktech.com
WC requires at least: 2.0.0
WC tested up to: 7.0
*/



/*
** define Globals 
*/
   $vtmam_info;  //initialized in VTMAM_Parent_Definitions
   $vtmam_rules_set;
   $vtmam_rule;
   $vtmam_cart;
   $vtmam_cart_item;
   $vtmam_setup_options;
   $vtmam_license_options; //v2.0.0 licensing


   //initial setup only, overriden later in function vtmam_debug_options
   //test test
   error_reporting(E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR); //v1.07.4
    
class VTMAM_Controller{
	
	public function __construct(){    
   
	define('VTMAM_VERSION',                               '2.0.0');
    define('VTMAM_MINIMUM_PRO_VERSION',                   '2.0.0'); 
    define('VTMAM_LAST_UPDATE_DATE',                      '2022-10-10');
    define('VTMAM_DIRNAME',                               ( dirname( __FILE__ ) ));
    define('VTMAM_URL',                                   plugins_url( '', __FILE__ ) );
    define('VTMAM_EARLIEST_ALLOWED_WP_VERSION',           '3.3');   //To pick up wp_get_object_terms fix, which is required for vtmam-parent-functions.php
    define('VTMAM_EARLIEST_ALLOWED_PHP_VERSION',          '7.0');    //v2.0.0   now using the "null coalescing operator" ("??"), first introduced in PHP7 
    define('VTMAM_PLUGIN_SLUG',                           plugin_basename(__FILE__));
    define('VTMAM_PLUGIN_PATH',                            WP_PLUGIN_DIR . '/min-and-max-purchase-for-woocommerce/vt-minandmax-purchase.php/');
    define('VTMAM_PRO_PLUGIN_NAME',                      'VarkTech Min and Max Purchase Pro for WooCommerce');    //V1.07.3
    define('VTMAM_PRO_PLUGIN_FOLDER',                    'min-and-max-purchase-pro-for-woocommerce');    //v2.0.0
    define('VTMAM_PRO_PLUGIN_FILE',                      'vt-minandmax-purchase-pro.php');    //v2.0.0 
    define('VTMAM_ADMIN_CSS_FILE_VERSION',               'v003');    //v2.0.0    
    
    require ( VTMAM_DIRNAME . '/woo-integration/vtmam-parent-definitions.php');
   
    /*  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
    //  these control the rules ui, add/save/trash/modify/delete
    /*  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
    add_action('init',          array( &$this, 'vtmam_controller_init' )); 
    add_action('admin_init',    array( &$this, 'vtmam_admin_init' ));
    
    //v1.07 begin
    add_action( 'draft_to_publish',       array( &$this, 'vtmam_admin_update_rule' )); 
    add_action( 'auto-draft_to_publish',  array( &$this, 'vtmam_admin_update_rule' ));
    add_action( 'new_to_publish',         array( &$this, 'vtmam_admin_update_rule' )); 			
    add_action( 'pending_to_publish',     array( &$this, 'vtmam_admin_update_rule' ));    
    //v1.07 end
        
    add_action('save_post',     array( &$this, 'vtmam_admin_update_rule' ));
    add_action('delete_post',   array( &$this, 'vtmam_admin_delete_rule' ));    
    add_action('trash_post',    array( &$this, 'vtmam_admin_trash_rule' ));
    add_action('untrash_post',  array( &$this, 'vtmam_admin_untrash_rule' ));
    /*  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
    
    //get rid of bulk actions on the edit list screen, which aren't compatible with this plugin's actions...
    add_action('bulk_actions-edit-vtmam-rule', array($this, 'vtmam_custom_bulk_actions') ); 
    
    add_action( 'admin_notices', array( &$this, 'vtmam_maybe_system_requirements') );  //v2.0.0 - added for data licensing
    
    add_action( 'admin_notices', array( &$this, 'vtmam_check_for_data_updates') );  //v2.0.0 - added for data conversion 

    //add_action( 'load-post.php', array( &$this, 'vtmam_admin_process' ) );      //v2.0.0a    added and removed       - function vtmin_admin_process also removed
    //add_action( 'load-post-new.php', array( &$this, 'vtmam_admin_process' ) );  //v2.0.0a    added and removed       - function vtmin_admin_process also removed

	}   //end constructor

  	                                                             
 /* ************************************************
 **   Overhead and Init
 *************************************************** */
	public function vtmam_controller_init(){    //v2.0.0.1   $is_admin_override comes from a fix where a 2nd call to this function is necessary
    global $vtmam_setup_options;
   
    load_plugin_textdomain( 'vtmam', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
                                                                               
    require_once ( VTMAM_DIRNAME . '/core/vtmam-backbone.php' );    
    require_once ( VTMAM_DIRNAME . '/core/vtmam-rules-classes.php');
    require_once ( VTMAM_DIRNAME . '/woo-integration/vtmam-parent-functions.php');
    // require ( VTMAM_DIRNAME . '/woo-integration/vtmam-parent-cart-validation.php'); //1.08.2.5 shifted below
    
    
    //moved here v1.07
    if (get_option( 'vtmam_setup_options' ) ) {
      $vtmam_setup_options = get_option( 'vtmam_setup_options' );  //put the setup_options into the global namespace
    }
    vtmam_debug_options();  //v1.07

    //***************
    //v2.0.0 begin
    // Licensing and Phone Home ONLY occurs when the purchased PRO version is installed
    //***************
    require_once ( VTMAM_DIRNAME . '/admin/vtmam-license-options.php');   
    global $vtmam_license_options; 
    $vtmam_license_options = get_option('vtmam_license_options'); 
    
    $this->vtmam_init_update_license();

    if ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ){   
       error_log( print_r(  'Begin FREE plugin, vtmam_license_options= ', true ) );  
       error_log( var_export($vtmam_license_options, true ) ); 
    }    
    
    //v2.0.0 end
        
    $pageURL = sanitize_url($_SERVER["REQUEST_URI"]); //v2.0.0
    if (strpos($pageURL,'wp-admin') !== false) {            //v2.0.0      

        require_once ( VTMAM_DIRNAME . '/admin/vtmam-setup-options.php');
        //fix 02-03-2013 - register_activation_hook now at bottom of file, after class instantiates
        
        define('VTMAM_ADMIN_URL', get_admin_url() );  //v2.0.0 licensing   
                
        if(defined('VTMAM_PRO_DIRNAME')) {
          require_once ( VTMAM_PRO_DIRNAME . '/admin/vtmam-rules-ui.php' );
          require_once ( VTMAM_PRO_DIRNAME . '/admin/vtmam-rules-update.php');
        } else {
          require_once ( VTMAM_DIRNAME .     '/admin/vtmam-rules-ui.php' );
          require_once ( VTMAM_DIRNAME .     '/admin/vtmam-rules-update.php');
        }
        
        require_once ( VTMAM_DIRNAME . '/admin/vtmam-checkbox-classes.php');
        require_once ( VTMAM_DIRNAME . '/admin/vtmam-rules-delete.php');        
        
        //V1.07.3begin
        if ( (defined('VTMAM_PRO_DIRNAME')) &&
             (version_compare(VTMAM_PRO_VERSION, VTMAM_MINIMUM_PRO_VERSION) < 0) ) {    //'<0' = 1st value is lower  
          add_action( 'admin_notices',array(&$this, 'vtmam_admin_notice_version_mismatch') );            
        }
        //V1.07.3begin 
           
    } else {  //v1.08.2.5 begin - added 'else' branch to Prevent some resources from loading in wp-admin which were causing woo api issues.
      //is_admin test doesn't alway work - test this way!!!!!!
       if ( (strpos($_SERVER["REQUEST_URI"],'wp-admin') !== false) ||
            (defined( 'DOING_CRON' )) ) {          
            //if is_admin, DO NOTHING
          $do_nothing = true;          
       } else {
         require_once ( VTMAM_DIRNAME . '/woo-integration/vtmam-parent-cart-validation.php');
      } 
    } //v1.08.2.5 end

    
    //unconditional branch for these resources needed for WOOCommerce, at "place order" button time
    require_once ( VTMAM_DIRNAME . '/core/vtmam-cart-classes.php');


/*  //v2.0.0a moved below the is_admin check    
    if(defined('VTMAM_PRO_DIRNAME')) {
      require_once ( VTMAM_PRO_DIRNAME . '/core/vtmam-apply-rules.php' );
      
      //v2.0.0a cronjobs check license twice a day
      require_once ( VTMAM_DIRNAME . '/core/vtmam-cron-class.php' );  //v2.0.0a
      add_action( 'vtmam_twice_daily_scheduled_events', 'vtmam_recheck_license_activation' );   //v2.0.0a 
      
    } else {
      require_once ( VTMAM_DIRNAME .     '/core/vtmam-apply-rules.php' );
    }
 */   
    wp_enqueue_script('jquery'); 

    //EXIT FUNCTION if is_admin
    $pageURL = sanitize_url($_SERVER["REQUEST_URI"]); //v2.0.0
    if (strpos($pageURL,'wp-admin') !== false) {      
       return;
    }
    //*******************************
 
    global $vtmam_info;
    $vtmam_info['woo_cart_url']      =  vtmam_woo_get_url('cart'); 
    $vtmam_info['woo_checkout_url']  =  vtmam_woo_get_url('checkout');
    $vtmam_info['currPageURL']       =  vtmam_currPageURL(); 

    if(defined('VTMAM_PRO_DIRNAME')) {
      require_once ( VTMAM_PRO_DIRNAME . '/core/vtmam-apply-rules.php' );
      
      //v2.0.0a cronjobs check license twice a day
      require_once ( VTMAM_DIRNAME . '/core/vtmam-cron-class.php' );  //v2.0.0a
      add_action( 'vtmam_twice_daily_scheduled_events', 'vtmam_recheck_license_activation' );   //v2.0.0a 
      
    } else {
      require_once ( VTMAM_DIRNAME .     '/core/vtmam-apply-rules.php' );
    }
    
  
     //v1.08.1 you can use this to clear each message on click
     //  the issue is that when an ajax add-to-cart is done, there's no msg
    //ONLY USED on checkout only
    $use_clear_cart_msgs = apply_filters('vtmam_use_clear_cart_msgs',TRUE );
    if ($use_clear_cart_msgs) {
      //v1.08.1 begin
      //enqueue not doing it's thing on this one...  - NEED wp_head to allow for is_product, is_shop etc TEST in function...   
      //add_action( "wp_enqueue_scripts", array(&$this, 'vtmam_enqueue_page_reload_on_ajax') );
      add_action( "wp_head", array(&$this, 'vtmam_enqueue_page_reload_on_ajax') ); 
      add_action( "wp_enqueue_scripts", array(&$this, 'vtmam_enqueue_cart_resources') );      
      //v1.08.1 end                  
    }
    
    return;
    //*****************
    //v1.08 end
    //*****************
  
  }
  
  //*****************
  //v1.08 New Function
  //*****************
  function vtmam_enqueue_cart_resources() {
    wp_register_script('vtmam-clear-cart-msgs', VTMAM_URL.'/woo-integration/js/vtmam-clear-cart-msgs.js' ); 
    wp_enqueue_script ('vtmam-clear-cart-msgs', array('jquery'), false, true);
    //error_log( print_r(  'vtmam-clear-cart-msgs', true ) ); 
    
    //*************************
    //v2.0.0a begin
    //*************************
    // the registered style is ending up at the bottom of the list, doesn't get activated until a screen refresh...
    // using inline style and the 'fake' registratiion puts it at the TOP in the 'embbeded' file
    
    //wp_register_style( 'vtmam-error-style', VTMAM_URL.'/core/css/vtmam-error-style.css' );  
    //wp_enqueue_style('vtmam-error-style');
    
    $vtmam_inline_css =  "
     /*CSS for Maximum Error Msg Display*/
            table.shop_table {clear:left} /*woo-only override, for table following msgs on cart page, but css also hits the same table on checkout page(no effect)*/
            div.vtmam-error {
             margin: 30px 0 0 0%;  /* v1.08 */
              /* v1.08  */
              /* margin: 30px 0 0 -15%; */
              /* width: 120%; */                
              width: 100%;   /* v1.08 */ 
              background-color: #FFEBE8;
              border-color: #CC0000;
              padding: 0px 0px 15px 1em;
              border-radius: 3px 3px 3px 3px;
              border-style: solid;
              border-width: 1px;
              line-height: 12px;
              font-size:10px;
              height:auto;
              float:left;
            }
            /* TEST TEST TEST TEST*********************************/
            /*div.vtmam-error p {  color:red; } */
            
            div.vtmam-error p {font-size:14px;line-height: 18px;}
            div.vtmam-error .error-title {
              color: red;
              font-size: 12px;
              letter-spacing: 0.1em;
              line-height: 2.6em;
              padding-bottom: 2px;
              text-decoration: underline;
              text-transform: uppercase;
            }
            div.vtmam-error .black-font {color:black;}
            div.vtmam-error .errmsg-begin {color:black;margin-left:20px;}
            div.vtmam-error .black-font-italic {color:black; font-style:italic;}
            div.vtmam-error .red-font-italic {color:red; font-style:italic;}
            div.vtmam-error .errmsg-text {color:blue;}
            div.vtmam-error .errmsg-amt-current,
            div.vtmam-error .errmsg-amt-required {  
              font-style:italic;
              }
             
             
             /* ***************************************************************** */
             /* TABLE FORMAT ERROR MSG AREA  */
             /* ***************************************************************** */
            div.vtmam-error #table-error-messages {float:left; color:black; width:100%;}
            div.vtmam-error .table-titles {float:left; width:100%; margin-top:15px;}
            div.vtmam-error .product-column {float:left; width:42%; }
            div.vtmam-error .quantity-column {float:left; width:18%; }
            div.vtmam-error .price-column {float:left; width:15%; } 
            div.vtmam-error .total-column {float:left; /*width:25%; */}
            div.vtmam-error .product-column-title, 
            div.vtmam-error .quantity-column-title, 
            div.vtmam-error .price-column-title, 
            div.vtmam-error .total-column-title {
              text-decoration:underline; 
              } 
            div.vtmam-error .quantity-column-total, 
            div.vtmam-error .total-column-total {
              text-decoration:overline; font-weight:bold; font-style:italic; width:auto;
              }
            div.vtmam-error .table-error-msg  {color:blue; float:left; margin:-1px 5px 3px 20px; font-size:16px;}
            div.vtmam-error .table-error-msg2 {color:blue; float:left; margin:3px 0 3px 30px; font-size:14px;}  
            div.vtmam-error .bold-this {font-weight:bold}
             
            div.vtmam-error .table-msg-line {float:left; width:100%;}
            div.vtmam-error .table-totals-line {float:left; width:100%;margin-bottom: 10px;}
            div.vtmam-error .table-text-line {float:left; width:100%;}
            
            div.vtmam-error .rule-id {font-size:10px;margin-left:5px;color:black;}

            
            /*2.0.0a begin*/            /*  all commented
            div#line-cnt1  {height:80px;}
            div#line-cnt2  {height:120px;}
            div#line-cnt3  {height:150px;}
            div#line-cnt4  {height:180px;}
            div#line-cnt5  {height:210px;}
            div#line-cnt6  {height:240px;}
            div#line-cnt7  {height:270px;}
            div#line-cnt8  {height:300px;}
            div#line-cnt9  {height:330px;}
            div#line-cnt10 {height:360px;}
            div#line-cnt11 {height:390px;}
            div#line-cnt12 {height:420px;}
            div#line-cnt13 {height:450px;}
            div#line-cnt14 {height:480px;}
            div#line-cnt15 {height:510px;}
            div#line-cnt16 {height:540px;}
            div#line-cnt17 {height:570px;}
            div#line-cnt18 {height:600px;}
            div#line-cnt19 {height:630px;}
            div#line-cnt20 {height:660px;}
                                        */
                                        
            div#line-cnt1,
            div#line-cnt2,
            div#line-cnt3,
            div#line-cnt4,
            div#line-cnt5,
            div#line-cnt6,
            div#line-cnt7,
            div#line-cnt8,
            div#line-cnt9,
            div#line-cnt10,
            div#line-cnt11,
            div#line-cnt12,
            div#line-cnt13,
            div#line-cnt14,
            div#line-cnt15,
            div#line-cnt16,
            div#line-cnt17,
            div#line-cnt18,
            div#line-cnt19,
            div#line-cnt20 {height:auto;}             
            /*2.0.0a end*/ 
               
             
            /*alternating colors for rule groups*/
            
            div.vtmam-error .color-grp0 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp1 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp2 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp3 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp4 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp5 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp6 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp7 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp8 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp9 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp10 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp11 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp12 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp13 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp14 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp15 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp16 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp17 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp18 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp19 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-grp20 {color:RGB(197, 3, 3);}  /*dark red*/
            div.vtmam-error .color-xgrp1 {color:RGB(0, 255, 5);}  /*neon green*/            
            div.vtmam-error .color-xgrp2 {color:RGB(255, 93, 0);}  /*orange*/
            div.vtmam-error .color-xgrp3 {color:RGB(0, 115, 2);}  /*dark green*/
            div.vtmam-error .color-xgrp4 {color:RGB(244, 56, 56);}  /*light red*/
            div.vtmam-error .color-xgrp5 {color:RGB(255, 200, 0);}  /*ochre*/ 
            div.vtmam-error .color-xgrp6 {color:RGB(74, 178, 255);}  /*light blue*/
            div.vtmam-error .color-xgrp7 {color:RGB(37, 163, 162);}  /*dark teal*/                        
            div.vtmam-error .color-xgrp8 {color:RGB(47, 255, 253);}  /*light teal*/
            div.vtmam-error .color-xgrp9 {color:RGB(72, 157, 74);}  /*med green*/
            div.vtmam-error .color-xgrp10 {color:RGB(142, 146, 144);}  /*med grey*/            
            div.vtmam-error .color-xgrp11 {color:RGB(5, 71, 119);}  /*dark blue*/           
            div.vtmam-error .color-xgrp12 {color:RGB(0,0,0);}  /*black*/   
           ";
    
      wp_register_style( 'vtmam-inline-css', false );
	  wp_enqueue_style ( 'vtmam-inline-css' );
	  wp_add_inline_style( 'vtmam-inline-css', $vtmam_inline_css );
    
    //v2.0.0a end
    //*************************
    return;
  } 
 
   
  //*****************
  //v1.08.1 New Function
  //*****************
  function vtmam_enqueue_page_reload_on_ajax() {
 //error_log( print_r(  'BEGIN vtmam_enqueue_page_reload_on_ajax', true ) ); 
  
    global $vtmam_setup_options;

    /*
    if ( ($vtmam_setup_options['show_errors_on_more_pages'] == 'all') &&    
         (is_shop() || is_product_category() ) ) { //earliest this works is at add_action( 'wp', 'init' );
 //error_log( print_r(  'LOAD JS', true ) ); 
    reload js was here!
      
      //wp_register_script('vtmam-page-reload-on-ajax', VTMAM_URL.'/woo-integration/js/vtmam-page-reload-on-ajax.js' ); 
      //wp_enqueue_script ('vtmam-page-reload-on-ajax', array('jquery'), false, true);
    
    } 
    */

    if (isset( $vtmam_setup_options['show_errors_on_more_pages'] )) {
       switch( $vtmam_setup_options['show_errors_on_more_pages'] ) {
        /*
        case 'productPages':
            if (is_product() || is_cart() || is_checkout() )  {
              $do_nothing = true;
            } else {
                //js remove error...
            }  
          break;
         */ 
        case 'all':  
            //reload on ajax completion needed to run this plugin, possibly creating another error warning for min/max issue
            
            //if (is_cart() || is_checkout() )  { //earliest this works is at add_action( 'wp', 'init' ); <<== can't use this, causes a loop of some kind on the single product page.
            
            if (is_shop() || is_product_category() )  { //earliest this works is at add_action( 'wp', 'init' );
               //error_log( print_r(  'LOAD JS', true ) ); 
                  ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                           $( document ).ajaxComplete(function() { 
                              //alert ('about to reload');
                              location.reload(true);
                            });
                        });   
                    </script>
                  <?php
                  
            }
      
          break;
          
       }  //end switch 
     }  
    
  } 
  
   
         
  /* ************************************************
  **   Admin - Remove bulk actions on edit list screen, actions don't work the same way as onesies...
  ***************************************************/ 
  function vtmam_custom_bulk_actions($actions){
    
    ?> 
    <style type="text/css"> #delete_all {display:none;} /*kill the 'empty trash' buttons, for the same reason*/ </style>
    <?php
    
    unset( $actions['edit'] );
    unset( $actions['trash'] );
    unset( $actions['untrash'] );
    unset( $actions['delete'] );
    return $actions;
  }
    
  /* ************************************************
  **   v2.0.0a - rewritten - Admin - overhead stuff
  *************************************************** 
  *  This function is executed whenever the add/modify screen is presented
  *  WP also executes it ++right after the update function, prior to the screen being sent back to the user.   
  */  
	public function vtmam_admin_init(){
     
      //************************
      //v2.0.0a begin 
      //************************
      $pageURL = sanitize_url($_SERVER["REQUEST_URI"]); //v2.0.0a    //v2.0.0.1  for ManageWP and InfiniteWP conflicts. for ManageWP and InfiniteWP 'admin' is not currently in $_SERVER["REQUEST_URI"], so don't run
      if (strpos($pageURL,'wp-admin') === false) {   //v2.0.0a  ) {   must be wp-admin to access this function
        return;
      } 
      //v2.0.0a end 
      //************************ 

     //v2.0.0a begin
     if (!class_exists('VTMAM_Rules_UI')) {    //v2.0.0.1  for ManageWP and InfiniteWP conflicts. for ManageWP and InfiniteWP 'admin' is not currently in $_SERVER["REQUEST_URI"], so this class doesn't yet exist.
        return;
     }
     //v2.0.0a end

     global $vtmam_license_options, $vtmam_setup_options; //v2.0.0a

     if ( current_user_can( 'edit_posts', 'vtmam-rule' ) ) {   //v2.0.0a moved to wrap around the UI display
        $vtmam_rules_ui = new VTMAM_Rules_UI;  
     }
     
      require_once  ( VTMAM_DIRNAME . '/core/vtmam-backbone.php' ); 
     //v1.1.7.2 end          
     
     if (!$vtmam_license_options) {
        $vtmam_license_options = get_option( 'vtmam_license_options' ); 
     }     

  
  
    //v2.0.0a Licensing - begin
    //error_log( print_r(  'BEGIN vtmam_admin_init_overhead, current_pro_version= ' .$vtmam_setup_options['current_pro_version'] , true ) ); 
    
    $this->vtmam_maybe_update_version_num(); //v1.1.6.3
  
          //error_log( print_r(  'AFTER vtmam_maybe_update_version_num, current_pro_version= ' .$vtmam_setup_options['current_pro_version'] , true ) ); 
  
      //VTMAM_PRO_VERSION only exists if PRO version is installed and active
    if (defined('VTMAM_PRO_VERSION')) { //v1.1.6.1

      $this->vtmam_maybe_pro_deactivate_action(); //pro only
      $this->vtmam_license_count_check(); //pro only
      //***************
      //v1.1.8.2 begin
      // require_once added here as the 2 functions below are in that file, and will not be there at admin_init time using the standard init path!
      //***************
      require_once ( VTMAM_DIRNAME . '/admin/vtmam-license-options.php'); 
      //v1.1.8.2 end 

      if ( function_exists('vtmam_maybe_delete_pro_plugin_action') ) { //v1.1.8.2 weird occasional fatal on not finding this function...
        vtmam_maybe_delete_pro_plugin_action(); //pro only
      }
      
      //vtmam_maybe_admin_recheck_license_activation(); //v1.1.6  fallback to cron job //pro only
      if ( function_exists('vtmam_recheck_license_activation') ) { //v1.1.8.2 weird occasional fatal on not finding this function...      
        vtmam_recheck_license_activation(); //v1.1.6.3  fallback to cron job //pro only
      }
    } 
    
    $this->vtmam_maybe_version_mismatch_action(); 
    //v2.0.0a Licensing - end 
    
    return;  
  }
  
 
  /* ************************************************
  **   Admin - Update Rule 
  *************************************************** */
	public function vtmam_admin_update_rule(){
    /* *****************************************************************
         The delete/trash/untrash actions *will sometimes fire save_post*
         and there is a case structure in the save_post function to handle this.
    
          the delete/trash actions are sometimes fired twice, 
               so this can be handled by checking 'did_action'
     ***************************************************************** */
         /* ******************************************
       The 'SAVE_POST' action is fired at odd times during updating.
       When it's fired early, there's no post data available.
       So checking for a blank post id is an effective solution.
      *************************************************** */      
      global $post, $vtmam_rules_set;
                   //error_log( print_r(  'function begin vtmam_admin_update_rule', true ) );
      // v1.07.4 begin
      if( !isset( $post ) ) {    
            //error_log( print_r(  'exit 001', true ) );
        return;
      }  
      // v1.07.4  end 
	  
      //v2.0.0a begin
      if ( !( $post->ID > ' ' ) ) { //a blank post id means no data to proces....
            //error_log( print_r(  'exit 002', true ) );
        return;
      } 
      //v2.0.0a end   
	  
      if ( !( 'vtmam-rule' == $post->post_type )) {
            //error_log( print_r(  'exit 003', true ) );
        return;
      }  
      
      if (( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
                //error_log( print_r(  'exit 004', true ) );
            return; 
      }
     if (isset($_REQUEST['vtmam_nonce']) ) {     //nonce created in vtmam-rules-ui.php  
          $nonce = $_REQUEST['vtmam_nonce'];
          if(!wp_verify_nonce($nonce, 'vtmam-rule-nonce')) { 
                //error_log( print_r(  'exit 005', true ) );
            return;
          }
      } 
      if ( !current_user_can( 'edit_posts', 'vtmam-rule' ) ) {
              //error_log( print_r(  'exit 006', true ) );
          return;
      }

      
      /* ******************************************
       The 'SAVE_POST' action is fired at odd times during updating.
       When it's fired early, there's no post data available.
       So checking for a blank post id is an effective solution.
      *************************************************** */      
      //if ( !( $post->ID > ' ' ) ) { //a blank post id means no data to proces....        //v2.0.0a moved abov
      //      //error_log( print_r(  'exit 007', true ) );
      //  return;
      //} 
      
      //AND if we're here via an action other than a true save, do the action and exit stage left
      $action_type = $_REQUEST['action'];
          //error_log( print_r(  '$action_type= ' .$action_type, true ) );
      if ( in_array($action_type, array('trash', 'untrash', 'delete') ) ) {
        switch( $action_type ) {
            case 'trash':               
                $this->vtmam_admin_trash_rule();  
              break;
            case 'untrash':
                $this->vtmam_admin_untrash_rule();
              break;
            case 'delete':
                $this->vtmam_admin_delete_rule();  
              break;
        }
            //error_log( print_r(  'exit 008', true ) );
        return;
      }
      
          //error_log( print_r(  'execute VTMAM_Rule_update', true ) );           
      $vtmam_rule_update = new VTMAM_Rule_update;
  }
   
  
 /* ************************************************
 **   Admin - Delete Rule
 *************************************************** */
	public function vtmam_admin_delete_rule(){

     global $post, $vtmam_rules_set; 
     
      //v2.0.0 begin
      if( !isset( $post ) ) {
        return;
      } 
     //v2.0.0 end 
	                 
     if ( !( 'vtmam-rule' == $post->post_type ) ) {
      return;
     }        

     if ( !current_user_can( 'delete_posts', 'vtmam-rule' ) )  {
          return;
     }
    
    $vtmam_rule_delete = new VTMAM_Rule_delete;            
    $vtmam_rule_delete->vtmam_delete_rule();
        
    if(defined('VTMAM_PRO_DIRNAME')) {
      require_once ( VTMAM_PRO_DIRNAME . '/core/vtmam-delete-purchaser-info.php' );   
    }
    
    return;
  }
  
  
  /* ************************************************
  **   Admin - Trash Rule
  *************************************************** */   
	public function vtmam_admin_trash_rule(){
     global $post, $vtmam_rules_set; 
     
      //v2.0.0 begin
      if( !isset( $post ) ) {
        return;
      }  
     //v2.0.0 end   
	  

     if ( !( 'vtmam-rule' == $post->post_type ) ) {
      return;
     }        
  
     if ( !current_user_can( 'delete_posts', 'vtmam-rule' ) )  {
          return;
     }  
     
     if(did_action('trash_post')) {    
         return;
    }
    
    $vtmam_rule_delete = new VTMAM_Rule_delete;            
    $vtmam_rule_delete->vtmam_trash_rule();
    
    return;  

  }
  
  
 /* ************************************************
 **   Admin - Untrash Rule
 *************************************************** */   
	public function vtmam_admin_untrash_rule(){
     global $post, $vtmam_rules_set; 
         
      //v2.0.0 begin
      if( !isset( $post ) ) {
        return;
      } 
     //v2.0.0 end 
	  

     if ( !( 'vtmam-rule' == $post->post_type ) ) {
      return;
     }        

     if ( !current_user_can( 'delete_posts', 'vtmam-rule' ) )  {
          return;
     }       
    $vtmam_rule_delete = new VTMAM_Rule_delete;            
    $vtmam_rule_delete->vtmam_untrash_rule();
    
    return;  
  }


  /* ************************************************
  **   Admin - Activation Hook
  *************************************************** */  
   function vtmam_activation_hook() {
     //the options are added at admin_init time by the setup_options.php as soon as plugin is activated!!!
    //verify the requirements for Vtmam.
    global $wp_version;
		if((float)$wp_version < 3.3){
			// delete_option('vtmam_setup_options');
			 wp_die( __('<strong>Looks like you\'re running an older version of WordPress, you need to be running at least WordPress 3.3 to use the Varktech Minimum and Maximum Purchase plugin.</strong>', 'vtmam'), __('VT Minimum Purchase not compatible - WP', 'vtmam'), array('back_link' => true));
			return;
		}
           
    //fix 02-13-2013 - changed php version_compare, altered error msg   
   if (version_compare(PHP_VERSION, VTMAM_EARLIEST_ALLOWED_PHP_VERSION) < 0) {    //'<0' = 1st value is lower 
			wp_die( __('<strong><em>PLUGIN CANNOT ACTIVATE &nbsp;&nbsp;-&nbsp;&nbsp;     Varktech Min and Max Purchase </em>
      <br><br>&nbsp;&nbsp;&nbsp;&nbsp;   Your installation is running on an older version of PHP 
      <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   - your PHP version = ', 'vtmam') .PHP_VERSION. __(' . 
      <br><br>&nbsp;&nbsp;&nbsp;&nbsp;   You need to be running **at least PHP version 5** to use this plugin.  
      <br><br>&nbsp;&nbsp;&nbsp;&nbsp;   Please contact your host and request an upgrade to PHP 5+ . 
      <br><br>&nbsp;&nbsp;&nbsp;&nbsp;   Then activate this plugin following the upgrade.</strong>', 'vtmam'), __('VT Min and Max Purchase not compatible - PHP', 'vtmam'), array('back_link' => true));
			return; 
		}

    if(defined('WOOCOMMERCE_VERSION') && (VTMAM_PARENT_PLUGIN_NAME == 'WooCommerce')) { 
      $new_version =      VTMAM_EARLIEST_ALLOWED_PARENT_VERSION;
      $current_version =  WOOCOMMERCE_VERSION;
      if( (version_compare(strval($new_version), strval($current_version), '>') == 1) ) {   //'==1' = 2nd value is lower 
  			// delete_option('vtmam_setup_options');
  			 wp_die( __('<strong>Looks like you\'re running an older version of WooCommerce. <br>You need to be running at least ** WooCommerce 1.0 **, to use the Varktech Minimum and Maximum Purchase plugin.</strong>', 'vtmam'), __('VT Minimum Purchase not compatible - WooCommerce', 'vtmam'), array('back_link' => true));
  			return;
  		}
    }   else 
    if (VTMAM_PARENT_PLUGIN_NAME == 'WooCommerce') {
        wp_die( __('<strong>Varktech Minimum and Maximum Purchase for WooCommerce requires that WooCommerce be installed and activated.</strong>', 'vtmam'), __('WooCommerce not installed or activated', 'vtmam'), array('back_link' => true));
  			return;
    }
 
    
      //*********************
      //v2.0.0 begin
      //*********************
      // run this to serialize the  'vtmam_rules_set' option as required in php8, if plugin was previously installed...
      //MUST be done IMMEDIATELY
      
      require_once  ( VTMAM_DIRNAME . '/woo-integration/vtmam-parent-functions.php');   
      require_once  ( VTMAM_DIRNAME . '/woo-integration/vtmam-parent-definitions.php');

      //check if setup_options exist, or need repairing. //v2.0.0b
      $this->vtmam_maybe_update_version_num();  //v2.0.0b  fix setup options, do update warnings
       	  
      $this->vtmam_check_for_data_updates();   //v2.0.0b  do any db or file updates
         
      //v2.0.0 end
      //********************* 
     
     return;
  }
 

   //V1.07.3 begin                          
   function vtmam_admin_notice_version_mismatch() {
      //v2.0.0 begin
      /*
      $message  =  '<strong>' . __('Please also update plugin: ' , 'vtmam') . ' &nbsp;&nbsp;'  .VTMAM_PRO_PLUGIN_NAME . '</strong>' ;
      $message .=  '<br>&nbsp;&nbsp;&bull;&nbsp;&nbsp;' . __('Your Pro Version = ' , 'vtmam') .VTMAM_PRO_VERSION. ' &nbsp;&nbsp;' . __(' The Minimum Required Pro Version = ' , 'vtmam') .VTMAM_MINIMUM_PRO_VERSION ;      
      $message .=  '<br>&nbsp;&nbsp;&bull;&nbsp;&nbsp;' . __('Please delete the old Pro plugin from your installation via ftp.'  , 'vtmam');
      $message .=  '<br>&nbsp;&nbsp;&bull;&nbsp;&nbsp;' . __('Go to ', 'vtmam');
      $message .=  '<a target="_blank" href="http://www.varktech.com/download-pro-plugins/">Varktech Downloads</a>';
      $message .=   __(', download and install the newest <strong>'  , 'vtmam') .VTMAM_PRO_PLUGIN_NAME. '</strong>' ;
      
      $admin_notices = '<div id="message" class="error fade" style="background-color: #FFEBE8 !important;"><p>' . $message . ' </p></div>';
      echo $admin_notices;
      */
      //v2.0.0 end
      
      
      //v1.07.7 added
      $plugin = VTMAM_PRO_PLUGIN_SLUG;
			if( is_plugin_active($plugin) ) {
			   deactivate_plugins( $plugin );
      }      
                
      return;    
  }   
   //V1.07.3 end    


     
  //***************************
  //v2.0.0  new function 
   
  // Message for ALL future updates
  //***************************
  
  public function vtmam_check_for_data_updates(){
        global $vtmam_info;

        //*************************
        //v2.0.0 begin

        $vtmam_data_update_options = get_option('vtmam_data_update_options');
        
        if (!$vtmam_data_update_options) {
          $vtmam_data_update_options = array();
        }
                
        $dataUpd = '2.0.0 Ruleset conversion';
 
        if ( (isset ($vtmam_data_update_options['required_updates'][$dataUpd])) &&
             ($vtmam_data_update_options['required_updates'][$dataUpd] === true) ) {
          //error_log( print_r(  'Done with Engines', true ) );
          $done_with_engines = true;
        } else {
                          
          //SERIALIZE the rules_set array 
          $vtmam_rules_set = vtmam_get_rules_set(); //v2.0.0
          //v2.0.3 
          $sizeof_rules_set = is_array($vtmam_rules_set) ? sizeof($vtmam_rules_set) : 0; //v2.0.0
          if ($sizeof_rules_set > 0) {  //v2.0.0
            //++++++++++++++++
            //BACKUP - so this can be rolled back....
            $todaysDate = date("Y.m.d");                      
            $option_key = 'vtmam_rules_set_v2.0.0_bkup_' .$todaysDate;
            $flat_rules_set = serialize($vtmam_rules_set);
            update_option( $option_key,$flat_rules_set ); 
 
            vtmam_set_rules_set($vtmam_rules_set);     //serialize the array and update the option.
          }
          
          if (!is_array($vtmam_data_update_options)) {
             $vtmam_data_update_options = array();
          }
          
          $vtmam_data_update_options['required_updates'][$dataUpd] = true; 
          update_option('vtmam_data_update_options',$vtmam_data_update_options); 

        }          
        //v2.0.0 end
        //*************************                
       
    return;
        
  }


   //**************************** 
   //v2.0.0 Licensing new function PRO ONLY
   //**************************** 
   public function vtmam_maybe_system_requirements() {
     global $vtmam_license_options;
    if (!defined('VTMAM_PRO_VERSION')) {
      return;    
    }

    
    //v2.0.0 begin 

    if (!$vtmam_license_options) {
      $vtmam_license_options = get_option('vtmam_license_options');
    }
    
    if ($vtmam_license_options['state'] == 'suspended-by-vendor') {
      return;    
    }

    //if fatal counts exceed limit, never allow pro plugin to be activated
    if ($vtmam_license_count >= 10 ) { //v1.1.6.7 upgraded from 5 to 10!
      vtmam_deactivate_pro_plugin();
      $vtmam_license_options['state'] = 'suspended-by-vendor';
      $vtmam_license_options['status'] = 'invalid';
      $vtmam_license_options['diagnostic_msg'] = 'suspended until contact with vendor';
      update_option('vtmam_license_options', $vtmam_license_options);
                    
    }    
      
    //display any system-level licensing issues
    $this->vtmam_maybe_pro_license_error();
     
    //v2.0.0 end
    
    return; 
  }

  
  /* ************************************************
  **   v2.0.0 Licensing - new function, run at plugin init
  * ONLY RUN IF PRO VERSION IS installed
  * However, the PRO version may have been deactivated
  * when this runs, so no test is applied directly     
  *************************************************** */ 
	public function vtmam_init_update_license() {
    global $vtmam_license_options;
    
    //don't run if license_options.php has NEVER RUN!
    if( get_option( 'vtmam_license_options' ) !== FALSE ) {
      $carry_on = true;  
    } else {
      return;
    }
    
       //error_log( print_r(  'BEGIN vtmam_init_update_license, global $vtmam_license_options=' , true ) );   

    /* vtmam_license_suspended / vtmam_license_checked
    is only created during the plugin updater execution

    However, you can't update the options table consistently, so this is done instead. 
    If the call to the home server produces a status change, it's updated here.
      ( Can't update vtmam_license_options in the plugin updater, things explode!! )
    */
    if (get_option('vtmam_license_suspended')) {
      $vtmam_license_options2 = get_option('vtmam_license_suspended');
      $vtmam_license_options['status']  = $vtmam_license_options2['status'];
      $vtmam_license_options['state']   = $vtmam_license_options2['state'];
      $vtmam_license_options['strikes'] = $vtmam_license_options2['strikes'];
      $vtmam_license_options['diagnostic_msg'] = $vtmam_license_options2['diagnostic_msg'];
      $vtmam_license_options['last_failed_rego_ts']        = $vtmam_license_options2['last_failed_rego_ts']; 
      $vtmam_license_options['last_failed_rego_date_time'] = $vtmam_license_options2['last_failed_rego_date_time']; 
      $vtmam_license_options['last_response_from_host'] = $vtmam_license_options2['last_response_from_host']; //v1.1.6
      $vtmam_license_options['msg'] = $vtmam_license_options2['msg']; //v1.1.6
      //v1.1.6 begin
      //moved here from PHONE HOME, as the cron job timing can't check is_installed!
      if ($license_data->state == 'suspended-by-vendor') {   
        vtmam_deactivate_pro_plugin();
      }
      //v1.1.6 end
      //update status change
      update_option('vtmam_license_options', $vtmam_license_options);
       //error_log( print_r(  'UPDATED FROM  vtmam_license_suspended', true ) );  
      //cleanup
      delete_option('vtmam_license_suspended'); 
      return;   //if suspneded, no further processing.        
    }
     
    if (get_option('vtmam_license_checked')) {
      $vtmam_license_options2 = get_option('vtmam_license_checked');
      $vtmam_license_options['last_successful_rego_ts']        = $vtmam_license_options2['last_successful_rego_ts']; 
      $vtmam_license_options['last_successful_rego_date_time'] = $vtmam_license_options2['last_successful_rego_date_time'];  
      //update ts change
      update_option('vtmam_license_options', $vtmam_license_options);
          
      //cleanup
      delete_option('vtmam_license_checked');            
    }  

    
    
    //check for PRO VERSION MISMATCH, comparing from Either side
    //$vtmam_license_options['pro_version'] only has a value if pro version has ever been installed.
    //on Pro uninstall clear out these values, so that if plugin uninstalled, values and accompanying error messages don't display!
    

    $pageURL = sanitize_url($_SERVER["REQUEST_URI"]); //v2.0.3
    if (strpos($pageURL,'wp-admin') !== false) {   //v2.0.3       
      /* vtmam_pro_plugin_deleted 
      is only created if the pro plugin is deleted by the admin.
      However, you can't update the options table consistently, so this is done instead. 
      If the call to the home server produces a status change, it's updated here.
        ( Can't update vtmam_license_options in the plugin updater, things explode!! )
      */     
      if (get_option('vtmam_pro_plugin_deleted')) {
        $vtmam_license_options['pro_version'] = null;      
        $vtmam_license_options['pro_plugin_version_status'] = null;
        $vtmam_license_options['pro_minimum_free_version'] = null; 
        update_option('vtmam_license_options', $vtmam_license_options);
  
        //cleanup
        delete_option('vtmam_pro_plugin_deleted');            
      }   
              
      $this->vtmam_pro_version_verify(); //v1.1.6.3 refactored into new function

      //v1.1.6.1 begin
      //conversion to storing home_url, used in anchors ...
      if ( (!isset($vtmam_license_options['home_url'])) ||
           ($vtmam_license_options['home_url'] == null) ) {
         $vtmam_license_options['home_url'] = sanitize_url( home_url() );
         update_option('vtmam_license_options', $vtmam_license_options);   
      }
      //v1.1.6.1 end
      
    }  
        
    return;   
  }



  /* ************************************************
  **   v2.0.0 Licensing - new function, run at admin init  
  *************************************************** */ 
	public function vtmam_pro_version_verify() {
    global $vtmam_license_options;               
    
      //EDIT only if PRO plugin installed or active
      if (defined('VTMAM_PRO_VERSION')) {
        $carry_on = true;
      } else {
        $pro_plugin_is_installed = vtmam_check_pro_plugin_installed();
        if ($pro_plugin_is_installed !== false) {
           $vtmam_license_options['pro_version'] = $pro_plugin_is_installed;
        } else {
 
          //PRO is not installed, however there may be cleanup to do if pro mismatch status left over
          if ($vtmam_license_options['pro_plugin_version_status'] == 'Pro Version Error' ) {
            $vtmam_license_options['pro_version'] = FALSE;
            $vtmam_license_options['pro_plugin_version_status'] = 'valid'; 
            update_option('vtmam_license_options', $vtmam_license_options);
          } 

          return;
        }    
      }
    
          //error_log( print_r(  'vtmam_pro_version_verify 001' , true ) ); 
      //PICK up any defined values from active PRO.  If inactive, the license_options value will have previously-loaded values
      //if ((defined('vtmam_PRO_DIRNAME')) )   { //changed to PRO_VERSION because PRO_DIRNAME is now controlled in THIS file 
      if (defined('VTMAM_PRO_VERSION')) {
      
          //error_log( print_r(  'vtmam_pro_version_verify 002' , true ) ); 
        if ( ($vtmam_license_options['pro_version'] == VTMAM_PRO_VERSION) &&
             ($vtmam_license_options['pro_minimum_free_version'] == VTMAM_PRO_MINIMUM_REQUIRED_FREE_VERSION) ) {
      
          //error_log( print_r(  'vtmam_pro_version_verify 003' , true ) );             
            $carry_on = true;   //v1.1.6.6
        } else {
       
          //error_log( print_r(  'vtmam_pro_version_verify 005' , true ) );        
          $vtmam_license_options['pro_version'] = VTMAM_PRO_VERSION;
          $vtmam_license_options['pro_minimum_free_version'] = VTMAM_PRO_MINIMUM_REQUIRED_FREE_VERSION;
          //update_option('vtmam_license_options', $vtmam_license_options);
        }

      } 

      if ($vtmam_license_options['pro_version'] > '') {
       
          //error_log( print_r(  'vtmam_pro_version_verify 006' , true ) );      
        if (version_compare($vtmam_license_options['pro_version'], VTMAM_MINIMUM_PRO_VERSION) < 0) {    //'<0' = 1st value is lower 
          
          //error_log( print_r(  'vtmam_pro_version_verify 007' , true ) );            
          $vtmam_license_options['pro_plugin_version_status'] = 'Pro Version Error'; 
        } else {
       
          //v1.1.6.7 begin
          // if previously pro version error, this would have been set, to allow a PLUGIN UPDATE.  Update has been completed, so no longer necessary!
          if ($vtmam_license_options['pro_plugin_version_status'] == 'Pro Version Error') {
            delete_option('vtmam_do_pro_plugin_update');  
          }
          //v1.1.6.7 begin
            
          //error_log( print_r(  'vtmam_pro_version_verify 008' , true ) );      
          $vtmam_license_options['pro_plugin_version_status'] = 'valid'; 
        }
        
        if ($vtmam_license_options['pro_plugin_version_status'] == 'valid') { 
         
          //error_log( print_r(  'vtmam_pro_version_verify 009' , true ) );     
          if  (version_compare(VTMAM_VERSION, $vtmam_license_options['pro_minimum_free_version']) < 0) {    //'<0' = 1st value is lower   
          
          //error_log( print_r(  'vtmam_pro_version_verify 010' , true ) );             
            $vtmam_license_options['pro_plugin_version_status'] = 'Free Version Error';
            //$vtmam_license_options['state']  = 'pending';  //v1.1.6.3 changed from PRO deactivation to status change
            //$vtmam_license_options['status'] = 'invalid';  //v1.1.6.3 changed from PRO deactivation to status change            
          } else {
       
          //error_log( print_r(  'vtmam_pro_version_verify 011' , true ) );          
            $vtmam_license_options['pro_plugin_version_status'] = 'valid'; 
          }
        } 
      //error_log( print_r(  'vtmam_pro_version_verify 012' , true ) );                         
        update_option('vtmam_license_options', $vtmam_license_options);
                         
      } 
        //error_log( print_r(  'vtmam_pro_version_verify 013' , true ) );     
      return;   
  }


  /* ************************************************
  **   v2.0.0 Licensing - new function, run at admin init  
  *************************************************** */ 
	public function vtmam_maybe_version_mismatch_action() {

    //if PRO **not active** but installed, and VERSION ERROR, still do the messaging
    //can only do this AFTER or as part of admin_init
    global $vtmam_license_options;
    if (!$vtmam_license_options) {
      $vtmam_license_options = get_option('vtmam_license_options');
    }
    
    if (( isset($vtmam_license_options['status']) ) &&            //v2.0.3
        (!$vtmam_license_options['pro_version']) ) {  //'pro_version' only has data when pro plugin INSTALLED
      return;
    } 

   
    if ($vtmam_license_options['pro_plugin_version_status'] == 'Pro Version Error') {
      //*******************
      //v1.1.6.7 refactored
      //ONLY show if the plugin is actually INSTALLED!!
      if (defined('VTMAM_PRO_VERSION')) {
        $pro_plugin_is_installed = TRUE;
      } else {
        $pro_plugin_is_installed = $this->vtmam_maybe_pro_plugin_installed(); // function pro_plugin_installed must be in the class!!
      }     
      if ($pro_plugin_is_installed !== false) {
         //v1.1.8.2 - ONLY SEND if previously registered - REGISTRATION SUPERCEDES MISMATCH
        add_action( 'admin_notices',    array(&$this, 'vtmam_admin_notice_version_mismatch_pro') );    //v2.0.0a moved above the below if
        add_action( 'after_plugin_row', array(&$this, 'vtmam_plugin_notice_version_mismatch_pro' ), 10, 3  );  //v2.0.0a moved above the below if
        
        if ( ($vtmam_license_options['status'] == 'valid') &&  //v1.1.8.2
             ($vtmam_license_options['state']  == 'active') ) { //v1.1.8.2 
           //v1.1.6.7 - plugin updater now runs *only* when a plugin mismatch is detected in the free version - so there must always be paired updates!! 
          update_option('vtmam_do_pro_plugin_update', TRUE);  //v1.1.6.7 ==>> allows pro_plugin_update action!
        }  //v1.1.8.2 

      //v1.1.6.7 end 
      //******************* 
      }  
    }
    
    if ($vtmam_license_options['pro_plugin_version_status'] == 'Free Version Error') {
      //v1.1.6.3 begin
      //ONLY show if the plugin is actually INSTALLED!!
      $pro_plugin_is_installed = $this->vtmam_maybe_pro_plugin_installed(); // function pro_plugin_installed must be in the class!!
      if ($pro_plugin_is_installed !== false) {      
        add_action( 'admin_notices',array(&$this, 'vtmam_admin_notice_version_mismatch_free') ); 
      }
      //v1.1.6.3 end                 
    } 
         
    return;    
  }  
 


   //**************************** 
   //  v2.0.0 Licensing -  new function, run at admin init  
   //****************************                       
   public function vtmam_admin_notice_version_mismatch_pro() {
  
      //error_log( print_r(  'Function begin - vtmam_admin_notice_version_mismatch_pro', true ) );

      global $vtmam_license_options;
   
      //$pageURL = $_SERVER["REQUEST_URI"];  //v2.0.3
      $pageURL =  sanitize_url($_SERVER["REQUEST_URI"]); //v2.0.3 
      //error_log( print_r(  '$pageURL = ' .$pageURL, true ) );     

      switch( true ) { 
        case ($vtmam_license_options['state'] == 'suspended-by-vendor'):   //v2.0.0b      
                return; //no action required if license is suspended
             break;
             
        case (strpos($pageURL,'delete-selected') !== false ):         
                return; //annoying to have warnings on the delete page!
             break;
 
        case (strpos($pageURL,'vtmam_license_options_page') !== false ):         
                //v1.1.6.7  NOW handled in vtmam-license-options as a direct message, as admin-notices are sometimes blocked by a conflicting plugin!! 
                return;
             break;
   
         case (strpos($pageURL,'plugin-install') !== false ):         
                //v2.0.3 annoying to have message on plugin install page
                return;
             break; 
         case (strpos($pageURL,'upload-plugin') !== false ):         
                //v2.0.3 annoying to have message on plugin install/upload page
                return;
             break; 
         //v2.0.0.1 begin
         case (strpos($pageURL,'plugins.php') !== false ):         
                $admin_notices = vtmam_full_pro_upd_msg(); //V2.0.0a MSG move to functions.php        
                $allowed_html = vtmam_get_allowed_html(); //v2.0.0
                echo wp_kses($admin_notices ,$allowed_html ); //v2.0.0
                return;
             break;
         //v2.0.0.1 end                                
        default:          
            
            $message  =  '<strong>' . __('Update Required for: ' , 'vtmam') . ' &nbsp;&nbsp;'  .VTMAM_PRO_PLUGIN_NAME . '</strong>' ;
            $message .=  "<span style='color:red !important;font-size:16px;'><strong><em>&nbsp;&nbsp;&nbsp; (pro plugin will **not discount** until updated)</em></strong></span>" ;  //v1.1.7 change color, wording
            
            $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __('Your Pro Version = ' , 'vtmam') .$vtmam_license_options['pro_version'] .'&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . __(' <em>Required</em> Pro Version = ' , 'vtmam') .VTMAM_MINIMUM_PRO_VERSION .'</strong>'; 
                         
            //ALL UPDATES MUST NOW BE MANUAL.
               
              $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;<span style="font-size:16px;font-weight:bold;">' . __('Go to the '  , 'vtmam') .'&nbsp;&nbsp;';
              $homeURL = VTMAM_ADMIN_URL.'plugins.php?plugin_status=all&paged=1&s';            //v2.0.0.a updated
              $message .=  '<a href="'.esc_url($homeURL).'">Plugins Page</a> For Instructions</span>' ;              //v2.0.0.a updated
           
            //v2.1.0 END              
          break;      
      }
      
      
      $admin_notices = '<div id="message" class="error fade" style="background-color: #FFEBE8 !important;"><p style="font-size: 18px !important;">' . $message . ' </p></div>';
      
      //echo $admin_notices; //v2.0.3
      $allowed_html = vtmam_get_allowed_html(); //v2.0.3
      echo wp_kses($admin_notices ,$allowed_html ); //v2.0.3
      
      return;    
  }       

   //**************************** 
   //  v2.0.0 Licensing -  new function, run at admin init 
   //****************************       
    function vtmam_plugin_notice_version_mismatch_pro( $plugin_file, $plugin_data, $status ) {
       global $vtmam_license_options;
       if (!$vtmam_license_options) {
          $vtmam_license_options = get_option( 'vtmam_license_options' ); 
       } 

       if ( ($vtmam_license_options['pro_plugin_version_status'] == 'Pro Version Error') &&  
          //  (strpos( $plugin_file, 'pricing-deals-for-woocommerce' ) !== false )  ) {
            (strpos( $plugin_file, VTMAM_PRO_PLUGIN_FOLDER ) !== false )  ) {
   
            if ( (isset($plugin_data['url'])) && 
                 (isset($plugin_data['package'])) &&
                 ($plugin_data['url'] !== false) &&
                 ($plugin_data['package'] !== false) ) {              
              //**************************************************************************
              //if update nag data is found, message unneccessary, 
              //   and actually gums up the works, so don't send!
              //**************************************************************************
              return;                    
            }
         
            $message  =  '<td colspan="5" class="update-msg" style="line-height:1.2em; font-size:12px; padding:1px;">';
            $message .=  '<div style="color:#000; font-weight:bold; margin:4px 4px 4px 5%; width:80%; padding:6px 5px; background-color:#fffbe4; border-color:#dfdfdf; border-width:1px; border-style:solid; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">';
            $message .=  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __('Get the New version ', 'vtmam') .'&nbsp; - &nbsp;&nbsp;<em>'. VTMAM_MINIMUM_PRO_VERSION .'</em>&nbsp;&nbsp; - &nbsp;'. __(' *required* &nbsp;&nbsp; for ', 'vtmam')  .'&nbsp;&nbsp;' . VTMAM_PRO_PLUGIN_NAME  ;
            $message .=  "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:red !important;font-size:16px;'><em>&nbsp;&nbsp;&nbsp; (pro plugin will **not discount** until updated)</em></span>" ; //v1.1.7 change color, wording
            //$message .=  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .VTMAM_PRO_PLUGIN_NAME  .'&nbsp;&nbsp;&nbsp;&nbsp;' . __('New version ', 'vtmam') .'&nbsp;&nbsp;<em>'. VTMAM_MINIMUM_PRO_VERSION .'</em>&nbsp;&nbsp;'. __(' *required* ! ', 'vtmam')   ;
            
            //***************** 
            //v2.1.0 begin
            //*****************
            /*
            
            WITH THE UPDATE TO THE INMOTION SERVER, AUTO UPDATES FOR PRICING DEALS NO LONGER FUNCTION
            
            ALL UPDATES MUST NOW BE MANUAL.
            */ 
            
              $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;' . __('See Plugin update instructions above'  , 'vtmin') .'&nbsp;&nbsp;';     //v2.0.0a        

            //v2.1.0 END
                       
            $message .=  '</div	></td>';  
                      
          //echo $message; //v2.0.3
          $allowed_html = vtmam_get_allowed_html(); //v2.0.3
          echo wp_kses($message ,$allowed_html ); //v2.0.3
      }
      
      return;
    }
  
   //**************************** 
   //   v2.0.0 Licensing -  new function, run at admin init 
   //****************************                       
   public function vtmam_admin_notice_version_mismatch_free() {
  
      //error_log( print_r(  'Function begin - vtmam_admin_notice_version_mismatch_free', true ) );
      global $vtmam_license_options;
      $message  =  '<strong>' . __('Please update the FREE plugin: ' , 'vtmam') . ' &nbsp;&nbsp;'  .VTMAM_PLUGIN_NAME . '</strong>' ;
      //VTMAM_PRO_VERSION only exists if PRO version is installed and active
      if (defined('VTMAM_PRO_VERSION')) {
        $message .=  '<br>&nbsp;&nbsp;&bull;&nbsp;&nbsp;' . __('Required FREE version  = ' , 'vtmam') .$vtmam_license_options['pro_minimum_free_version']. ' &nbsp;&nbsp;<strong>' . 
              __(' Current Free Version = ' , 'vtmam') .VTMAM_VERSION .'</strong>';
      }  else {
        $message .=  '<br>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<strong>' . __('FREE Plugin update required!! ' , 'vtmam').'</strong>';
      }          
            
      $message .=  '<br><br><strong>' . 'The PRO Plugin:' . ' &nbsp;&nbsp;</strong><em>'  .VTMAM_PRO_PLUGIN_NAME . '</em>&nbsp;&nbsp;<strong>' . '  ** will not give discounts ** until this is resolved.' .'</strong>' ;              
                   
      $message .=  '<br><br>&nbsp;&nbsp; 1. &nbsp;&nbsp;<strong>' . __('You should see an update prompt on your '  , 'vtmam');
      $homeURL = VTMAM_ADMIN_URL.'plugins.php?plugin_status=all&paged=1&s';
      $message .=     '<a class="ab-item" href="'.esc_url($homeURL).'">' . __('Plugins Page', 'vtmam') . '</a>'; //v1.1.8.2 
      $message .=     __(' for a FREE Plugin automated update'  , 'vtmam') .'</strong>';
      $message .=  '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;' . __('If no FREE Plugin update nag is visible, you can request Wordpress to check for an update: '  , 'vtmam');
      $homeURL = VTMAM_ADMIN_URL.'edit.php?post_type=vtmam-rule&page=vtmam_license_options_page&action=force_plugin_updates_check';
      $message .=  '<a href="'.esc_url($homeURL).'">' . __('Check for Plugin Updates', 'vtmam'). '</a>'; //v1.1.8.2 - bounces to license page, which then sets the transient and goes on to the plugins page.      

      $message .=  '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;' . __('Be sure to  <em> re-Activate the PRO Plugin </em>, once the FREE plugin update has been completed. ', 'vtmam');
      $message .=  '</strong>';
      
      $message .=  "<span style='color:grey !important;'><br><br><em>&nbsp;&nbsp;&nbsp; (This message displays when the Pro version is installed, regardless of whether it's active)</em></span>" ;

      $admin_notices = '<div id="message" class="error fade" style="background-color: #FFEBE8 !important;"><p>' . $message . ' </p></div>';
      //echo $admin_notices; //v2.0.3
      $allowed_html = vtmam_get_allowed_html(); //v2.0.3
      echo wp_kses($admin_notices ,$allowed_html ); //v2.0.3
      return;    
  } 
       
  /* ************************************************
  **   v2.0.0 Licensing -  new function, run at admin init
  *************************************************** */ 
	public function vtmam_maybe_pro_plugin_installed() {
     
    // Check if get_plugins() function exists. This is required on the front end of the
    // site, since it is in a file that is normally only loaded in the admin.
    if ( ! function_exists( 'get_plugins' ) ) {
    	require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    
    $all_plugins = get_plugins();

    foreach ($all_plugins as $key => $data) { 
      if ($key == VTMAM_PRO_PLUGIN_FOLDER.'/'.VTMAM_PRO_PLUGIN_FILE) {    
        return true;      
      } 
    } 
    
    return false;  
 
  }
    

   //*************************
   //v2.0.0 licensing -  new function       //v2.0.0a
   //*************************   
   /*
   If plugin activated
    unregistered - Yellow box rego msg on all pages - mention that PRO will not work until registered - handles 1st time through
    suspended - fatal msg everywhere
    other stuff  - msg on plugins page and plugin pages - mention that PRO will not work until registered
   If plugin deactivated
    unregistered - none
    suspended - fatal msg everywhere
    other stuff  - none  
   */
   
	public function vtmam_maybe_pro_license_error() {
     //if PRO is ACTIVE or even INSTALLED, do messaging.
    //error_log( print_r(  'Begin vtmam_maybe_pro_license_error', true ) );
    
    global $vtmam_license_options;
    
    $pageURL = sanitize_url($_SERVER["REQUEST_URI"]); 
    switch( true ) { 
        case (strpos($pageURL,'delete-selected') !== false ):         
        case (strpos($pageURL,'vtmam_license_options_page') !== false ):         
        case (strpos($pageURL,'plugin-install') !== false ):         
        case (strpos($pageURL,'upload-plugin') !== false ):         
                //v2.0.3 annoying to have message on plugin install/upload page
                return;
             break;                   
        default:          
            
      
              //if deactivated, warn that PRO will NOT function!!
                //VTMAM_PRO_VERSION only exists if PRO version is installed and active
              if ( (defined('VTMAM_PRO_VERSION')) &&
                   ($vtmam_license_options['status'] == 'valid') &&
                   ($vtmam_license_options['state']  == 'deactivated') ) {
                $message = '<span style="color:black !important;">
                             &nbsp;&nbsp;&nbsp;<strong> ' . VTMAM_ITEM_NAME .   ' </strong> &nbsp;&nbsp; License is not registered</span>';
                $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '** the PRO Plugin will not function until Registered** ' ; 
                $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '* Please go the the ' ;  
                $homeURL = VTMAM_ADMIN_URL.'edit.php?post_type=vtmam-rule&page=vtmam_license_options_page';
                $message .=  '&nbsp; <a href="'.esc_url($homeURL).'">License Page</a> &nbsp;' ; //v1.1.8.2       
                $message .=  ' and REGISTER the PRO License. </strong>' ;  
                $admin_notices = '<div class="error fade is-dismissible" 
                  style="
                        line-height: 19px;
                        padding: 0px 15px 11px 15px;
                        font-size: 14px;
                        text-align: left;
                        margin: 25px 20px 15px 2px;
                        background-color: #fff;
                        border-left: 4px solid #ffba00;
                        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); " > <p>' . $message . ' </p></div>';  //send yellow box
                //echo $admin_notices; //v2.0.3
                $allowed_html = vtmam_get_allowed_html(); //v2.0.3
                echo wp_kses($admin_notices ,$allowed_html ); //v2.0.3 
                return;  
              }
             break; 
    }    
    
    if (( isset($vtmam_license_options['status']) ) &&            //v2.0.3
         ($vtmam_license_options['status'] == 'valid') ) {
        return;
    }  
        
    //$pageURL = $_SERVER["REQUEST_URI"];  //v2.0.3
    $pageURL = sanitize_url($_SERVER["REQUEST_URI"]); //v2.0.3

    //***************************************************************** //v1.1.8.2
    //License page messaging handled in license-options.php, so EXIT!
    //***************************************************************** //v1.1.8.2
    if (strpos($pageURL,'vtmam_license_options_page') !== false ) {    
      return;
    }
    
    $pro_plugin_installed = false;
      //VTMAM_PRO_VERSION only exists if PRO version is installed and active
    
    /* v2.0.0 begin recoded and placed below
    if (defined('VTMAM_PRO_VERSION')) { 
      
      //PRO IS INSTALLED and ACTIVE, show these msgs on ALL PAGES       
      if ($vtmam_license_options['state'] == 'suspended-by-vendor') { 
        $this->vtmam_pro_suspended_msg();            
        return;   
      }    
      if ($vtmam_license_options['status'] != 'valid')  { //v1.1.8.2 
        $this->vtmam_pro_unregistered_msg();            
        return;
      }   
                   
      $pro_plugin_installed = true; //show other error msgs
    }
    */
    if (defined('VTMAM_PRO_VERSION')) { 
        $pro_plugin_installed = true; //show other error msgs
    }
    //v2.0.0 end
    
    
    if (!$pro_plugin_installed) {       
      $pro_plugin_installed = vtmam_check_pro_plugin_installed();
    }
     
    //if pro not in system, no further msgs
    if (!$pro_plugin_installed) {   
      return;
    }
    
    //IF PRO at least installed, show this on ALL pages (except license page)
    if ($vtmam_license_options['state'] == 'suspended-by-vendor') { 
      $this->vtmam_pro_suspended_msg(); 
      return;     
    } 
    
    /*  v2.0.0b REMOVED in favor of other message from vtmam_pro_unregistered_msg
    //show other msgs for Plugins Page and vtmam pages 
      //VTMAM_PRO_VERSION only exists if PRO version is installed and active
    if ( (defined('VTMAM_PRO_VERSION')) &&                                                          //v2.0.0b
         ($vtmam_license_options['state'] == 'pending') &&                                          //v2.0.0b
         ($vtmam_license_options['pro_plugin_version_status'] != 'Pro Version Error') ) {         //v2.0.0b
      //ACTIVE PRO Plugin and we are on the plugins page or a vtmam page

        //OTHER MESSAGES, showing on vtmam Pages and PLUGINS.PHP
        $message = '<span style="color:black !important;">
                     &nbsp;&nbsp;&nbsp;<strong> ' . VTMAM_ITEM_NAME .   ' </strong> has NOT been successfully REGISTERED, and **will not function until registered**. </span><br><br>';
        $message .= '&nbsp;&nbsp;&nbsp; Licensing Error Message: <em>' . $vtmam_license_options['msg'] . '</em>';
        $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '* Please go the the ' ;  
        $homeURL = VTMAM_ADMIN_URL.'edit.php?post_type=vtmam-rule&page=vtmam_license_options_page';
        $message .=  '&nbsp; <a href="'.esc_url($homeURL).'">License Page</a> &nbsp;' ;  //v1.1.8.2        
        $message .=  ' for more information. </strong>' ;  
        $admin_notices = '<div class="error fade is-dismissible" 
          style="
                line-height: 19px;
                padding: 0px 15px 11px 15px;
                font-size: 14px;
                text-align: left;
                margin: 25px 20px 15px 2px;
                background-color: #fff;
                border-left: 4px solid #ffba00;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
                box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); " > <p>' . $message . ' </p></div>';  //send yellow box
        //echo $admin_notices; //v2.0.3
        $allowed_html = vtmam_get_allowed_html(); //v2.0.3
        echo wp_kses($admin_notices ,$allowed_html ); //v2.0.3       
        return;      
      // } //v1.1.8.2
    }        
    */
      
    
    /*  v2.0.0b REMOVED in favor of other message from vtmam_pro_unregistered_msg
    //show other msgs for Plugins Page and vtmam pages 
      //VTMAM_PRO_VERSION only exists if PRO version is installed and active
    if ( (defined('VTMAM_PRO_VERSION')) 
          &&
       ( (strpos($pageURL,'plugins.php') !== false ) || 
         (strpos($pageURL,'vtmam')       !== false ) ) ) {
      //ACTIVE PRO Plugin and we are on the plugins page or a vtmam page

        //OTHER MESSAGES, showing on vtmam Pages and PLUGINS.PHP
        $message = '<span style="color:black !important;">
                     &nbsp;&nbsp;&nbsp;<strong> ' . VTMAM_ITEM_NAME .   ' </strong> has NOT been successfully REGISTERED, and **will not function until registered**. </span><br><br>';
        $message .= '&nbsp;&nbsp;&nbsp; Licensing Error Message: <em>' . $vtmam_license_options['msg'] . '</em>';
        $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '* Please go the the ' ;  
        $homeURL = VTMAM_ADMIN_URL.'edit.php?post_type=vtmam-rule&page=vtmam_license_options_page';
        $message .=  '&nbsp; <a href="'.esc_url($homeURL).'">License Page</a> &nbsp;' ; //v1.1.8.2 
        $message .=  ' for more information. </strong>' ;  
        $admin_notices = '<div class="error fade is-dismissible" style="background-color: #FFEBE8 !important;"><p>' . $message . ' </p></div>';
        //echo $admin_notices; //v2.0.3
        $allowed_html = vtmam_get_allowed_html(); //v2.0.3
        echo wp_kses($admin_notices ,$allowed_html ); //v2.0.3
        return;  //v2.0.0b
      // } //v1.1.8.2
    }
    */ 
    
    //v2.0.0b begin
    if ( ($vtmam_license_options['status'] != 'valid') &&
         ($vtmam_license_options['pro_plugin_version_status'] != 'Pro Version Error') )  { //v1.1.8.2 
      $this->vtmam_pro_unregistered_msg();            
      return;
    }       
    //v2.0.0b end     
    
  return;  
  } 
  
  //********************************
  //v2.0.0 licensing - new function     //v2.0.0a
  //********************************
	public function vtmam_pro_unregistered_msg() { 
    //plugin version mismatch takes precedence over registration message.
    global $vtmam_license_options;
/* v1.1.8.2 removed
    if ( ($vtmam_license_options['pro_plugin_version_status'] == 'valid') ||
         ($vtmam_license_options['pro_plugin_version_status'] == null)) { //null = default
      $carry_on = true;
    } else { 
      return;
    }
*/
    
   
    $message  = '<h2>' . VTMAM_PRO_PLUGIN_NAME . '</h2>';
      //VTMAM_PRO_VERSION only exists if PRO version is installed and active    
    $homeURL = VTMAM_ADMIN_URL.'edit.php?post_type=vtmam-rule&page=vtmam_license_options_page';
    
    /*   v2.0.0b shortened to BELOW
    if (VTMAM_PRO_VERSION == VTMAM_PRO_LAST_PRELICENSE_VERSION) {
      $message .=   '<strong>' . __(' - We have introduced Plugin Registration,' , 'vtmam')  ; 
      $message .=  '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __('Please take a moment to ', 'vtmam')  ;
      $message .=  '<a href="'.esc_url($homeURL).'">register</a>' ; //v1.1.8.2 
      $message .=   __(' your plugin.', 'vtmam')  ;
      $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __('You may use your original purchase <em>SessionID</em> as your registration key.', 'vtmam')  ;
      
      $message .=  '<h3 style="color:grey !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>' . __(' Your PRO plugin will not function until registered', 'vtmam')  . '</em>' . '</h3>' ;    
    } else {
     // $message .= '<span style="background-color: RGB(255, 255, 180) !important;"> ';
      $message .=   '<strong>' . __(' - Requires valid ' , 'vtmam')  ; //v1.1.8.2
      $message .=  '<a href="'.esc_url($homeURL).'">Registration</a>' ; //v1.1.8.2 
      $message .=  '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>' . __(' and will not function until registered -', 'vtmam')  . '</em><br><br>' ; //. '</span>' ;        
    }
    */

      $message .=   '<strong>' . __(' - Requires valid ' , 'vtmam')  ; //v1.1.8.2
      $message .=  '<a href="'.esc_url($homeURL).'">Registration</a>' ; //v1.1.8.2 
      $message .=  '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>' . __(' and will not function until registered -', 'vtmam')  . '</em><br><br>' ; //. '</span>' ;  
                             
    $message .=  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="'.esc_url($homeURL).'">Register Pro License</a></strong> ' ; //v1.1.8.2 
             
     
     //yellow line box override      
    $admin_notices = '<div class="error fade is-dismissible" 
      style="
            line-height: 19px;
            padding: 0px 15px 11px 15px;
            font-size: 14px;
            text-align: left;
            margin: 25px 20px 15px 2px;
            background-color: #fff;
            border-left: 4px solid #ffba00;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); " > <p>' . $message . ' </p></div>';
    //echo $admin_notices; //v2.0.3
    $allowed_html = vtmam_get_allowed_html(); //v2.0.3
    echo wp_kses($admin_notices ,$allowed_html ); //v2.0.3  
    return;
  } 
 
        
  /* ************************************************
  **  //v2.0.0a licensing -  new function   
  *************************************************** */ 
	public function vtmam_maybe_update_version_num() { 
    global $vtmam_license_options, $vtmam_setup_options;

      //error_log( print_r(  'BEGIN vtmam_maybe_update_version_num ', true ) );
    
/*  CURRENTLY, this function has to run all the time, to pick up the new 
    //vtmam_new_version_in_progress ONLY created if plugin_updater has found one.
    //this function updates the current version ONLY after an UPDATED install is complete.
    if (get_option('vtmam_new_version_in_progress') !== false) {
           //error_log( print_r(  'vtmam_new_version OPTION = ' .get_option('vtmam_new_version'), true ) );  
       $carry_on = true;  
    } else {
      return;
    }
 */   
    //v2.0.0b begin
    require_once ( VTMAM_DIRNAME . '/admin/vtmam-setup-options.php');
    require_once ( VTMAM_DIRNAME . '/admin/vtmam-license-options.php'); 
     
    $vtmam_setup_options = get_option( 'vtmam_setup_options' );  
	if  ( ( !$vtmam_setup_options ) ||
          (!isset($vtmam_setup_options['show_error_messages_in_table_form'])) ) {   //picks up incorrect setup_options from previous generation of plugin       
       $vtmam_setup_plugin_options = new VTMAM_Setup_Plugin_Options;
       $default_options = $vtmam_setup_plugin_options->vtmam_get_default_options();
       update_option( 'vtmam_setup_options', $default_options );
       $vtmam_setup_options = get_option( 'vtmam_setup_options' );
    } 
    
    vtmam_maybe_update_pro_version_num(); 

    //v2.0.0b end
    
    
    
    //v2.0.0b begin moved to functions
    /*
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
    */
    //v2.0.0b end
    
           //error_log( print_r(  'vtmam_maybe_update_version_num, $vtmam_setup_options =', true ) );
              //error_log( var_export($vtmam_setup_options, true ) ); 
            //error_log( print_r(  '$pro_plugin_installed = ' .$pro_plugin_installed, true ) );   
    return;
  }  


          
  /* ************************************************
  **  //v2.0.0a licensing -  new function
  * //only runs if PRO version is installed and active    
  *************************************************** */ 
	public function vtmam_maybe_pro_deactivate_action() {
    global $vtmam_license_options;             

    if ($vtmam_license_options['state'] == 'suspended-by-vendor') {                                  
      //set up deactivate during admin_init - it's not available yet! done out of vtmam_maybe_pro_deactivate_action
      $vtmam_license_options['pro_deactivate'] = 'yes';
      update_option('vtmam_license_options', $vtmam_license_options); 
    }

    if ($vtmam_license_options['pro_deactivate'] != 'yes') {
      return;
    }
    
    
    vtmam_deactivate_pro_plugin();
    vtmam_increment_license_count(); 
    $vtmam_license_options['pro_deactivate'] = null;
    update_option('vtmam_license_options', $vtmam_license_options); 
                        
    if ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ){   
           //error_log( print_r(  'PRO deactivated, VTMAM_PRO_DIRNAME not defined ', true ) );
    }
  
    
    return; 
  } 

  
  /* ************************************************
  ** //v2.0.0a licensing -  new function, run at admin init 
  * //only runs if PRO version is installed and active     
  *************************************************** */ 
	public function vtmam_license_count_check() {

    $vtmam_license_count = get_option( 'vtmam_license_count');
    if (!$vtmam_license_count) {
      return;
    }
    //if PRO **not active** but installed, and VERSION ERROR, still do the messaging
    //can only do this AFTER or as part of admin_init
    global $vtmam_license_options;
    if (!$vtmam_license_options) {
      $vtmam_license_options = get_option('vtmam_license_options');
    }
    
    if ($vtmam_license_options['state'] == 'suspended-by-vendor') {
      return;    
    }
      //VTMAM_PRO_VERSION only exists if PRO version is installed and active
    if (!defined('VTMAM_PRO_VERSION')) {
      return;
    }
   
    //if fatal counts exceed limit, never allow pro plugin to be activated
    if ($vtmam_license_count >= 10 ) { //v1.1.6.7 upgraded from 5 to 10!
      vtmam_deactivate_pro_plugin();
      $vtmam_license_options['state'] = 'suspended-by-vendor';
      $vtmam_license_options['status'] = 'invalid';
      $vtmam_license_options['diagnostic_msg'] = 'suspended until contact with vendor';
      update_option('vtmam_license_options', $vtmam_license_options);
                    
    }
    
    return;    
  } 
   
  /* ***********************
  ** //v2.0.0b New Function    
  ************************** */ 
	public function vtmam_pro_suspended_msg() { 
    global $vtmam_license_options;
    $message = '<span style="color:black !important;">
                 &nbsp;&nbsp;&nbsp;<strong> ' . VTMAM_PRO_PLUGIN_NAME .   ' </strong>
                 <span style="background-color: RGB(255, 255, 180) !important;">LICENSE HAS BEEN SUSPENDED. </span>
                 </span><br><br>';
    $message .= '&nbsp;&nbsp;&nbsp; Licensing Error Message: <em>' . $vtmam_license_options['msg'] . '</em>';           
    $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . '* ' .VTMAM_PRO_PLUGIN_NAME. ' HAS BEEN DEACTIVATED.' ;
    $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '* Please go to your ' ;  
    $homeURL = VTMAM_ADMIN_URL.'edit.php?post_type=vtmam-rule&page=vtmam_license_options_page';
    $message .=  '&nbsp; <a href="'.esc_url($homeURL).'">Register Pro License</a> &nbsp;' ; //v1.1.8.2 
    $message .=  ' page for more information. </strong>' ;  
              
    $message .=  "<span style='color:grey !important;'><br><br><em>&nbsp;&nbsp;&nbsp; (This message displays when the Pro version is installed, regardless of whether it's active)</em></span>" ;
    
    $admin_notices = '<div class="error fade is-dismissible" style="background-color: #FFEBE8 !important;"><p>' . $message . ' </p></div>';
    //echo $admin_notices; //v2.0.3
    $allowed_html = vtmam_get_allowed_html(); //v2.0.3
    echo wp_kses($admin_notices ,$allowed_html ); //v2.0.3
    
    //double check PRO deactivate
      //VTMAM_PRO_VERSION only exists if PRO version is installed and active
    if (defined('VTMAM_PRO_VERSION')) {  
      vtmam_deactivate_pro_plugin();
    }
       
    return;
  } 

   
   
  /* ************************************************
  **   Admin - **Uninstall** Hook and cleanup
  *************************************************** */ 
  function vtmam_uninstall_hook() {
      
      if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
      	return;
        //exit ();
      }
  
      delete_option('vtmam_setup_options');
      $vtmam_nuke = new VTMAM_Rule_delete;            
      $vtmam_nuke->vtmam_nuke_all_rules();
      $vtmam_nuke->vtmam_nuke_all_rule_cats();
      wp_clear_scheduled_hook( 'vtmam_twice_daily_scheduled_events' ); //v2.0.0a
      
  }


} //end class
$vtmam_controller = new VTMAM_Controller; 

  //***************************************************************************************
  //fix 02-13-2013  -  problems with activation hook and class, solved herewith....
  //   FROM http://website-in-a-weekend.net/tag/register_activation_hook/
  //***************************************************************************************
  if (strpos($_SERVER["REQUEST_URI"],'wp-admin') !== false) {    //v2.0.0 
        register_activation_hook(__FILE__, array($vtmam_controller, 'vtmam_activation_hook'));
        register_activation_hook(__FILE__, array($vtmam_controller, 'vtmam_uninstall_hook'));                                   
  }
