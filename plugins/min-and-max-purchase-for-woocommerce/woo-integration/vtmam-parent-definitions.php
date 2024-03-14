<?php
/*
VarkTech Minimum Purchase for WooCommerce
Woo-specific functions
Parent Plugin Integration
*/


class VTMAM_Parent_Definitions {
	
	public function __construct(){
    
    define('VTMAM_PARENT_PLUGIN_NAME',                      'WooCommerce');
    define('VTMAM_EARLIEST_ALLOWED_PARENT_VERSION',         '2.1');
    define('VTMAM_TESTED_UP_TO_PARENT_VERSION',             '2.3.8');
    define('VTMAM_DOCUMENTATION_PATH_PRO_BY_PARENT',        'http://www.varktech.com/woocommerce/min-and-max-purchase-pro-for-woocommerce/?active_tab=tutorial');                                                                                                     //***
    define('VTMAM_DOCUMENTATION_PATH_FREE_BY_PARENT',       'http://www.varktech.com/woocommerce/min-and-max-purchase-for-woocommerce/?active_tab=tutorial');      
    define('VTMAM_INSTALLATION_INSTRUCTIONS_BY_PARENT',     'http://www.varktech.com/woocommerce/min-and-max-purchase-for-woocommerce/?active_tab=instructions');
    define('VTMAM_PRO_INSTALLATION_INSTRUCTIONS_BY_PARENT', 'http://www.varktech.com/woocommerce/min-and-max-purchase-pro-for-woocommerce/?active_tab=instructions');
    define('VTMAM_PURCHASE_PRO_VERSION_BY_PARENT',          'http://www.varktech.com/woocommerce/min-and-max-purchase-pro-for-woocommerce/');
    define('VTMAM_DOWNLOAD_FREE_VERSION_BY_PARENT',         'http://wordpress.org/extend/plugins/min-and-max-purchase-for-woocommerce/');
    
    //html default selector locations in checkout where error message will display before.
    define('VTMAM_CHECKOUT_PRODUCTS_SELECTOR_BY_PARENT',    '.shop_table');        // PRODUCTS TABLE on BOTH cart page and checkout page
    define('VTMAM_CHECKOUT_ADDRESS_SELECTOR_BY_PARENT',     '#customer_details');      //  address area on checkout page    default = on


    global $vtmam_info;
    $default_full_msg   =  __('Enter Custom Message (optional)', 'vtmam');   //v1.07
    $vtmam_info = array(                                                                    
      	'parent_plugin' => 'woo',
      	'parent_plugin_taxonomy' => 'product_cat',
        'parent_plugin_taxonomy_name' => 'Product Categories',
        'parent_plugin_cpt' => 'product',
        'applies_to_post_types' => 'product', //rule cat only needs to be registered to product, not rule as well...
        'rulecat_taxonomy' => 'vtmam_rule_category',
        'rulecat_taxonomy_name' => 'Min and Max Purchase Rules',
        
        //elements used in vtmam-apply-rules.php at the ruleset level
        'error_message_needed' => 'no',
        'cart_grp_info' => '',
          /*  cart_grp_info will contain the following:
            array(
              'qty'    => '',
              'price'    => ''
            )
          */
        'cart_color_cnt' => '',
        'rule_id_list' => '',
        'line_cnt' => 0,
        'action_cnt'  => 0,
        'bold_the_error_amt_on_detail_line'  => 'no',
        'currPageURL'  => '',
        'woo_cart_url'  => '',
        'woo_checkout_url'  => '',
        'woo_pay_url'  => '',
        
        //elements used at the ruleset/product level 
        'purch_hist_product_row_id'  => '',              
        'purch_hist_product_price_total'  => '',      
        'purch_hist_product_qty_total'  => '',          
        'get_purchaser_info' => '',          
        'purch_hist_done' => '',
        'purchaser_ip_address' => vtmam_get_ip_address(), //v2.0.0 - changed since the IP address function is now a standalone, not part of the class
        'default_full_msg'  => $default_full_msg, //v1.07  
        
        //v2.0.0 begin
        'data_update_options_done_array'  => array ( 
            'required_updates'  => array (
                '2.0.0 Ruleset conversion'    => true          
            ),
            'optional_updates'  => array (
            )
         )
        //v2.0.0 end
      );

	}

 
} //end class
$vtmam_parent_definitions = new VTMAM_Parent_Definitions; 


  //*****************
  //v2.0.0 BEGIN 
  //*****************
  //NEEDS TO BE HERE

  function  vtmam_get_ip_address() {
    
    /* 
        //IF YOU MUST OVERRIDE THE IP ADDRESS ON A PERMANENT BASIS
        //USE SOMETHING LIKE https://www.site24x7.com/find-ip-address-of-web-site.html to find your website IP address (**NOT** your CLIENT ip address)
        //copy code begin
        add_filter('vtmam_override_with_supplied_ip_address', 'override_with_supplied_ip_address', 10 );        
        function override_with_supplied_ip_address() {  return 'YOUR IP ADDRESS HERE'; }
        //copy code end                
    */
    if (apply_filters('vtmam_override_with_supplied_ip_address',FALSE) ) {
      return apply_filters('vtmam_override_with_supplied_ip_address');
    }
    
    
    /*  // IP address license check can fail if you have copied your whole site with options table from one IP address to another
        // ==>>>>> only ever do this with a SINGLE RULE SCREEN ACCESS, 
        // then remove from your theme functions.php file ==>>>>> heavy server resource cost if executed constantly!!!!!!!
        //copy code begin
        add_filter('vtmam_force_new_ip_address', 'force_new_ip_address', 10 );        
        function force_new_ip_address() {  return 'yes'; } 
        //copy code end
    */
    if (apply_filters('vtmam_force_new_ip_address',FALSE) ) {
      $skip_this = true;
    } else {
      $vtmam_ip_address = get_option( 'vtmam_ip_address' );
      if ($vtmam_ip_address) {
        return $vtmam_ip_address;
      }    
    }

    
    //THIS ONLY OCCURS WHEN THE PLUGIN IS FIRST INSTALLED!
    // from http://stackoverflow.com/questions/4305604/get-ip-from-dns-without-using-gethostbyname
    
    //v1.1.6.3 refactored, put in test for php version
    $php_version = phpversion();
    if ( version_compare( $php_version, '5.3.1', '<' ) ) {
      $vtmam_ip_address = $_SERVER['SERVER_ADDR'];
    } else {    
      $host = gethostname();
      $query = `nslookup -timeout=$timeout -retry=1 $host`;
      if(preg_match('/\nAddress: (.*)\n/', $query, $matches)) {
        $vtmam_ip_address =  trim($matches[1]);
      } else {
        $vtmam_ip_address = gethostbyname($host);
      }    
    }	

    
    update_option( 'vtmam_ip_address', $vtmam_ip_address );
    
    return $vtmam_ip_address;

  }
  //v2.0.0 END
  //*****************