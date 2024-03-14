<?php 
/** 
 * Parent class of all marketplaces  
 * Handle common stuff like getting a token or exporting orders 
 * This class should probably be 2, where we extract all order methods 
 * into a ShiptimizeMarketplaceOrder class that extends the woo order class, but with current  
 * functions that's more mess than profit 
 * 
 */ 
abstract class ShiptimizeMarketplace {
    private static $me;  

    /** 
     * The app key to send to the api 
     */ 
    protected $appkey = ''; 

    /** 
     * @var int - userid 
     */ 
    protected $userid = ''; 

    public function __construct() {
        $this->is_dev = file_exists(ABSPATH.'isdevmachine');     
    }

    public function actions() {  
        add_action( 'shiptimize_api_update' , array($this, 'api_update') , 10, 1 );

        if ($this->userid) {
            /**  
             * Remember that the connector is not instanciated by default, there fore any event subscriptions should be 
             * kept in the marketplace  
             **/
            add_action( 'wp_ajax_shiptimizeconnectuser', array( $this, 'ajax_connect_user' ) ); 

            add_action( 'parse_request', array ( $this, 'parse_request') );
            add_action( 'wp_mail_failed', array ( $this, 'on_mail_error' ), 10, 1 ); 

            add_filter( 'query_vars', array( $this, 'custom_query_vars' ) );                
        } 
    }

    /** 
     * User declares they already have an account,
     * connect and then via JS redirect them to the settings page 
     */ 
    public function ajax_connect_user(){
      if(isset($_GET['userid'])){
        $this->userid = $_GET['userid']; 
      }
      $this->connect();
      die(); 
    }

    /** 
     * @param mixed $errors - array(Id, Tekst)
     */ 
    public function append_errors($orderid, $errors){
        $messages = ''; 

        foreach ($errors as $error) {
            if( $error->Id == ShiptimizeOrder::$ERROR_ORDER_EXISTS){
                $this->set_status($orderid, ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY);
                $this->add_message($orderid, WooShiptimizeOrder::get_formated_message("Order Exists"));
            }
            else {
                $messages .= WooShiptimizeOrder::get_formated_message( isset($error->Tekst) ? $error->Tekst : var_export($error,true) );
            }  
        }  

        $this->add_message($orderid, $messages);
    }    

    /** 
     * There is no instance of the marketplace on the activate hook so this needs to be static 
     */
    public static function activate (){ 
        self::create_tables(); 
    }

    /**
     * Add a message to the existing list of messages 
     * If you want to append a date make sure to run the getFormatedMessage before 
     * 
     * @param string message 
     */ 
    public function add_message($orderid, $message){
        global $wpdb; 

        $meta = $this->get_order_meta($orderid);  
        $previous_message = isset($meta->message) ? $meta->message : ''; 
        
        $sql = sprintf( "update %sshiptimize_marketplace_order set message=\"%s\" where orderid=%d and vendorid=%d",
            $wpdb->prefix,
            $previous_message.$message,
            $orderid, 
            $this->userid); 

        if (\WooShiptimize::$is_dev) {
            error_log($sql);
        }
 
        return $wpdb->query( $sql );
    }


    /**
     * Create the necessary datamodel to support marketplace features 
     * Each vendor will see seperate shiptimize statuses and shiptimize messages 
     */ 
    public static function create_tables(){
        global $wpdb; 

        $wpdb->query("CREATE TABLE  IF NOT EXISTS `{$wpdb->prefix}shiptimize_marketplace_order` (
          `orderid` INT NOT NULL,
          `vendorid` INT NOT NULL,
          `status` INT NULL,
          `message` VARCHAR(255) NULL,
          PRIMARY KEY (`orderid`, `vendorid`)
        )");
    }

    /** 
     * Add an entry of order meta 
     */ 
    public function create_dummy_order_meta($orderid) {
        global $wpdb; 

        $wpdb->query("INSERT INTO `{$wpdb->prefix}shiptimize_marketplace_order` (`orderid`,`vendorid`) VALUES($orderid, $this->userid)"); 
    }

    public function custom_query_vars($vars) {
      $vars[] = 'shiptimize_connect';
      $vars[] = 'shiptimize_disconnect';
      return $vars; 
    }

