<?php 
include_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(SHIPTIMIZE_PLUGIN_PATH.'/includes/admin/class-shiptimize-order-ui.php');
require_once(SHIPTIMIZE_PLUGIN_PATH.'/includes/plugins/class-shiptimize-marketplace.php');

/** 
 * Integrate with wcfm 
 * - Vendors must have their own keys - saved in user meta 
 * - App key sent to shiptimize should be wcfm not woo 
 * - Order info does not include items 
 * - Automatic export on new order
 */ 
class ShiptimizeWCFM extends ShiptimizeMarketplace { 
 
    protected $wcfm_shipping_properties = array(
        '_wcfmmp_shipping_by_country',
        '_wcfmmp_country_rates',
        '_wcfmmp_state_rates',
        '_wcfmmp_country_weight_rates', 
        '_wcfmmp_country_weight_mode', 
        '_wcfmmp_country_weight_unit_cost', 
        '_wcfmmp_country_weight_default_costs', 
        '_wcfmmp_shipping_by_distance', 
        '_wcfmmp_shipping_by_distance_rates', 
        '_wcfmmp_shipping_by_weight', 
        
    ); 

    /** 
     * The app key to send to the api 
     */ 
    protected $appkey = '22CA8477-8B6B-3AF4-B4B0-2FC010DEB00D'; 


    /** 
     * @param int userid - the logged in user - the user in the marketplace, might not match the current user if it's admin 
     * if the variable vendors-manage is present consider that as the user instead of the current user 
     * $wp_query is not available at the time this class instanciated 
     */ 
    public function __construct($userid) {
        parent::__construct($userid); 
 
        $this->userid =  $userid;  
        $this->is_dev = file_exists(ABSPATH.'isdevmachine');  
        $this->connected =  $this->is_user_connected($userid); 
    }

    public function actions() {
        parent::actions(); 

        if ($this->userid) {
            $isactive = $this->isActive(); 
            add_action('wcfm_marketplace_shipping',array($this,'vendor_options'));
            
            /** When the vendor saves their settings **/ 
            add_action('wcfm_vendor_settings_update', array($this,'save_options'),50,2);

            /** when the admin saves the vendor's settings **/ 
            add_action('wcfm_vendor_shipping_settings_update', array($this,'save_options'),50,2);

            if($isactive){
                add_action('wcfm_after_orders_filter_wrap', array($this, 'order_list_before'),50,2); 
                add_filter( 'wcfm_orders_additional_info_column_label', function( $column_label ) { return  'Shiptimize'; });
                add_filter( 'wcfm_orders_additonal_data_hidden', function(){ return false; } );
                add_filter( 'wcfm_orders_additonal_data',array($this, 'shiptimize_column'), 50, 2);
            }
            
            add_action( 'wp_enqueue_scripts', array($this, 'scripts_and_styles' ), 50, 2); 

            /** 
             * Receive the ids to export  
             **/
            add_action( 'wp_ajax_shiptimize_export_selected', array( $this, 'ajax_export_selected' ) );     

            add_filter( 'wcfmmp_settings_fields_shipping', array($this, 'vendor_shipping_options'), 50, 3 );
            /** 
             * If users are connected to a master account then don't allow them to edit the rules. 
             */ 
            add_filter( 'wcfmmp_settings_fields_shipping_rates_by_country', array($this, 'vendor_shipping_rules'), 50, 2 );
            add_filter( 'wcfmmp_settings_fields_shipping_rates_by_weight', array($this, 'vendor_shipping_rules'), 50, 2 );
            add_filter( 'wcfmmp_settings_fields_shipping_by_distance', array($this, 'vendor_shipping_rules'), 50, 2 );

            add_filter( 'wcfmmp_settings_fields_shipping_by_country', array($this, 'vendor_shipping_fields'), 50, 2 );
            add_filter( 'wcfmmp_settings_fields_shipping_by_weight', array($this, 'vendor_shipping_fields'), 50, 2 );
            add_filter( 'wcfmmp_settings_fields_shipping_rates_by_distance', array($this, 'vendor_shipping_fields'), 50, 2 );
            
            /** 
             * Make sure connected vendors send comission to admin if the admin pays 
             * remember filters with multiple arguments must receive the number of arguments
             */ 
            add_filter( 'wcfmmp_vendor_get_shipping', array($this, 'vendor_shipping'), 50, 2);

            /** 
             * Save match between shiptimize carriers and wcfm rules 
             */ 
            add_action('wcfm_settings_update', array($this, 'save_shipping'),50,2); 

            /** 
             * Check if keys are valid 
             */ 
            add_action( 'wp_ajax_shiptimize_check_keys', array( $this, 'check_keys' ) );  

            /** 
             * If it's a master account 
             */ 
            if ($this->is_master_account()) {
                add_action( 'wcfm_vendors_actions', array( $this, 'vendor_actions' ), 50 ,2 );
            }

            /** 
             * Add an export link to the order details 
             */
            add_action( 'wcfm_after_order_quick_actions', array($this, 'before_order_details'), 50,1);

            /** 
             * Export single order from order details 
             */ 
            add_action('wp_ajax_shiptimize_wcfm_export_order', function (){ 
                echo $this->export(array($_GET['orderid']),0,1);
                die('');
            });

            add_action('wp_ajax_shiptimize_wcfm_export_orders', function(){
                echo $this->export($_GET['orderids'],0,1);
                die('');
            }); 
        }
    }
 
