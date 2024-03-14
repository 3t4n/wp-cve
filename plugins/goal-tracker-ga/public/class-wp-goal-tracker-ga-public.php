<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.wpgoaltracker.com/
 * @since      1.0.0
 *
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/public
 * @author     yuvalo <support@wpgoaltracker.com>
 */
class Wp_Goal_Tracker_Ga_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    private  $gtga_events_queue = array(
        "pending" => array(
        "view_item"      => array(),
        "begin_checkout" => array(),
        "add_to_cart"    => array(),
        "view_cart"      => array(),
        "purchase"       => array(),
    ),
    ) ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name       The name of the plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function gtga_track_woocommerce_begin_checkout__primium_only()
    {
        // $cart = WC()->cart;
        // if (is_null(WC()->cart)) {
        //     WC()->frontend_includes();
        //     WC()->session = new WC_Session_Handler();
        //     WC()->session->init();
        //     WC()->customer = new WC_Customer(get_current_user_id(), true);
        //     WC()->cart = new WC_Cart();
        //     WC()->cart->get_cart();
        // }
        $cart = WC()->cart;
        $items = [];
        foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
            $product = $cart_item['data'];
            $items[] = [
                'item_id'     => ( $product->get_sku() ? $product->get_sku() : $product->get_id() ),
                'item_name'   => $product->get_name(),
                'affiliation' => 'Your Store Name',
                'price'       => $product->get_price(),
                'quantity'    => $cart_item['quantity'],
            ];
        }
        $applied_coupons = $cart->get_applied_coupons();
        $coupon_string = ( !empty($applied_coupons) ? implode( ", ", $applied_coupons ) : "" );
        $total_value = $cart->get_cart_contents_total();
        
        if ( count( $items ) > 0 ) {
            $items_json = json_encode( $items );
            $event_data = array(
                'currency' => get_woocommerce_currency(),
                'value'    => $total_value,
                'items'    => $items,
                'coupon'   => $coupon_string,
            );
            $ecommerce_settings = wp_goal_tracker_ga_get_options( "ecommerceTrackingSettings" );
            $woo_settings = $ecommerce_settings["wooCommerceSettings"];
            if ( isset( $woo_settings["beginCheckout"] ) && $woo_settings["beginCheckout"] ) {
                echo  "\n\t\t\t\t\t\t\t<script>\n\t\t\t\t\t\t\t\t\twindow.wpGoalTrackerGaEvents['pending']['begin_checkout'] = " . json_encode( $event_data ) . ";\n\t\t\t\t\t\t\t</script>\n\t\t\t\t\t\t\t" ;
            }
            // $this->gtga_events_queue[ "pending" ][ "begin_checkout" ] = $event_data;
            if ( isset( $woo_settings["addShippingInfo"] ) && $woo_settings["addShippingInfo"] || isset( $woo_settings["addPaymentInfo"] ) && $woo_settings["addPaymentInfo"] ) {
                echo  "\n\t\t\t\t\t\t\t\t\t<script>\n\t\t\t\t\t\t\t\t\tvar wpGoalTrackerWooData = {\n\t\t\t\t\t\t\t\t\t\t'items': {$items_json},\n\t\t\t\t\t\t\t\t\t\t'value': " . $total_value . ",\n\t\t\t\t\t\t\t\t\t\t'currency': '" . get_woocommerce_currency() . "',\n\t\t\t\t\t\t\t\t\t\t'coupon': '" . esc_js( $coupon_string ) . "'\n\t\t\t\t\t\t\t\t\t}\n\t\t\t\t\t\t\t\t\t</script>\n\t\t\t\t\t\t\t\t\t" ;
            }
        }
    
    }
    
    /**
     * Register the scripts when the footer loads.
     *
     * @since    1.0.15
     */
    public function localize_script_in_footer()
    {
        // Check if data exists and the script is enqueued
        if ( wp_script_is( $this->plugin_name, 'enqueued' ) ) {
        }
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Goal_Tracker_Ga_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Goal_Tracker_Ga_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        // wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-goal-tracker-ga-public.css', array(), $this->version, 'all');
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Goal_Tracker_Ga_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Goal_Tracker_Ga_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        // Check if the user choose to disable tracking for logged in admin users.
        if ( $this->should_skip_tracking_for_admins() ) {
            return;
        }
        // Vimeo API
        $gtga_dependencies = array( 'jquery' );
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/wp-goal-tracker-ga-public.js',
            $gtga_dependencies,
            $this->version,
            false
        );
        wp_localize_script( $this->plugin_name, 'wpGoalTrackerGaEvents', $this->gtga_events_queue );
        wp_localize_script( $this->plugin_name, 'wpGoalTrackerGa', $this->prepareSettings() );
    }
    
    /**
     * Add the GA4 API code snippet to the page
     *
     */
    public function wp_goal_tracker_ga_add_ga4_code_snippet()
    {
        if ( $this->should_skip_tracking_for_admins() ) {
            return;
        }
        $options = wp_goal_tracker_ga_get_options();
        $general_settings = $options['generalSettings'];
        if ( !isset( $general_settings['measurementID'] ) ) {
            return;
        }
        $ga_config_options = array();
        if ( $general_settings['gaDebug'] ) {
            $ga_config_options['debug_mode'] = true;
        }
        if ( $general_settings['disablePageView'] ) {
            $ga_config_options['send_page_view'] = false;
        }
        
        if ( gtg_fs()->is__premium_only() && $general_settings['trackUsers'] && is_user_logged_in() ) {
            // Generating a unique hash for the user based on user id and user name
            $user_token = get_current_user_id() . "_" . wp_get_current_user()->user_login;
            $hashed_token = hash( 'sha256', $user_token );
            $ga_config_options['user_id'] = $hashed_token;
        }
        
        $gtag_config = ( sizeof( $ga_config_options ) > 0 ? "," . wp_json_encode( $ga_config_options ) : "" );
        $trackerCode = '<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_html( $general_settings['measurementID'] ) . '"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag("js", new Date());

			gtag("config", "' . esc_html( $general_settings['measurementID'] ) . '"' . $gtag_config . ');
		</script>';
        echo  $trackerCode ;
    }
    
    public function prepareSettings()
    {
        global  $wp_query ;
        $options = wp_goal_tracker_ga_get_options();
        $page_title = ( !empty($wp_query->post->post_title) ? $wp_query->post->post_title : '' );
        $general_settings = $options['generalSettings'];
        $settings = array(
            'version'         => $this->version,
            'is_front_page'   => is_front_page(),
            'trackLinks'      => $general_settings['trackLinks'],
            'trackEmailLinks' => $general_settings['trackEmailLinks'],
            'click'           => $options['click'],
            'visibility'      => $options['visibility'],
            'pageTitle'       => $page_title,
        );
        return $settings;
    }
    
    public function addPublicPluginSettings()
    {
        $options = wp_goal_tracker_ga_get_options();
        $public_options = array();
    }
    
    private function should_skip_tracking_for_admins()
    {
        
        if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
            $options = wp_goal_tracker_ga_get_options();
            $general_settings = $options['generalSettings'];
            if ( key_exists( 'disableTrackingForAdmins', $general_settings ) && $general_settings['disableTrackingForAdmins'] == true ) {
                return true;
            }
        }
        
        return false;
    }

}