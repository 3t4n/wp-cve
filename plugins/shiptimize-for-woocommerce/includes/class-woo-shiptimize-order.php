<?php
/**
 * @package shiptmize
 * @since 1.0.0
 */


defined( 'ABSPATH' ) || exit;

include_once(SHIPTIMIZE_PLUGIN_PATH.'/includes/core/class-shiptimize-order.php');

/**
 * This class translates a woocommerce order object into a shiptimize order object
 */
class WooShiptimizeOrder extends ShiptimizeOrder {

    /**
     *
     * @var mixed orderMeta
     */
    private $woo_order = null;

    protected static $billing_fields;
    protected static $shipping_fields;

    public static $api_status2wp_status = array(
        1 => 'wp-pending',
        2 => 'wc-processing',
        3 => 'wc-on-hold',
        4 => 'wc-completed',
        5 => 'wc-canceled',
        6 => 'wc-refunded',
        7 => 'wc-failed'
    );

    /**
     *  This order contains only virtual products
     */
    protected $is_virtual = 0;

    /**
     * this order is to be picked up at a physical location owned by the shop
     */
    protected $is_local_pickup = 0;


    /**
     * An array of carrier info & options as returned by the API
     * */
    protected $carriers;


    /**
     * return a new instance of a wooshiptimimizeorder
     *
     * @param $order_id
     */
    public function __construct($order_id){
        global $wpdb;

        $this->db_prefix = $wpdb->prefix;
        $this->shiptimizeOptions = ShiptimizeOptions::getInstance();
        parent::__construct($order_id);
    }

    /**
     * @override
     * returns the order id
     * @return string  - the order id
     */
    public function get_id( ) {
        return $this->ShopItemId;
    }

    /**
     * @override
     * @return boolean true if this order is valid
     */
    public function is_valid()
    {
        $notvirtual = (get_option('shiptimize_export_virtual_orders') || !$this->is_virtual);

        if(!$notvirtual) {
            array_push($this->errors, "Only virtual products in order");
        }

        if($this->is_local_pickup) {
            array_push($this->errors, "Local Pickup Orders are not shipped, change the shipping method if you wish to ship this order");
        }

        return parent::is_valid() && $notvirtual && !$this->is_local_pickup;
    }

    /**
     * Set the fields necessary for brazilian orders
     */
    public function set_brazilian_fields(){
        $fields_cnpj = get_option('shiptimize_cnpj');
        $fields_cpf = get_option('shiptimize_cpf');
        $fields_neighborhood = get_option('shiptimize_neighborhood');
        $fields_number = get_option('shiptimize_number');

        $data = $this->woo_order->get_data();

        foreach ( $data['meta_data']  as $meta ) {
            $current = $meta->get_data();

            switch ( $current['key'] ) {
                case $fields_cnpj:
                    $this->CNPJ  = $current['value'];
                    break;

                case $fields_number:
                    $this->HouseNumber = $current['value'];
                    break;

                case $fields_neighborhood:
                    $this->Neighborhood = $current['value'];
                    break;

                case $fields_cpf:
                    $this->CPF = $current['value'];
                    break;
            }
        }
    }

    /**
     * @override
     */
    protected function set_client_reference ( ) {
        $this->ClientReferenceCode = apply_filters( 'woocommerce_order_number', $this->ShopItemId, $this->woo_order );
    }

    private function set_name(){
        $this->CompanyName = $this->woo_order->get_shipping_company()  ? trim( $this->woo_order->get_shipping_company() ) : trim($this->woo_order->get_billing_company() );

        $this->Name =  $this->woo_order->get_shipping_first_name() ? trim( $this->woo_order->get_shipping_first_name().' '.$this->woo_order->get_shipping_last_name() ) : ( $this->woo_order->get_billing_first_name(). ' '. $this->woo_order->get_billing_last_name() );
    }


    /**
     * fill the pickup point info
     */
    public function set_meta(){
        $meta = $this->get_order_meta();

        if ($meta) {
            WooShiptimize::log("This order contains metada: " . var_export($meta,true));
            //$this->PointId = $meta->pickup_id;
            //$this->ExtendedInfo = $meta->pickup_extended ? json_decode($meta->pickup_extended) : null ;
            $this->Transporter = $meta->carrier_id;

            /**
             * Check which option id matches the point for this carrier
             * **/

            foreach($this->carriers as $carrier) {
                if($carrier->Id == $this->Transporter) {
                    foreach($carrier->OptionList as $option) {
                        if(isset($option->IsPickup) && $option->IsPickup)  {
                            $pickupoption = array(
                                'Id' => $option->Id,
                                'Value' => $meta->pickup_id
                            );



                            /** Find the map field to push **/
                            if ($meta->pickup_extended) {
                                if (!isset($carrier->MapFields)) {
                                    WooShiptimize::log("Map fields not defined for carrier $this->Transporter extended is [$meta->pickup_extended]");
                                }
                                else {
                                    $pickupoption['OptionFields'] = array();
                                    foreach ( $carrier->MapFields as $mapField ) {
                                        array_push( $pickupoption['OptionFields'], array(
                                            'Id' => $mapField->Id,
                                            'Value' => $meta->pickup_extended
                                        ) );
                                    }
                                }
                            }

                            if ($meta->pickup_id) {
                                array_push($this->OptionList, $pickupoption);
                            }
                        }
                    }

                }
            }
        }
    }