    /***
     * 
     */
    public static function after_order_object_save($order){
        if(\WooShiptimize::$is_dev) {
            error_log("after_order_object_save " . $order->get_id() );    
        }
        
        ShiptimizeWCFM::auto_export($order->get_id()); 
    }

    /** 
     * Displayed in the order details  page 
     */ 
    public function before_order_details($orderid ){
        echo "<button onclick='shiptimize_wcfm.exportOrder($orderid)' class='shiptimize-wcfm-export-selected shiptimize-btn '> " . WooShiptimize::instance()->translate('Export to') . " Shiptimize </button>";  
        echo "<div id='shiptimize-export-status'></div>";
    }

    /** 
     * Check if keys produced a token 
     */ 
    public function check_keys(){
        if (!$this->userid) {
            die("No user id is set ");
        }

        $token = get_user_meta($this->userid, 'shiptimize_token', true); 
        $data = (object)array("msg"=> "");   

        if(!$token){
            $data = (object)array("err" => WooShiptimize::instance()->translate('Invalid Credentials')); 
        }

        die(json_encode($data)); 
    }

    /**
     * Connect the user 
     */ 
    public function connect(){
        parent::connect($this->userid); 

        if (!$this->is_user_connected($this->userid)) {
            if($this->is_dev){
                error_log("user $this->userid is not yet connected");
            }
            return; 
        }

        if (!$this->is_master_account()) {
            if ($this->is_dev) {
                error_log("Not masteraccount ignoring admin rules"); 
            }
            return;
        }


        error_log("wcfm connect user " . $this->userid);
        $this->copy_shipping_rules_to_user($this->userid); 
    }

    /** 
     * Connected users should use the same rules as admin. 
     * We should keep a backup to restaure should they decide to disconnect
     * 1. Backup existing settings 
     * 2. Copy any admin rules into the user's profile 
     */ 
    public function copy_shipping_rules_to_user($userid, $backup=true) {  
        if($this->is_dev){
            error_log("===== Copy Shipping rules from admin to user $userid ===== "); 
        }

        foreach ($this->wcfm_shipping_properties as $meta_key) { 
            $value = get_user_meta($this->userid, $meta_key, true);
            if($value && $backup){
                // backup value 
                update_user_meta($userid,'shiptimizebck' . $meta_key, $value);
                if($this->is_dev){
                    error_log("Backup existing value for " . $meta_key  . ' ' . json_encode($value));
                }
            }

            // copy value from admin into the user 
            $value =  get_option($meta_key); 
            update_user_meta($userid, $meta_key, $value);  
            if($this->is_dev){
                error_log("Copy admin value for " . $meta_key  . ' ' .json_encode($value));
            }
        } 

        if($backup){
            /** Assign the default shipping method  **/ 
            $admin_default = get_option('shiptimize_default_shipping_type'); 

            if($admin_default){
                $shipping = get_user_meta($this->userid, '_wcfmmp_shipping',true);
                update_user_meta($this->userid, 'shiptimizebckshiptimize_default_shipping_type',$shipping['_wcfmmp_user_shipping_type']);

                if($this->is_dev){
                    error_log("Assign a shipping type as set by admin  " . $admin_default);     
                }
                error_log("WAS " . json_encode($shipping));
                $shipping['_wcfmmp_user_shipping_type'] = $admin_default;
                error_log("IS " . json_encode($shipping));
                update_user_meta($this->userid, '_wcfmmp_shipping', $shipping);    
            }            
        }
    }

    /** 
     * Restaure any previously existing shipping settings 
     */ 
    public function disconnect(){

        foreach($this->wcfm_shipping_properties as $meta_key) {

            $value = get_user_meta($this->userid, 'shiptimizebck' . $meta_key, true);
              
            if ($value) {
                if($this->is_dev){
                    error_log("Restaure value for " . $meta_key  . ' ' . json_encode($value));
                }
                update_user_meta($this->userid, $meta_key, $value); 
                delete_user_meta($this->userid, 'shiptimizebck' . $meta_key);
            }
        }

        $shipping_type = get_user_meta($this->userid, 'shiptimizebckshiptimize_default_shipping_type'); 
        if($shipping_type){
            $shipping = get_user_meta($this->userid, '_wcfmmp_shipping',true);
            $shipping['_wcfmmp_user_shipping_type'] = $shipping_type;
            update_user_meta($this->userid, '_wcfmmp_shipping', $shipping);
        }
        
        parent::disconnect();  
    }

