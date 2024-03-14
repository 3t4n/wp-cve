<?php
/**
 * Woo Shiptimize
 * specific woo stuff should be bootstraped here
 *
 * @package Shiptimize
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

include_once (SHIPTIMIZE_PLUGIN_PATH.'/includes/core/class-shiptimize.php');
include_once (SHIPTIMIZE_PLUGIN_PATH.'/includes/core/class-shiptimize-api-v3.php');
include_once (SHIPTIMIZE_PLUGIN_PATH.'/includes/class-woo-shiptimize-order.php');
include_once (SHIPTIMIZE_PLUGIN_PATH.'/includes/core/ShiptimizeOptions.php');
include_once (SHIPTIMIZE_PLUGIN_PATH.'includes/admin/class-shiptimize-shipping.php');
include_once (SHIPTIMIZE_PLUGIN_PATH.'includes/plugins/class-shiptimize-connector.php');
include_once (SHIPTIMIZE_PLUGIN_PATH.'includes/plugins/class-shiptimize-marketplace.php');

/**
 * Main shiptimize class
 * @class WooShiptimize
 */
class WooShiptimize extends ShiptimizeV3 {


    /**
     * Shiptimize version
     *
     * @var string
     */
    public static $version = SHIPTIMIZE_VERSION;

    public static $provinces = array(
        'ES' => array(
            'CN' => 'Canarias'
        ),
        'PT' => array(
            'IL' => 'Ilhas',
            'CT' => 'Continente'
        )
    );
    /**
     * Data version - the datamodel version
     */
    public static $data_version = '1';

    /**
     * The single instance
     *
     * @var Shiptimize
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * is this one of our test  machines
     **/
    public static $is_dev = false;

    /**
     * The app class for woo
     *
     * @var Number
     */
    public static $SHIPTIMIZE_WOOCOMMERCE = SHIPTIMIZE_APP_KEY;

    /**
     * We use this to know if the user changed the keys
     */
    public static $OPTION_SHIPTIMIZE_CACHE_KEY = 'shiptimize_cache_key';
    public static $OPTION_SHIPTIMIZE_PUBLIC_KEY = 'shiptimize_public_key';
    public static $OPTION_SHIPTIMIZE_PRIVATE_KEY = 'shiptimize_private_key';
    public static $OPTION_PRIVATE_KEY = 'shiptimize_private_key';
    public static $OPTION_CALLBACK_URL = 'shiptimize_callbackurl';
    public static $OPTION_SHIPTIMIZE_TOKEN = 'shiptimize_token';
    public static $OPTION_SHIPTIMIZE_TOKEN_EXPIRES = 'shiptimize_token_expires';
    public static $OPTION_SHIPTIMIZE_USEWPAPI = 'shiptimize_usewpapi';

    protected $known_issues  = [];

    private function __construct(){
        global $wpdb;

        $this->db_prefix = $wpdb->prefix;
    }

    /**
     * Singleton pattern ensures only one shiptimize instance
     * @since 1.0.0
     * @see Shiptimize()
     * @return Shiptimize - Main instance.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$is_dev = defined('SHIPTIMIZE_DEV');
            self::$_instance = new self();
            self::shiptimize_check_upgrade();
        }
        return self::$_instance;
    }

    public function bootstrap() {

        register_activation_hook(SHIPTIMIZE_PLUGIN_FILE, array($this, 'activate'));
        register_deactivation_hook(SHIPTIMIZE_PLUGIN_FILE, array($this, 'deactivate'));
        ShiptimizeShipping::get_instance();

        $active_plugins = (array) get_option( 'active_plugins', array() );
        if ( is_multisite() ) {
            $network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
            $active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
        }

        if ( !in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
            add_action( 'admin_notices', array( $this, 'notice_wc_required' ) );
            return;
        }

        if( ! $this->is_options_valid() && !shiptimize_is_marketplace() ) {
            add_action( 'admin_notices', array( $this, 'notice_incomplete_options' ) );
        }

        $this->admin_includes();
        $this->actions();
        $this->filters();
    }

    /**
     * Detect and warn the user about known incompatibilities
     */
    public function known_issues() {
        global $shiptimize;

    }

