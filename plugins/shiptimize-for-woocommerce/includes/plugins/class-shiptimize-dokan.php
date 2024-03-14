<?php
//include_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(SHIPTIMIZE_PLUGIN_PATH.'/includes/admin/class-shiptimize-order-ui.php');
include_once(SHIPTIMIZE_PLUGIN_PATH.'/includes/core/class-shiptimize-api-v3.php');
include_once(SHIPTIMIZE_PLUGIN_PATH.'/includes/class-woo-shiptimize-order.php');
include_once(SHIPTIMIZE_PLUGIN_PATH.'/includes/class-woo-shiptimize.php');

/**
 * Allow for client specific shiptimize credentials.
 * Set ui for client to add credentials on seller dashboard
 * 
 */
class ShiptimizeDokan extends ShiptimizeMarketplace
{
    /**
     * An instance of the api
     */ 
    private $api = NULL; 

    /** 
     * The app key to send to the api 
     */ 
    protected $appkey = '16FDA156-8E10-387C-B83D-00608285FF08'; 

    /**
     * @var int $vendor_id 
     */ 
    public function __construct($vendor_id){
        $this->vendor_id = $vendor_id;  
        $this->is_dev = file_exists(ABSPATH.'isdevmachine'); 
    }

    public function actions()
    {
        parent::actions(); 

        /** action defined in templates/settings/store_form **/
        add_action( 'dokan_settings_form_bottom', array($this, 'ui_keys') );
        add_action( 'wp_ajax_dokan_settings',array($this,'ui_save_settings') );

        /** orders **/ 
        add_action( 'dokan_order_content_inside_after', array($this,'order_list_actions') );
        add_action( 'wp_ajax_shiptimize_dokan_export_selected', array($this, 'export_selected') );
        add_action( 'woocommerce_admin_order_actions_end', array ( $this, 'order_actions'), 50, 1 );

        add_action( 'wp_enqueue_scripts', array($this, 'scripts_and_styles' ), 50); 
    }


    public function api_update ($data) {
      if(!isset( $_GET['userid'] ) || !is_numeric($_GET['userid'])){       
        $error = json_encode( (object) array ( "Error"=> "Invalid userid, this is a marketplace. WCFM requires a userid" ) ); 
        error_log($error); 
        die($error); 
      } 

      $this->vendor_id = $_GET['userid']; 
 
      //Grant we are using the marketplace api obj 
      $api = $this->get_api(); 

      if( !$api->validate_update_request($data->Status,$data->TrackingId,$this->get_callback(),$data->Hash) ){
        error_log("RESTAURE!! API_UPDATE INVALID SIGNATURE IGNORING ");
        die(json_encode((object)array("Error"=> "Invalid Signature"))); 
      } 

      $order = new WooShiptimizeOrder($data->ShopItemId);
      if( $data->Status ){
        $order->set_status_from_api($data->Status); 
      }

      if( $data->TrackingId ){
        $order->set_tracking_id($data->TrackingId);
      }
      die(); 
    }

    public function get_api()
    {
        if ( $this->api == NULL ) {
            
            $public_key = get_user_meta($this->vendor_id, 'shiptimize_public_key',true);
            $private_key = get_user_meta($this->vendor_id,'shiptimize_private_key',true);

            $token = get_user_meta($this->vendor_id,'shiptimize_token',true);
            $token_expires = get_user_meta($this->vendor_id,'shiptimize_token_expires',true);

            if ($this->is_dev) {
                error_log("Dokan get_api public $public_key, private $private_key,  appkey $this->appkey, test false,token $token, token_expires $token_expires");     
            }
            $this->api = ShiptimizeApiV3::instance($public_key, $private_key, $this->appkey, false, $token, $token_expires);
        }

        return $this->api;
    }

    /**
     * @param int $orderid 
     * 
     */
    public function get_order_meta($orderid)
    {
        global $wpdb; 

        $results = $wpdb->get_results(" select * from `{$wpdb->prefix}shiptimize` where id={$orderid}");
        return count($results) ? $results[0] : null;
    }

    /** 
     * @var int $order_id - the order to send to shiptimize
     */ 
    public function export($orderids, $try = 0)
    { 
        if( $this->is_dev ){
            error_log("Dokan exporting orders " . var_export($orderids,true));
        }

       
        $append_errors = 1; 
        $summary = WooShiptimizeOrder::export($orderids, $try , $append_errors);
    
        $html = WooShiptimizeOrder::get_export_summary($summary);
        if (!empty($summary->errors) ) { 
            $html .= '<div class="shiptimize-errors">';
            foreach ($summary->errors as $error ) { 
                $html .= '<br/>' . $error; 
            }
            $html .= '</div>';
        }  
        return '<br/><br/>' . $html;         
    }
 
    /** 
     * Bulk actions 
     */ 
    public function order_list_actions() {
        echo '<button onclick="shiptimize_dokan.exportSelected()" style="background:#f2a900" class="dokan-btn dokan-btn-sm shiptimize-export-btn">' . WooShiptimize::instance()->translate('Export to') . ' Shiptimize</button>';  
    }

