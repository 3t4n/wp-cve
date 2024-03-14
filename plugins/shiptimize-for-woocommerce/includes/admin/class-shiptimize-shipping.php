<?php
/**
 * Handle the shipping options
 * Add carriers as shipping methods, generate the necessary classes to declare them on woocommerce
 *
 * Furthermore, woo replaces the checkout html so to grant people will see the button when it's available
 * for the selected shipping method we must devide this in to 2 parts :
 * 1. declare an html element in the checkout summary table
 * 2. on update order review fragments send the button if available
 */
class ShiptimizeShipping {

    private static $_instance = null;

    /**
     * @var bool wbs_active
     */
    private $wbs_active = false;

    /** Did we init shipping ? **/
    private $started = 0;

    private function __construct(){
        $this->actions();
        $this->filters();

        $this->is_dev = file_exists(ABSPATH . 'isdevmachine');
    }

    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function actions() {
        add_action( 'woocommerce_shipping_init', array( $this, 'shiptimize_init_shipping' ) );
        add_action( 'wp_footer', array( $this, 'script_carriers_with_pickup' ) );
        add_action( 'wp_ajax_nopriv_shiptimize_pickup_locations', array( $this, 'ajax_get_pickup_locations' ) );
        add_action( 'wp_ajax_shiptimize_pickup_locations', array( $this, 'ajax_get_pickup_locations' ) );

        /** save wbs instance properties **/
        add_action('wp_ajax_shiptimize_wbs_settings', array( $this, 'ajax_wbs_settings') );
        add_action( 'woocommerce_checkout_update_order_meta' , array( $this, 'shiptimize_order_submited') );

        // declare the pickup point
        add_action( 'woocommerce_checkout_fields', array( $this, 'checkout_fields'),10, 2);

        // display the pickup point in the order details
        add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'checkout_pickup_field_display_admin_order_meta') );

        // Validate the pickup points
        add_action( 'woocommerce_after_checkout_validation', array( $this, 'checkout_validation'), 10, 2 );
    }


    /**
     * This function will be called from ajax
     * http://{domain}/?wc-ajax=checkout
     */
    public function shiptimize_order_submited ( $order_id ) {
        global $wpdb;

        require_once SHIPTIMIZE_PLUGIN_PATH.'/includes/core/class-shiptimize-order.php';

        $selected_pickup = filter_input( INPUT_POST ,  'shipping_pickup_id' );
        $carrier_id = filter_input(INPUT_POST, 'shipping_carrier_id');
        $shipping_method = filter_input(INPUT_POST, 'shipping_method',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $pickup_label = filter_input(INPUT_POST, 'shipping_pickup_label');
        $pickup_extended = filter_input(INPUT_POST,'shiptimize_pickup_extended');

        #
        # Append this info into the post meta so it can be picked up more easily by third party plugins
        ##Reseno

        if ( ! empty( $_POST['shiptimizepickup'] ) ) {
            $order = wc_get_order( $order_id ); // returns WC_Order object.

            $order->update_meta_data('shiptimizepickup', sanitize_text_field( $_POST['shiptimizepickup'] ));
            $order->update_meta_data('shiptimizepickuplabel', sanitize_text_field( $pickup_label ) );
            $order->update_meta_data('shiptimizepickupextended', sanitize_text_field( $pickup_extended ));

            $order->save();
        }

        if( !is_numeric( $carrier_id ) && isset($shipping_method[0]) && ( $carrier = $this->get_shiptimize_carrier($shipping_method[0], $order_id) ) ){

            $carrier_id = $carrier->Id;
        }

        $sql = sprintf( "insert into %sshiptimize (`id`, `status`, `pickup_id`, `carrier_id`, `pickup_label`,`pickup_extended`) VALUES(%d, %d,\"%s\", %d,\"%s\",\"%s\") ",
            $wpdb->prefix,
            $order_id,
            ShiptimizeOrder::$STATUS_NOT_EXPORTED,
            $selected_pickup,
            $carrier_id,
            $pickup_label,
            $pickup_extended
        );

        $wpdb->query( $sql );
    }

    /**
     * Get shipping address from session, use it to get the pickup locations to display on the map
     * https://docs.woocommerce.com/wc-apidocs/source-class-WC_Cart_Session.html#167-181
     */
    public function ajax_get_pickup_locations(){

        $address = filter_input(INPUT_GET,"Address",FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);
        $shipping_method_id = filter_input( INPUT_GET, 'CarrierId' );

        if( !is_numeric( $shipping_method_id ) ){
            # There's only one plugin that uses generic rates to which we associate our carriers
            $carrier = $this->get_shiptimize_carrier_from_table_rates($shipping_method_id);
            if(!$carrier){
                echo "{\"Error\":1, \"Info\":\"This carrier is not a shiptimize carrier\"}";
            }

            $shipping_method_id = $carrier->Id;
        }

        $pickup_points = WooShiptimize::get_pickup_locations( $address, $shipping_method_id );

        if($pickup_points){
            if(isset($pickup_points->Error) && $pickup_points->Error->Id == 401) {
                WooShiptimize::refresh_token();
                $pickup_points = WooShiptimize::get_pickup_locations( $address, $shipping_method_id );
            }

            $pickup_points->carrierId = $shipping_method_id;
            echo json_encode($pickup_points);
        }
        else {
            echo json_encode((object)array(
                "Error" => "fatal error in requesting the pickup points",
                "Id" => -1
            ));
        }

        die('');
    }

    /**
     * Save wbs instance settings
     *
     */
    public function ajax_wbs_settings(){
        $data = filter_input(INPUT_POST,  'data',FILTER_DEFAULT , FILTER_REQUIRE_ARRAY);
        $instance_id = filter_input(INPUT_POST,  'instance_id');

        $settings = array();
        foreach($data as $setting){
            $settingname = $setting['name'];
            $settings[$settingname] = $setting['value'];
        }

        $setting_name = 'wbs_'.$instance_id.'_shiptimize';

        update_option($setting_name, $settings);
        var_export($settings);
        die('');
    }

    /**
     * Register Filters :  register_shipping_methods
     */
    public function filters() {
        add_filter( 'woocommerce_shipping_methods', array($this, 'add_shipping_methods') );
        add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'shipping_fragments' ) , 200 );
        add_filter( 'woocommerce_review_order_before_payment' , array( $this, 'shiptimize_shipping_options') , 200 ); //avoid conflicts with other plugins trying to print stuff Woo-wallet will print twice without this
    }

    /**
     * We will call this function every time we refresh the carriers
     * Thus granting all classes are up to date, while not generating them more often than necessary
     * This is done every time the user saves the options page
     */
    public static function clear_carrier_classes() {
        global $wpdb;

        $files = glob(SHIPTIMIZE_PLUGIN_PATH.'/includes/shipping-methods/*'); // get all file names
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        $wbs_active =  class_exists('\Wbs\ShippingMethod');

        //Make sure we also clean any unavailable methods from the datamodel
        $methods = $wpdb->get_results( "select * from {$wpdb->prefix}woocommerce_shipping_zone_methods " );


        foreach($methods as $method){
            if(stripos($method->method_id, 'shiptimize') === false ){
                continue;
            }

            if( stripos($method->method_id,'weight') !== false && !$wbs_active){
                $wpdb->query( "delete from {$wpdb->prefix}woocommerce_shipping_zone_methods where instance_id = ".$method->instance_id);
            }
        }

    }

    /**
     * We need this method to insert the html where we will update the pickup button
     * However.. it is called from ajax also, which would duplicate our html snipet, so we must return null if it's ajax.
     */
    public function shiptimize_shipping_options () {
        if ( is_ajax() ) {
            return;
        }
        echo "<table class='shiptimize-shipping-options'></table>";
    }

    /**
     * We use this action to return a fragment
     * that will display the options for the selected shipping method
     */
    public function shipping_fragments( $fragments ){
        global $shiptimize;

        if(get_option('shiptimize_pickupdisable')){
            WooShiptimize::log("pickup points are disabled");
            return $fragments;
        }

        $shiptimize_options = "<table class='shiptimize-shipping-options'>";
        $choosen_methods_woo = WC()->session->get( 'chosen_shipping_methods' );

        WooShiptimize::log("Session " . var_export(WC()->session, true));
        WooShiptimize::log("Methods " . var_export($choosen_methods_woo, true));

        if( isset($choosen_methods_woo[0]) ) {

            $carrier = $this->get_shiptimize_carrier($choosen_methods_woo[0]);
            $HasPickup = self::is_carrier_pickup_able($carrier);

            if($carrier && $HasPickup) {
                // Display the select point button unless it's configured as impossible
                $settings = $this->get_settings_from_shipping_method_id($choosen_methods_woo[0]);
                $pickupbehaviour = isset($settings['pickupbehaviour']) ? $settings['pickupbehaviour'] : 0;

                if($pickupbehaviour != ShiptimizeOrder::$PICKUP_BEHAVIOUR_IMPOSSIBLE) {
                    $shiptimize_options .= "<tr><td><input type='hidden' name='shipping_carrier_id' id='shipping_carrier_id'/>";

                    $shiptimize_options  .=  "
                <button class='button alt shiptimize-pick-location' onclick='shiptimize.getPickupLocations(event)''>" .$shiptimize->translate('Choose Pickup Location') . "</button> 
                <input type='hidden' name='shipping_pickup_id' id='shipping_pickup_id'/>
                
                <input type='hidden' name='shipping_pickup_label' id='shipping_pickup_label'/>
                <input type='hidden' name='shiptimize_pickup_extended' id='shipping_pickup_extended'/>
                <span class='shiptimize-pickup__description'></span>
                <script>
                var shiptimize_selected_pickup = '" . $shiptimize->translate( 'Selected Pickup' ) . "';
                var shiptimize_geolocationfailed = '" . $shiptimize->translate( 'geolocationfailed' ) . "';
                var shiptimize_mapfieldmandatory = '" . $shiptimize->translate( 'mapfieldmandatory' ) . "';
                </script>
             ";
                    $shiptimize_options.= "<script> jQuery( function(){ shiptimize.platform.setCarrier($carrier->Id); } ); </script>";
                    $shiptimize_options.=  "</td></tr>";
                }
                else {
                    WooShiptimize::log("$carrier->Name  is configured to NOT ALLOW SELECTION OF SERVICE POINTS ");
                }
            }
            else {
                WooShiptimize::log( $carrier ? "$carrier->Name does not have pickup points " : "No carrier for ".$choosen_methods_woo[0]);
            }
        }
        else {
            WooShiptimize::log("No Shipping method was choosen ");
        }

        $fragments['.shiptimize-shipping-options'] = $shiptimize_options.'</table>';

        return $fragments;
    }

    public function checkout_fields( $fields ) {

        $fields['order']['shiptimizepickup'] = array(
            'label'     => "", # the label will still show up even if it's a hidden field
            'placeholder'   => '',
            'required'  => false,
            'type' => 'hidden',
            'validate' => array('shiptimizepickup'),
        );

        return $fields;
    }

    /**
     * @return bool does this carrier contain at least one option providing
     */
    public static function is_carrier_pickup_able($carrier) {
        $HasPickup = false;

        WooShiptimize::log("is_carrier_pickup_able " . var_export($carrier,true));
        if (isset($carrier->OptionList)) {
            foreach($carrier->OptionList as $option) {
                if ($option->Type == 1 && isset($option->OptionValues)) { // Points in service levels
                    foreach($option->OptionValues as $optionValue) {
                        if(isset($optionValue->IsPickup) && $optionValue->IsPickup) {
                            WooShiptimize::log("HasPickup for $carrier->Name found in $option->Name");
                            $HasPickup = true;
                        }
                    }
                } else if(isset($option->IsPickup) && $option->IsPickup > 0) { // Points in regular options
                    WooShiptimize::log("HasPickup for $carrier->Name found in $option->Name");
                    $HasPickup = true;
                }

            }
        }

        return $HasPickup;
    }

    public function get_settings_from_shipping_method_id($shipping_method) {
        if (!$shipping_method) {
            return;
        }

        $matches = array();
        preg_match("/shipping_shiptimize_([0-9]+)[a-z0-9\_]*\:?([0-9]*)?/", $shipping_method, $matches);

        if(empty($matches)) {
            return;
        }

        $shipping_method_id = $matches[1];
        $shipping_instance_id = $matches[2];


        if (stripos($shipping_method, '_weight')) { //wbs
            $settings =  get_option("wbs_" . $shipping_instance_id . "_shiptimize");
            $msg = "wbs_" . $shipping_instance_id . "_shiptimize";
        }
        else if (stripos($shipping_method, '_free')) {
            $settings = get_option('woocommerce_shipping_shiptimize_' . $shipping_method_id . '_free_' . $shipping_instance_id . '_settings');
            $msg = '<br/>woocommerce_shipping_shiptimize_' . $shipping_method_id.'_free_'.$shipping_instance_id.'_settings';
        }
        else { //flat rates
            $settings = get_option('woocommerce_shipping_shiptimize_' . $shipping_method_id . '_' . $shipping_instance_id . '_settings');
            $msg = '<br/>woocommerce_shipping_shiptimize_' . $shipping_method_id.'_'.$shipping_instance_id.'_settings';
        }

        WooShiptimize::log("Selected Shipping Method: $shipping_method Carrier $shipping_method_id , instanceid $shipping_instance_id matches: " . json_encode( $matches) . " $msg ");

        return $settings;
    }

    /**
     * Validate the checkout
     */
    public function checkout_validation($fields, $errors) {
        global $shiptimize;

        $settings = $this->get_settings_from_shipping_method_id($fields['shipping_method'][0]);

        if (!$settings) {
            WooShiptimize::log("Not a shiptimize method");
            return array('error' => 'not a shiptimize method ' . $fields['shipping_method'][0]);
        }

        $pickupbehaviour = isset($settings['pickupbehaviour']) ? $settings['pickupbehaviour'] : '';
        $shiptimizepoint = $fields['shiptimizepickup'];

        $msg = "<br/>Selected Point:$shiptimizepoint\npickup [ " . $pickupbehaviour ." ]\n<br/>Settings " . var_export($settings,true) ;
        WooShiptimize::log($msg);

        if (isset($settings['pickupbehaviour']) && $pickupbehaviour == ShiptimizeOrder::$PICKUP_BEHAVIOUR_MANDATORY && !$shiptimizepoint) {
            $errors->add( 'validation', $shiptimize->translate('mandatorypointmsg') );
        }

    }

    /**
     * Display field value on the order edit page
     */
    function checkout_pickup_field_display_admin_order_meta($order){
        ##Reseno
        $wc_order = wc_get_order( $order->get_id() ); // returns WC_Order object.

        $pickuppoint = $wc_order->get_meta( 'shiptimizepickup', true );
        if ($pickuppoint) {
            echo '<p><strong>'.__('Pickup Point').':</strong> ' . get_post_meta($order->get_id(), 'shiptimizepickuplabel', true) . '</p>';
        }
    }

    /**
     * Register the shipping methods available for this seller
     * @param array $methods - an array of registerd shipping methods
     */
    public function add_shipping_methods($methods){
        $shiptimize_methods = get_option('shiptimize_carriers');

        if(!$shiptimize_methods) {
            return $methods;
        }

        $carriers = json_decode($shiptimize_methods);

        foreach($carriers as $carrier){
            $methods['shipping_shiptimize_' . $carrier->Id] = $this->get_class_name_for_carrier($carrier);
            $methods['shipping_shiptimize_' . $carrier->Id . '_free'] = $this->get_class_name_for_carrier($carrier) . 'Free';
            if($this->wbs_active){
                $methods['shipping_shiptimize_' . $carrier->Id.'_weight'] = $this->get_class_name_for_carrier($carrier) . 'Weight';
            }
        }
        return $methods;
    }

    /**
     * Woocommerce forces us to have one class per shipping method.
     * We'll create the file once.
     * Load the classes
     */
    public function shiptimize_init_shipping() {
        WooShiptimize::log( "shiptimize_init_shipping " . $this->started );
        if ( $this->started++ ) {
            return;
        }

        $this->wbs_active = class_exists( '\Wbs\ShippingMethod');
        $carrier_json = get_option('shiptimize_carriers');
        $carriers = json_decode($carrier_json);

        if ( !$carriers ) {
            return;
        }

        foreach ( $carriers as $carrier ) {
            $this->loadFlatRateCarrier( $carrier );
            $this->loadFreeShippingCarrier( $carrier );

            if($this->wbs_active){
                $this->loadWeightBasedCarrier( $carrier );
            }
        }

    }

    public function loadFreeShippingCarrier($carrier){
        $class_name = $this->get_class_name_for_carrier($carrier) . 'Free';
        $file_name = 'class-shiptimize-shipping' . $class_name . '.php';
        $file_path = SHIPTIMIZE_PLUGIN_PATH . 'includes/shipping-methods/' . $file_name;

        if( !file_exists($file_path) ) {
            $this->writeShippingClassFree( $file_path, $class_name, $carrier);
        }

        require_once ( $file_path );
    }

    public function loadFlatRateCarrier($carrier){
        $class_name = $this->get_class_name_for_carrier($carrier);
        $file_name = 'class-shiptimize-shipping' . $class_name . '.php';
        $file_path = SHIPTIMIZE_PLUGIN_PATH . 'includes/shipping-methods/' . $file_name;

        if( !file_exists($file_path) ) {
            $this->writeShippingClass( $file_path, $class_name, $carrier);
        }

        require_once ( $file_path );
    }

    public function loadWeightBasedCarrier($carrier){
        $class_name = $this->get_class_name_for_carrier($carrier).'Weight';
        $weighbasedFile = 'class-shiptimize-shipping' . $class_name . '-weight.php';
        $file_path = SHIPTIMIZE_PLUGIN_PATH . 'includes/shipping-methods/' . $weighbasedFile;

        if( !file_exists( $file_path ) ){
            $this->writeWeightShippingClass( $file_path, $class_name , $carrier );
        }

        require_once ( $file_path );
    }

    /**
     *  We detect if pickup is available client side so we need to know which shipping methods allow it
     */
    public function script_carriers_with_pickup  ( ) {
        global $shiptimize;

        if(get_option('shiptimize_pickupdisable')){
            return;
        }
        ?>
        <div class="shiptimize-pickup">
            <div class="shiptimize-pickup__overlay"  onclick="shiptimize.hideMap()"></div>
            <div class='shiptimize-pickup__mapWrapper'>
                <div class='shiptimize-pickup__options'>
                    <h2 class='shiptimize-pickup__title'><?php echo $shiptimize->translate('maptitle') ?></h2>
                    <div class='shiptimize-pickup__other'>
                    </div>
                    <button class='button shiptimize-pickup__validate' onclick="shiptimize.selectFromList()"> <?php  echo $shiptimize->translate('Select'); ?></button>
                </div>

                <div class='shiptimize-pickup__error'></div>
                <div class="shiptimize-pickup__map" id='shiptimizeMap'></div>
                <div class="shiptimize-pickup__map-loader"><div class="shiptimize-loader"><div></div><div></div><div></div></div></div>
            </div>
            <div class="shiptimize-pickup__close" onclick="shiptimize.hideMap()"></div>
        </div>
        <script>
            var shiptimize_maps_key = '<?php echo get_option('shiptimize_maps_key') ?>';
            var SHIPTIMIZE_PLUGIN_URL = '<?php echo SHIPTIMIZE_PLUGIN_URL ?>';
            var shiptimize_icon_folder = SHIPTIMIZE_PLUGIN_URL +'/assets/images/markers/';
            var shiptimize_no_points_found = '<?php  echo $shiptimize->translate("No pickup points returned by the carrier for this address", "shiptimize") ?>';
        </script>
        <?php
    }

    /**
     *
     * @param object $carrier - the carrier object as returned by the api
     * @return a class name for this carrier
     */
    private function get_class_name_for_carrier( $carrier ) {
        $clean_name = preg_replace('/[^a-zA-Z0-9]+/', '_', $carrier->Name);

        return 'ShiptimizeShipping' . $clean_name;
    }

    /**
     * If there is a shiptimize carrier associated with this rate_id return it
     */
    private function get_shiptimize_carrier_from_table_rates( $choosen_method, $order_id = '' ) {
        $results = array();
        preg_match( "/wc_table_rate_plus_([\d]*)/", $choosen_method, $results );

        if ( !empty( $results ) ) {
            $shiptimize_rates = get_option('shiptimize_table_rate_shipping_plus');

            if( ! isset( $shiptimize_rates[ $results[1] ] ) ){
                return;
            }

            $selected_rate = $shiptimize_rates[ $results[1] ];

            if( $order_id ){
                ##Reseno
                $order = wc_get_order( $order_id ); // returns WC_Order object.
                $order->update_meta_data( 'shiptimize_carrier', json_encode($selected_rate) );
                $order->update_meta_data( 'table_rate_plus_rate_id', $results[1] );

                $order->save();

            }

            $carriers  = json_decode ( get_option( 'shiptimize_carriers' ) );
            foreach( $carriers as $carrier ) {
                if($carrier->Id == $selected_rate['carrier_id']){
                    return $carrier;
                }
            }
        }
    }

    /**
     * Determine the carrier for this order which can be:
     *  + An instance of one of our methods
     *  + Determined by association if belongs to a different plugin
     *
     *   @param string $choosen_method - the method id choosen by the user
     *   @param int $orderid - optional - the order for which we are requesting this info
     */
    public function get_shiptimize_carrier($choosen_method, $order_id ='') {
        WooShiptimize::log('choosen_method ' . $choosen_method);

        if(stripos($choosen_method, 'table_rate_plus')) {
            return $this->get_shiptimize_carrier_from_table_rates($choosen_method, $order_id);
        }

        if(! stripos($choosen_method, 'shiptimize') ){
            return;
        }

        $method_parts = explode( ':' , $choosen_method );
        $method_id = $method_parts[0];

        $carriers  = json_decode ( get_option( 'shiptimize_carriers' ) );

        foreach($carriers as $carrier){
            $shiptimize_method_id = $this->get_shipping_method_id($carrier);
            if( $shiptimize_method_id == $method_id || $shiptimize_method_id . '_weight' == $method_id ||  $shiptimize_method_id . '_free' == $method_id ){
                return $carrier;
            }
        }

    }

    /**
     * Get a unique id for woo
     * @param object $carrier - the carrier object as returned by the api
     * @return string - an id for this carrier
     */
    private function get_shipping_method_id( $carrier ) {
        return 'shipping_shiptimize_'.$carrier->Id;
    }

    /**
     * Return a string to append to the class file refresenting this option
     */
    public  function get_option_string($type,$class,$optionname,$defaultvalue='',$options=''){
        $str= "\$this->instance_form_fields['$optionname'] = array(
                'title'             => \$translate('$optionname'),
                'type'              => '$type',
                'class'       => '$class',
                'default'           => $defaultvalue,";
        if($options) {
            $str.=" 'options' => $options";
        }
        $str.="  );
          \$this->$optionname = \$this->get_option( '$optionname' , '');";

        return $str;
    }

    /**
     * Since we are forced to declare  shipping classes we will generate them for the carriers available in the shop admin's contract.
     *
     * @param String $file_path - the absolute  file_path where to save this class
     * @param String $class_name  - the class name
     * @param Object $carrier - object returned by the api representing this carrier
     * @param String $service_level_options - optional - the options to append to the config in the format 'service_id_0' => 'service_label_0', 'service_id_1' => 'service_label_1' ...
     */
    public function writeShippingClass ( $file_path, $class_name , $carrier) {
        global $shiptimize;

        $shiptimizeOptions = ShiptimizeOptions::getInstance();

        /**
         * Ids that are safe to forward to the user
         * These serviçes must be GLOBAL to the carrier and not depend on factors like country
         * There is nothing in the endpoint that tells us what rules apply
         * So if you add something here make sure you  know  what your are doing
         * and if necessary modify the write shipping class to be smart enough to handle it.
         *
         * sendinsured are insurances of type 0 -  all type 0 show up in the same dropdown and are  mutually exclusive
         * sendinsuredV are insurances of type 2 - these are checkboxes
         */
        $extra_option_fields_ids = $shiptimizeOptions->getAllowedExtraOptions();
        $checkbox_option_fields_ids  = $shiptimizeOptions->getCheckboxFieldIds();

        $service_level_options = '';

        $checkbox_fields =array(); // an array of strings, each one is a checkbox field
        $extraoptions_field = '';
        $extraoptions_values = '';
        $optionvalues = array();

        $HasPickup = self::is_carrier_pickup_able($carrier);

        if (isset($carrier->OptionList)) {
            $pickup0 = $shiptimize->translate('pickuppointbehavior0');
            $pickup1 = $shiptimize->translate('pickuppointbehavior1');
            $pickup2 = $shiptimize->translate('pickuppointbehavior2');

            if($HasPickup) {
                $extraoptions_field = "\n\n" . $this->get_option_string('select','shiptimize-pickupbehaviour','pickupbehaviour',0,"array('0'=> \"$pickup0\", '1' => \"$pickup1\", '2' =>\"$pickup2\")");
            }

            foreach ($carrier->OptionList as $option) {
                switch($option->Type) {
                    case 0: //extra options these all go in the same select
                        //Filter stuff out if it's on the list treat it as a field with options
                        if (in_array($option->Id, $extra_option_fields_ids)) {
                            $extraoptions_values .= ($extraoptions_values ? ',':'')."'$option->Id'=>'$option->Name'";

                            if (isset($option->OptionFields)) {
                                foreach ($option->OptionFields as $field) {
                                    $curroptionvalues = array(
                                        'name' => 'extraoptions' . $option->Id,
                                        'class' => 'shiptimize-extra-option-values',
                                        'values' => array()
                                    );
                                    if (isset($field->OptionValues) && is_array($field->OptionValues)) {
                                        foreach ($field->OptionValues as $optionValue) {
                                            array_push($curroptionvalues['values'], "\"$optionValue->Id\" =>\"$optionValue->Name\"");
                                        }
                                        array_push($optionvalues, $curroptionvalues);
                                    }
                                }
                            }
                        }
                        break;

                    case 1: //Service level -  we ALWAYS display items of this type
                        $service_level_options  = ' "" => "-" ';
                        if(isset($option->OptionValues )){
                            foreach ($option->OptionValues as $serviceLevel) {
                                $service_level_options .= " , '". $serviceLevel->Id ."'  => '$serviceLevel->Name'";
                            }
                        }
                        break;

                    default:
                        //checkbox type of fields type 2
                        //Filter stuff out if it's on the list
                        foreach ($checkbox_option_fields_ids as $key => $value) {
                            if ($key == $option->Id) {
                                $options = "array(0=>\$shiptimize->translate('No'),'$option->Id'=>\$shiptimize->translate('Yes'))";
                                array_push($checkbox_fields,$this->get_option_string('select','shiptimize-'.$value,$value,0,$options));

                            }
                        }
                        break;
                }
            }  /** /Foreach optionList **/

            if($extraoptions_values){
                $extraoptions_field .= "\n\n" . $this->get_option_string('select','shiptimize-extra-options','extraoptions',0,"array('0'=>'-',$extraoptions_values)");
                // Append any valid option values

                if (count($optionvalues)>1) {
                    foreach ($optionvalues as $option) {
                        $extraoptions_field .= "\n\n".$this->get_option_string('select',
                                $option['class'],
                                $option['name'],
                                0,
                                "array('0'=>'-'," . join(',',$option['values']) . ")");
                    }
                }
            }

        }


        // Only display the exclude classes if the advanced shipping plugin is active
        $excludeclasses = '';
        if ( is_plugin_active('woocommerce-advanced-shipping/woocommerce-advanced-shipping.php') ) {
            $excludeclasses = $this->get_option_string('multiplecheckboxes','shiptimize-excludeclasses','excludeclasses',0,"");
        }

        WooShiptimize::log( $carrier->Name . " ".(  isset($carrier->OptionList ) ? json_encode( $carrier->OptionList )  :'' ));

        $service_level_field = $service_level_options ? $this->get_option_string('select','shiptimize-service-level','service_level',0,"array($service_level_options)") : '';

        $class_contents = sprintf("<?php 
    /** 
     * Declares a shipping method for carrier {$carrier->Name} 
     */ 
    class {$class_name} extends WC_Shipping_Flat_Rate { 
        /**
         * Constructor.
         *
         * @param int \$instance_id Instance ID.
         */
        public function __construct( \$instance_id = 0){
          global \$shiptimize; 

          \$this->instance_id = absint( \$instance_id );
          \$this->id = '" . $this->get_shipping_method_id( $carrier ) .  "';
          \$this->title = '{$carrier->Name} Flat Rate'; //name for end user 
          \$this->method_title = 'Shiptimize: {$carrier->Name} Flat Rate'; //name for admin 
          \$this->method_description = 'Shiptimize {$carrier->Name}'; // description for admin 
          \$this->has_pickup = " . ($HasPickup ? 'true' : 'false' ). ";
      
          \$this->supports = array(
                'shipping-zones',
                'instance-settings',
                'instance-settings-modal',
          );

          // Default to returning the string passed by param 
          \$translate = function (\$str){ return \$str;}; 

          // how it's possible that someone declares this method before there's an instance of the plugin is a mistery to solve later 
          // possible they copied the code and did their own  rendition of the thing? 
          if(!\$shiptimize && class_exists('WooShiptimize')) {
            \$shiptimize = WooShiptimize::instance();
          }

          # If we have an instance of shiptimize, then use that translate function 
          if(\$shiptimize) {
            \$translate = function (\$str) { global \$shiptimize; return \$shiptimize->translate(\$str); };
          }

          \$this->init();
          \$this->options = get_option(\$this->id . '_' . \$this->instance_id . '_settings');
          
          /*Service Level*/
          %s
          
          /*Extraoptions */ 
          %s

          /** checkboxfields **/  
          %s 

          /** Exclude classes **/ 
          %s

         
          add_action( 'woocommerce_update_options_shipping_' . \$this->id, array( \$this, 'process_admin_options' ) ); 
 
        } 

        public function validate_excludeclasses_field( \$key , \$value ) {
          if ( \$key === 'excludeclasses' ) {
            return empty(\$value) ? '' : implode( ',' , \$value );
          }
          return \$value;
        }

        public function get_admin_options_html()
        {
          if ( \$this->instance_id ) {
            \$settings_html = \$this->generate_settings_html( \$this->get_instance_form_fields(), false );
          } else {
            \$settings_html = \$this->generate_settings_html( \$this->get_form_fields(), false );
          }

          
          \$excludeclassesoptions = array();
          if ( is_plugin_active('woocommerce-advanced-shipping/woocommerce-advanced-shipping.php') ) { 
              \$shipping  = new \WC_Shipping(); 
              \$opt_exclude_classes = explode( ',', \$this->get_instance_option('excludeclasses') );
              
              foreach ( \$shipping->get_shipping_classes() as \$shipping_class ) {

                array_push( \$excludeclassesoptions, array( 
                  'id' => \$shipping_class->term_id,
                  'name' => \$shipping_class->name,
                  'selected' => in_array( \$shipping_class->term_id , \$opt_exclude_classes ) ? 'checked' : ''
                ));
              }
          }
          return '<table class=\"form-table\">' . \$settings_html . '</table><script>
          var optionsid = \"#woocommerce_' . \$this->id . '_extraoptions\";
          
//          setTimeout( function(){
//            console.log(\"Helloo from '.\$this->id.'\");
//
//            jQuery(\".shiptimize-extra-option-values\").parent().parent().parent().hide();
//            shiptimize_extraoption_values();
//
//            jQuery(optionsid).change(shiptimize_extraoption_values); 
//
//            setExcludeClasses();
//          }, 0);

          function shiptimize_extraoption_values(){
              var selectedoption = jQuery(optionsid).val(); 
              jQuery(\".shiptimize-extra-option-values\").parent().parent().parent().hide();
              jQuery(\"#woocommerce_' . \$this->id . '_extraoptions\" + selectedoption).parent().parent().parent().show(); 
          }

          function setExcludeClasses() {
            var excludeoptions =' . json_encode(\$excludeclassesoptions) . ';
            var select = jQuery(\"#woocommerce_shipping_shiptimize_{$carrier->Id}_excludeclasses\"); 
            var content = select.parent();
            select.remove();  
            for ( var x=0; x< excludeoptions.length; ++x ) { 
              content.append(\'<span class=\"shiptimize-ib shiptimize-exclude-class\"> <input type=\"checkbox\" name=\"woocommerce_shipping_shiptimize_{$carrier->Id}_excludeclasses[]\" value=\"\' + excludeoptions[x].id + \'\" \' + excludeoptions[x].selected + \' /> \' + excludeoptions[x].name + \'</span>\');
            }


          }
          </script>';
        }
    }",
            $service_level_field,
            $extraoptions_field,
            join("\n",$checkbox_fields),
            $excludeclasses
        );

        $class_file = fopen( $file_path , 'w' );
        if( !fwrite( $class_file, $class_contents) ){
            WooShiptimize::log("can't write to path: $file_path, classfile: $class_file please check your file permissions ");
        }

        fclose( $class_file );
    }


    /**
     * Since we are forced to declare  shipping classes we will generate them for the carriers available in the shop admin's contract.
     *
     * @param String $file_path - the absolute  file_path where to save this class
     * @param String $class_name  - the class name
     * @param Object $carrier - object returned by the api representing this carrier
     * @param String $service_level_options - optional - the options to append to the config in the format 'service_id_0' => 'service_label_0', 'service_id_1' => 'service_label_1' ...
     */
    public function writeShippingClassFree ( $file_path, $class_name , $carrier) {
        global $shiptimize;

        $shiptimizeOptions = ShiptimizeOptions::getInstance();

        /**
         * Ids that are safe to forward to the user
         * These serviçes must be GLOBAL to the carrier and not depend on factors like country
         * There is nothing in the endpoint that tells us what rules apply
         * So if you add something here make sure you  know  what your are doing
         * and if necessary modify the write shipping class to be smart enough to handle it.
         *
         * sendinsured are insurances of type 0 -  all type 0 show up in the same dropdown and are  mutually exclusive
         * sendinsuredV are insurances of type 2 - these are checkboxes
         */
        $extra_option_fields_ids = $shiptimizeOptions->getAllowedExtraOptions();
        $checkbox_option_fields_ids  = $shiptimizeOptions->getCheckboxFieldIds();

        $service_level_options = '';

        $checkbox_fields =array(); // an array of strings, each one is a checkbox field
        $extraoptions_field = '';
        $extraoptions_values = '';
        $optionvalues = array();
        $HasPickup = self::is_carrier_pickup_able($carrier);

        if (isset($carrier->OptionList)) {
            $pickup0 = $shiptimize->translate('pickuppointbehavior0');
            $pickup1 = $shiptimize->translate('pickuppointbehavior1');
            $pickup2 = $shiptimize->translate('pickuppointbehavior2');

            if($HasPickup) {
                $extraoptions_field = "\n\n" . $this->get_option_string('select','shiptimize-pickupbehaviour','pickupbehaviour',0,"array('0'=> \"$pickup0\", '1' => \"$pickup1\", '2' =>\"$pickup2\")");
            }

            foreach ($carrier->OptionList as $option) {
                switch($option->Type) {
                    case 0: //extra options these all go in the same select
                        //Filter stuff out if it's on the list treat it as a field with options
                        if (in_array($option->Id, $extra_option_fields_ids)) {
                            $extraoptions_values .= ($extraoptions_values ? ',':'')."'$option->Id'=>'$option->Name'";

                            if (isset($option->OptionFields)) {
                                foreach ($option->OptionFields as $field) {
                                    $curroptionvalues = array(
                                        'name' => 'extraoptions' . $option->Id,
                                        'class' => 'shiptimize-extra-option-values',
                                        'values' => array()
                                    );
                                    if (isset($field->OptionValues) && is_array($field->OptionValues)) {
                                        foreach ($field->OptionValues as $optionValue) {
                                            array_push($curroptionvalues['values'], "\"$optionValue->Id\" =>\"$optionValue->Name\"");
                                        }
                                        array_push($optionvalues, $curroptionvalues);
                                    }
                                }
                            }
                        }
                        break;

                    case 1: //Service level -  we ALWAYS display items of this type
                        $service_level_options  = ' "" => "-" ';
                        if(isset($option->OptionValues )){
                            foreach ($option->OptionValues as $serviceLevel) {
                                $service_level_options .= " , '". $serviceLevel->Id ."'  => '$serviceLevel->Name'";
                            }
                        }
                        break;

                    default:
                        //checkbox type of fields type 2
                        //Filter stuff out if it's on the list
                        foreach ($checkbox_option_fields_ids as $key => $value) {
                            if ($key == $option->Id) {
                                $options = "array(0=>\$shiptimize->translate('No'),'$option->Id'=>\$shiptimize->translate('Yes'))";
                                array_push($checkbox_fields,$this->get_option_string('select','shiptimize-'.$value,$value,0,$options));

                            }
                        }
                        break;
                }
            }  /** /Foreach optionList **/

            if($extraoptions_values){
                $extraoptions_field .= "\n\n" . $this->get_option_string('select','shiptimize-extra-options','extraoptions',0,"array('0'=>'-',$extraoptions_values)");
                // Append any valid option values

                if (count($optionvalues)>1) {
                    foreach ($optionvalues as $option) {
                        $extraoptions_field .= "\n\n".$this->get_option_string('select',
                                $option['class'],
                                $option['name'],
                                0,
                                "array('0'=>'-'," . join(',',$option['values']) . ")");
                    }
                }
            }

        }

        // Only display the exclude classes if the advanced shipping plugin is active
        $excludeclasses = '';
        if ( is_plugin_active('woocommerce-advanced-shipping/woocommerce-advanced-shipping.php') ) {
            $excludeclasses = $this->get_option_string('multiplecheckboxes','shiptimize-excludeclasses','excludeclasses',0,"");
        }

        WooShiptimize::log( $carrier->Name . " ".(  isset($carrier->OptionList ) ? json_encode( $carrier->OptionList )  :'' ));

        $service_level_field = $service_level_options ? $this->get_option_string('select','shiptimize-service-level','service_level',0,"array($service_level_options)") : '';

        $class_contents = sprintf("<?php 
    /** 
     * Declares a shipping method for carrier {$carrier->Name} 
     */ 
    class {$class_name} extends WC_Shipping_Free_Shipping { 
        /**
         * Constructor.
         *
         * @param int \$instance_id Instance ID.
         */
        public function __construct( \$instance_id = 0){
          global \$shiptimize; 

          \$this->instance_id =  absint( \$instance_id );
          \$this->id = '" . $this->get_shipping_method_id( $carrier ) .  "_free';
          \$this->method_title = 'Shiptimize: {$carrier->Name} Free Shipping'; //name for admin 
          \$this->method_description = 'Shiptimize {$carrier->Name} Free Shipping'; // description for admin 
          \$this->has_pickup = " . ($HasPickup ? 'true' : 'false' ) . ";
      
          \$this->supports = array(
                'shipping-zones',
                'instance-settings',
                'instance-settings-modal',
          );

          \$this->init();

          // Default to returning the string passed by param 
          \$translate = function (\$str){ return \$str;}; 

          // how it's possible that someone declares this method before there's an instance of the plugin is a mistery to solve later 
          // possible they copied the code and did their own  rendition of the thing? 
          if(!\$shiptimize && class_exists('WooShiptimize')) {
            \$shiptimize = WooShiptimize::instance();
          }

          # If we have an instance of shiptimize, then use that translate function 
          if(\$shiptimize) {
            \$translate = function (\$str) { global \$shiptimize; return \$shiptimize->translate(\$str); };
          }

          /*Service Level*/
          %s
          
          /*Extraoptions */ 
          %s

          /** checkboxfields **/  
          %s 

          /** exclude classes **/
          %s

          add_action( 'woocommerce_update_options_shipping_' . \$this->id, array( \$this, 'process_admin_options' ) );
        } 

        public function validate_excludeclasses_field( \$key , \$value ) {
          if ( \$key === 'excludeclasses' ) {
            return empty(\$value) ? '' : implode( ',' , \$value );
          }
          return \$value;
        }
       
        /**
         * Initialize free shipping.
         */
        public function init() { 
          // Load the settings.
          \$this->init_form_fields();
          \$this->init_settings();

          // Define user set variables.
          \$this->title            = \$this->get_option( 'title' );
          \$this->min_amount       = \$this->get_option( 'min_amount', 0 );
          \$this->requires         = \$this->get_option( 'requires' );
          \$this->ignore_discounts = \$this->get_option( 'ignore_discounts' );

          // Actions.
          add_action( 'woocommerce_update_options_shipping_' . \$this->id, array( \$this, 'process_admin_options' ) );
        }

        public function get_admin_options_html()
        {
          if ( \$this->instance_id ) {
            \$settings_html = \$this->generate_settings_html( \$this->get_instance_form_fields(), false );
          } else {
            \$settings_html = \$this->generate_settings_html( \$this->get_form_fields(), false );
          }

          \$excludeclassesoptions = array();
          if ( is_plugin_active('woocommerce-advanced-shipping/woocommerce-advanced-shipping.php') ) { 
              \$shipping  = new \WC_Shipping(); 
              \$opt_exclude_classes = explode( ',', \$this->get_instance_option('excludeclasses') );               
              \$opt_exclude_classes = explode( ',', \$this->get_instance_option('excludeclasses') );
          
              foreach ( \$shipping->get_shipping_classes() as \$shipping_class ) {

                array_push( \$excludeclassesoptions, array( 
                  'id' => \$shipping_class->term_id,
                  'name' => \$shipping_class->name,
                  'selected' => in_array( \$shipping_class->term_id , \$opt_exclude_classes ) ? 'checked' : ''
                ));
              }
          }
          return '<table class=\"form-table\">' . \$settings_html . '</table><script>
          var optionsid = \"#woocommerce_' . \$this->id . '_extraoptions\";
          
          setTimeout( function(){
            console.log(\"Helloo from '.\$this->id.'\");

            jQuery(\".shiptimize-extra-option-values\").parent().parent().parent().hide();
            shiptimize_extraoption_values();

            jQuery(optionsid).change(shiptimize_extraoption_values); 
            setExcludeClasses();

          }, 0);

          function shiptimize_extraoption_values(){
              var selectedoption = jQuery(optionsid).val(); 
              jQuery(\".shiptimize-extra-option-values\").parent().parent().parent().hide();
              jQuery(\"#woocommerce_' . \$this->id . '_extraoptions\" + selectedoption).parent().parent().parent().show(); 
          }

          function setExcludeClasses() {
            var excludeoptions =' . json_encode(\$excludeclassesoptions) . ';
            var select = jQuery(\"#woocommerce_shipping_shiptimize_{$carrier->Id}_free_excludeclasses\"); 
            var content = select.parent();
            select.remove();  
            for ( var x=0; x< excludeoptions.length; ++x ) { 
              content.append(\'<span class=\"shiptimize-ib shiptimize-exclude-class\"> <input type=\"checkbox\" name=\"woocommerce_shipping_shiptimize_{$carrier->Id}_free_excludeclasses[]\" value=\"\' + excludeoptions[x].id + \'\" \' + excludeoptions[x].selected + \' /> \' + excludeoptions[x].name + \'</span>\');
            }
          }
          </script>';
        }
 
    }",
            $service_level_field,
            $extraoptions_field,
            join("\n",$checkbox_fields),
            $excludeclasses
        );

        $class_file = fopen( $file_path , 'w' );
        if ( !fwrite( $class_file, $class_contents) ) {
            error_log("can't write to path: $file_path, classfile: $class_file please check your file permissions ");
        }

        fclose( $class_file );
    }
    /**
     * If the client has the weightbaseshipping extension installed allow them
     * to use it with shiptmize Shipping Class
     */
    public function writeWeightShippingClass($file_path, $class_name, $carrier) {
        global $shiptimize;

        $shiptimizejs =  "var shiptimize_carrier=" . addslashes(json_encode($carrier)).";";

        if(isset($carrier->OptionList)){

            $shiptimizeOptions = ShiptimizeOptions::getInstance();
            $shiptimizejs .= "var shiptimize_extraoptions=" . addslashes(json_encode($shiptimizeOptions->getAllowedExtraOptions())).";";
            $shiptimizejs .= "var shiptimize_checkboxes=" . addslashes(json_encode($shiptimizeOptions->getCheckboxFieldIds())).";";
        }

        $pickup_behaviour_label = "\$pickup_behaviour_label = \$shiptimize->translate('pickupbehaviour')";

        $pickup0 = "\$pickup0 = \$translate('pickuppointbehavior0');";
        $pickup1 = "\$pickup1 = \$translate('pickuppointbehavior1');";
        $pickup2 = "\$pickup2 = \$translate('pickuppointbehavior2');";
        $extraoptions = "\$extraoptions = \$translate('extraoptions');";
        $servicelevel = "\$servicelevel = \$translate('service_level');";

        $shiptimizejs .= "shiptimize_labels = {
          'pickupbehaviour' : \\\"\$pickup_behaviour_label\\\",
          'pickup0' : \\\"\$pickup0\\\",
          'pickup1' : \\\"\$pickup1\\\",
          'pickup2' : \\\"\$pickup2\\\",
          'extraoptions': \\\"\$extraoptions\\\",
          'servicelevel':\\\"\$servicelevel\\\",
    };";

        $HasPickup = self::is_carrier_pickup_able($carrier);

        $class_contents = "<?php
    use Wbs\ShippingMethod; 

    /** 
     * Declares a shipping method for carrier {$carrier->Name} 
     */ 
    class {$class_name} extends ShippingMethod { 
        /**
         * Constructor.
         *
         * @param int \$instance_id Instance ID.
         */
        public function __construct( \$instance_id = 0){ 

          \$this->instance_id        = absint( \$instance_id );
          \$this->id                 = '" . $this->get_shipping_method_id( $carrier ) .  "_weight';
          \$this->plugin_id = 'wbs';
          \$this->title       =  '{$carrier->Name} for Weight Based Shipping'; //name for end user 
          \$this->method_title =  'Shiptimize: {$carrier->Name} for Weight Based Shipping'; //name for admin 
          \$this->method_description = 'Shiptimize {$carrier->Name}'; // description for admin 
          \$this->has_pickup = " . ($HasPickup ? 'true' : 'false' ) . ";
          
          \$this->supports = array( 
            'shipping-zones',
            'instance-settings',
          );

          \$this->init_settings();
        }         

        /** 
         * @override 
         * We don't want our methods confused with the global settings which has instance_id= '' 
         * Only called in wp-admin/
         */ 
        public function get_option_key()
        {
            if(!\$this->instance_id ){
              return ''; 
            }

            \$option_key =  join('_', array_filter(array(
                \$this->plugin_id,
                \$this->instance_id,
                'config',
            )));

            return \$option_key;
        }

        public function get_admin_options_html()
        {
          global \$shiptimize; 
          
          // Default to returning the string passed by param 
          \$translate = function (\$str){ return \$str;}; 

          // how it's possible that someone declares this method before there's an instance of the plugin is a mistery to solve later 
          // possible they copied the code and did their own  rendition of the thing? 
          if(!\$shiptimize && class_exists('WooShiptimize')) {
            \$shiptimize = WooShiptimize::instance();
          }

          # If we have an instance of shiptimize, then use that translate function 
          if(\$shiptimize) {
            \$translate = function (\$str) { global \$shiptimize; return \$shiptimize->translate(\$str); };
          }

            \$shiptimize_options = json_encode(get_option('wbs_'.\$this->instance_id.'_shiptimize'));
            $pickup_behaviour_label;
            $pickup0
            $pickup1
            $pickup2
            $extraoptions
            $servicelevel

            ob_start(); 
                echo \"<script>
                $shiptimizejs

                //previous options 
                var shiptimize_options = \$shiptimize_options;
                </script>\";
                /** @noinspection PhpIncludeInspection */
                include(Wbs\Plugin::instance()->meta->paths->tplFile);
            return ob_get_clean();
        }
    }";

        $class_file = fopen( $file_path , 'w' );
        if( !fwrite( $class_file, $class_contents)){
            error_log("can't write to $file_path, classfile: $class_file please check your file permissions ");
        }
        fclose( $class_file );
    }

    /**
     * Return a list of shipping methods present in this shop
     * remember to include checks for any plugin integration we support
     */
    static function get_shipping_methods() {
        $zones = WC_Shipping_Zones::get_zones();

        $methods = array();
        foreach ($zones as $zone) {
            $woomethods = $zone['shipping_methods'];
            foreach($woomethods as $id =>  $woomethod) {
                array_push($methods, array('id' => $woomethod->id . '_' . $woomethod->instance_id, 'title' => $zone['zone_name'] . ' > ' . $woomethod->method_title));
            }
        }
        return json_encode($methods);
    }
}