    public function parse_request($wp = ''){ 
      if( !empty( $wp->query_vars['shiptimize_connect'] ))  { 
        ShiptimizeConnector::getInstance()->account_page(); 
        $this->connect(); 
      } 
 
      if(!empty($wp->query_vars['shiptimize_disconnect']) ){
        $this->disconnect(); 
      }
    }

    /**
     * If the marketplace requires aditional action on connect override this function 
     */
    public function connect( ){
        ShiptimizeConnector::getInstance($this->userid)->connect_user(); 
    }

    /**
     * If the marketplace requires aditional action on disconnect override this function 
     */
    public function disconnect(){
        ShiptimizeConnector::getInstance($_GET['userid'])->disconnect_user($_GET['urlto']); 
    }

    /** 
     * Determine which marketplace is being used and return an instance of the correct child class 
     * This function is meant to be used sparsely in situations where we don't want 
     * to repeat this code;  are already inside the flow and strictly need generic code
     * Like on export orders. Consider 100 times if you need it before invoking it. 
     */ 
    public static function instance(){
        global $shiptimize_wcfm, $shiptimize_dokan; 

        if(!self::$me){
            if(isset($shiptimize_dokan)){
                self::$me = $shiptimize_dokan;
            }
            else if(isset($shiptimize_wcfm)){
                self::$me = $shiptimize_wcfm; 
            }
        }

        return self::$me; 
    }

    /** 
     * @return bool true if $userid is connected 
     */ 
    public function is_user_connected ($vendor_id){ 
        $connected =  get_user_meta( $vendor_id , 'shiptimize_marketplace_installed', true)  == 'YES'; 
        return $connected; 
    }

    public function is_master_account(){
        return get_option('shiptimize_wcfm_master_account') == 1;
    }

    public function get_app_key() {
        return $this->appkey;  
    }

    public function get_api(){  
        error_log("marketplace get_api
          userid: {$this->userid}
          public". $this->get_public_key(). " 
          private ". $this->get_private_key() ."
          app". $this->appkey . "
          Token: " . $this->get_token() . " 
          Expires: " .  $this->get_token_expires()
        ); 

        return ShiptimizeApiV3::instance( 
          $this->get_public_key(), 
          $this->get_private_key(),
          $this->appkey,
          false,
          $this->get_token(),
          $this->get_token_expires()
        ); 
    }

    /** 
     * @return string - the callback url including the protocol 
     */ 
    public abstract function get_callback(); 

    /** 
     * @return the id of hte carrier if there is a match with the shipping method
     */ 
    public function get_carrier_for_order($orderid){}

    /*** 
     * @return the list of connected users 
     */ 
    public function get_connected_users(){
        global $wpdb; 
        return $wpdb->get_results("select user_id from {$wpdb->prefix}usermeta where meta_key ='shiptimize_marketplace_installed' and meta_value = 'YES'");
    }

    public function get_default_shipping_type(){
        return get_option('shiptimize_default_shipping_type'); 
    }

    /**
     * Retrieve shiptimize metadata for the order with orderid for this vendor  
     * @param int $orderid 
     * @return the order meta  
     */
    public function get_order_meta($orderid)
    {
        global $wpdb; 

        $sql = " select `{$wpdb->prefix}shiptimize`.pickup_id, `{$wpdb->prefix}shiptimize`.pickup_label, `{$wpdb->prefix}shiptimize`.tracking_id, `{$wpdb->prefix}shiptimize_marketplace_order`.message, `{$wpdb->prefix}shiptimize_marketplace_order`.status, vendorid
            from `{$wpdb->prefix}shiptimize` 
            inner join 
            `{$wpdb->prefix}shiptimize_marketplace_order`
            on id = orderid 
            where id={$orderid} and vendorid ={$this->userid}";
        $results = $wpdb->get_results($sql);
 
        if(empty($results)){
            $this->create_dummy_order_meta($orderid);  
            return;
        }

        return  $results[0];
    }
    
    /** 
     * Not all marketplaces will save vendor meta in the same tables 
     * @return string public key
     */
    public function get_public_key() {
        return get_user_meta($this->userid, 'shiptimize_public_key',true);
    }