    /**
     * Check if all the mandatory options are set
     *
     * @return bool - true if all required options are set
     * @override
     */
    public function is_options_valid(){
        $username = get_option('shiptimize_public_key');
        $password = get_option('shiptimize_private_key');

        return $username && $password;
    }

    /**
     * Declare the routes on activation, some plugins will rely on the flush rules to do stuff
     * We can't load it everytime the plugin loads
     * @param $network_wide if it's a network wordpress install denotes install for the entire network
     */
    public static function activate($network_wide) {
        global $wpdb;

        $database_version = get_option('shiptimize_db_version');
        self::log("Activating " . (is_multisite() ? 'multisite' : 'regular') . " network_wide:  $network_wide current_db " . $database_version . "plugindb " . self::$database_version);

        if($database_version < self::$database_version) {

            if ( is_multisite() && $network_wide ) {

                foreach (get_sites( ['fields'=>'ids'] ) as $blog_id) {
                    switch_to_blog($blog_id);

                    self::log("Activating site: " . site_url());

                    //This class is a singleton foreach site we need to update this manually if iterating several sites
                    $shiptimize =  WooShiptimize::instance();
                    $shiptimize->db_prefix = $wpdb->prefix;
                    WooShiptimize::_active_site($network_wide);
                    restore_current_blog();
                }

            } else {
                WooShiptimize::_active_site($network_wide);
            }

            update_option( 'shiptimize_db_version', self::$database_version );
        }
    }

    /**
     * Activate a single website in the network
     */
    private static function _active_site($network_wide) {

        self::callback_url();

        // Plugin is not run when we get here..
        $shiptimize = WooShiptimize::instance();
        $shiptimize->create_shiptimize_data_model('bigint(20) unsigned ');
        ShiptimizeMarketplace::activate($network_wide);
    }

    /**
     * Generic plugin actions
     *
     * @return void
     */
    public function actions( ) {
        add_action( 'plugins_loaded', array( $this,'plugins_loaded' ) );

        add_action( 'upgrader_process_complete', array( $this,'upgrade_function' ) ,10, 2);

        add_action('admin_enqueue_scripts', array( $this, 'shiptimize_admin_styles' ) );
        add_action('admin_enqueue_scripts', array( $this, 'shiptimize_admin_scripts' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'shiptimize_scripts' ) );
        add_action( 'admin_init', array( $this, 'known_issues' ) );
        add_action( 'admin_notices', array( $this, 'notice_list' ) );

        add_action('parse_request', array( $this,'parse_request' ) );
        add_filter('query_vars', array( $this,'custom_query_vars' ) );

        add_action( 'rest_api_init', array( $this,'register_api_routes' ) );
        add_action('init', 'WooShiptimize::callback_url');

        //https://docs.woocommerce.com/wc-apidocs/source-class-WC_Order.html#366
        $autoexport_status = get_option('shiptimize_autoexport');
        if( strlen($autoexport_status) > 0 ){
            $status_name  =  str_replace('wc-', '', $autoexport_status);
            $action_name = 'woocommerce_order_status_' . $status_name;

            add_action( $action_name , array($this, 'auto_export'));
        }
    }

    public function plugins_loaded() {
        $this->check_marketplaces();
        $this->load_plugin_textdomain();
    }

    public function check_marketplaces() {
        global $shiptimize_dokan,$shiptimize_wcfm;

        $active_plugins =  apply_filters('active_plugins', get_option( 'active_plugins' ));
        if ( in_array( 'dokan-lite/dokan.php', $active_plugins )) {
            require_once(SHIPTIMIZE_PLUGIN_PATH . 'includes/plugins/class-shiptimize-dokan.php');
        }

        if (in_array('wc-multivendor-marketplace/wc-multivendor-marketplace.php', $active_plugins)) {
            require_once(SHIPTIMIZE_PLUGIN_PATH . 'includes/plugins/class-shiptimize-wcfm.php');
        }
    }

    public function upgrade_function( $upgrader_object, $options ) {
        global $shiptimize;

        $current_plugin_path_name = plugin_basename( __FILE__ );

        if ($options['action'] == 'update' && $options['type'] == 'plugin' ){
            if(isset($options['plugins'])){
                foreach($options['plugins'] as $each_plugin){
                    if ($each_plugin==$current_plugin_path_name){
                        self::shiptimize_check_upgrade();
                    }
                }
            }
        }
    }