    /**
     *
     */
    private function set_address( ) {
        #returns a code as defined in i18n/states/{country_iso2}.php
        $this->State = $this->woo_order->get_shipping_state();
        $countries = new WC_Countries();

//   Some clients hide the shipping country on checkout and belive it or not.. some leave it empty!
        $country =  $this->woo_order->get_shipping_country() ?  $this->woo_order->get_shipping_country() :  $this->woo_order->get_billing_country();

        $states = $countries->get_states($country);

        //States in dropdowns
        if(!isset($states[$this->State])){
            $this->State = '';  # save the iso only
        }

        //It's possible for the shipping address to be entirely blank
        if($this->woo_order->get_shipping_address_1()){
            $this->Streetname1 = $this->woo_order->get_shipping_address_1();
            $this->Streetname2 = $this->woo_order->get_shipping_address_2();
            $this->City = $this->woo_order->get_shipping_city();
            $this->PostalCode = $this->woo_order->get_shipping_postcode();
        }
        else {
            $this->Streetname1 = $this->woo_order->get_billing_address_1();
            $this->Streetname2 = $this->woo_order->get_billing_address_2();
            $this->City = $this->woo_order->get_billing_city();
            $this->PostalCode = $this->woo_order->get_billing_postcode();
        }

        $this->Country = $country;
        $this->Email = $this->woo_order->get_billing_email();
        $this->Phone = $this->woo_order->get_billing_phone();

        $this->check_custom_checkout_fields();
    }

    /**
     * If plugin is active and we have stored a map for those fields
     * Set them in the order
     **/
    private function check_custom_checkout_fields ( ) {
        # Get our settings for the fields
        $oursettings = get_option('shiptimize_custom_checkout_fields');

        if (!$oursettings) {
            return;
        }

        # Does this order contain any mapped custom fields?
        foreach ($oursettings as $customfield => $apiField) {
            ##Reseno
            $order = wc_get_order( $this->ShopItemId ); // returns WC_Order object.

            $customvalue = $order->get_meta( '_' . $customfield  , true );

            if ($customvalue) {
                $this->{$apiField} = $customvalue;
            }
        }
    }

    /*
     * We have no way to know what box size people are using so we ignore dimensions.
     * We add the weight of the product when available
     * TODO: ask about this, should we set it to zero accross the board or is this method ok?
     */
    private function set_dimensions( ) {
        $items = $this->woo_order->get_items();
        $weight = 0;
        $description = '';
        $this->ShipmentItems = array();
        $exportvirtualproducts = get_option('shiptimize_export_virtual_products');

        $is_virtual = 1;
        foreach( $items as $item ) {
            $product = $item->get_product();
            if (!empty($product) && !is_bool($product)){
                $includeproduct = $exportvirtualproducts || !$product->is_virtual();

//    Remember that not all items are products
                if($product && $includeproduct){
                    $is_virtual = 0;
                    $qty = $item->get_quantity();
                    $item_weight = 0;

                    if( $product->has_weight() ) {
                        $productweight = $product->get_weight();
                        if(is_numeric($qty) && is_numeric($productweight)) {
                            $item_weight = wc_get_weight(floatval($productweight),'g');
                        }

                        $weight += $qty * $item_weight;
                    }

                    $description .= $qty . ' - ' . $product->get_name()." ";

                    $item = array(
                        'Count' => $qty,
                        'Id' => $item->get_product_id(),
                        'Name' => $this->escape_text_data($product->get_name()),
                        'Type' => 4, // 1 - Gift, 2 - Documents, 3 - Sample , 4 - Other
                        'Value' => $item->get_subtotal(),
                        'Weight' => floor($item_weight)
                    );

                    array_push($this->ShipmentItems, $item);
                }
            }
        }

        if( $weight > 0 ) {
            $this->Weight  = floor($weight);  // The api receives grams
        }

        $this->is_virtual = $is_virtual;

        // @deprecated in favor of item lists
        // $this->Description = $this->escape_text_data($description);

        // if(!$this->Description){
        //   $this->add_message( $this->get_formated_message("WARNING: We could not determine the list of items in this order.
        //     Customs information must be set manually."));
        // }
    }

    /**
     *  Check if the paymentmethod is COD then:
     *  1) Client has only one carrier and  it has COD => set the COD OPTION
     *  2) It's a shiptimize carrier which supports COD if not set, add the COD Option
     */
    public function set_carrier_cod() {
        $payment_method = $this->woo_order->get_payment_method();

        if ($payment_method != 'cod') {
            return;
        }

        if(count($this->carriers) == 1) {
            $this->Transporter = $this->carriers[0]->Id;
        }

        # client has more than one carrier
        if (!$this->Transporter) {
            foreach ($this->carriers as $carrier) {
                if ($carrier->Id ==  $this->Transporter) {
                    foreach ( $carrier->OptionList as $option) {
                        if ($option->Id == 2) {
                            array_push( $this->OptionList ,
                                array('Id' => 2,
                                    'Value' => $this->woo_order->get_total(),
                                    'OptionFields' => array(
                                        array('Id'=> 1, 'Value' => $this->woo_order->get_total())
                                    )
                                )
                            );
                        }
                    }
                }
            }
        }
    }