    /** 
     * When the status changes, it's not necessarily something that happens by the hand of the vendor.
     * So we need to check for each vendor in the order if they enabled the auto export 
     * and if the status matches send the order to the api 
     * 
     * !!! The order can have a different status for the vendor and for the admin. 
     */ 
    public static function auto_export($orderid){
        global $wpdb; 
 
        $is_dev = WooShiptimize::instance()->is_dev;
        if ($is_dev) { 
            error_log("=====    Auto_export "); 
        }
        
        // wcfm ajax will post the order id 
        if (isset($_POST['order_id'])) {
            $orderid = absint( $_POST['order_id'] );
        }

        if(!$orderid || !is_numeric($orderid)){
            error_log("======  shiptimize wcfm invalid order for auto_export: [$orderid]"); 
            return; 
        }


        $order_sync  = isset( $WCFMmp->wcfmmp_marketplace_options['order_sync'] ) ? $WCFMmp->wcfmmp_marketplace_options['order_sync'] : 'no';

        $orderdata = $wpdb->get_results("select post_status from {$wpdb->prefix}posts where ID=$orderid"); 
        $orderstatus = $orderdata[0]->post_status; 

        $vendors = $wpdb->get_results("select vendor_id, commission_status from {$wpdb->prefix}wcfm_marketplace_orders where order_id=$orderid"); 



        foreach ($vendors as $vendor) { 
            if ($order_sync == 'no') {
                $orderstatus = 'wc-'.$vendor->commission_status; 
            }

            # That's how wclovers calls it.. :S 
            if ($orderstatus == 'wc-pending') { 
                $orderstatus = 'wc-on-hold';
            }

            $autoexport_status = get_user_meta($vendor->vendor_id, 'shiptimize_autoexport', true); 
            if($is_dev){
                error_log("Evaluating vendor $vendor->vendor_id ; auto_export_status: $autoexport_status ; orderstatus: $orderstatus "); 
            }

            if ($autoexport_status == $orderstatus) {
                if ($is_dev) { 
                    error_log("==== Exporting order $orderid with status $orderstatus/$autoexport_status; vendor ". var_export($vendor,true) . "   defined status:  $autoexport_status");
                }

                // Check if this order was already sucessfully exported for the user 
                $order_status = $wpdb->get_results("select status from {$wpdb->prefix}shiptimize_marketplace_order where vendorid=$vendor->vendor_id and orderid=$orderid");

                if(!$orderstatus || !isset($orderstatus[0]) || $order_status[0]->status != ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY){
                    $wcfm = new ShiptimizeWCFM($vendor->vendor_id); 
                    ob_start();
                    $wcfm->export(array($orderid)); 
                    ob_get_clean();   
                }
                else if($is_dev){
                    error_log("Order $orderid was already successfuly exported by the vendor ignoring "); 
                }
            } 
        }
    }
 
    public function custom_query_vars($vars){
        $vars = parent::custom_query_vars($vars);
        $vars[] = 'shiptimize_export_vendor'; 
        return $vars;
    } 

    public function ajax_export_selected () {   
        $orderids = filter_input(INPUT_POST, 'orderids',FILTER_DEFAULT , FILTER_REQUIRE_ARRAY); 
        $this->export($orderids); 
        die();
    } 

    public function isActive() {
        $public_key = get_user_meta($this->userid, 'shiptimize_public_key',true);
        $private_key = get_user_meta($this->userid, 'shiptimize_private_key',true); 
        return $this->connected && $public_key && $private_key;  
    }


    /** 
     * Return the client in an API friendly format 
     */ 
    public function get_client_data ($userid,$password=''){
        $userinfo = get_userdata($userid); 

        $username= $userinfo->user_login;
        $name = get_user_meta($userid,'first_name',true) . ' ' . get_user_meta($userid,'last_name',true); 
        $phone = get_user_meta($userid, 'billing_phone',true); 
        $streetname1 = get_user_meta($userid, '_wcfm_street_1',true); 
        $streename2 = get_user_meta($userid, '_wcfm_street_2', true); 
        $housenumber = ''; 
        $matches = array(); 
        
        if (preg_match('~([\d]+)~', $streetname1, $matches)) {
            $housenumber = $matches[1]; 
        }
        else if (preg_match('~([\d]+)~',$streename2, $matches)) {
            $housenumber = $matches[1]; 
        }

        return (object)  array ( 
                'Address' => array( 
                            (object)array ( 
                                'AddressType' => 1, //main address 
                                'City' => get_user_meta($userid,'_wcfm_city',true),
                                'CompanyName' => get_user_meta($userid, 'wcfmmp_store_name',true), 
                                'Country' => get_user_meta($userid,'_wcfm_country',true), 
                                'Email' => $userinfo->user_email, 
                                'HouseNumber' => $housenumber,
                                'Name' => get_user_meta($userid,'first_name',true) , 
                                'Neighborhood' => '', 
                                'NumberExtension' => '',  
                                'Phone'  => $phone,
                                'PostalCode' => get_user_meta($userid,'_wcfm_zip',true),  
                                'State' => '',  
                                'Streetname1' => $streetname1,
                                'Streetname2' => $streename2, 
                                'Timezone' => get_option('timezone_string') ? get_option('timezone_string') : 'Europe/Amsterdam', //Continent/City 
                                'VAT' => '123456789'
                            ) 
                ), 
                'Contact' =>  (object)array( 
                    'Email' => $userinfo->user_email, 
                    'Name' =>  $name,
                    'Phone' => $phone,  
                 ), 
                'User' => (object)array(
                    'Email' =>  $userinfo->user_email,  
                    'LoginName' => $username,  
                    'Name' => $name ,
                    'Password' => $password //*: 10-100 chars  
                ),
                'Invoicing' => (object)array(
                    'IBAN' => '0000 0000 0000 0000 00',
                    'SWIFT' => 'AAAAAAAA'
                ),
        ); 
    }

    public function export_field($value){
        return "\"$value\";"; 
    }