    /** 
     * Not all marketplaces will save vendor meta in the same tables 
     * @return string private key
     */
    public function get_private_key() {
        return get_user_meta($this->userid, 'shiptimize_private_key',true);
    }

    /** 
     * Not all marketplaces will save vendor meta in the same tables 
     * @return string token
     */
    public function get_token() {
        return get_user_meta($this->userid, 'shiptimize_token',true);
    }

    /** 
     * Not all marketplaces will save vendor meta in the same tables 
     * @return string token expire date 
     */
    public function get_token_expires() {
        return get_user_meta($this->userid, 'shiptimize_token_expires',true);
    }

    /** 
     * @param int $orderid 
     * return an array of products [{id:'',name:'','qty':'','weight'},]
     */ 
    public abstract function get_products($order_id);  

    /** 
     * Receives a list of order ids, returns the html we append on the regular plugin 
     * @param array $orderids - list of numeric order ids 
     */ 
    public abstract function export($orderids); 

    public function set_default_shipping_type($type){
        update_option('shiptimize_default_shipping_type', $type); 
    }

    public function on_mail_error($wp_error){
        echo "<pre>";
        print_r($wp_error); 
        echo "</pre>";
    }

    /**
     * Set the status for this order
     *
     * @param int status
     */
    public function set_status($orderid, $status)
    {
        global $wpdb; 

        $sql = "update `{$wpdb->prefix}shiptimize_marketplace_order` set status=$status  where orderid= {$orderid} and vendorid = $this->userid ";  

        $wpdb->query($sql);
    }

    /**
     * Set the message for this order
     *
     * @param string status
     */
    public function set_message($orderid, $message)
    {
        global $wpdb; 

        $sql = "update `{$wpdb->prefix}shiptimize_marketplace_order` set message= " . addslashes(sanitize_text_field($message)) . " where orderid= {$orderid} and vendorid = $this->userid"; 

        if (\WooShiptimize::$is_dev) {
            error_log($sql); 
        }
        $wpdb->query($sql);
    }

   /** 
   * Request a new token Get the keys /callbackurl from the marketplace class 
   * @return return the api response 
   */ 
    public function refresh_token() {
        global $woocommerce;
 
        update_user_meta($this->userid, 'shiptimize_token', '' );
        update_user_meta($this->userid, 'shiptimize_token_expires', '' ); 

        if ($this->is_dev) {
            $msg = "Get API with CALLBACK: ". $this->get_callback() . ",version:" . $woocommerce->version ." , PluginV: ".WooShiptimize::$version;
            error_log($msg);
        }

        $response =  $this->get_api()->get_token($this->get_callback(), $woocommerce->version, WooShiptimize::$version);
        
        if(isset($response->Key)){
            update_user_meta($this->userid, 'shiptimize_token', $response->Key);
            update_user_meta($this->userid, 'shiptimize_token_expires', $response->Expire);
        }
        
        return $response; 
    }

    /** 
     * Allow the marketplace owner to easily request an account for a user 
     * @param $vendor - a pre-filled object with as many properties we can fill in from marketplace info
     */ 
    public function request_account($vendor  = '') { 
        if ( !current_user_can('administrator') ) {
            die("Only administrators can request accounts "); 
        }

        $message = ''; 
        $error = ''; 

        if(isset($_POST['name'])){
            $email = get_option('admin_email'); 
            $emailbody = 'Admin of website: <a href="'. get_site_url() . '" target="_blank">' . get_site_url() . '</a> <br/>requests that a new client account be appended to their current account with data:<br/><br/>'; 
            $fields = ['companyname', 'name' , 'phone', 'streetname', 'zipcode','city','province','country','fiscal']; 

            foreach ( $fields as $field ) {
                $emailbody .= "<br/>$field: " . ( isset($_POST[$field]) ? $_POST[$field] : ''); 
            } 

            $headers = array(
            'Content-Type: text/html',
            'charset=UTF-8',
            'From: '. $email ,
            'CC: '. $email 
            );

            $emailto = $this->is_dev ? $email : 'sales@shiptimize.me'; 
            $subject = 'Wordpress with master account at ' . get_site_url() . '  Vendor account request '; 
            
            if($this->is_dev){
                error_log("Sending email $subject ; to $emailto with headers " . json_encode($headers) .  "; body:  \n\n $emailbody "); 
            }

            if(wp_mail($emailto,  $subject ,  $emailbody,$headers)){
                $message = WooShiptimize::instance()->translate('requestsent');
            }
            else {
                $error = 1; 
                $message = 'Could not sent email to ' . $emailto . ', is your server properly configured? '; 
            }
        }
 
        require_once( SHIPTIMIZE_PLUGIN_PATH . 'includes/views/request-account-form.php' );

        shiptimize_account_print_form($vendor,$error, $message);
        die("");
    }