    /**
     * Set carrier associated stuff like service level
     */
    public function set_carrier() {
        // Figure out if this is a local pickup

        ##Reseno
        $order = wc_get_order( $this->ShopItemId ); // returns WC_Order object.

        $shiptimize_carrier = $order->get_meta(  'shiptimize_carrier' , true);
        $settings = '';
        $this->serviceLevelOptionId ='';

//  plugins that don't use instance ids ..
        if( $shiptimize_carrier ) {
            $shiptimize_carrier = json_decode($shiptimize_carrier);

            $this->Transporter = $shiptimize_carrier->carrier_id;

            if( $shiptimize_carrier->service_level != '-' ){
                $this->serviceLevelOptionId = $shiptimize_carrier->service_level;
                $this->extraOptionId = $shiptimize_carrier->extra_option;
            }
        }
//  Regular woo shipping methods you add to zones
        else {
            WooShiptimize::log("Regular Woo method  will request shipping items  ");

            $this->ShippingMethodName = $this->woo_order->get_shipping_method();
            foreach ( $this->woo_order->get_items('shipping') as $item_id => $shipping_item  ) {
                $shipping_method_id = $shipping_item->get_method_id();
                if ( $shipping_method_id == 'local_pickup' ) {
                    $this->is_local_pickup = 1;
                }
                else {
                    $shipping_instance_id = $shipping_item->get_instance_id();
                    $this->ShippingMethodId = $shipping_method_id . '_' . $shipping_instance_id;

                    if (stripos($shipping_method_id, '_weight')) { //wbs
                        $settings =  get_option("wbs_".$shipping_instance_id."_shiptimize");
                    }
                    else { //flat rates
                        $settings = get_option('woocommerce_'.$shipping_method_id.'_'.$shipping_instance_id.'_settings');
                    }

                    $results = array();
                    preg_match( "/shipping_shiptimize_([\d]*)/", $shipping_method_id, $results);
                    if(isset($results[1])){
                        $this->Transporter = $results[1];

                        if(isset($settings['service_level']) && $settings['service_level'] && $settings['service_level'] != '-'){
                            $this->serviceLevelOptionId = $settings['service_level'];
                        }
                    }
                }
            } /** for each **/
        }

        if ($this->serviceLevelOptionId) {
            $serviceLevelIds =  get_option( 'shiptimize_servicelevelids' );
            if (isset($serviceLevelIds[$this->Transporter])) {
                $this->add_option($serviceLevelIds[$this->Transporter], $this->serviceLevelOptionId);
            }
            else {
                WooShiptimize::log("Invalid service level id for order $this->ShopItemId. Did something change in the carrier settings ? ");
            }
        }

        if ( $settings ) {
            WooShiptimize::log(  $this->ShippingMethodName  . " settings " . var_export($settings,true));

            foreach ($this->shiptimizeOptions->getCheckoutStrIds() as  $fieldstr) {
                WooShiptimize::log("CheckOUT  OPTION $fieldstr ");
                $this->checkSimpleOptionWithOrderValue($settings,$fieldstr);
            }

            $this->checkSimpleOptionWithOrderValue($settings,'extraoptions');
        }

        if (!$this->Transporter && shiptimize_is_marketplace()) {
            $mkp = ShiptimizeMarketplace::instance();
            $transporter = $mkp->get_carrier_for_order($this);
            if ($transporter > 0) {
                $this->Transporter = $transporter;
            }
        }

        $this->set_carrier_cod();
    }

    protected function add_option ($optionid, $optionvalue) {
        if ($optionid == '-') {
            WooShiptimize::log("Invalid optionid $optionid");
            return;
        }

        if ($optionvalue == '-') {
            WooShiptimize::log("Invalid option value $optionvalue");
            return;
        }

        array_push($this->OptionList, array(
            "Id" => $optionid,
            "Value" => $optionvalue
        ));
    }

    /**
     * Options who's value is the order value
     * cash on delivery and insurance
     */
    protected function checkSimpleOptionWithOrderValue($settings,$optionname) {
        WooShiptimize::log("Checking $optionname");
        if (isset($settings[$optionname]) && $settings[$optionname]) { //can be 0 in checkboxes for "no"

            $optionvalue = $settings[$optionname];
            $childOptionId = 1;

            WooShiptimize::log("optionvalue $optionvalue");
            if (isset($settings[$optionname . $optionvalue])) {
                $childOptionId = $settings[$optionname . $optionvalue];
            }

            if ($optionvalue != '-') {
                $option = $this->shiptimizeOptions->getOptionValue($this,$optionvalue, $childOptionId);

                if (!$option) {
                    WooShiptimize::log("Error fetching  option for $optionname " . $settings[$optionname]);
                    return;
                }

                # It's a checkbox or it's an option with a valid value
                if (!isset($option->Value) || $option->Value != '-') {
                    array_push( $this->OptionList , $option );
                }
            }
        }
    }