    /**
     *  shiptimize option ids match the DOM ids on the page
     *  @param WooShiptimizeOrder $shiptimize_order 
     *  Return a shiptimize carrier if defined 
     *  Match the vendor id 
     *  Match methodid and country 
     */
    public function get_carrier_for_order($shiptimize_order){
        $woo_order =  $shiptimize_order->get_woo_order(); 
        // if already set ignore 

        $items = $this->get_items_for_vendor($shiptimize_order->get_shop_item_id(), $this->userid);
        $shiptimize_rules=[]; 
        $wcfmp_rules = []; 
        $country = $shiptimize_order->get_country(); 

        foreach ($woo_order->get_items('shipping') as $item_id => $shipping_item  ){
            $shipping_method_id = $shipping_item->get_method_id(); 
            $shipping_instance_id = $shipping_item->get_instance_id(); 
            $vendor_id = wc_get_order_item_meta($item_id,'vendor_id',true); 
            error_log("Method id $shipping_method_id , Instance id $shipping_instance_id  , vendorid $vendor_id"); 

            if (stripos($shipping_method_id, 'wcfmmp') !== false) {  
                switch ($shipping_method_id) {
                    case 'wcfmmp_product_shipping_by_country':
                        $wcfmp_rules = get_option('_wcfmmp_state_rates');
                        $shiptimize_rules = get_option('shiptimize_wcfmmp_shipping_rates');
                        break;
                    case 'wcfmmp_product_shipping_by_weight':
                        $wcfmp_rules = get_option('_wcfmmp_shipping_by_weight');
                        $shipping_rules = get_option('shiptimize_wcfmmp_shipping_rates_by_weight');
                    break;
                    
                    default: 
                        break;
                }
                error_log("shiptimize_rules for method $shipping_method_id ".json_encode($shiptimize_rules)); 
            }
        }

        if($this->is_dev){
            error_log( "Shiptimize Rules " . json_encode($shiptimize_rules)); 
            error_log( "Wcfmp_rules for $country " . json_encode($wcfmp_rules));
        }
  
        $i = 0; 
        $carrier_id = ''; 
        foreach($wcfmp_rules as $rule_country => $rules){
            if($country  == $rule_country && isset($shiptimize_rules[$i])){
                $carrier_id = $shiptimize_rules[$i];
                if($this->is_dev){
                    error_log("Shiptimize Carrier $carrier_id "); 
                }
                return $carrier_id; 
            }
            ++$i;
        } 
        return ''; 
    }

    public function export_vendor(){
        global $WCFM;
        header("content-type:application/csv;charset=UTF-8");
        header('Content-Disposition: attachment;Filename="vendor_list.csv";'); 

        $vendor_arr = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list( true );
        echo '"CompanyName";Name";"Email";"Phone";"Streetname1";"Streetname2";"PostalCode";"City";"Contry"';
        foreach ($vendor_arr as $vendor_id => $vendor_name) {
            if($vendor_id){
                echo "\n";
                $client = $this->get_client_data($vendor_id);  
                echo $this->export_field($client->Address[0]->CompanyName);
                echo $this->export_field($client->Contact->Name); 
                echo $this->export_field($client->Contact->Email); 
                echo $this->export_field($client->Contact->Phone);
                echo $this->export_field($client->Address[0]->Streetname1);
                echo $this->export_field($client->Address[0]->Streetname2);
                echo $this->export_field($client->Address[0]->PostalCode);
                echo $this->export_field($client->Address[0]->City);
                echo $this->export_field($client->Address[0]->Country);
            }
        }
       die( ); 
    }    

    public function get_callback(){
        return site_url().'/?shiptimize_update=1&userid=' . $this->userid;
    }

    /** 
     * @override 
     * @param int $orderid 
     * return an array of products [{id:'',name:'','qty':'','weight'},]
     */ 
    public function get_products($orderid){

    }

    /** 
     * The orders status depends on the sync configuration. 
     * If plugin is configured to sync order status it's in the post table , else it's in the wcfm_marketplace_orders
     */ 
    public function get_vendor_order_status($orderid){
        global $wpdb; 

        $status = $wpdb->get_results("select commission_status from {$wpdb->prefix}wcfm_marketplace_orders where vendor_id=$this->userid and order_id=$orderid"); 

        return $status ?  $status[0]->commission_status  : '';   
    }

    /** 
     * Display the appropriate connection options 
     * only if admin or not master account 
     */ 
    public function vendor_options() {
        $masteraccount = $this->is_master_account()  ? 1 : 0;
        
        if(!current_user_can('administrator') && $masteraccount){
            return;
        }

        $userid =  get_query_var('vendors-manage', $this->userid);

        if ($userid != $this->userid) {
            $this->userid = $userid; 
            $this->connected = $this->is_user_connected($this->userid);     
        }
        
        $connector = ShiptimizeConnector::getInstance($this->userid); 
        $connector->options_section(array( 
        'masteraccount' => $masteraccount,
        'item_wrapper' => 'div', 
        'item_wrapper_class' => '',
        'label_item' => 'p',
        'label_class' => 'wcfmmp_pt wcfm_title wcfm_ele hide_if_shipping_disabled',
        'input_class' => 'wcfm-text wcfm_ele',
        'select_class' => 'wcfm-select',
        'private_key' => get_user_meta($this->userid, 'shiptimize_private_key',true),
        'public_key' => get_user_meta($this->userid, 'shiptimize_public_key', true),
        'token_expires' => get_user_meta($this->userid,'shiptimize_token_expires',true),
        'automatic_export' => true,
        'auto_export_status' => get_user_meta($this->userid, 'shiptimize_autoexport', true),
        'errors' =>  '',
        'username' => isset($username) ? $username : '', 
        'password' => isset($password) ? $password : '',
      ));          
    }