  /** 
   * Process the server response and append the appropriate status and messages to each shipment
   * @param object response - the response as sent by the server  
   * @param int $append_errors - if > 0 include individual message errors in the summary 
   */ 
  public function shipments_response( $response , $append_errors ) {
    global $shiptimize; 

    if($response->httpCode != 200){
      return; 
    }

    $n_success = 0; 
    $n_errors = 0; 

    foreach($response->response->Shipment as $shipment){
        $orderid = $shipment->ShopItemId; 
        $order = new WooShiptimizeOrder($orderid); 
        $hasErrors = isset($shipment->ErrorList); 
        
        $this->set_status($orderid, $hasErrors ?  ShiptimizeOrder::$STATUS_EXPORT_ERRORS : ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY );
        
        if($hasErrors){
          $this->append_errors($orderid, $shipment->ErrorList);  
          if($append_errors){
            echo "<div class='notice shiptimize-error'> ** " . $shipment->ErrorList[0]->Tekst  . " ** </div>"; 
          }  
          ++$n_errors;
        } else {
          ++$n_success;
        }

//      Check if Carrier does not match , carrier exists , ID is not 0 and ID != Transporter 
        if(isset($shipment->CarrierSelect) && $shipment->CarrierSelect->Id && $shipment->CarrierSelect->Id != $order->get_carrier_id() ){
            $this->add_message( $orderid , $order->get_formated_message($shiptimize->translate("Diferent carrier selected by the api")) );
        }

        $meta = $this->get_order_meta($orderid); 
        $message = WooShiptimizeOrder::get_formated_message( WooShiptimizeOrder::get_status_label( $meta->status ) );
        $this->add_message($orderid,  $message );
    }

    return (object) array(
      "n_success" => $n_success,
      "n_errors" => $n_errors
    );
  }

    /** 
     * Allow the admin to choose to offer Shiptimize by defaut to all vendors 
     * All payments are directly between shiptimize and the marketplace owner 
     */ 
    public function shiptimize_options(){
        global $shiptimize; 
?>
        <div class='shiptimize-settings__section'>
            <h2>Provinces</h2> 

            <div class='shiptimize-settings__field'>
              <div class='shiptimize-settings__field'> 
                <input type="checkbox" value="1"  <?php echo get_option('shiptimize_provinces') ? 'checked': '' ?> name="shiptimize_provinces" class=''/>
                <?php 
                    echo $shiptimize->translate('provincesdescription').' '; 
                    foreach ( WooShiptimize::$provinces as $country => $values){
                      echo "$country:"; 
                      foreach ($values as $code => $name) {
                        echo "$name "; 
                      }
                    }
                ?>
              </div>
            </div>
        </div>
<?php
    }

    /** 
     * Since we are apending to an existing plugin the action is often not install but upgrade 
     */ 
    public function plugin_update (){
        $this->create_tables();
    }

    public function register_settings() {
        register_setting( 'shiptimize_group' , 'shiptimize_wcfm_master_account' );
        register_setting( 'shiptimize_group', 'shiptimize_default_shipping_type' );
        # Declare provinces for Portugal (Islands, Mainland)
        register_setting( 'shiptimize_group','shiptimize_provinces');
    }

    /** 
     * Not all marketplaces save information in the default tables 
     * @param $data - the data as received from the API 
     */ 
    public function api_update($data){ die(" apiupdate override this function in the marketplace "); }

}