    /**
     * https://docs.woocommerce.com/wc-apidocs/class-WC_Order.html
     * @override
     */
    protected function bootstrap ( ) {

        $this->woo_order = wc_get_order($this->ShopItemId);
        $this->carriers = json_decode(get_option('shiptimize_carriers'));

        if(!$this->woo_order){
            WooShiptimize::log("Invalid order to bootstrap with id $this->ShopItemId ");
            return;
        }

        $this->set_name();
        $this->set_address();
        $this->set_dimensions();
        $this->set_client_reference();

        $this->Value = $this->woo_order->get_total();

        $meta = self::get_shipping_meta($this->ShopItemId);

        if ($meta) {
            $this->shiptimize_message = $meta->message;
            $this->shiptimize_status = $meta->status;
        }

        if ( get_locale() == 'pt_BR' ) {
            $this->set_brazilian_fields();
        }

        $this->set_carrier();

        // Meta must be checked after because we want to check the option id for the point
        $this->set_meta();
    }

    /**
     * @override
     */
    public function set_message ( $message ) {
        global $wpdb;

        if(!$this->ShopItemId){
            return false;
        }

        $this->shiptimize_message = $message;

        if( self::get_shipping_meta( $this->ShopItemId ) ) {
            return $wpdb->query( $wpdb->prepare( "update {$wpdb->prefix}shiptimize set message=%s where id=%d", $message , $this->ShopItemId ) );
        }

        return $wpdb->query( $wpdb->prepare(" insert into {$wpdb->prefix}shiptimize (`id`,`message`) VALUES(%d, %s)", $this->ShopItemId , $message ) );
    }

    public function set_shipment_items($items){
        $this->ShipmentItems = $items;
    }

    /**
     * @override
     */
    public function set_status ( $status ) {
        global $wpdb;

        if(!$this->ShopItemId){
            return false;
        }

        $this->shiptimize_status = $status;

        if( self::get_shipping_meta( $this->ShopItemId ) ) {
            return $wpdb->query( $wpdb->prepare( "update {$wpdb->prefix}shiptimize set status=%d where id=%d", $status , $this->ShopItemId ) );
        }

        return $wpdb->query( $wpdb->prepare(" insert into {$wpdb->prefix}shiptimize (`id`,`status`) VALUES(%d, %d)", $this->ShopItemId , $status ) );
    }


    /**
     * Used to push status from the api into the plugin
     */
    public function set_status_from_api($status){
        global $shiptimize;

        WooShiptimize::log("status update for order {$this->ShopItemId} => $status");

        if(!isset(self::$api_status2wp_status[$status])){
            WooShiptimize::log("unknown status ignoring ");
            return;
        }

        $wp_status = self::$api_status2wp_status[ $status ];
        $this->woo_order->update_status($wp_status ,'pushed from the api '.date('d-m-Y H:i'));
        $this->add_message($this->get_formated_message($shiptimize->translate("api sent status").' '.$wp_status) );
    }


    public function set_tracking_id($tracking_id, $tracking_url = ''){
        global $wpdb, $shiptimize;

        ##Reseno
        $order = wc_get_order( $this->ShopItemId ); // returns WC_Order object.
        if ($tracking_id == $order->get_meta( 'shiptimize_trackingid', true)) {
            WooShiptimize::log("Order already has trackingid $tracking_id ignoring");
            return;
        }

        WooShiptimize::log("Updating tracking $tracking_id for order [$this->ShopItemId]");
        $this->add_message($this->get_formated_message($shiptimize->translate("api sent trackingId:").' '.$tracking_id));

        $sql = sprintf("update  %sshiptimize set tracking_id=\"%s\" where id=%d  ",
            $wpdb->prefix,
            $tracking_id,
            $this->ShopItemId
        );

        $this->executeSQL($sql);

        // Programatically add the tracking id to the order notes
        $this->woo_order->add_order_note("TRACKING$tracking_id");

        ##Reseno
        $order = wc_get_order( $this->ShopItemId ); // returns WC_Order object.

        $order->update_meta_data( 'shiptimize_trackingid', $tracking_id );
        update_post_meta($this->ShopItemId, 'shiptimize_trackingid', $tracking_id);

        if ($tracking_url) {
            ##Reseno
            $order->update_meta_data( 'shiptimize_trackingurl', $tracking_url );
        }

        $order->save();
        $this->set_status( ShiptimizeOrder::$LABEL_STATUS_PRINTED );
    }

    /**
     * @return iso2 country
     */
    public function get_country(){
        return $this->Country;
    }