    /**   
     * Runs after the filters 
     */ 
    public function order_list_before() { 
        if(!$this->connected) {
            return; 
        }
        
        ?>
        <button onclick='shiptimize_wcfm.exportSelectedOrders()' class='shiptimize-wcfm-export-selected shiptimize-btn'>Export to Shiptimize</button>
        <div id='shiptimize-wcfm-message'></div>
        <script>
            /** 
             * Wait  for  the data to come in via AJAX and then do our thing
             */ 
            function shiptimize_column(){
                if(jQuery(".shiptimize-tooltip-message").length == 0) {
                    setTimeout(shiptimize_column,500); 
                    console.log("waiting...");
                    return;
                }
                shiptimize.tooltips();
            }

            shiptimize_column(); 

            <?php echo ' var shiptimize_label_sending = "' . WooShiptimize::instance()->translate('sending') . '";' ?> 
            
        </script>
        <?php
    }

    /** 
     * User is saving shipping 
     */ 
    public function save_shipping(){
        global $wpdb; 

        $wcfm_settings_form_data = array();
        parse_str($_POST['wcfm_settings_form'], $wcfm_settings_form); 
        
        foreach ($wcfm_settings_form as $key => $val) {
            if(stripos($key, 'shiptimize') !== false){ 
                update_option($key, $val);
                if($this->is_dev){
                    error_log("$key => ".var_export($val,true));    
                } 
            }
        }

        if ($this->is_master_account()) {
            if($this->is_dev){
                error_log("Master account, updating seller options"); 
            }

            $vendor_ids = $this->get_connected_users();

            foreach ($vendor_ids as $v) {
                if($this->is_dev){
                    error_log("updating seller $v->user_id"); 
                }
                $this->copy_shipping_rules_to_user( $v->user_id, false ); 
            }
        }
    }

    /** 
    * Remember these functions are called via AJAX 
    * These elements are not present when the page loads
    */
    public function shiptimize_column($column_data, $order_id) {
        if ($this->is_dev) {
            error_log("shiptimize_column user $this->userid, connected: $this->connected");
        }
        if (!$this->connected) {
            return; 
        }

        $order = new ShiptimizeOrderUI($order_id);  
        $ordermeta = $this->get_order_meta( $order_id); 

        $column_data = '<div>' . $order->get_status_icon( $ordermeta ) .'</div>'; 

        $column_data .= "<input type='checkbox' class='shiptimize-wcfm-checkbox wcfm-checkbox' name='shiptimize_order_" . $order_id . "[]' value='$order_id' class='wcfm-checkbox wcfm_ele'/>";
        return $column_data;
    }

    /** 
     * Shiptimize options for the marketplace admin 
     */ 
    public function shiptimize_options(){
        parent::shiptimize_options(); 

        $masteraccount =  $this->is_master_account() ? 1 : 0; 
        $default_shipping_type = $this->get_default_shipping_type(); 
?>
        <div class='shiptimize-settings__section'>
            <h2>WCLovers</h2> 
            <div class='shiptimize-settings__field'>
                <label class='shiptimize-settings__label'><?php echo WooShiptimize::instance()->translate('whopays')?></label>
                <select name='shiptimize_wcfm_master_account' onchange='shiptimize_account_type()'>
                    <option value='0'><?php echo WooShiptimize::instance()->translate('yourvendors')?></option>
                    <option value='1' <?php echo $masteraccount ? "selected" : ""?>><?php echo WooShiptimize::instance()->translate('you')?></option>
                </select>
                <p>
                    <?php echo WooShiptimize::instance()->translate('whopaysdescription'); ?> 
                </p>
            </div> 
            <div class='shiptimize-settings__field hidden' id='master-shipping'>
                <label class='shiptimize-settings__label'><?php echo WooShiptimize::instance()->translate('defaultshipping')?></label>
                <select name='shiptimize_default_shipping_type'>
                    <option value='by_country'<?php echo $default_shipping_type == 'by_weight' ? "selected" : ""?>><?php echo WooShiptimize::instance()->translate('by_country')?></option>
                    <option value='by_weight' <?php echo $default_shipping_type == 'by_weight' ? "selected" : ""?>><?php echo WooShiptimize::instance()->translate('by_weight')?></option>
                </select> 
            </div> 
            <div class='shiptimize-settings__field'>  
                <a class='button button-secondary' href='<?php echo site_url() ?>?shiptimize_export_vendor=1'>
                    <?php echo WooShiptimize::instance()->translate('exportvendorsbtn'); ?> 
                </a> 
            </div>  
        <script>
            function shiptimize_account_type(){
                var e = jQuery('select[name="shiptimize_wcfm_master_account"]'); 
                if(e.val() == 1){
                    jQuery("#master-shipping").show();
                }
                else {
                    jQuery("#master-shipping").hide();
                }
            }

            jQuery(function (){shiptimize_account_type(); }); 
        </script>
<?php 
    }

