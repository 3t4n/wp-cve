<?php
/**
 * Distance_Rate_Shipping_Main
 * This class defines all the required code for plugin configuration.
 * 
 * @package Distance_Rate_Shipping
 * @subpackage Distance_Rate_Shipping/woocommerce
 * @author tusharknovator
 * @since 1.0.0
 */
class Distance_Rate_Shipping_Main{

    /**
     * Store the name of the plugin
     * @access private
     * @var string $plugin_name
     */
    private $plugin_name;

    /**
     * Store the version of the plugin
     * @access private
     * @var string $plugin_version
     */
    private $plugin_version;

    /**
     * Store the config instance of the plugin
     * @access private
     * @var string $plugin_config
     */
    private $plugin_config;

    /**
     * __constructor function
     * To initiate class variables.
     * It run on object creation of class.
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function __construct($plugin_name, $plugin_version, $plugin_config, $instance_id = 0){
        $this->plugin_name = $plugin_name;
        $this->plugin_version = $plugin_version;
        $this->plugin_config = $plugin_config;
        $this->prefix = str_replace('-', '_', $plugin_name);

    }

    /**
     * load_distance_based_shipping_method function
     * To load distance based shipping method in woocommerce.
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function load_distance_based_shipping_method(){
        include plugin_dir_path(dirname(__FILE__)) . 'woocommerce/class-distance-rate-shipping-method.php';
    }

    /**
     * register_distance_based_shipping_method function
     * To register distance based shipping method in shipping methods list.
     * @access public
     * @return void
     * @since 1.0.0
     */
    function register_distance_based_shipping_method( $methods ) {
        $shipping_method_id = $this->plugin_config->get_shipping_method_id();
        $methods[$shipping_method_id] = 'Distance_Rate_Shipping_Method';
        return $methods;
    }

    /**
     * filter_shipping_methods function
     * filter and show only choosen shipping method
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function filter_shipping_methods($rates){
        $chosen_method = $this->validateMethod($_SESSION['chosen_method']);
        $chosen_method_instance = $this->validateMethodInstance($_SESSION['chosen_method_instance']);
        
        if( empty($chosen_method) || empty($chosen_method_instance)) {
            return $rates;
        }

        $chosen_rates = array();
        foreach ( $rates as $rate_id => $rate ) {
            if ( $chosen_method === $rate->method_id && $chosen_method_instance === $rate->instance_id) {
                $chosen_rates[ $rate_id ] = $rate;
                break;
            }
        }
        
        return !empty( $chosen_rates ) ? $chosen_rates : $rates;
    }
    /**
     * validateMethod function
     * validate choosen method retrieved form session
     * @access public
     * @param mixed $method
     * @return string
     * @since 1.0.0
     */
    public function validateMethod($method){
        if(!empty($method) && is_string($method)){
            return $method;
        }
        return "";
    }
    /**
     * validateMethodInstance function
     * validate choosen method instance retrieved form session
     * @access public
     * @param mixed $method_instance
     * @return string
     * @since 1.0.0
     */
    public function validateMethodInstance($method_instance){
        if(!empty($method_instance) && is_numeric($method_instance)){
            return $method_instance;
        }
        return "";
    }
}