    /**
     * We will use this to generate the notice on the bulk action,
     * Since we are forced to redirect
     *
     * @param object $summary
     *
     * @return a string containing the notifications html
     */
    public static function get_export_summary($summary){
        global $shiptimize;


        if( isset( $summary->message ) ){
            return  '<div class="notice notice-info"><p>' . $summary->message . '</p></div>';
        }

        $html = '<div class="notice notice-info">';

        $html.= '<p>'.sprintf(
                $shiptimize->translate('Sent %d orders. <br/>Exported: %d <br/> With Errors: %d'),
                $summary->nOrders,
                $summary->n_success,
                $summary->nInvalid + $summary->n_errors
            );

        $html.='</p>';

        if( isset($summary->login_url) ) {
            $html.='<p> 
        <strong>'. $shiptimize->translate( 'Click') .' <a href="' . $summary->login_url . '" target="_blank">' . SHIPTIMIZE_BRAND . '</a> ' .$shiptimize->translate('if not opened') . '.</strong></p>';
        }

        $html.= '</div>';

        return $html;
    }

    /**
     * @return array of billing fields
     */
    public static function get_billing_fields( ) {
        if(!self::$billing_fields){
            self::set_woo_extra_fields();
        }

        return self::$billing_fields;
    }

    /**
     * @return array of billing fields
     */
    public static function get_shipping_fields( ) {
        if(!self::$shipping_fields){
            self::set_woo_extra_fields();
        }

        return self::$shipping_fields;
    }

    /**
     *  Some plugins call this function from inside the custom id filter
     *
     * @return the order in which this order was created
     */
    public function get_date_created( ) {
        return $this->woo_order->get_date_created();
    }

    /**
     * @return the order value
     */
    public function getOrderValue(){
        return  number_format($this->Value,2,'.','');
    }

    /**
     *  Get status for this order
     * @param int $post_id
     */
    public static function get_shipping_status( $post_id ) {
        global $wpdb;

        $spMeta = $wpdb->get_row($wpdb->prepare("select status from {$wpdb->prefix}shiptimize where id = %d ",$post_id));
        return $spMeta ? $spMeta->status : 1;
    }

    /**
     *  Get message for this order
     * @param int $post_id
     */
    public static function get_shipping_message( $post_id) {
        global $wpdb;

        $spMeta = $wpdb->get_row($wpdb->prepare("select message from {$wpdb->prefix}shiptimize where id = %d ",$post_id));
        return $spMeta ? $spMeta->message : '';
    }

    /**
     *  Get shiptimize metadata associated with this order
     *
     *  @param int $post_id
     *
     *  @return object - the row in the shiptimize order meta  with id post_id
     */
    public static function get_shipping_meta( $post_id ) {
        global $wpdb;

        return $wpdb->get_row($wpdb->prepare("select * from {$wpdb->prefix}shiptimize where id=%d",$post_id));
    }

    /**
     * return a label for the given status
     *
     * @param int $status
     *
     * @return a string representation of the status
     */
    public static function get_status_label($status){
        global $shiptimize;

        switch($status){
            case ShiptimizeOrder::$STATUS_NOT_EXPORTED:
                return $shiptimize->translate('Not Exported');

            case ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY:
                return $shiptimize->translate('Exported');

            case ShiptimizeOrder::$STATUS_EXPORT_ERRORS:
                return $shiptimize->translate('Error on Export');

            default:
                return $shiptimize->translate("Unknown status of id"). ' ' . $status;
        }
    }

    /**
     * return a list of items containing the given meta in a format we can use to send to the api
     * @param string Meta key - the item meta key
     * @param string Meta value - the item meta value
     */
    public function get_product_with_meta($meta_key, $meta_value, $replace_total_weight = false){

        $items = $this->woo_order->get_items();

        if(!is_array($items)) {
            return array();
        }

        $weight = 0;

        $ShipmentItems = array();
        foreach( $items as $item ) {
            $product = $item->get_product();

            $vendor_id = wc_get_order_item_meta( $item->get_id(), $meta_key, true );

            //    Remember that not all items are products
            if($product && ($meta_value == $vendor_id) ){
                $qty = $item->get_quantity();
                $item_weight = 0;

                if( $product->has_weight() ) {
                    $item_weight = wc_get_weight(floatval($product->get_weight()),'g');
                    $weight += $qty * $item_weight;
                }

                $item = array(
                    'Count' => $qty,
                    'Id' => $item->get_product_id(),
                    'Name' => $this->escape_text_data($product->get_name()),
                    'Type' => 4, // 1 - Gift, 2 - Documents, 3 - Sample , 4 - Other
                    'Value' => $item->get_subtotal(),
                    'Weight' => $item_weight
                );

                array_push($ShipmentItems, $item);
            }
        }

        if ($replace_total_weight) {
            $this->Weight = $weight;
        }

        return $ShipmentItems;
    }

    /**
     * @return WC_Order the woocommecer order
     */
    public function get_woo_order (){
        return $this->woo_order;
    }