    /** 
     * Save the keys if present 
     * If both keys are set refresh the token for this user 
     */ 
    public function save_options($user_id, $wcfm_settings_form){
        if($this->is_dev){
            error_log("\nSaving options for user $user_id");
        }
 
        $this->userid = $user_id;       

        $settings = array(); 
        parse_str($_POST['wcfm_settings_form'],$settings); 
 
        $shiptimize_public_key = isset($settings['shiptimize_public_key']) ? trim($settings['shiptimize_public_key']) : ''; 
        $shiptimize_private_key = isset($settings['shiptimize_private_key']) ? trim($settings['shiptimize_private_key']) : '';  

        if($shiptimize_public_key) {
            update_user_meta($this->userid, 'shiptimize_public_key',$shiptimize_public_key);
        }

        if($shiptimize_private_key) {
            update_user_meta($this->userid, 'shiptimize_private_key',$shiptimize_private_key); 
        }

        if (isset($settings['shiptimize_autoexport'])) {
            update_user_meta($this->userid, 'shiptimize_autoexport',$settings['shiptimize_autoexport']);    
        }

        if($shiptimize_private_key && $shiptimize_public_key){
            $response = $this->refresh_token( );

            if($this->is_dev){
                error_log("APi sent back " . var_export($response,true));    
            } 
        }

        if ($this->is_user_connected($this->userid) && $this->is_master_account()){
            if($this->is_dev){
                error_log("saving options, $this->userid is connected to a master account, copying admin rules"); 
            }
            $this->copy_shipping_rules_to_user($this->userid,false);
        }
    }

    /** 
     * Include these only in the orders view
     */  
    public function scripts_and_styles() { 
        $isadmin = current_user_can('editor') || current_user_can('administrator');  

        //if(!wcfm_is_vendor()... 
        wp_register_script('shiptimize_admin_script' , SHIPTIMIZE_PLUGIN_URL.'assets/js/shiptimize-admin.js', array ( 'jquery' ), '1.0.2' );     
        wp_enqueue_script( 'shiptimize_admin_script');     
        wp_register_style( 'shiptimize_admin_styles', SHIPTIMIZE_PLUGIN_URL.'assets/css/shiptimize-admin.css', array(), '1.0.1');
        wp_enqueue_style( 'shiptimize_admin_styles' ); 

        if(wcfm_is_vendor() || $isadmin){
            wp_register_script('shiptimize_wcfm' , SHIPTIMIZE_PLUGIN_URL.'assets/js/shiptimize-wcfm.js', array ( 'jquery' ), '1.0.2' );     
            wp_enqueue_script( 'shiptimize_wcfm');    
 
            $shiptimize_wcfmmp_shipping_rates = get_option('shiptimize_wcfmmp_shipping_rates'); 
            $shiptimize_wcfmmp_shipping_rates_by_weight = get_option('shiptimize_wcfmmp_shipping_rates_by_weight'); 

            $shiptimize_carriers = get_option('shiptimize_carriers');
            $data = "var shiptimize_carriers =  ". ( $shiptimize_carriers ? $shiptimize_carriers : '[]' ) . ";";
            if($shiptimize_wcfmmp_shipping_rates){
                $data .= "var shiptimize_wcfmmp_shipping_rates=".json_encode($shiptimize_wcfmmp_shipping_rates).";"; 
            }

            if($shiptimize_wcfmmp_shipping_rates_by_weight){
                $data .= "var shiptimize_wcfmmp_shipping_rates_by_weight=".json_encode($shiptimize_wcfmmp_shipping_rates_by_weight).";"; 
                $data .=' var shiptimize_label_sending = "' . WooShiptimize::instance()->translate('sending') . '";';
            }

            wp_add_inline_script('shiptimize_wcfm', $data);
        } 
    }

    public function get_items_for_vendor($order_id,$vendor_id){
        global $wpdb; 
        
        $sql = "select item_id from " . $wpdb->prefix .  "wcfm_marketplace_orders where vendor_id=$vendor_id AND order_id=$order_id "; 
        return $wpdb->get_results($sql); 
    }
 
