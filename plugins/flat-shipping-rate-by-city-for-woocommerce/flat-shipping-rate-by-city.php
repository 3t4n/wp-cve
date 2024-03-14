<?php
/**
 * Plugin Name: Shipping Rates by City for WooCommerce
 * Plugin URI: https://logiceverest.com
 * Description: Custom Shipping Method for WooCommerce
 * Version: 1.0.3
 * Author: LogicEverest
 * Author URI: https://logiceverest.com
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /lang
 * Text Domain: wccfee
 */
 
if ( ! defined( 'WPINC' ) ) die;

/** @class Wc City Fee */
class  WccFeeFlatShippingCity {
    /**
     * Wc City Fee version.
     * @var string
     */
    public $version = '1.0.3';

    /**
     * Stores notices.
     * @var array
     */
    private static $notices = [];

    /**
     * Logger context.
     * @var array
     */
    public $context = ['source' => 'wccfee'];

    /** The single instance of the class. */
    protected static $_instance = null;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Wc City Fee Constructor.
     */
    private function __construct()
    {
        $this->defineConstants();
        $this->init_hooks();
        $this->session();
    }

    private function init_hooks()
	{
		/**
         * Activation/Deactivation
         */
        register_activation_hook(WCCFEE_PLUGIN_FILE, [$this, 'activation']);
		register_deactivation_hook(WCCFEE_PLUGIN_FILE, [$this, 'deactivation']);

		/**
         * Enqueue Scripts
         */
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
		

        /**
         * Check if WooCommerce is active
         */        
         if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

        /**
         * Shipping method init
         */
        add_action( 'woocommerce_shipping_init', [$this, 'wccfee_shipping_method'] );
        add_filter( 'woocommerce_shipping_methods', [$this, 'add_wccfee_shipping_method'] );

        // Change text box to select and set cities options
        add_filter( 'woocommerce_checkout_fields', array( $this, 'city_field_options' ) );

        // add script to footer to update checkout on city select
        add_filter( 'wp_footer', array( $this, 'city_wp_footer' ) );

        // add settings link to plugin list
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [$this, 'plugin_settings_link']);


        /**
         * order validation
         */
        // add_action( 'woocommerce_review_order_before_cart_contents', [$this, 'wccfee_validate_order'] );
        // add_action( 'woocommerce_after_checkout_validation', [$this, 'wccfee_validate_order'] );

        }
		
	}

	public function session()
    {
        if ( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }
    }

    public function activation()
    {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . "wccfee_cities";
        $query = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` int(11) AUTO_INCREMENT,
            `city_name` VARCHAR(255) NOT NULL,
            `cost` VARCHAR(25) NOT NULL,
            `status` VARCHAR(25) NULL,
            `create_date` DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) AUTO_INCREMENT=1001 $charset_collate;";
        dbDelta( $query );
    }

    public function deactivation() 
    {
		// deactivatation code
    }

    /**
     * Define Wc City Fee Constants.
     */
    private function defineConstants()
    {
		
        $this->define('WCCFEE_PLUGIN_FILE', __FILE__);
		$this->define('WCCFEE_VERSION', $this->version);
		$this->define('WCCFEE', 'wccfee');
		
    }

    /**
     * Define constant if not already set.
     *
     * @param string      $name  Constant name.
     * @param string|bool $value Constant value.
     */
    private function define( $name, $value )
    {
        if (!defined($name)) {
            define($name, $value);
        }
	}


	/**
	 * Enquene Scripts
	 */
	public function enqueueScripts()
    {
        wp_enqueue_script('jquery');
        // wp_enqueue_script(WCCFEE, plugins_url('/assets/wccfee.js', WCCFEE_PLUGIN_FILE), ['jquery'], WCCFEE_VERSION);
	}

	/**
	 * Enquene Admin Scripts
	 */
	public function enqueueAdminScripts()
	{
		wp_enqueue_script('jquery');
        wp_enqueue_script(WCCFEE, plugins_url('/assets/wccfee-admin.js', WCCFEE_PLUGIN_FILE), ['jquery'], WCCFEE_VERSION);
	}
	

    function wccfee_shipping_method() {
        include_once "shipping-method-class.php";
    }

    function plugin_settings_link( $actions ) {

        $mylinks = array(
            '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=wccfee' ) . '">Settings</a>',
         );

         return array_merge($mylinks,  $actions  );
    }
 
    
 
    function add_wccfee_shipping_method( $methods ) {
        $methods[] = 'WccFee_FlatShippingCity_Method';
        return $methods;
    }


    function wccfee_validate_order( $posted )   {
 
        $packages = WC()->shipping->get_packages();
 
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
         
        if( is_array( $chosen_methods ) && in_array( 'wccfee', $chosen_methods ) ) {
             
            foreach ( $packages as $i => $package ) {
 
                if ( $chosen_methods[ $i ] != "wccfee" ) {
                             
                    continue;
                             
                }
 
                $WccFee_Shipping_Method = new WccFee_Shipping_Method();
                $weightLimit = (int) $WccFee_Shipping_Method->settings['weight'];
                $weight = 0;
 
                foreach ( $package['contents'] as $item_id => $values ) 
                { 
                    $_product = $values['data']; 
                    $weight = $weight + $_product->get_weight() * $values['quantity']; 
                }
 
                $weight = wc_get_weight( $weight, 'kg' );
                
                if( $weight > $weightLimit ) {
 
                        $message = sprintf( __( 'Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'wccfee' ), $weight, $weightLimit, $WccFee_Shipping_Method->title );
                             
                        $messageType = "error";
 
                        if( ! wc_has_notice( $message, $messageType ) ) {
                         
                            // wc_add_notice( $message, $messageType );
                      
                        }
                }
            }       
        } 
    }
	

    function city_field_options( $fields ) {
        global $wpdb;
        $table = $wpdb->prefix . "wccfee_cities";        
        $cities = $wpdb->get_results("SELECT city_name FROM $table");
        $options[] = 'Select city';

        foreach($cities as $city){
            $options[$city->city_name] = $city->city_name;
        }



        $city_args = wp_parse_args(array(
            'type'    => 'select',
            'options' => $options,
            'autocomplete' => true
        ), $fields['shipping']['shipping_city']);

        $fields['shipping']['shipping_city'] = $city_args;
        $fields['billing']['billing_city']   = $city_args; // Also change for billing field
        return $fields;
    }

    function city_wp_footer(){
        if(is_checkout()){
        ?>
        <script>
            jQuery( function($) {
                $('#billing_city').change(function(){
                    jQuery('body').trigger('update_checkout');
                });
            }); 
        </script>
        <?php
        }
    }
}

/**
 * Returns the main instance of WC.
 *
 * @since  2.1
 * @return WooCommerce
 */
function wccfee_shipping() {
	return WccFeeFlatShippingCity::instance();
}

// Global for backwards compatibility.
$GLOBALS['wccfee'] = wccfee_shipping();