    /**
     * Process the server response and append the appropriate status and messages to each shipment
     */
    public static function shipments_response( $response ) {
        global $shiptimize;

        $summary = (object) array(
            'errors' => array(),
            'n_success' => 0,
            'n_errors' => 0,
            'orderresponse' => array()
        );

        WooShiptimize::log("API responded with $response->httpCode");

        if($response->httpCode != 200){
            $summary->message = $response->httpCode ? "Error in api  $response->httpCode" : "Api did not respond ";
            return $summary;
        }

        foreach ($response->response->Shipment as $shipment) {
            $id = $shipment->ShopItemId;
            $order = new WooShiptimizeOrder($id);
            $hasErrors = isset($shipment->ErrorList);

            array_push($summary->orderresponse, $shipment);

            if ( $hasErrors ) {
                $order->append_errors($shipment->ErrorList);
                $actualerror = 1;
                foreach ($shipment->ErrorList as $error) {
                    if($error->Id == 298) { // Shipment was deleted in the app and contains incorrect export status in the shop system
                        WooShiptimize::log("Order $shipment->ShopItemId ");
                        $order->set_status(ShiptimizeOrder::$STATUS_NOT_EXPORTED);
                        $order->append_errors( array(
                            (object) array("Tekst" => "Shipment was deleted in the app. Export again")
                        ));
                    }
                    else if($error->Id == 297 || $error->Id == 200) { // Shipment already pre-alerted
                        WooShiptimize::log("Considering error $error->Id as warning. $error->Tekst");
                        $actualerror  = 0;
                    }
                    else {
                        WooShiptimize::log("Appending Error $error->Id $error->Tekst");
                        array_push ( $summary->errors, "$id - " . $error->Tekst );
                    }
                }

                // some stuff the api considers an error we consider a warning
                if ($actualerror) {
                    ++$summary->n_errors;
                }
                else {
                    ++$summary->n_success;
                }

                $order->set_status( $actualerror ?  ShiptimizeOrder::$STATUS_EXPORT_ERRORS : ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY );

            } else {
                ++$summary->n_success;
                $order->set_status( ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY );
            }

            /**
             * Some warnings we care about as errors for label creation such as "on hold"
             */
            if (isset($shipment->WarningList)) {
                foreach ($shipment->WarningList as $warning) {
                    WooShiptimize::log("Appending Error $warning->Id $warning->Tekst");
                    array_push( $summary->errors, array(
                        'Id' => $warning->Id,
                        'Tekst' => 'Warn ' . $warning->Tekst,
                        'ShopItemId' => $shipment->ShopItemId
                    ) );
                }
            }

//      Check if Carrier does not match , carrier exists , ID is not 0 and ID != Transporter
            if(isset($shipment->CarrierSelect) && $shipment->CarrierSelect->Id && $shipment->CarrierSelect->Id != $order->get_carrier_id() ){
                $order->add_message( $order->get_formated_message($shiptimize->translate("Diferent carrier selected by the API")) );
            }

            $meta = $order->get_order_meta();
            $message = self::get_formated_message( self::get_status_label( $meta->status ) );
            $order->add_message( $message );
        }

        return $summary;
    }


    /**
     * Export the specific orders received by param
     * If the orders where already exported patch them!
     *
     * @param Number[] $orders2Export - array containing the ids of the orders to export
     * @param int $try  - iteration. We use this to retry once on auth failed, since the token can expire or be invalidated externally
     * @return a summary object containing the number of
     */
    public static function export ( $orders2Export, $try = 0 ) {
        global $wpdb;

        $summary = (object)array(
            'n_success' => 0,
            'n_errors' => 0,
            'nOrders' => 0,
            'nInvalid' => 0,
            'errors' => array(),
            'orderresponse' => array()
        );

        if(! count( $orders2Export ) ){
            return $summary;
        }

        $shiptimize_patch_orders = array();
        $shiptimize_orders = array();
        $nInvalid = 0;

        $api = WooShiptimize::get_api();

        foreach($orders2Export as $id){
            $order = new WooShiptimizeOrder($id);
            $ordermeta = $order->get_order_meta();

            if ( isset($ordermeta->status) && $ordermeta->status == ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY ) {
                array_push( $shiptimize_patch_orders, $order->get_api_props() );
            }
            else if ( $order->is_valid() ){
                array_push( $shiptimize_orders, $order->get_api_props() );
            }
            else {
                ++$nInvalid;
                $order->set_status(WooShiptimizeOrder::$STATUS_EXPORT_ERRORS);
                $order->set_message('<b>Error:</b><br/> ' . $order->get_error_messages());
                //Fetch this posts metadata
                $post_meta = $wpdb->get_results(sprintf("select * from %spostmeta where post_id=%d", $wpdb->prefix, $id));
                $metastr = '<br/><b> Shipping && Billing Metadata</b>';
                foreach($post_meta as $m){
                    if(stripos($m->meta_key, 'shipping') || stripos($m->meta_key, 'billing')){
                $metastr .= '<br/>'.`Hello`;
                    }
            }

            $order->add_message($metastr);
            array_push( $summary->errors ,$order->get_error_messages());
            }
        }

        // POST new orders
        if (count($shiptimize_orders)) {
            $response =  $api->post_shipments($shiptimize_orders);

            if ($response->httpCode == 401 && $try < 1) {
                WooShiptimize::refresh_token();
                return self::export($orders2Export , 1);
            }
            else if ( $response->httpCode == 401 ) {
                $shiptimize = WooShiptimize::instance();
                $summary->message = $shiptimize->translate('setcredentials');
            }
            else {
                $summary = self::shipments_response($response);
            }

            if (isset($response->response->AppLink)) {
                $summary->login_url = $response->response->AppLink;
            }
        }

        // PATCH Existing orders
        else if (count($shiptimize_patch_orders)) {
            $response =  $api->patch_shipments($shiptimize_patch_orders);

            if ($response->httpCode == 401 && $try < 1 ) {
                WooShiptimize::refresh_token();
                return self::export($orders2Export , 1);
            }
            else if ($response->httpCode == 401 ) {
                $shiptimize = WooShiptimize::instance();
                $summary->message .= '' . $shiptimize->translate('setcredentials');
            }
            else {
                $patchsummary = self::shipments_response($response);
                $summary->n_success += $patchsummary->n_success;
                $summary->n_errors += $patchsummary->n_errors;

                foreach($patchsummary->orderresponse as $order) {
                    array_push($summary->orderresponse, $order);
                }

            }

            if(isset($response->response->AppLink)){
                $summary->login_url =$response->response->AppLink;
            }
        }

        $summary->nOrders = count($orders2Export);
        $summary->nInvalid = $nInvalid;

        return $summary;
    }