    /** 
     * @var int $order_id - the order to send to shiptimize
     */ 
    public function export($orderids, $try = 0,$append_error=0)
    {
        global $wpdb; 
        if($this->is_dev) {
            error_log("Export orders " . json_encode($orderids) . " Try $try ");
        }
 
        $summary = (object)array(
          'n_success' => 0,
          'n_errors' => 0, 
          'nOrders' => count($orderids),
          'nInvalid' => 0,
        ); 

        if(empty($orderids)){
           $summary->message = "No order is selected "; 
           echo WooShiptimizeOrder::get_export_summary($summary);
           return; 
        }   

        $nInvalid = 0; 
        $shiptimize_orders = array();  
        foreach($orderids as $order_id){

            $order = new WooShiptimizeOrder($order_id);
            //Maybe not all products are from this vendor 
            $ShipmentItems = $order->get_product_with_meta('_vendor_id', $this->userid, true);  
            $order->set_shipment_items($ShipmentItems); 
          
            if ($order->is_valid() && ($this->get_api() != null )) {
                array_push($shiptimize_orders, $order->get_api_props());      
            }
            else
            {
                $this->set_status($order->ShopItemId, WooShiptimizeOrder::$STATUS_EXPORT_ERRORS);
                $this->set_message($order->ShopItemId, '<b>Error:</b><br/>  ' . $order->get_error_messages());
                error_log("Cannot export invalid order  ".var_export($order->get_api_props(), true));
                ++$nInvalid;
            }    
        }

        if (\WooShiptimize::$is_dev) {
            error_log("Sending ". var_export($shiptimize_orders,true)); 
        }

        $response =  $this->get_api()->post_shipments($shiptimize_orders);

        if (\WooShiptimize::$is_dev) {
            error_log("Server sent back $response->httpCode: ". var_export($response->response,true)); 
        }
        

        if($response->httpCode == 401 && $try < 1 ){
            $this->refresh_token(); 
            return $this->export(array($order_id) , 1);
        }
        else if ($response->httpCode == 0){
            $summary->message = "Could not connect to API!"; 
        }
        else if ($response->httpCode != 200 ){
           $summary->message = "Error $response->httpCode: ". json_encode($response->response); 
        }
        else { 
            $summary = ShiptimizeMarketplace::shipments_response($response,$append_error);
        }

        if(isset($response->response->AppLink)){
            $summary->login_url =$response->response->AppLink; 
        }

        $summary->nOrders = count($shiptimize_orders); 
        $summary->nInvalid = $nInvalid; 

        echo WooShiptimizeOrder::get_export_summary($summary);
    }

    /** 
     * @param object $data - as received from the api 
     */ 
    public function api_update($data) {
      global $wpdb; 
       
      if(!isset( $_GET['userid'] ) || !is_numeric($_GET['userid'])){       
        error_log(json_encode((object)array("Error"=> "Invalid userid, this is a marketplace. WCFM requires a userid"))); 
        return; 
      }

      $order = new WooShiptimizeOrder($data->ShopItemId);

      add_filter( 'wcfm_current_vendor_id', function(){
        return  $_GET['userid'];
      });
      
      /** 
       * Tracking id is stored for each item in the order 
       */ 
      if( $data->TrackingId ){ 
            $dbitems = $this->get_items_for_vendor($data->ShopItemId, $_GET['userid']); 
            $product_ids = array(); 
            $items_ids = array(); 

            foreach($dbitems as $dbitem){
                $item = new WC_Order_Item_Product( $dbitem->item_id );
                $product  = $item->get_product();   
                
                // Remember that not all items are products 
                if($product){ 
                    array_push($items_ids, $dbitem->item_id); 
                    array_push($product_ids, $item->get_product_id());
                    error_log("adding product " . $item->get_product_id() ); 
                }
            }

            $_POST['orderid']  = $data->ShopItemId; 
            $_POST['tracking_data'] = "wcfm_tracking_order_id=" . $data->ShopItemId 
            . "&wcfm_tracking_product_id=" . urlencode(implode(',', $product_ids)) 
            . "&wcfm_tracking_order_item_id=" . urlencode(implode(',',$items_ids)) 
            . "&wcfm_tracking_url=" . urlencode($data->TrackingUrl) 
            . "&wcfm_tracking_code=" . urlencode($data->TrackingId);
            
            echo "calling tracking with STRING ". $_POST['tracking_data']; 
            $wcfm_tracking = new WCFMu_Shipment_Tracking();
            $wcfm_tracking->wcfm_wcfmmarketplace_order_mark_shipped(); 
        }

        if( $data->Status ){
            $woostatus = WooShiptimizeOrder::$api_status2wp_status[ $data->Status]; 

            $order_sync  = isset( $WCFMmp->wcfmmp_marketplace_options['order_sync'] ) ? $WCFMmp->wcfmmp_marketplace_options['order_sync'] : 'no';
            if($order_sync){
                $wpdb->query("update {$wpdb->prefix}wcfm_marketplace_orders set commission_status=\"" . substr($woostatus,3). "\" where order_id=$data->ShopItemId and vendor_id=" . $_GET['userid']); 
                die(json_encode((object)array("Error"=> "Status updated to " . substr($woostatus,3) ))); 
            }
            else {
                $this->woo_order->update_status($woostatus ,'pushed from the api '.date('d-m-Y H:i')); 
                die(json_encode((object)array("Error"=> "Status updated to $woostatus ")));  
            } 
        }

        die( json_encode( (object) array('msg' => 'ok') ) );
    } 

    /** 
     * Add actions to be displayed in the list of vendors 
     */ 
    public function vendor_actions($actions, $wcfm_vendor_id){ 
        $requestaccount = WooShiptimize::instance()->translate("requestaccount");

        return $actions . "<a class='wcfm-action-icon shiptimize-account-btn' href='" . get_site_url() . '/shiptimize-request-account/?vendor_id=' . $wcfm_vendor_id . "' target='_blank'>
        <span class='wcfmfa fa-address-book text_tip' data-tip='$requestaccount'></span>
        </a>"; 
    }

    /**  
     * 
     */
    public function parse_request( $wp ='' ) {
        parent::parse_request($wp);
        if( !empty( $wp->query_vars['shiptimize_export_vendor'] ) ) {  
          $this->export_vendor(); 
        }
    }