    /**
     * Actions for each order in a list  
     * It's the only decent spot to past the icons 
     */
    public function order_actions($order){   
        $order_id = $order->get_id(); 

        $orderui = new ShiptimizeOrderUI();  
        $ordermeta = $this->get_order_meta( $order_id ); 

        $column_data = '<div>' . $orderui->get_status_icon( $ordermeta ) .'</div>'; 
        echo $column_data;
    }

    public function refresh_token(){
        global $woocommerce; 

        $shop_url = $this->get_callback();
        $plugin_version=  WooShiptimize::$version;
        $api = $this->get_api();
        $token = $api->get_token($shop_url, $woocommerce->version, $plugin_version);
    
        //die(var_export($token)); 

       if( isset($token->Key) ) {
        update_user_meta($this->vendor_id,'shiptimize_token', $token->Key);
        update_user_meta($this->vendor_id,'shiptimize_token_expires', $token->Expire);
       }
       else {
        wc_add_notice("Invalid Credentials");
       }
    }

    /** 
     * Export selected Ids 
     */ 
    public function export_selected (){
        $ordersids = filter_input(INPUT_GET, 'ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY); 
        error_log("Export order ids = " . var_export($ordersids, true)); 
        echo $this->export($ordersids); 
    }

    /** 
     * Include these only in the orders view
     */  
    public function scripts_and_styles() { 
        if ( !current_user_can( 'dokandar' ) ) {
            return; 
        }

        wp_register_script( 'shiptimize_dokan' , SHIPTIMIZE_PLUGIN_URL.'assets/js/shiptimize-dokan.js', array ( 'jquery' ), '1.0.2' );     
        wp_enqueue_script( 'shiptimize_dokan' );    

        wp_register_script('shiptimize_admin_script' , SHIPTIMIZE_PLUGIN_URL.'assets/js/shiptimize-admin.js', array ( 'jquery' ), '1.0.2' ); 
        wp_enqueue_script( 'shiptimize_admin_script');     
        wp_register_style( 'shiptimize_admin_styles', SHIPTIMIZE_PLUGIN_URL.'assets/css/shiptimize-admin.css', array(), '1.0.1');
        wp_enqueue_style( 'shiptimize_admin_styles' ); 

           
        $data =' var shiptimize_label_sending = "' . WooShiptimize::instance()->translate('sending') . '";';
        wp_add_inline_script('shiptimize_dokan', $data);
    }

    /** 
     * @var string label what to display as label 
     * @var string $name the option name in the db 
     */ 
    private function ui_input ($label, $name){
        $value = get_user_meta($this->vendor_id, $name,true);
    ?> 
        <div class="dokan-form-group">
            <label class="dokan-w3 dokan-control-label"><?php echo $label?></label>
            <div class="dokan-w5 dokan-text-left"><input type='text' value="<?php echo $value ?>" name="<?php echo $name?>"/></div>
        </div>
    <?php

    }

    public function ui_keys()
    { 

        global $shiptimize; 
        echo "<hr/>";
        echo "<h3>Shiptimize</h3>";
        $keys = array(
            $shiptimize->__('Public Key') => 'shiptimize_public_key',
            $shiptimize->__('Private Key') => 'shiptimize_private_key',
        );

        foreach ($keys as $label => $name) {
            $this->ui_input($label, $name);
        }

        $token = get_user_meta($this->vendor_id,'shiptimize_token',true);
        if ($token) { 
?>
        <div class="dokan-form-group">
            <label class="dokan-w3 dokan-control-label">Token</label>
            <div class="dokan-w5 dokan-text-left"><?php echo $token ?></div>
        </div>
<?php         
        } 
        echo "<hr/>";
    }

    /** 
     * settings are saved via ajax action:dokan_settings
     */ 
    public function ui_save_settings(){
        $public_key = sanitize_text_field($_REQUEST['shiptimize_public_key']);
        $private_key = sanitize_text_field($_REQUEST['shiptimize_private_key']);

        update_user_meta($this->vendor_id,'shiptimize_public_key',trim($public_key));
        update_user_meta($this->vendor_id,'shiptimize_private_key',trim($private_key));

        $this->refresh_token();
    }

    /** 
     * Dokan creates separate orders for each seller 
     * @param int $orderid 
     * return an array of products [{id:'',name:'','qty':'','weight'},]
     */ 
    public function get_products($order_id) {

    } 
    
    /** 
     * All marketplaces should have a user id in url 
     * Because signature validation uses the private key 
     * That is unique to each user 
     */ 
    public function get_callback(){
        return site_url().'/?shiptimize_update=1&userid=' . $this->vendor_id;
    }
}

//  Plug our selves into the ui if it makes sense
if(function_exists('wp_get_current_user')) { 
  $current_user = wp_get_current_user(); 
  $user_id = 0; 
  if (current_user_can('administrator') ||  $current_user  && get_user_meta( $current_user->ID , 'dokan_enable_selling',true)  == 'yes'  ) {
    $user_id = $current_user->ID; 
  } 

  $shiptimize_dokan = new ShiptimizeDokan($user_id);
  $shiptimize_dokan->actions();
}