    /**
     * Export all orders with status != exported sucessfully;
     * We use the id and not the clientReference to identify the order because that's the system identifier.
     * ClientReferences may be manipulated by external plugins and clients may activate and de-activate them at will.
     *
     * @return mixed an object describing the success of the export
     * @return void
     */
    public static function export_all() {
        global $wpdb;

        $export_status = '"'.implode( '","', self::get_valid_status_to_export_all() ).'"';

        $sql = sprintf(
            " select {$wpdb->prefix}posts.ID , status, post_status
      from {$wpdb->prefix}shiptimize
      right join {$wpdb->prefix}posts
      on {$wpdb->prefix}shiptimize.id = {$wpdb->prefix}posts.ID
      where post_type=%s and (status is null or status != %d) and post_status in ( %s  ) ",
            '"shop_order"',
            ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY,
            $export_status
        );

        $orders2Export = $wpdb->get_results( $sql );

        $ids = array();
        foreach( $orders2Export as $dbOrder){
            $ids[] = $dbOrder->ID;
        }

        WooShiptimize::log("Export all orders: " . join(',',$ids));
        $summary = self::export( $ids );

        wp_redirect(WooShiptimizeOrder::get_redirect_from_summary($summary));
        die();
    }

    /**
     * Print label
     * @param array - array of integers representing orderids
     */
    public static function print_label($orderids) {
        global $shiptimize;

        if (empty($orderids)) {
            WooShiptimize::log("\nno order id was provided, cannot print label");
            return array('error' => 'no order id was provided');
        }

        WooShiptimize::log("\n\n=== Requesting label for ". implode(',', $orderids));

        // Export the order
        $summary = WooShiptimizeOrder::export($orderids);
        WooShiptimize::log("\n Export summary " . json_encode($summary));

        $labelorders = array();
        $errors = array();

        if(isset($summary->orderresponse)) {
            if (isset($summary->message)) {
                array_push($errors, $summary->message);
            }


            // Print Labels for exported orders without errors
            foreach ($summary->orderresponse as $order) {
                if(isset($order->ErrorList)) {
                    foreach($order->ErrorList as $error) {
                        if ($error->Id > 0 && ($error->Id != 200) && ($error->Id != 297)) {
                            array_push($errors, 'Error: ' . $error->Tekst);
                            WooShiptimize::log("Not printing $order->ShopItemId because $error->Tekst ");
                        }
                        else { // error we want to ignore
                            WooShiptimize::log("Ignoring error $error->Id for $order->ShopItemId $error->Tekst ");

                            array_push($labelorders, $order->ShopItemId);
                        }
                    }
                }
                else { // print the label
                    WooShiptimize::log("Printing label fol $order->ShopItemId");
                    array_push($labelorders, $order->ShopItemId);
                }
            }
        }
        else {
            WooShiptimize::log("No orders in export summary");
        }

        WooShiptimize::log("Label orders  " . json_encode($labelorders));
        WooShiptimize::log("\nErrors " . json_encode($errors));

        if (!empty($labelorders)) {
            // The api receives client references make sure we're sending that and not ShopItemIds
            $labelReference = array();
            foreach ($labelorders as $orderid) {
                $reference = apply_filters( 'woocommerce_order_number', $orderid,  wc_get_order( $orderid ) );
                array_push($labelReference, $reference);
                WooShiptimize::log("label for order id $orderid  ref $reference");
            }

            $labelresponse = WooShiptimize::get_api()->post_labels_step1($labelReference);
            WooShiptimize::log("Label response is " . var_export($labelresponse,true));
            if (isset($labelresponse->response->ErrorList)){
                foreach ($labelresponse->response->ErrorList as $error) {
                    array_push($errors, $error->Info);
                }
            }
            WooShiptimize::log( "Labelresponse " . json_encode($labelresponse) );
            return $labelresponse;
        }
        else {
            // Push previous errors to the errors we will display to the user
            array_push($errors, 'no labels to print');
        }

        return array('errors' =>  array_merge($summary->errors, $errors));
    }