    public function load_plugin_textdomain() {
        global $shiptimize;

        load_plugin_textdomain( 'shiptimize-for-woocommerce', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

    /**
     * This hook is only called when a corresponding woocommerce_order_status_ is matched
     */
    public function auto_export($order_id) {
        global $wpdb;

        self::log("Auto export order_id: $order_id ");

        $exported = $wpdb->get_results("select status from {$wpdb->prefix}shiptimize where id=$order_id ");

        self::log("Exported " . var_export($exported,true));

        if (!$exported || ($exported[0]->status != ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY) ) {
            WooShiptimizeOrder::export(array($order_id));
        }
        else if( self::$is_dev) {
            self::log("order $order_id was already exported status is $exported[0]->status:  ignore");
        }
    }

    /**
     * Append stuff
     */
    public function filters(){
        add_filter('woocommerce_order_details_after_order_table_items', array($this,'print_pickup_label'));

        /**
         * Add spanish special zones like canary islands
         */
        add_filter( 'woocommerce_states', array($this, 'add_states') );

        add_filter( 'woocommerce_package_rates', array( $this, 'rates_filter'), 10, 2 );
    }

    /**
     * Checks exclude classes if defined to determine if the rate is valid
     **/
    public function is_rate_valid( $r , $package ) {
        $options = get_option( 'woocommerce_' . $r->method_id . '_' . $r->instance_id . '_settings' );

        if( isset( $options['excludeclasses'] ) && $options['excludeclasses'] ) {
            WooShiptimize::log( $r->label );
            $opt_exclude_classes = explode( ',', $options['excludeclasses'] );
            $contains_classes_to_exclude = false;

            // Check if the package contains products that have at least one of the selected classes
            foreach ( $package['contents'] as $key => $item ) {
                foreach ( $opt_exclude_classes  as $c2exclude ) {
                    $terms = get_the_terms( $item['product_id'], 'product_shipping_class' );
                    if ( $terms ) {
                        foreach( $terms as $term ) {
                            $contains_classes_to_exclude |=  $term->term_id == $c2exclude ;
                        }
                    }
                }
            }

            WooShiptimize::log("contains class " . ( $contains_classes_to_exclude ? 1 : 0 ));
            return !$contains_classes_to_exclude;
        }

        return true;
    }

    /**
     * Allow users to choose, if free shipping, then hide anything not free
     * Also check if exclude classes is set inside the shipping method
     **/
    public function rates_filter ( $rates, $package ) {

        // Check if hide not free enabled

        $free_not_local = false;
        if ( get_option('shiptimize_hide_not_free') ) {
            $free = array();
            foreach ( $rates as $id => $r) {
                if ( $r->cost == 0 && $this->is_rate_valid( $r, $package ) ) {
                    $free_not_local |= ($r->method_id != 'local_pickup');
                    $free[$id] = $r;
                }
            }

            if ( !empty( $free ) && $free_not_local) {
                $rates = $free;
            }
        }

        // Check for exclude classes
        $newrates = array();
        foreach ( $rates as $id => $r) {
            if( $this->is_rate_valid($r, $package) ) {
                $newrates[$id] = $r ;
            }
        }

        return $newrates;
    }

    /**
     * If user choose to add provinces then display them
     */
    public function add_states ($states) {
        if( !get_option('shiptimize_provinces',false)){
            return $states;
        }

        foreach ( WooShiptimize::$provinces as $country => $values){
            foreach ($values as $code => $name) {
                $states[$country][$code] = $name;
            }
        }
        return $states;
    }


    public function parse_request( $wp ='' ) {

        if( !empty( $wp->query_vars['shiptimize_update'] ) ) {
            $this->api_update();
        }

        if( !empty( $wp->query_vars['shiptimize_create_account'] ) ) {
            $this->create_account();
        }

        if( !empty($wp->query_vars['shiptimize_request_account'] ) && shiptimize_is_marketplace() ) {
            ShiptimizeMarketplace::instance()->request_account();
        }

    }

    /**
     * when the api sends an update
     */
    public function api_update($wp=''){
        header("Content-Type:application/json");
        //Receive the RAW post data via the php://input IO stream.
        $content = file_get_contents("php://input");
        self::log(" API_UPDATE ".var_export($content,true));

        if(!trim($content)){
            die(json_encode((object)array("Error"=> "No content")));
        }

        $data = json_decode($content);

        // If it's a marketplace doing custom stuff remember to die at the end
        do_action('shiptimize_api_update', $data);

        $url = self::get_callback_url();

        //Grant we are using the marketplace api obj
        $api = self::get_api();

        if( !$api->validate_update_request($data->Status,$data->TrackingId,$url,$data->Hash) ){
            self::log("RESTAURE!! API_UPDATE INVALID SIGNATURE IGNORING ");
            die(json_encode((object)array("Error"=> "Invalid Signature")));
        }


        if (isset($data->Action) && $data->Action == 'getshippingmethods') {
            echo ShiptimizeShipping::get_shipping_methods();
            die();
        }

        $order = new WooShiptimizeOrder($data->ShopItemId);
        if( $data->Status ){
            $order->set_status_from_api($data->Status);
        }

        if( $data->TrackingId ){
            $order->set_tracking_id($data->TrackingId, $data->TrackingUrl);
        }

        die("{\"msg\":\"update done\"}");
    }

    public function register_api_routes () {
        // APP Sends updates
        register_rest_route( 'shiptimize/v1', '/update', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'api_update'),
            'permission_callback' => '__return_true',
        ) );

        // APP fecthes shipping method list
        register_rest_route( 'shiptimize/v1', '/update', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array($this, 'api_update'),
            'permission_callback' => '__return_true',
        ) );
    }

    /**
     * Define routes
     * - to receive calls from the api
     */
    public static function callback_url(){
        global $wp_rewrite;


        if(!$wp_rewrite || get_option(self::$OPTION_SHIPTIMIZE_USEWPAPI)){
            return;
        }


        $rules = get_option( 'rewrite_rules' );
        $allrulesset = isset( $rules['^shiptimize-callback/?'] ) && isset( $rules['^shiptimize-create-account'] ) && isset( $rules['^shiptimize-request-account'] );

        if ( !$rules ||  $allrulesset) {
            return;
        }

        WooShiptimize::log("Add route for shiptimize callback ");
        add_rewrite_rule(
            '^shiptimize-callback/?',
            'index.php?shiptimize_update=1',
            'top'
        );

        $wp_rewrite->flush_rules( true );
    }

    public function custom_query_vars($vars){
        $vars[] = 'shiptimize_update';
        $vars[] = 'shiptimize_create_account';
        $vars[] = 'shiptimize_request_account';
        return $vars;
    }

    /**
     * Enqueue frontend scripts && styles
     */
    public function shiptimize_scripts( ) {
        if(get_option('shiptimize_pickupdisable') && !shiptimize_is_marketplace()){
            return;
        }

        wp_register_script('shiptimize_script' , SHIPTIMIZE_PLUGIN_URL.'assets/js/shiptimize.js', array ( 'jquery' ),'2.2' );
        wp_enqueue_script( 'shiptimize_script');

        wp_register_style( 'shiptimize_style', SHIPTIMIZE_PLUGIN_URL.'assets/css/shiptimize.css' );
        wp_register_style( 'checkout_style', SHIPTIMIZE_PLUGIN_URL.'assets/css/checkout.css' );
        wp_enqueue_style( 'shiptimize_style' );
        wp_enqueue_style( 'checkout_style' );

    }


    /**
     * Enqueue admin styles
     */
    public function shiptimize_admin_styles( ) {
        wp_register_style( 'shiptimize_admin_styles', SHIPTIMIZE_PLUGIN_URL.'assets/css/shiptimize-admin.css', array(), '1.1');
        wp_enqueue_style( 'shiptimize_admin_styles' );
    }

    /**
     * Enqueue admin scripts
     */
    public function shiptimize_admin_scripts( ) {
        wp_register_script('shiptimize_admin_script' , SHIPTIMIZE_PLUGIN_URL.'assets/js/shiptimize-admin.js', array ( 'jquery' ), '1.0.3' );
        wp_enqueue_script( 'shiptimize_admin_script');
    }

    /**
     *
     * @return void
     */
    public static function deactivate() {
    }

    /**
     *
     */
    public static function uninstall() {

    }

    /**
     * Only clean the data if people explicitly want to clear it, for it may hinder information preservation
     * if the user wants to say: re-install the plugin.
     */
    public static function clear_shiptimize_data(){
        $shiptimize = new WooShiptimize();
        $shiptimize->drop_shiptimize_data_model();
    }


    public function welcome() {
        if (!get_option('shiptimize_version')) {
            add_option( 'shiptimize_version', WooShiptimize::$version );
            wp_safe_redirect();
        }
    }

    /**
     * bootstrap admin
     */
    public function admin_includes() {
        if( is_admin() ){
            include_once ( SHIPTIMIZE_PLUGIN_PATH . 'includes/admin/class-shiptimize-order-ui.php' );
            include_once ( SHIPTIMIZE_PLUGIN_PATH . 'includes/admin/class-shiptimize-options-ui-default.php' );
        }
    }

    /**
     *  Display a list of notices like known incompatibilities
     */
    public function notice_list(){
        if(! count($this->known_issues) ){
            return;
        }
        ?>
        <div class='notice notice-warning'>
            <?php
            foreach($this->known_issues as $notice){
                echo  "<p>$notice</p>";
            }
            ?>
        </div>
        <?php
    }

    /**
     * Notify the user that they should set credentials
     *
     */
    public function notice_incomplete_options ( ) {
        ?>
        <div class="notice notice-error">
            <p>
                <?php echo $this->translate('To use Shiptimize you must ');?>
                <a href="<?php echo admin_url('options-general.php?page=shiptimize-settings');?>"><?php echo $this->translate('set your credentials')?></a>
            </p>
        </div>
        <?php
    }

    /**
     * if wc is not installed notify user
     */
    public function notice_wc_required() {
        ?>
        <div class="error">
            <p><?php echo $this->translate( 'Shiptimize requires WooCommerce to be installed and activated!' ); ?></p>
        </div>
        <?php
    }

    /**
     * Executed after order details
     * @param WC_Order
     */
    public function print_pickup_label( $order ){
        global $shiptimize;

        $order = new WooShiptimizeOrder($order->get_id());
        $wc_order = wc_get_order($order->get_id()); // returns WC_Order object.

        $meta = $order->get_order_meta();

        if($meta && $meta->pickup_id){
            echo '<tr><td>' . $shiptimize->translate("Pickup Point").'</td><td>'.$meta->pickup_label.'</td></tr>';
        }

        if($meta && $meta->tracking_id){
            ##Reseno proveriti
            $trackingurl = $wc_order->get_meta('shiptimize_trackingurl', true);
            echo '<tr><td>' .  "Tracking" . '</td><td>' . ($trackingurl ? "<a href='$trackingurl' target='_blank'>" : "") . $meta->tracking_id . ($trackingurl ? '</a>' : '') . '</td></tr>';
        }
    }

    /**
     * Everytime someone requests the api... make sure we have a valid token
     * If is a marketplace and the user requesting the api is a vendor
     * Return the marketplace api instance
     *
     * @param $form_refresh boolean - force refresh always get a new instance
     * - relevant for network activations where we will be iterating through several sites
     * @override
     */
    public static function get_api( $force_refresh = false ){
        if( self::$api != null && !$force_refresh) {
            return self::$api;
        }

        $app_key = self::$is_dev ? SHIPTIMIZE_DEV_APP_KEY : SHIPTIMIZE_APP_KEY;

        if ( shiptimize_is_marketplace() ) {
            $app_key =  ShiptimizeMarketplace::instance()->get_app_key();

            if( !current_user_can('administrator') ){
                self::$api = ShiptimizeMarketplace::instance()->get_api();
                return self::$api;
            }
        }

        self::$api = ShiptimizeApiV3::instance(
            get_option( self::$OPTION_SHIPTIMIZE_PUBLIC_KEY),
            get_option( self::$OPTION_SHIPTIMIZE_PRIVATE_KEY),
            $app_key,
            get_option( 'shiptimize_test' ),
            get_option( 'shiptimize_token' ),
            get_option('shiptimize_token_expires') );

        return self::$api;
    }

    /**
     * string return the callback_url used by the api to push updates to the plugin
     */
    public static function get_callback_url() {
        $forceapi = get_option(self::$OPTION_SHIPTIMIZE_USEWPAPI);
        $callback_url = get_option('shiptimize_callbackurl');

        if( ( stripos($callback_url,'wp-json') > 0 ) && $forceapi ) {
            WooShiptimize::log("Callbackurl stored callback $callback_url");
            return $callback_url;
        }

        $homeurl = get_home_url();
        # remove trailing /
        $homeurl = preg_replace("/\/$/", '', $homeurl);

        // the api is active, there isn't a token and usewpapi was not defined yet
        $defaultapi = WooShiptimize::is_api_active() && (get_option( self::$OPTION_SHIPTIMIZE_USEWPAPI ) == '') && !get_option( self::$OPTION_SHIPTIMIZE_TOKEN );

        if ( $defaultapi || $forceapi) {
            $callbackurl = $homeurl . '/wp-json/shiptimize/v1/update';
        }
        else {
            $callbackurl = $homeurl . '/shiptimize-callback';
        }

        WooShiptimize::log("Callbackurl $callbackurl defaultapi [$defaultapi]");
        update_option('shiptimize_callbackurl', $callbackurl);

        return $callbackurl;
    }

    /**
     * Load the carriers
     *
     */
    protected static function refresh_carriers() {
        if ( self::$api->get_token_string() && self::$api->is_token_valid()  ) {
            $response  = self::$api->get_carriers();

            $carriers = !isset($response->Error) ? $response : null;
            update_option('shiptimize_carriers', json_encode($carriers));

            // because there is only one of each and we don't want to polute the db by repeating it at nausium
            $serviceLevelIds = array();
            if($carriers){
                foreach ( $carriers as $c ){
                    if( isset($c->OptionList)){
                        foreach($c->OptionList as $option){
                            if($option->Type == 1){
                                $serviceLevelIds[$c->Id] = $option->Id;
                            }
                        }
                    }
                }
            }

            update_option('shiptimize_servicelevelids', $serviceLevelIds);

            if(class_exists('ShiptimizeShipping')){
                self::log("clear_carrier_classes");
                ShiptimizeShipping::clear_carrier_classes();
            }
            else {
                $message  = "Class ShiptimizeShipping not found; can't update the carrier classes. File should be at " . SHIPTIMIZE_PLUGIN_PATH . 'includes/admin/class-shiptimize-shipping.php. Does the file exist? ' . (file_exists(SHIPTIMIZE_PLUGIN_PATH . 'includes/admin/class-shiptimize-shipping.php') ? 'Yes': 'No');
                self::log($message);
            }

            return $carriers;
        }

        return null;
    }

    /**
     * Get the pickup points
     * If the token was invalidated try to get it and refresh the carriers
     * @param mixed $address
     * @param int $shipping_method_id
     *
     */
    public static function get_pickup_locations($address, $shipping_method_id){
        $api = self::get_api();
        $pickup_points  = $api->get_pickup_locations( $address, $shipping_method_id);

        if( isset($pickup_points->Error) && $pickup_points->Error->Id == 401 ){
            WooShiptimize::refresh_token(); // Try to get a new token once!
            $pickup_points  = $api->get_pickup_locations( $address, $shipping_method_id);
        }

        return $pickup_points;
    }

    /**
     * Request a new token
     * Since changes to the carrier options in the app will invalidate the token we must also refresh the carriers
     * Every time we refresh the token
     */
    public static function refresh_token(){
        global $woocommerce;

        self::log("Refresh Token");

        self::get_api();

        $tokenresp =  self::$api->get_token(self::get_callback_url(), $woocommerce->version, WooShiptimize::$version);
        $token = '';
        $tokenexpires = '';

        if( isset($tokenresp->Key) ) {

            $token = $tokenresp->Key;
            $tokenexpires = $tokenresp->Expire;

            if ( get_option( self::$OPTION_SHIPTIMIZE_CACHE_KEY ) != self::$api->get_public_key() ) {
                update_option( self::$OPTION_SHIPTIMIZE_CACHE_KEY, self::$api->get_public_key() );
            }

            self::refresh_carriers();
        }

        WooShiptimize::log("Refresh Token with token=$token, token_expires=$tokenexpires");
        update_option(  self::$OPTION_SHIPTIMIZE_TOKEN,$token );
        update_option(  self::$OPTION_SHIPTIMIZE_TOKEN_EXPIRES, $tokenexpires );

        return $token;
    }

    /**
     * @override
     */
    public function executeSQL( $sql ) {
        global $wpdb;

        self::log($sql);
        return $wpdb->query($sql);
    }

    /**
     * @override
     */
    public function sqlSelect( $sql ) {
        global $wpdb;

        self::log($sql);
        return $wpdb->get_results($sql);
    }


    /**
     * Append a message to the log file, used in dev only
     *
     * @param string msg
     * @param bool $force - if true print the message regardless of debug mode
     */
    public static function log($msg, $force = false)
    {

        if (!$force && !self::$is_dev) {
            return;
        }

        if (defined('SHIPTIMIZE_LOG')) {
            $f = fopen(SHIPTIMIZE_LOG,'a');
            fwrite($f,"\n" . date("Y-m-d H:i:s") . "\t" . $msg);
            fclose($f);
        }
        else if(self::$is_dev) {
            error_log("\n" . date("Y-m-d H:i:s") . "\t" . $msg);//, 3, SHIPTIMIZE_PLUGIN_PATH . '/shiptimize.log');
        }
        else {
            error_log(date("Y-m-d H:i:s") . '\t' . $msg);
        }
    }

    /**
     * @return an iso2 string
     */
    public function get_lang(){
        return $this->get_ISO2_from_localisation(get_locale());
    }

    /**
     * Wordpress picks up our __() calls as strings meant to be translated via wordpress which is incorrect
     * so we should use a function with another name for that
     */
    public function translate($origin){
        return $this->__($origin);
    }

    /***
     * Try to determine programatically if the API is active
     */
    public static function is_api_active() {
        set_error_handler(function() { /* ignore errors */ });

        $checkapiurl = get_home_url() . '/wp-json';
        $contentvalid = true;

        try {
            $content = file_get_contents($checkapiurl);
            $json = json_decode($content);
            $contentvalid = isset($json->name);
        }
        catch (Exception $e) {
            $contentvalid = false;
            self::log("Exception ocurred trying to get the api url " . $e->getMessage());
        }

        restore_error_handler();
        self::log("==== is_api_active " . ($contentvalid ? 'Yes' : 'No'));
        return $contentvalid;
    }

    /**
     * Check if we need to run any upgrade functions
     * All elements in this function should be idempotent
     **/
    static function shiptimize_check_upgrade () {
        global $wpdb;

        $current_version = get_option('shiptimize_app_version');
        $current_version = floatval($current_version);

        if($current_version >= floatval(SHIPTIMIZE_VERSION) ) {
            return;
        }

        WooShiptimize::log("Shiptimize Upgrade from $current_version ",1);

        // Label creation table changes introduced in v3.1.34
        $ordercolumns = $wpdb->get_results("show columns from {$wpdb->prefix}shiptimize where field='labelurl'");

        if(!isset($ordercolumns[0]->Field)) {
            WooShiptimize::log("Updating shiptimize datamodel to include label creation ");
            $wpdb->query("alter table " . $wpdb->prefix . "shiptimize  add column labelurl varchar(255) null");
        }

        if ( !get_option( WooShiptimize::$OPTION_SHIPTIMIZE_CACHE_KEY ) && get_option( WooShiptimize::$OPTION_SHIPTIMIZE_PUBLIC_KEY ) ) {
            update_option( WooShiptimize::$OPTION_SHIPTIMIZE_CACHE_KEY, get_option(WooShiptimize::$OPTION_SHIPTIMIZE_PUBLIC_KEY) );
            update_option( WooShiptimize::$OPTION_CALLBACK_URL , WooShiptimize::get_callback_url());
        }

        update_option('shiptimize_app_version',SHIPTIMIZE_VERSION);
    }
}