    /** 
     * 
     * Request a new account try to fill in as much info as we can 
     */ 
    public function request_account($vendor  = '') {
        if ( !isset($_POST['name']) && ( !isset($_GET['vendor_id']) || !is_numeric($_GET['vendor_id']) ) ) { 
            die("invalid vendor id " . $_GET['vendor_id']);
        }

        if (!$vendor) {
            $vendor = new stdClass(); 

            if(isset($_GET['vendor_id'])){ 
                $userid = $_GET['vendor_id'];
                $profilesettings = get_user_meta($userid, 'wcfmmp_profile_settings', true); 
                $country = isset($profilesettings['address']['country']) ? $profilesettings['address']['country'] : '';
                $state = isset($profilesettings['address']['state']) ? $profilesettings['address']['state'] : '';
                
                if ($country) {
                    $state = WC()->countries->get_states( $country )[$state];    
                }
                
                # var_export($profilesettings);
                $vendor = (object) array(
                    'userid' =>  $userid,
                    'companyname' => isset($profilesettings['store_name']) ?  $profilesettings['store_name'] : '',
                    'name' => isset($profilesettings['first_name']) ? ($profilesettings['first_name'] . ' ' ) : '' . (isset($profilesettings['last_name']) ? $profilesettings['last_name'] : ''), 
                    'email' => isset($profilesettings['store_email']) ? $profilesettings['store_email'] : '',
                    'phone' => isset($profilesettings['phone']) ? $profilesettings['phone'] : '',
                    'streetname' => isset($profilesettings['address']['street_1']) ? $profilesettings['address']['street_1'] . ' ' . $profilesettings['address']['street_2'] : '',
                    'city' => isset($profilesettings['address']['city']) ? $profilesettings['address']['city'] : '',
                    'zipcode' => isset($profilesettings['address']['zip']) ? $profilesettings['address']['zip'] : '',
                    'country' => $country,
                    'province' => $state
                );                  
            } 

        }


        parent::request_account($vendor); 
    }

    /** 
     * if vendor connected and admin pays return false, else return default  
     * true means vendor gets shipping commission 
     */ 
    public function vendor_shipping($vendor_get_shipping, $vendor_id = '') {
        if(!$vendor_id) {
            return $vendor_get_shipping; 
        }

        $isadmin = current_user_can('editor') || current_user_can('administrator');  
        $connected =  $this->is_user_connected($vendor_id);

        if (!$isadmin && $connected && $this->is_master_account()) {
            if($this->is_dev){
                error_log("admin pays and vendor $vendor_id is connected  return false");     
            } 
            return false; 
        }

        return $vendor_get_shipping; 
    }
    
    /** 
     * Not all types suport limiting the rules sellers can set, thouse should not be available in a connected account  
     * @return the available options for the vendor 
     */ 
    public function vendor_shipping_options($options,$userid, $wcfmShipping ){ 
        $isadmin = current_user_can('editor') || current_user_can('administrator'); 
        if($this->is_dev){  
            error_log("====================== wcfmmp_settings_fields_shipping "); 
            error_log("Options " . json_encode($options)); 
            error_log("Userid " . json_encode($userid)); 
            error_log("WcfmShipping " . json_encode($wcfmShipping)); 
        }
        
        if (!$isadmin && $this->connected && $this->is_master_account()) {
            if($this->is_dev){
                error_log( "User is connected to a master account limiting options to available rules ");
            }

            if(isset($options['wcfmmp_shipping_type']) && isset($options['wcfmmp_shipping_type']['options'])) {
                unset($options['wcfmmp_shipping_type']['options']['by_zone']);    
                unset($options['wcfmmp_shipping_type']['options']['by_distance']);
            }
        }

        return $options; 
    }

    /** 
     * Lock shipping rules to admin if master account 
     */  
    public function vendor_shipping_rules($rates) {  
        $isadmin = current_user_can('editor') || current_user_can('administrator'); 

        if($this->is_dev){  
            error_log("=== vendor_shipping_rules");
        }

        if (!$isadmin && $this->connected && $this->is_master_account()) {
            echo WooShiptimize::instance()->translate('inheritadminrates');
            if($this->is_dev){
                error_log("=== it's not an admin account and this vendor is connected to an admin pays account ");
            }
            return array(); 
        } 
        return $rates;  
    }

    public function vendor_shipping_fields($rates){
        $isadmin = current_user_can('editor') || current_user_can('administrator'); 

        if($this->is_dev){
            error_log("vendor_shipping_fields");
        }

        if (!$isadmin && $this->connected && $this->is_master_account()) { 
            return array(); 
        }

        return $rates;  
    }
}

# These status changes can happen when no SELLER is logged in such as when a user buys a product 
add_action('init', function (){
    $woo_statuses = wc_get_order_statuses(); 

    foreach ( $woo_statuses as $status_key => $status_label) {
        $statushandle = str_replace("wc-", "", $status_key); 
        add_action( 'woocommerce_order_status_' . $statushandle, 'ShiptimizeWCFM::auto_export',50,1);
    } 
}); 

add_action( 'wcfmmp_vendor_order_status_updated' , 'ShiptimizeWCFM::auto_export',50,1);
add_action( 'woocommerce_after_order_object_save', 'ShiptimizeWCFM::after_order_object_save',50,1);  

if(function_exists('wp_get_current_user')) { 
    # Someone is logged in and that someone is a seller. 
    $current_user = wp_get_current_user();
    if ($current_user) {
        $shiptimize_wcfm = new ShiptimizeWCFM($current_user->ID);  
        $shiptimize_wcfm->actions(); 
    }    
}