    /**
     * Return a redirect url string from the summary of the export
     * @return String
     */
    public static function get_redirect_from_summary($summary){
        $url_string = '';
        foreach( (array) $summary as $key=>$value){
            $url_string.="&shiptimize_$key=$value";
        }

        return admin_url("edit.php?post_type=shop_order&paged=".filter_input( INPUT_GET, 'paged').$url_string);
    }

    /**
     * @override
     * @return mixed - an array of valid status ids
     */
    static public function get_valid_status_to_export_all() {
        $statuses = wc_get_order_statuses();
        $status_list = array();

        foreach( $statuses as $status_key => $status_label ) {
            if ( get_option('shiptimize_export_statuses-'.$status_key) ) {
                array_push($status_list, $status_key);
            }
        }
        return $status_list;
    }

    /**
     * @override
     */
    public function executeSQL( $sql ) {
        global $wpdb;

        //WooShiptimize::instance()->log($sql);
        return $wpdb->query($sql);
    }

    /**
     * @override
     */
    public function sqlSelect( $sql ) {
        global $wpdb;

        //WooShiptimize::instance()->log($sql);
        return $wpdb->get_results($sql);
    }

    /**
     * Init billing and shipping fields we display + save.
     * as defined in WC_Meta_Box_Order_Data
     * @param array $fields - an array of declared fields
     */
    public static function set_woo_extra_fields (  ) {
        self::$billing_fields = apply_filters(
            'woocommerce_admin_billing_fields', array(
                'first_name' => array(
                    'label' => __( 'First name', 'woocommerce' ),
                    'show'  => false,
                ),
                'last_name'  => array(
                    'label' => __( 'Last name', 'woocommerce' ),
                    'show'  => false,
                ),
                'company'    => array(
                    'label' => __( 'Company', 'woocommerce' ),
                    'show'  => false,
                ),
                'address_1'  => array(
                    'label' => __( 'Address line 1', 'woocommerce' ),
                    'show'  => false,
                ),
                'address_2'  => array(
                    'label' => __( 'Address line 2', 'woocommerce' ),
                    'show'  => false,
                ),
                'city'       => array(
                    'label' => __( 'City', 'woocommerce' ),
                    'show'  => false,
                ),
                'postcode'   => array(
                    'label' => __( 'Postcode / ZIP', 'woocommerce' ),
                    'show'  => false,
                ),
                'country'    => array(
                    'label'   => __( 'Country', 'woocommerce' ),
                    'show'    => false,
                    'class'   => 'js_field-country select short',
                    'type'    => 'select',
                    'options' => array( '' => __( 'Select a country&hellip;', 'woocommerce' ) ) + WC()->countries->get_allowed_countries(),
                ),
                'state'      => array(
                    'label' => __( 'State / County', 'woocommerce' ),
                    'class' => 'js_field-state select short',
                    'show'  => false,
                ),
                'email'      => array(
                    'label' => __( 'Email address', 'woocommerce' ),
                ),
                'phone'      => array(
                    'label' => __( 'Phone', 'woocommerce' ),
                ),
            )
        );

        self::$shipping_fields = apply_filters(
            'woocommerce_admin_shipping_fields', array(
                'first_name' => array(
                    'label' => __( 'First name', 'woocommerce' ),
                    'show'  => false,
                ),
                'last_name'  => array(
                    'label' => __( 'Last name', 'woocommerce' ),
                    'show'  => false,
                ),
                'company'    => array(
                    'label' => __( 'Company', 'woocommerce' ),
                    'show'  => false,
                ),
                'address_1'  => array(
                    'label' => __( 'Address line 1', 'woocommerce' ),
                    'show'  => false,
                ),
                'address_2'  => array(
                    'label' => __( 'Address line 2', 'woocommerce' ),
                    'show'  => false,
                ),
                'city'       => array(
                    'label' => __( 'City', 'woocommerce' ),
                    'show'  => false,
                ),
                'postcode'   => array(
                    'label' => __( 'Postcode / ZIP', 'woocommerce' ),
                    'show'  => false,
                ),
                'country'    => array(
                    'label'   => __( 'Country', 'woocommerce' ),
                    'show'    => false,
                    'type'    => 'select',
                    'class'   => 'js_field-country select short',
                    'options' => array( '' => __( 'Select a country&hellip;', 'woocommerce' ) ) + WC()->countries->get_shipping_countries(),
                ),
                'state'      => array(
                    'label' => __( 'State / County', 'woocommerce' ),
                    'class' => 'js_field-state select short',
                    'show'  => false,
                ),
            )
        );
    }
}